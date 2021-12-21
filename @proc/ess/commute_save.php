<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$type = $_REQUEST['type'];
@$tc_num = $_REQUEST['tc_num'];
@$tc_return = $_REQUEST['tc_return'];
@$reason = $_REQUEST['reason'];
@$holidaydata = $_REQUEST['holidaydata'];
@$user_mmseq = $_REQUEST['user_mmseq'];
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

//echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($type) || empty($tc_num) || empty($mmseq) || empty($coseq)){
	echo json_encode($result);
	exit;
}
$query = " select * from tbl_commute where tc_num = {$tc_num} ";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$add_query = '';
if(!empty($tc_return)){
    $add_query = " , tc_return = '{$tc_return}'";
}
$query = "update tbl_commute set tc_confirm1_mmseq = {$mmseq} {$add_query} , tc_confirm1_date = now() , tc_confirm1_state ='{$type}' where tc_num = {$tc_num}";
pdo_query($db,$query,array());

$mail_type='';
//반려시 다시 돌려줌
if($type=='N'){
	$query = "update ess_member_code set 
				 mc_commute_use = mc_commute_use-{$data['tc_vacation_count']},
				 mc_commute_remain = mc_commute_remain+{$data['tc_vacation_count']} 
				 where mc_mmseq = {$user_mmseq} and mc_coseq = {$coseq}";
    pdo_query($db,$query,array());
    $mail_type='반려';
}else{
    $mail_type='승인';
}

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$user_mmseq} ";
$ps = pdo_query($db,$query,array());
$data_user = $ps ->fetch(PDO::FETCH_ASSOC);

$mm_name = $enc->decrypt($data_user['mm_name']);
$to_email = $enc->decrypt($data_user['mm_email']);

if($type=='N'){
    //반려시
    sendmail_mssHoliday($data_coperation['co_name'],$mm_name,$to_email,$mail_type,$holidaydata,$tc_return);
}else{
    //승인시
    sendmail_mssHoliday($data_coperation['co_name'],$mm_name,$to_email,$mail_type,$holidaydata,$reason);
}


$result['code']='TRUE';
$result['msg']='처리가 완료되었습니다.';
echo json_encode($result);
?>
