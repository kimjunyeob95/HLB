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


@$qseq = $_REQUEST['qseq'];
@$qType = $_REQUEST['qType'];
@$qcType = $_REQUEST['qcType'];
@$qTitle = $_REQUEST['qTitle'];
@$qQanswerNum = $_REQUEST['qQanswerNum'];
@$qAnswer1 = $_REQUEST['qAnswer1'];
@$qAnswer2 = $_REQUEST['qAnswer2'];
@$qAnswer3 = $_REQUEST['qAnswer3'];
@$qAnswer4 = $_REQUEST['qAnswer4'];
@$aAnswerOx = $_REQUEST['aAnswerOx'];
@$qQanswer2 = $_REQUEST['qQanswer2'];
@$qSource1 = $_REQUEST['qSource1'];
@$qQanswer = $_REQUEST['qQanswer'];
@$adseq2 = $_REQUEST['adseq2'];
@$adSdate = $_REQUEST['adSdate'];
@$adEdate = $_REQUEST['adEdate'];
@$qMovieUrl = $_REQUEST['qMovieUrl'];
@$adBrand = $_REQUEST['adBrand'];
@$adLink = $_REQUEST['adLink'];
@$adBseq = $_REQUEST['adBseq'];
@$adGS  = $_REQUEST['adGS'];

if(empty($qQanswerNum)){
	$qQanswerNum="0";
}

if(empty($adBseq)){
	$adBseq="0";
}

if(empty($adGS)){
	$adGS="G";
}


$qMovie="TRUE";
if(empty($qMovieUrl )){
	$qMovie='FALSE';
}


$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($qType) || empty($adseq2) || empty($qTitle)){
	echo json_encode($result);
	exit;
}

if(!empty($qseq)){
	if(!is_numeric($qseq)){
		echo json_encode($result);
		exit;
	}
	if($qType=="O"){
		$qQanswer = $qQanswer2;
	}
	// 퀴즈수정하기
	$query="UPDATE tbl_quiz_ad_data SET
					qType=?,
					adseq = ?,
					qTitle=?,
					qQanswerNum=?,
					qQanswer=?,
					aAnswerOx=?,
					qAnswer1=?,
					qAnswer2=?,
					qAnswer3=?,
					qAnswer4=?,
					qSource1=?,
					adBrand=?,
					adSdate=?,
					adEdate=?,
					qMovie=?,
					qMovieUrl=?,
					adLink = ?,
					adGS = ?,
					adBseq = ?
					WHERE qseq=?";
	$ps = pdo_query($db,$query,array($qType,$adseq2,$qTitle,$qQanswerNum,$qQanswer,$aAnswerOx,$qAnswer1,$qAnswer2,$qAnswer3,$qAnswer4,$qSource1,$adBrand ,$adSdate,$adEdate,$qMovie,$qMovieUrl,$adLink,$adGS,$adBseq,$qseq));
	$result['code']='TRUE';
	$result['msg']='';
	echo json_encode($result);
	
	
}else{
	if($qType=="O"){
		$qQanswer = $qQanswer2;
	}
	if(empty($aAnswerOx)){
		$aAnswerOx="O";
	}
	//퀴즈 등록
	$query="INSERT INTO tbl_quiz_ad_data SET
					qType=?,
					adseq = ?,
					qTitle=?,
					qQanswerNum=?,
					qQanswer=?,
					aAnswerOx=?,
					qAnswer1=?,
					qAnswer2=?,
					qAnswer3=?,
					qAnswer4=?,
					qAnswer5='',
					qSource1=?,
					qSource2='',
					adBrand=?,
					adSdate=?,
					adEdate=?,
					qMovie=?,
					qMovieUrl=?,
					adLink = ?,
					adGS=?,
					adBseq=?,
					qDelete='FALSE'";
	$ps = pdo_query($db,$query,array($qType,$adseq2,$qTitle,$qQanswerNum,$qQanswer,$aAnswerOx,$qAnswer1,$qAnswer2,$qAnswer3,$qAnswer4,$qSource1,$adBrand ,$adSdate,$adEdate,$qMovie,$qMovieUrl,$adLink,$adGS,$adBseq));
	$result['code']='TRUE';
	$result['msg']='';
	echo json_encode($result);
}






?>