<?

/*******************************************
	파 일 명 : /manage/proc/reportUpdate.php
	파일 설명 : 기업 처리 페이지
 ********************************************/
include_once $_SERVER['DOCUMENT_ROOT'] . '/manage/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/manage/include/auth_check.php';

session_unset();


page_move('/manage/login.php', '로그아웃되었습니다.');


?>