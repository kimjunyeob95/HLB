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


@$seq = $_REQUEST['seq'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($seq)){
	echo json_encode($result);
	exit;
}

if(!is_numeric($seq) ){
	echo json_encode($result);
	exit;
}

$query="DELETE FROM tbl_member 
				WHERE mseq=?";
$ps = pdo_query($db,$query,array($seq));


$result['code']='TRUE';
$result['msg']='';
echo json_encode($result);


?>