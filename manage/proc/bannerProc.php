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




$bgseq = $_REQUEST['bgseq'];
$bTitle = $_REQUEST['bTitle'];
$gManName = $_REQUEST['gManName'];
$bseq = $_REQUEST['bseq'];


$bgseq2 = $_REQUEST['bgseq2'];
$bTitle2 = $_REQUEST['bTitle2'];
$bLink = $_REQUEST['bLink'];
$bLink2 = $_REQUEST['bLink2'];
$bSdate = $_REQUEST['bSdate'];
$bEdate = $_REQUEST['bEdate'];
$bType = $_REQUEST['bType'];

/*		파일 업로드	**/
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/banner';
@$file_content1 = $_FILES['file1']['name'];
$c_file1="";




$subquery="&bTitle=".urlencode($bTitle)."&bgseq=".urlencode($bgseq);


if(!empty($file_content1)){
	$tmp_file_check = file_check_fn($_FILES['file1']);
	for($i=0;$i<sizeof($tmp_file_check);$i++){

		if($tmp_file_check[$i]['result']=='FALSE'){
			page_move("/manage/bannerDetail.php?page=".$page."&seq=".$bseq ,'업로드 할 수 없는 형식의 파일입니다.');
			exit;
		}
	}	
}

if(empty($file_content1)==FALSE){
	list($_ori_filename, $ext) = explode('.', $_FILES['file1']['name']);
	$ext = pathinfo( $_FILES['file1']['name'], PATHINFO_EXTENSION);
	$filename =  'data_'.date('YmdHis');
	$ff = $_FILES['file1']['tmp_name'];
	move_uploaded_file($ff, $save_path.'/'.$filename.".".$ext);
	$c_file1 = $filename.".".$ext;
}



if(empty($bseq)){
	$query="INSERT INTO tbl_banner SET
					bgseq=?,
					bTitle=?,
					bImage=?,
					bLink=?,
					bSdate=?,
					bEdate=?,
					bType=?,
					bLink2=?,
					bRegdate=now(),
					bIsDel='FALSE'";
	$ps = pdo_query($db,$query,array($bgseq2,$bTitle2,$c_file1,$bLink,$bSdate,$bEdate,$bType,$bLink2));
	
	page_move("/manage/banner.php",'배너가 등록 되었습니다.');

}else{

	//파일 업로드가 안된 경우
	if(empty($c_file1)){
		$query="UPDATE  tbl_banner SET
					bgseq=?,
					bTitle=?,
					bLink=?,
					bLink2=?,
					bSdate=?,
					bEdate=?,
					bType=?
					WHERE bseq=?";
		$ps = pdo_query($db,$query,array($bgseq2,$bTitle2,$bLink,$bLink2,$bSdate,$bEdate,$bType,$bseq));
	}else{
		$query="UPDATE  tbl_banner SET
					bgseq=?,
					bTitle=?,
					bImage=?,
					bLink=?,
					bLink2=?,
					bSdate=?,
					bEdate=?,
					bType=?
					WHERE bseq=?";
		$ps = pdo_query($db,$query,array($bgseq2,$bTitle2,$c_file1,$bLink,$bLink2,$bSdate,$bEdate,$bType,$bseq));
	}


	page_move("/manage/bannerDetail.php?page=".$page."&seq=".$bseq ,'배너 정보가 수정 되었습니다.');

}




?>
