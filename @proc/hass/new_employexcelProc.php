<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/PHPExcel/Classes/PHPExcel.php';
date_default_timezone_set('Asia/Seoul');

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mc_code = $_SESSION['mInfo']['mc_code'];

// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($mc_coseq)){
    echo json_encode($result);
    exit;
}
$validation = True;
//파일 업로드 함수
function fileUpload_channel($file_content,$num){
    // @$saveDir = "/data/excelData/".date('Y')."/".date('m');
    @$saveDir = "/data/excelData/employe";
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
				page_move("/hass/humanExcel.php" ,'업로드 할 수 없는 형식의 파일입니다.');
				exit;
			}
		}
	}

	if(empty($file_content)==FALSE){
		list($_ori_filename, $ext) = explode('.', $_FILES['file'.$num]['name']);
		$ext = pathinfo( $_FILES['file'.$num]['name'], PATHINFO_EXTENSION);
        // $filename =  date('d').'_data_'.date('YmdHis').create_random_string(6);
        $filename =  'Newemploye_data_'.date('YmdHis');
		$ff = $_FILES['file'.$num]['tmp_name'];
		move_uploaded_file($ff, $save_path.'/'.$filename.".".$ext);
		$c_file = $saveDir."/".$filename.".".$ext;
	}
	return $c_file;
}

@$file_content1 = $_FILES['file05']['name'];
$c_file1=fileUpload_channel($file_content1,"05");


try {
	$objPHPExcel = new PHPExcel();
	$allData = array();

	$filename=$_SERVER['DOCUMENT_ROOT'].$c_file1;
    // $filename = iconv("UTF-8", "EUC-KR", $_FILES['file05']['tmp_name']);
    
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
	$extension = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
	$sheetsCount = $objPHPExcel -> getSheetCount();
    
    $objPHPExcel -> setActiveSheetIndex(0);		//0번쨰 시트만 읽음
    
	$activesheet = $objPHPExcel -> getActiveSheet();
	$highestRow = $activesheet -> getHighestRow();             // 마지막 행
    $highestColumn = $activesheet -> getHighestColumn();    // 마지막 컬럼
    
    @$height_index=0;

	for($row = 3; $row < $highestRow+1; $row++) {
		// $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
		$rowData = $activesheet -> rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);
		// $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.
        $allData[$height_index] = $rowData[0];
        $height_index++;
	}
    // echo('<pre>');print_r($allData);echo('</pre>');exit;
    for($i=0;$i<sizeof($allData);$i++){
        $allData[$i][0]=$enc->encrypt($allData[$i][0]);
        $allData[$i][1]=$enc->encrypt($allData[$i][1]);
        $allData[$i][2]=$enc->encrypt($allData[$i][2]);
        $allData[$i][3]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][3], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        $allData[$i][4]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][4], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
    }
    for($i=0;$i<sizeof($allData);$i++){
        $query = "select count(*) as cnt from ess_member_base where mm_email = '{$allData[$i][1]}' and mm_is_del = 'FALSE' ";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        if($data['cnt']>0){$validation=False;};
    }
    if($validation == False){
        return page_move('/hass/employeNewExcel.php','중복되는 이메일이 있습니다. 엑셀을 확인해주세요.');
    }else{
        for($i=0;$i<sizeof($allData);$i++){
        $mm_seq='';
        $query="INSERT INTO ess_member_base SET
                mm_name='{$allData[$i][0]}',
                mm_coseq={$mc_coseq},
                mm_birth='{$allData[$i][4]}',
                mm_email='{$allData[$i][1]}',
                mm_password='{$allData[$i][2]}',
                mm_regdate=now(),
                mm_applydate=now(),
                mm_apply_mmseq={$mmseq},
                mm_confirm_date=now(),
                mm_status='S'";
        $ps = pdo_query($db,$query,array());
        
        $query = "select mmseq from ess_member_base where mm_email = '{$allData[$i][1]}' and mm_coseq={$mc_coseq} and mm_name='{$allData[$i][0]}'";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        $mm_seq = $data['mmseq'];
        
        $query = "select right(mc_code,4) as mc_code from ess_member_code emc where mc_coseq = {$mc_coseq} order by mc_code desc limit 1";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        $mc_code_new = sprintf("%06d",substr(date('Y'),2,2).sprintf("%04d",$data['mc_code'] * 1 + 1));

        $query = "INSERT INTO ess_member_code SET 
                mc_mmseq={$mm_seq}, mc_coseq={$mc_coseq}, mc_code={$mc_code_new}, mc_regdate='{$allData[$i][3]}', ";
        $query .= " mc_main='T' ";
        // echo('<pre>');print_r($query);echo('</pre>');exit;
        $ps = pdo_query($db,$query,array());
        }
    }
	
	unlink($filename);
    page_move('/hass/humanExcel.php','임직원이 등록되었습니다.');
	
} catch(exception $exception) {
	//엑셀 파일 오류
	// echo $exception;
	page_move('/hass/humanExcel.php', '엑셀파일에 오류가 있습니다. 다시 확인하시고 업로드 해 주세요');
}
?>
