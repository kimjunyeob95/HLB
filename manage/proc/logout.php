<?php
header("Content-Type: text/html; charset=UTF-8");
include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';
session_start();
session_destroy();
// foreach($_SESSION as $key => $val) {
// 	$_SESSION[$key] = '';
// }
page_move('/manage/');
?>