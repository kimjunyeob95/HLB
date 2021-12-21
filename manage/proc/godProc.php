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







$gseq = $_REQUEST['gseq'];
$gName = $_REQUEST['gName'];
$gManName = $_REQUEST['gManName'];
$page = $_REQUEST['page'];

$gName2 = $_REQUEST['gName2'];
$gManName2 = $_REQUEST['gManName2'];
$gTel = $_REQUEST['gTel'];
$gMail = $_REQUEST['gMail'];
$gNumber = $_REQUEST['gNumber'];

$subquery="&gName=".urlencode($gName)."&gManName=".urlencode($gManName);


/*		파일 업로드	**/
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/god';
@$file_content1 = $_FILES['file1']['name'];
$c_file1="";



if(!empty($file_content1)){
	$tmp_file_check = file_check_fn($_FILES['file1']);
	for($i=0;$i<sizeof($tmp_file_check);$i++){

		if($tmp_file_check[$i]['result']=='FALSE'){
			page_move("/manage/godDetail.php?page=".$page."&seq=".$gseq ,'업로드 할 수 없는 형식의 파일입니다.');
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



if(empty($gseq)){
	$query="INSERT INTO tbl_god SET
					gName=?,
					gManName=?,
					gTel=?,
					gMail=?,
					gNumber=?,
					gFile1=?,
					gRegdate=now(),
					gIsDel='FALSE'";
	$ps = pdo_query($db,$query,array($gName2,$gManName2,$gTel,$gMail,$gNumber,$c_file1));
	
	page_move("/manage/god.php",'광고주 정보가 등록 되었습니다.');

}else{

	//파일 업로드가 안된 경우
	if(empty($c_file1)){
		$query="UPDATE  tbl_god SET
					gName=?,
					gManName=?,
					gTel=?,
					gMail=?,
					gNumber=?
					WHERE gseq=?";
		$ps = pdo_query($db,$query,array($gName2,$gManName2,$gTel,$gMail,$gNumber,$gseq));
	}else{
		$query="UPDATE  tbl_god SET
					gName=?,
					gManName=?,
					gTel=?,
					gMail=?,
					gNumber=?,
					gFile1=?
					WHERE gseq=?";
		$ps = pdo_query($db,$query,array($gName2,$gManName2,$gTel,$gMail,$gNumber,$c_file1,$gseq));
	}


	page_move("/manage/godDetail.php?page=".$page."&seq=".$gseq ,'광고주 정보가 수정 되었습니다.');

}




?>
