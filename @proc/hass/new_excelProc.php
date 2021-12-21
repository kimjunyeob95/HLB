<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/PHPExcel/Classes/PHPExcel.php';
date_default_timezone_set('Asia/Seoul');

@$mmseq = $_SESSION['mmseq'];
@$mm_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mm_code = $_SESSION['mInfo']['mc_code'];
@$hu_salaryDate_year = $_REQUEST['hu_salaryDate_year'];
@$hu_salaryDate_month = $_REQUEST['hu_salaryDate_month'];
@$hu_showDate = $_REQUEST['hu_showDate']." ".$_REQUEST['hu_showDate_hour'];

// @$hu_showDate_hour = $_REQUEST['hu_showDate_hour'];
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';


if(empty($mmseq) || empty($mm_coseq)){
    echo json_encode($result);
    exit;
}


//파일 업로드 함수
function fileUpload_channel($file_content,$num){
    // @$saveDir = "/data/excelData/".date('Y')."/".date('m');
    @$saveDir = "/data/excelData";
	@$save_path = $_SERVER['DOCUMENT_ROOT'].$saveDir;
	if(!is_dir($save_path."/")){
		mkdir($save_path,"0777");
		@chmod($save_path, 0777);
	}
	
	@$c_file="";

	if(!empty($file_content)){
		$tmp_file_check = file_check_fn($_FILES['file'.$num]);
		for($i=0;$i<sizeof($tmp_file_check);$i++){
			if($tmp_file_check[$i]['result']=='FALSE'){
				page_move("/hass/salaryregister.php" ,'업로드 할 수 없는 형식의 파일입니다.');
				exit;
			}
		}
	}

	if(empty($file_content)==FALSE){
		list($_ori_filename, $ext) = explode('.', $_FILES['file'.$num]['name']);
		$ext = pathinfo( $_FILES['file'.$num]['name'], PATHINFO_EXTENSION);
		$filename =  date('d').'_data_'.date('YmdHis').create_random_string(6);
		$ff = $_FILES['file'.$num]['tmp_name'];
		move_uploaded_file($ff, $save_path.'/'.$filename.".".$ext);
		$c_file = $saveDir."/".$filename.".".$ext;
	}
	return $c_file;
}

@$file_content1 = $_FILES['file05']['name'];
$c_file1=fileUpload_channel($file_content1,"05");

//기존파일 검사
$query="SELECT count(*) as cnt,hu_fileName FROM hass_upload_file WHERE hu_hss_seq=? and hu_hss_code=? and hu_salaryDate_year=? and hu_salaryDate_month=?";
$ps = pdo_query($db,$query,array($mm_coseq,$mm_code,$hu_salaryDate_year,$hu_salaryDate_month));
$data_check = $ps->fetch(PDO::FETCH_ASSOC);
$total_count = $data_check['cnt'];
// echo('<pre>');print_r($total_count);echo('</pre>');exit;
if($total_count>0){
    //기존 급여 있을 시

    //엑셀 삭제
    unlink($_SERVER['DOCUMENT_ROOT'].$data_check['hu_fileName']);
    //update
    $query="UPDATE hass_upload_file SET
				hu_del='FALSE',
				hu_hss_seq=?,
                hu_hss_code=?,
                hu_fileName=?,
				hu_showDate=?,
                hu_regDate=now() WHERE
                hu_salaryDate_year=? and hu_salaryDate_month=?";
    pdo_query($db,$query,array($mm_coseq,$mm_code,$c_file1,$hu_showDate,$hu_salaryDate_year,$hu_salaryDate_month));
    $cudfseq = $db->lastInsertId();
    //엑셀 db 삭제
    $query="DELETE FROM hass_upload_excel_data WHERE hue_hss_seq=? and hue_hss_code=? and hue_salaryDate_year=? and hue_salaryDate_month=?";
    $ps = pdo_query($db,$query,array($mm_coseq,$mm_code,$hu_salaryDate_year,$hu_salaryDate_month));
    $data = $ps->fetch(PDO::FETCH_ASSOC);

}else{
    //새로운 급여 등록
    $query="INSERT INTO hass_upload_file SET
				hu_del='FALSE',
                hu_mm_seq=?,
				hu_hss_seq=?,
                hu_hss_code=?,
                hu_fileName=?,
                hu_salaryDate_year=?,
                hu_salaryDate_month=?,
				hu_showDate=?,
				hu_regDate=now()";
    pdo_query($db,$query,array($mmseq,$mm_coseq,$mm_code,$c_file1,$hu_salaryDate_year,$hu_salaryDate_month,$hu_showDate));
    $cudfseq = $db->lastInsertId();
}



$fieldList = array('hue_ess_code','hue_mm_name','hue_mm_buseo','hue_mm_level','pay01','pay02','pay03','pay04','pay05','pay06','pay07','pay08','pay09','pay10','pay11','pay12','deduct01','deduct02','deduct03','deduct04','deduct05','deduct06','deduct07','deduct08','deduct09','deduct10','deduct11','deduct12','total_pay');

try {
	$objPHPExcel = new PHPExcel();
	$allData = array();

	$filename=$_SERVER['DOCUMENT_ROOT'].$c_file1;
    // $filename = iconv("UTF-8", "EUC-KR", $_FILES['file05']['tmp_name']);
    // echo('<pre>');print_r($objPHPExcel);echo('</pre>');exit;
    $objPHPExcel = PHPExcel_IOFactory::load($filename);
    
	$extension = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
	$sheetsCount = $objPHPExcel -> getSheetCount();
    
    $objPHPExcel -> setActiveSheetIndex(0);		//0번쨰 시트만 읽음
    
	$activesheet = $objPHPExcel -> getActiveSheet();
	$highestRow = $activesheet -> getHighestRow();             // 마지막 행
    $highestColumn = $activesheet -> getHighestColumn();    // 마지막 컬럼

    @$height_index=0;

	for($row = 3; $row < $highestRow; $row++) {
        
		// $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
		$rowData = $activesheet -> rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);

		// $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.
        $allData[$height_index] = $rowData[0];
        $height_index++;
	}
	
	for($i=0;$i<sizeof($allData);$i++){
		$no = $allData[$i][0];
        
		$query="INSERT INTO hass_upload_excel_data SET";
        
		for($q=0;$q<sizeof($fieldList);$q++){
            
         
            $query.=" ".$fieldList[$q]." = '".$allData[$i][$q]."', ";
            // $query.=" ".$fieldList[$q]." = '".mysql_escape_string($allData[$i][$q])."', ";
        }
        
        // $query.=" hue_regDate=now() ";
        //echo $query."<br><br><br>=====================<br><br><br>";
        $query.=' hue_regDate=now(), hue_del="False", ';
        $query.=" hue_hss_seq = {$mm_coseq}, hue_showDate=?, hue_hss_code= {$mm_code}, hue_salaryDate_year={$hu_salaryDate_year}, hue_salaryDate_month={$hu_salaryDate_month}";
		$ps = pdo_query($db,$query,array($hu_showDate));
        
	}
    
    page_move('/hass/salaryregister.php','급여가 등록되었습니다.');
	
} catch(exception $exception) {
	//엑셀 파일 오류
	// echo $exception;
	page_move('/hass/salaryregister.php', '엑셀파일에 오류가 있습니다. 다시 확인하시고 업로드 해 주세요');
}
?>
