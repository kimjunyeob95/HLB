<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}
// echo('<pre>');print_r($_SESSION);echo('</pre>');exit;
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/admin_auth_check.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");

$enc = new encryption();

@$data = $_REQUEST['data'];
$result['code']='True';
$result['msg']='잘못된 접근입니다.';

if(empty($data)){
	echo json_encode($result);
	exit;
}
$query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($data)}' and mm_is_del = 'FALSE' ";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);


if($data['cnt'] >0){
    $result['msg']='중복된 이메일이 있습니다.';
    $result['code']='False';
    echo json_encode($result);
    exit;
}else{
    echo json_encode($result);
}

?>