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


@$mseq = $_REQUEST['mseq'];
@$mAuthType = $_REQUEST['mAuthType'];
@$mAuthVal = $_REQUEST['mAuthVal'];
@$mAuthTxt = $_REQUEST['mAuthTxt'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($mseq) || empty($mAuthType) || empty($mAuthVal)){
	echo json_encode($result);
	exit;
}


$query="SELECT mPoint FROM tbl_member WHERE mseq=?";
$ps = pdo_query($db,$query,array($mseq));
$data = $ps->fetch(PDO::FETCH_ASSOC);
$mPoint = $data['mPoint']*1;

if($mAuthType =="P") $mPoint = $mPoint+($mAuthVal*1);
if($mAuthType =="M") $mPoint = $mPoint-($mAuthVal*1);

if($mPoint<0){
	$result['code']='FALSE';
	$result['msg']='포인트는 0보다 작을 수 없습니다.';
	echo json_encode($result);
	exit;
}

$query="INSERT INTO tbl_memberq_history SET
				mqhmseq=?,
				mqhType=?,
				mqhPoint=?,
				mqhAuth='TRUE',
				mqhMemo=?,
				mqhDate=now()";
$ps = pdo_query($db,$query,array($mseq,$mAuthType,$mAuthVal,$mAuthTxt));




$query="UPDATE tbl_member SET
				mPoint = ? 
				WHERE mseq=?";
$ps = pdo_query($db,$query,array($mPoint ,$mseq));


$result['code']='TRUE';
$result['msg']='';
echo json_encode($result);


?>