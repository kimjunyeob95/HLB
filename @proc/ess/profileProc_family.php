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
$enctArr = array('ml_name','ml_resident','ml_job_name','ml_job','ml_education','ml_birth');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}
$query = "update ess_family_log set ml_state = 'C' where ml_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT ml_division+1 as cnt FROM ess_family_log WHERE ml_mmseq=".$mmseq." order by ml_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}
for($i=0;$i<sizeof($_REQUEST['ml_name']);$i++){
    $insertQuery="INSERT INTO  ess_family_log  SET
					ml_mmseq='".$mmseq."', 
					ml_applydate = now()  ";
    $insertQuery .=  "
        , ml_name = '{$_REQUEST['ml_name'][$i]}'
        , ml_resident = '{$_REQUEST['ml_resident'][$i]}'
        , ml_birth = '{$_REQUEST['ml_birth'][$i]}'
        , ml_gender = '{$_REQUEST['ml_gender'][$i]}'
        , ml_foreigner = '{$_REQUEST['ml_foreigner'][$i]}'
        , ml_relationship ='{$_REQUEST['ml_relationship'][$i]}' 
        , ml_householder ='{$_REQUEST['ml_householder'][$i]}'
        , ml_job_name ='{$_REQUEST['ml_job_name'][$i]}'
        , ml_job ='{$_REQUEST['ml_job'][$i]}'
        , ml_education ='{$_REQUEST['ml_education'][$i]}'
        , ml_allowance ='{$_REQUEST['ml_allowance'][$i]}' 
        , ml_together ='{$_REQUEST['ml_together'][$i]}'
        , ml_division = '{$data['cnt']}'
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
    sendmail_essInfo($data_coperation['co_name'],$mm_name,$to_email,'가족사항');
}

$result['code']='TRUE';
$result['msg']='가족사항이 수정 요청 되었습니다.';
echo json_encode($result);

?>
