<?
if(empty($_SESSION['mmseq']) || ($_SESSION['mInfo']['mm_status'] !='S' && $_SESSION['mInfo']['mm_status'] !='N') || empty($_SESSION['mInfo']['mc_coseq'])){
	//비회원의 경우
	page_move("/new");
	exit;
}
?>