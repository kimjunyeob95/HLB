<?php
header("Content-Type: text/html; charset=UTF-8");
include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';
include $_SERVER['DOCUMENT_ROOT'].'/manage/include/auth_check.php';


@$nseq = $_REQUEST['nseq'];
@$nTitle = $_REQUEST['nTitle'];
@$nContent = $_REQUEST['nContent'];
@$procType = $_REQUEST['procType'];	// I : 등록, U : 수정, D : 삭제
@$nRegdate = $_REQUEST['nRegdate'];	// I : 등록, U : 수정, D : 삭제

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");



if($procType == "I"){
	$query="INSERT INTO tbl_notice(nTitle, nContent, nRegdate) VALUES(?, ?, now())";
	$ps = pdo_query($db,$query,array($nTitle, $nContent));
	
	page_move("/manage/notice.php",'공지사항이 등록되었습니다.');
}else if($procType == "U"){
	if(!empty($nseq)){
		$query="UPDATE tbl_notice SET
				nTitle = ?
				, nContent = ?
				,nRegdate=?
				WHERE nseq = ?";
		$ps = pdo_query($db,$query,array($nTitle, $nContent, $nRegdate,$nseq));
		
		page_move("/manage/notice.php",'공지사항이 수정되었습니다.');
	}
}else if($procType == "D"){
	if(!empty($nseq)){
		$query="UPDATE tbl_notice SET
				nDel = 'TRUE'
				WHERE nseq = ?";
		$ps = pdo_query($db,$query,array($nseq));
		
		page_move("/manage/notice.php",'공지사항이 삭제되었습니다.');
	}
}

?>