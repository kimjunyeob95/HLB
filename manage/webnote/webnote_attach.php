<?php
session_start();
/******************************************************************************************
* WebNote
* 공식배포처 : http://www.phpwork.kr
* 테스트 : http://www.webnote.kr
* 제작자 이메일 : master@phpwork.kr
* 이프로그램은 개인/기업/영리/비영리 에 관계없이 웹개발에 무료로 자유롭게 사용할 수 있습니다.
* 소스내의 주석문은 어떠한 경우라도 수정/삭제할 수 없습니다.
* 제작자가 배포한상태 그대로인 경우에 한해 재배포를 허용하며 수정된 소스나, 수정된 소스가 포함된 프로그램, 소스중의 일부분을 타인에게 배포하거나 판매할 수 없습니다.
* 제작자와 별도의 상의없이 본프로그램의 버전및 공식사이트 정보를 보여주는 "WebNote"(제일 오른쪽에 "?" 형태의 아이콘으로 출력됨) 버튼을 임의로 안보이도록 할 없습니다.(모든 툴바를 감추는 경우는 자동으로 감추어지므로 관계없습니다.)
* 이프로그램의 사용으로 인해 발생한 어떠한 문제도 제작자는 책임지지 않습니다.
* 사용중 문의나 건의사항은 공식 배포사이트의 게시판을 이용해주시거나 메일로 보내 주시기 바랍니다.
******************************************************************************************/
$webnote_uri			= "/webnote";			//계정내의 webnote 설치경로(계정의 최상위부터의 위치 표기 : "/"로 시작)
$upload_path			= "upload/_quick/";		//파일 업로드 디렉토리 (현재 파일을 기준으로 상대 또는 절대경로)
$allow_file_ext			= array("jpg","gif","png");	//업로드 허용 파일 확장자
$upload_url			= "http://".$_SERVER["HTTP_HOST"].$webnote_uri."/upload/_quick/";			//파일 URL
$dir_type			= "day";				//year,month,day (year: $image_webpath/2012) , default : day
$allow_image_max_width		= 650;	    //이미지 업로드 시 가능한 최대 가로사이즈(픽셀) : 초과시 이 사이즈로 리사이징되어 저장됨(0: 제한없음)
$allow_image_max_volume		= 1024*1024*1;  // 업로드제한용량(byte) (기본 : 1메가, 0: 무제한 , 서버의 설정에따라 별도의 제한이 걸릴 수 있습니다.)

if(!preg_match('@^http[s]*:\/\/'.$_SERVER["SERVER_NAME"].'@ui',$_SERVER['HTTP_REFERER'])) {
	echo('0|File Not Found'); 
	exit;
}

makeDir($upload_path);
if($dir_type == "year" || $dir_type == "month" || $dir_type == "day") {
	$upload_path		.= date("Y");
	$upload_url			.= date("Y");
	makeDir($upload_path);
}
if($dir_type == "month" || $dir_type == "day") {
	$upload_path		.= "/".date("m");
	$upload_url			.= "/".date("m");
	makeDir($upload_path);
}
if($dir_type == "day") {
	$upload_path		.= "/".date("d");
	$upload_url			.= "/".date("d");
	makeDir($upload_path);
}

function makeDir($path) {
	if(!is_dir($path)) {
		if(!mkdir($path,0707)) {
		    echo "0|check permission : '".$path."'";
		    exit;		    
		}
		chmod($path,0707);
	}
}

if(!$_SESSION['USERKEY']) {
	$_SESSION['USERKEY'] = substr(md5(time()),0,15);
}

if($_POST["proc_type"] == "up") {

	if(is_uploaded_file($_FILES["up_file"]["tmp_name"])) {

		$tmp_names = explode(".",$_FILES["up_file"]["name"]);
		$fileExt = array_pop($tmp_names);
		$fileExt = strtolower($fileExt);
		if(!in_array($fileExt,$allow_file_ext)) {
			echo "0|upload fail!(not allow file)";
			exit;
		}
		if($allow_image_max_volume && $_FILES['up_file']['size'] > $allow_image_max_volume) {
			echo "0|upload fail!(not allow file volume: Max ".$allow_image_max_volume."byte)";
			exit;
		}
		$imageObj	= getimagesize($_FILES["up_file"]["tmp_name"]);
		if($imageObj[2] > 0 && $imageObj[2] < 4) {		    
		    
			$img_alt = $org_filename = $_FILES["up_file"]["name"];
			$org_filename_array = explode(".",$org_filename);
			array_pop($org_filename_array);
			$img_title = implode(".",$org_filename_array);
			$filenamebody = $_SESSION['USERKEY']."_".microtime(true).rand(100000,999999);
			$filenamebody = str_replace(".","",$filenamebody);		//. 제거
			$filename = $filenamebody.".".$fileExt;
			
			$upfile_path = $upload_path.'/'.$filename;			
		    
			//지정된 가로사이즈보다 크면 리사이징한다.
			$allow_image_max_width = (int)$allow_image_max_width;
			if($allow_image_max_width > 0 && $imageObj[0] > $allow_image_max_width) {
			    
				if($imageObj[2] == 1) {
					$im = imagecreatefromgif($_FILES["up_file"]["tmp_name"]); 
				} elseif($imageObj[2] == 2) {
					$im = imagecreatefromjpeg($_FILES["up_file"]["tmp_name"]); 
				} elseif($imageObj[2] == 3) {
					$im = imagecreatefrompng($_FILES["up_file"]["tmp_name"]); 
				}
				
				$width = $allow_image_max_width; 
				$height = ($imageObj[1]*$allow_image_max_width) / $imageObj[0];
				$sheet = imagecreatetruecolor($width, $height); 
				imagecopyresampled($sheet, $im, 0, 0, 0, 0, $width, $height, $imageObj[0], $imageObj[1]); 

				if($imageObj[2] == 1) {
					imagegif($sheet,$upfile_path, 90);
				} elseif($imageObj[2] == 2)	{
					imagejpeg($sheet,$upfile_path, 90);
				} elseif($imageObj[2] == 3) {
					imagepng($sheet,$upfile_path, 9);
				}
				imagedestroy($sheet);
				@unlink($_FILES["up_file"]["tmp_name"]);
			    
			} else {

			    if(!move_uploaded_file($_FILES["up_file"]["tmp_name"],$upfile_path)) {
				    echo "0|upload fail";
				    exit;
			    }
			    
			}
			
			@chmod($upfile_path,0707);		    

			$fileUrl = $upload_url.'/'.$filename;
			echo "1|".$fileUrl."|".$img_alt."|".$img_title;
			exit;
			
		} else {
			echo "0|upload fail (not image file)";
			exit;	
		}
	} else {
		echo "0|not uploaded file(".$_FILES["up_file"]["tmp_name"].")";
		exit;	
	}

} else if($_GET["proc_type"] == "del") {

	$filename = $_GET['filename'];
	$servefilename = $_SESSION['USERKEY']."_".$filename;
	$servefilepath = $upload_path."/".$servefilename;
	if(@unlink($servefilepath)) {

		$fileUrl = $upload_url.'/'.$servefilename;
		echo "1|".$fileUrl;

	} else {
		echo "0|error";
	}
	exit;

} else {
//	print_r($_REQUEST);
	echo "0|unknown error";
	exit;	
}
?>