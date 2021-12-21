<?
/*****************************************
	파일경로 : /index.php
	파일설명 : 시스템 INDEX 페이지
	
******************************************/
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

if(empty($_SESSION['mmseq'])){
	//비회원의 경우
	page_move("/login");
}else{
	//회원의 경우
	page_move("/main");
}

?>