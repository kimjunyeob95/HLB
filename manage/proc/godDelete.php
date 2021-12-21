<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}

include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");


@$seq = $_REQUEST['seq'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($seq)){
	echo json_encode($result);
	exit;
}

if(!is_numeric($seq) ){
	echo json_encode($result);
	exit;
}


//사업자 등록증 파일 삭제
$query="SELECT gFile1 FROM tbl_god WHERE gseq=?";
$ps = pdo_query($db,$query,array($seq));
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(!empty($data['gFile1'])){
	$datadir = "/data/god/".$data['gFile1'];
	@unlink( $_SERVER['DOCUMENT_ROOT'].$datadir);
}


$query="UPDATE tbl_god SET gIsDel='TRUE',gFile1='' WHERE gseq=? ";
$ps = pdo_query($db,$query,array($seq));


//광고주의 광고 삭제
$query="UPDATE tbl_quiz_ad_data SET
				qDelete='TRUE'
				WHERE adseq=?";
$ps = pdo_query($db,$query,array($seq));


//광고주의 배너 삭제
$query="UPDATE tbl_banner SET
				bIsDel='TRUE'
				WHERE bgseq=?";
$ps = pdo_query($db,$query,array($seq));


$result['code']='TRUE';
$result['msg']='';
echo json_encode($result);

?>


