<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}
//암호화 대상 필드
$enctArr = array('cl_institution','cl_class');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}
$query = "update ess_certificate_log set cl_state = 'C' where cl_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT cl_division as cnt FROM ess_certificate_log WHERE cl_mmseq=".$mmseq." order by cl_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data1 = $ps->fetch(PDO::FETCH_ASSOC);

$query="SELECT epl_division as cnt FROM ess_premier_log WHERE epl_mmseq=".$mmseq." order by epl_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data2 = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data1)){
    $data1['cnt'] = 1;
}
if($data1['cnt'] < $data2['cnt']){
    $data['cnt'] = $data2['cnt']*1+1;
}else{
    $data['cnt'] = $data1['cnt']*1+1;
}

for($i=0;$i<sizeof($_REQUEST['cl_cert_name']);$i++){
    $insertQuery="INSERT INTO  ess_certificate_log  SET
					cl_mmseq='".$mmseq."', 
					cl_applydate = now()  ";
    $insertQuery .=  "
        , cl_cert_name = '{$_REQUEST['cl_cert_name'][$i]}'
        , cl_institution = '{$_REQUEST['cl_institution'][$i]}'
        , cl_class = '{$_REQUEST['cl_class'][$i]}'
        , cl_date = '{$_REQUEST['cl_date'][$i]}'
        , cl_num = '{$_REQUEST['cl_num'][$i]}'
        , cl_division ='{$data['cnt']}' 
    ";
    pdo_query($db,$insertQuery,array());
}

for($i=0;$i<sizeof($_REQUEST['epl_date']);$i++){
    $insertQuery="INSERT INTO  ess_premier_log  SET
					epl_mmseq='".$mmseq."', 
					epl_applydate = now()  ";
    $insertQuery .=  "
        , epl_date = '{$_REQUEST['epl_date'][$i]}'
        , epl_content = '{$_REQUEST['epl_content'][$i]}'
        , epl_institution = '{$_REQUEST['epl_institution'][$i]}'
        , epl_division ='{$data['cnt']}' 
    ";
    pdo_query($db,$insertQuery,array());
}

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$mmseq} ";
$ps = pdo_query($db,$query,array());
$data_user = $ps ->fetch(PDO::FETCH_ASSOC);

$mm_name = $enc->decrypt($data_user['mm_name']);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mc_coseq = {$mc_coseq} and mm_status = 'Y' and mc_hass='T' ";
$ps = pdo_query($db,$query,array());
$list_hass = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_hass,$data_gnb);
}
foreach($list_hass as $index => $val){
    $to_email = $enc->decrypt($val['mm_email']);
    sendmail_essInfo($data_coperation['co_name'],$mm_name,$to_email,'어학 / 자격증 / 수상');
}

    $result['code']='TRUE';
    $result['msg']='어학/자격증 정보가 수정 요청 되었습니다.';
    echo json_encode($result);

?>
