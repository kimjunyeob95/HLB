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
@$val =  $_REQUEST['val'];

$result['code']='FALSE';

if(empty($seq)){
	echo json_encode($result);
	exit;
}

$query="UPDATE tbl_config SET
				cValue=?
				WHERE cseq=?";
$ps = pdo_query($db,$query,array($val,$seq));
$result['code']='TRUE';
echo json_encode($result);

?>