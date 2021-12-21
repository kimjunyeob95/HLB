<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$mail_type='';
if($type=='Y') {
    update_member_info_log($db,$mmseq,$division,$type);
    $mail_type='승인';
}else{
    $mail_type='반려';
}

update_status_member_log($db,$mmseq,$division,$type);

//사유
if(!empty($cause_return)) {
    $query = "insert tbl_cause_of_return set tco_division = {$division}, tco_type='nomal' , tco_regdate = now() , tco_text = '{$cause_return}' , tco_mmseq = {$mmseq}";
    $ps = pdo_query($db,$query,array());
}

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$mmseq} ";
$ps = pdo_query($db,$query,array());
$data_user = $ps ->fetch(PDO::FETCH_ASSOC);

$mm_name = $enc->decrypt($data_user['mm_name']);
$to_email = $enc->decrypt($data_user['mm_email']);

sendmail_memberInfo($data_coperation['co_name'],$mm_name,$to_email,'기본사항',$mail_type);


$result['code']='TRUE';
$result['msg']= '처리되었습니다.';
echo json_encode($result);

?>
