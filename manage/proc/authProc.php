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

@$mm_seq = $_REQUEST['mm_seq'];
@$mm_id = $_REQUEST['mm_id'];
@$mm_name = $_REQUEST['mm_name'];
@$mm_pwd = $_REQUEST['mm_pwd'];
@$type = $_REQUEST['type'];
@$mm_last_ip = $_SERVER["REMOTE_ADDR"];

$result['code']='False';
$result['msg']='잘못된 접근입니다.';

if(empty($type) || empty($mm_seq)){
	echo json_encode($result);
	exit;
}

if($type == "등록"){
    $query = "INSERT into tbl_manager_member SET mm_id = '{$mm_id}', mm_name = '{$mm_name}', mm_regdate = now(), mm_pwd = '{$enc->encrypt($mm_pwd)}', mm_last_ip = '{$mm_last_ip}' ";
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}else if($type == "수정"){
    $query = "UPDATE tbl_manager_member SET mm_id = '{$mm_id}', mm_name = '{$mm_name}', mm_pwd = '{$enc->encrypt($mm_pwd)}', mm_last_ip = '{$mm_last_ip}' WHERE mm_seq = '{$mm_seq}' ";
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}

$result['msg'] = $type.'처리 되었습니다.';
$result['code'] ='True';
echo json_encode($result);exit;
?>