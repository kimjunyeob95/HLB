<?
if(empty($_SESSION['admin_idx']) || empty($_SESSION['admin_info'])){
	page_move("manage/login.php");
	exit;
}
?>