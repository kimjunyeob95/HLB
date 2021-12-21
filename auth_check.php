<?
if(empty($_SESSION['mmseq']) || $_SESSION['mInfo']['mm_status'] != 'Y' || empty($_SESSION['mInfo']['mc_coseq'])){
	//비회원의 경우
	page_move("/login");
	exit;
}
?>