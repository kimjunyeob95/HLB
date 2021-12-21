<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$mmseq = $_REQUEST['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mc_code = $_REQUEST['mc_code'];
@$mm_name = $_REQUEST['mm_name'];
@$mm_email = $_REQUEST['mm_email'];

$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($mc_coseq)){
    echo json_encode($result);
    exit;
}

sendmail_newReg($data_coperation['co_name'],$mm_name,$mm_email,$mc_code);

$result['code']='TRUE';
$result['msg']='메일이 발송되었습니다.';
echo json_encode($result);


?>
