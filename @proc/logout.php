<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

$_SESSION['mmseq'] = "";
$_SESSION['mInfo'] = "";
// session_unset();

page_move('/login', '로그아웃되었습니다.');

?>