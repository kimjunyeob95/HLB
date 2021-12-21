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
$enctArr = array('crl_company','crl_position','crl_duties');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}
$query = "update ess_career_log set crl_state = 'C' where crl_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT crl_division+1 as cnt FROM ess_career_log WHERE crl_mmseq=".$mmseq." order by crl_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}
for($i=0;$i<sizeof($_REQUEST['crl_company']);$i++){
    $insertQuery="INSERT INTO  ess_career_log  SET
					crl_mmseq='".$mmseq."', 
					crl_applydate = now()  ";
    $insertQuery .=  "
        , crl_company = '{$_REQUEST['crl_company'][$i]}'
        , crl_group = '{$_REQUEST['crl_group'][$i]}'
        , crl_sdate = '{$_REQUEST['crl_sdate'][$i]}'
        , crl_edate = '{$_REQUEST['crl_edate'][$i]}'
        , crl_position = '{$_REQUEST['crl_position'][$i]}'
        , crl_career = '{$_REQUEST['crl_career'][$i]}'
        , crl_duties = '{$_REQUEST['crl_duties'][$i]}'
        , crl_division = '{$data['cnt']}'
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
    sendmail_essInfo($data_coperation['co_name'],$mm_name,$to_email,'경력사항');
}

$result['code']='TRUE';
$result['msg']='경력사항 정보가 수정 요청 되었습니다.';
echo json_encode($result);

?>
