<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/admin_auth_check.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");

@$co_seq = $_REQUEST['co_seq'];
@$co_status = $_REQUEST['co_status'];
@$title = $_REQUEST['title'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($co_seq) || empty($co_status) || empty($title)){
	echo json_encode($result);
	exit;
}


// echo('<pre>');print_r($_FILES);echo('</pre>');

$query="UPDATE tbl_coperation SET
            co_status=? WHERE co_seq=?";
            
$ps = pdo_query($db,$query,array($co_status,$co_seq));


$result['code']='TRUE';
$result['msg']=$title.'처리 되었습니다.';
echo json_encode($result);
?>