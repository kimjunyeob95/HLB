<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];

@$password_origin = $_REQUEST['password_origin'];
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($password_origin)){
	page_move('/login.php','잘못된 접근입니다.');
}


//암호화 대상 필드
$password_origin = $enc->encrypt($password_origin);

$query="UPDATE ess_member_base SET mm_password=? WHERE mmseq=?";
$ps = pdo_query($db,$query,array($mm_password,$mmseq));
page_move('/@proc/logout.php','비밀번호가 변경되었습니다.');

?>
