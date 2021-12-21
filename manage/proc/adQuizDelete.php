<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}

include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");


@$qseq = $_REQUEST['qseq'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($qseq)){
	echo json_encode($result);
	exit;
}

if(!is_numeric($qseq) ){
	echo json_encode($result);
	exit;
}

$query="UPDATE tbl_quiz_ad_data SET qDelete='TRUE' WHERE qseq=? ";
$ps = pdo_query($db,$query,array($qseq));

$result['code']='TRUE';
$result['msg']='';
echo json_encode($result);

?>