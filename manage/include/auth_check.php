<?
	session_start();
	if(empty($_SESSION['admin_info'])){
		page_move('/manage/login');
		exit;
	}
	
	$admin_idx = $_SESSION['admin_idx'];
	$admin_info = $_SESSION['admin_info'];
	
?>