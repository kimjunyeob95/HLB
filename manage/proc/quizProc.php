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
@$qSource2 = $_REQUEST['qSource2'];
@$qQanswer = $_REQUEST['qQanswer'];
@$qSdate =  $_REQUEST['qSdate'];
@$qEdate =  $_REQUEST['qEdate'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($qType) || empty($qcType) || empty($qTitle)){
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
	if(empty($qQanswerNum)){
		$qQanswerNum =0;
	}
	// 퀴즈수정하기
	$query="UPDATE tbl_quiz_data SET
					qType=?,
					qCtype=?,
					qTitle=?,
					qQanswerNum=?,
					qQanswer=?,
					aAnswerOx=?,
					qAnswer1=?,
					qAnswer2=?,
					qAnswer3=?,
					qAnswer4=?,
					qSource1=?,
					qSource2=?,
					qSdate = ?,
					qEdate=?
					WHERE qseq=?";
	$ps = pdo_query($db,$query,array($qType,$qcType,$qTitle,$qQanswerNum,$qQanswer,$aAnswerOx,$qAnswer1,$qAnswer2,$qAnswer3,$qAnswer4,$qSource1,$qSource2,$qSdate,$qEdate ,$qseq));
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
	if(empty($qQanswerNum)){
		$qQanswerNum =0;
	}

	//퀴즈 등록
	$query="INSERT INTO tbl_quiz_data SET
					qType=?,
					qCtype=?,
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
					qSource2=?,
					qSdate = ?,
					qEdate=?,
					qDelete='FALSE'";
	$ps = pdo_query($db,$query,array($qType,$qcType,$qTitle,$qQanswerNum,$qQanswer,$aAnswerOx,$qAnswer1,$qAnswer2,$qAnswer3,$qAnswer4,$qSource1,$qSource2,$qSdate,$qEdate));
	$result['code']='TRUE';
	$result['msg']='';
	echo json_encode($result);
}






?>