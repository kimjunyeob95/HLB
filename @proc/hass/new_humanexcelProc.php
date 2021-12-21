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
        $filename =  'employe_data_'.date('YmdHis');
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

    for($index=0;$index<9;$index++){
        $objPHPExcel -> setActiveSheetIndex($index);		// 0번쨰 시트만 읽음
        $activesheet = $objPHPExcel -> getActiveSheet();
        $highestRow = $activesheet -> getHighestRow();             // 마지막 행
        $highestColumn = $activesheet -> getHighestColumn();    // 마지막 컬럼
        
        if((int)$highestRow == 2){
            $allData[$index] = '';
        }
        
        //기본정보
        if($index==0){
            // $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
            $rowData = $activesheet -> rangeToArray("A" . 3 . ":" . "AD" . 3, NULL, TRUE, FALSE);
            // $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.
            $allData[$index] = $rowData[0];
        //나머지
        }else{
            $count=0;
            for($row = 3; $row < $highestRow+1; $row++) {
                // $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
                $rowData = $activesheet -> rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);
                // $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.
                $allData[$index][$count] = $rowData[0];
                $count++;   
            }
        }   
    }
    
    //빈 배열 삭제
    foreach($allData as $index => $val){
        if($index!=0){
            foreach($val as $index_d2 => $val_d2){
                if(empty($val_d2[0]) && empty($val_d2[1])){
                    unset($allData[$index][$index_d2]);
                }
            }
        }
    }
    // echo('<pre>');print_r($allData);echo('</pre>');exit;
    //날짜 변환, 암호화
    for($i=0;$i<sizeof($allData);$i++){
        $query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($allData[0][1])}' and mm_is_del = 'FALSE' ";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        if($data['cnt'] >0){
            unlink($filename);
            page_move('/hass/humanExcel.php','중복된 이메일이 있습니다.');
            exit;
        }
        
        $query = "select right(mc_code,4) as mc_code from ess_member_code emc where mc_coseq = {$mc_coseq} order by mc_code desc limit 1";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        $mc_code = sprintf("%06d",substr(date('Y'),2,2).sprintf("%04d",$data['mc_code'] * 1 + 1));
        $mm_seq;

        $fieldList=array();
        if(empty($allData[$i])){continue;};
        if($i==0){
            //기본정보
            $allData[0][0]=$enc->encrypt($allData[0][0]);
            $allData[0][1]=$enc->encrypt($allData[0][1]);
            $allData[0][3]=$enc->encrypt($allData[0][3]);
            $allData[0][5]=$enc->encrypt($allData[0][5]);
            $allData[0][9]=$enc->encrypt($allData[0][9]);
            $allData[0][10]=$enc->encrypt($allData[0][10]);
            $allData[0][11]=$enc->encrypt($allData[0][11]);
            $allData[0][12]=$enc->encrypt($allData[0][12]);
            $allData[0][15]=$enc->encrypt($allData[0][15]);
            $allData[0][4]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][4], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $allData[0][6]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][6], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $allData[0][7]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][7], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $allData[0][16]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][16], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $allData[0][17]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][17], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $allData[0][29]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][29], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $allData[0][28]=PHPExcel_Style_NumberFormat::toFormattedString($allData[0][28], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
     
            $fieldList = array('mm_name','mm_email','mm_en_name','mm_password',
            'mm_birth','mm_serial_no','pass','pass','mm_post','mm_address','mm_address_detail',
            'mm_cell_phone','mm_phone','mm_gender','mm_prepare_relation','mm_prepare_phone','pass','pass','pass','pass','pass','pass','pass',
            'mm_arm_type','mm_arm_reason','mm_arm_group','mm_arm_class','mm_arm_discharge',
            'mm_arm_sdate','mm_arm_edate');
            $query = "INSERT INTO ess_member_base SET mm_coseq={$mc_coseq}, mm_regdate=now(),mm_apply_mmseq={$mmseq}, mm_status='Y', mm_applydate=now(), mm_confirm_date=now(), ";
        }else if($i==1){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][0]=$enc->encrypt($allData[$i][$findex][0]);
                $allData[$i][$findex][1]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][1], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][1]=$enc->encrypt($allData[$i][$findex][1]);
            }
            //가족사항
            $fieldList = array('mf_name','mf_birth','mf_gender','mf_relationship','mf_householder','mf_together');
        }else if($i==2){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][0]=$enc->encrypt($allData[$i][$findex][0]);
                $allData[$i][$findex][1]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][1], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][2]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][2], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            }
            //학력
            $fieldList = array('me_name','me_sdate','me_edate','me_level','me_degree','me_major','me_graduate_type','me_etc');
        }else if($i==3){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][0]=$enc->encrypt($allData[$i][$findex][0]);
                $allData[$i][$findex][1]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][1], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][2]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][2], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][3]=$enc->encrypt($allData[$i][$findex][3]);
                $allData[$i][$findex][4]=$enc->encrypt($allData[$i][$findex][4]);
            }
            //사외경력
            $fieldList = array('mc_company','mc_sdate','mc_edate','mc_position','mc_duties','mc_career');
        }else if($i==4){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][0]=$enc->encrypt($allData[$i][$findex][0]);
                $allData[$i][$findex][1]=$enc->encrypt($allData[$i][$findex][1]);
                $allData[$i][$findex][3]=$enc->encrypt($allData[$i][$findex][3]);
                $allData[$i][$findex][4]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][4], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            }
            //어학 / 자격증
            $fieldList = array('mct_cert_name','mct_institution','mct_num','mct_class','mct_date');
        }else if($i==5){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][1]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][1], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][2]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][2], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][3]=$enc->encrypt($allData[$i][$findex][3]);
                $allData[$i][$findex][4]=$enc->encrypt($allData[$i][$findex][4]);
            }
            //교육 / 활동
            $fieldList = array('mad_type','mad_sdate','mad_edate','mad_name','mad_institution');
        }else if($i==6){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][0]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][0], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][1]=$enc->encrypt($allData[$i][$findex][1]);
                $allData[$i][$findex][2]=$enc->encrypt($allData[$i][$findex][2]);
            }
            //논문 / 저서
            $fieldList = array('mp_date','mp_name','mp_institution');
        }else if($i==7){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][0]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][0], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][1]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][1], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $allData[$i][$findex][2]=$enc->encrypt($allData[$i][$findex][2]);
            }
            //프로젝트
            $fieldList = array('mpd_sdate','mpd_edate','mpd_name','mpd_contribution','mpd_position','mpd_result','mpd_content','mpd_keyword');
        }else if($i==8){
            for($findex=0;$findex<sizeof($allData[$i]);$findex++){
                $allData[$i][$findex][1]=PHPExcel_Style_NumberFormat::toFormattedString($allData[$i][$findex][1], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            }
            //상벌
            $fieldList = array('mp_type','mp_date','mp_title','mp_content','mp_etc');
            
        }
        
        if($i==0){
            for($array_size=0;$array_size<1;$array_size++){
                for($q=1;$q<sizeof($fieldList);$q++){
                    if($q==6 || $q==7 || $q==16 || $q==17 || $q==18 || $q==19 || $q==20 || $q==21 || $q==22){continue;}
                    if(empty($allData[$i][$q]) || $allData[$i][$q]==' '){
                        $allData[$i][$q] = '';
                    }else{
                        $query.=" ".$fieldList[$q]." = '".$allData[$i][$q]."', ";
                    }
                    
                }
            }
            $query .= " mm_name = '{$allData[0][0]}' ";
            // echo('<pre>');print_r($allData);echo('</pre>');exit;
            $ps = pdo_query($db,$query,array());
        }else{
            if(empty($allData[$i])){continue;};
            for($array_size=0;$array_size<sizeof($allData[$i]);$array_size++){
                if($i==1){
                    $query = "INSERT INTO member_family_data SET ";
                }else if($i==2){
                    $query = "INSERT INTO member_education_data SET ";
                }else if($i==3){
                    $query = "INSERT INTO member_career_data SET ";
                }else if($i==4){
                    $query = "INSERT INTO member_certificate_data SET ";
                }else if($i==5){
                    $query = "INSERT INTO member_activity_data SET ";
                }else if($i==6){
                    $query = "INSERT INTO member_paper_data SET ";
                }else if($i==7){
                    $query = "INSERT INTO member_project_data SET ";
                }else if($i==8){
                    $query = "INSERT INTO member_punishment_data SET ";
                }
                for($q=0;$q<sizeof($fieldList);$q++){
                    if(empty($allData[$i][$array_size][$q])){continue;}
                    $query.=" ".$fieldList[$q]." = '".$allData[$i][$array_size][$q]."', ";
                }
                if($i==1){
                    $query .= " mf_mmseq= '{$mm_seq}' ";
                }else if($i==2){
                    $query .= " me_mmseq= '{$mm_seq}' ";
                }else if($i==3){
                    $query .= " mc_mmseq= '{$mm_seq}' ";
                }else if($i==4){
                    $query .= " mct_mmseq = '{$mm_seq}' ";
                }else if($i==5){
                    $query .= " mad_mmseq = '{$mm_seq}' ";
                }else if($i==6){
                    $query .= " mp_mmseq= '{$mm_seq}' ";
                }else if($i==7){
                    $query .= " mpd_mmseq= '{$mm_seq}' ";
                }else if($i==8){
                    $query .= " mp_mmseq= '{$mm_seq}' ";
                    
                }
                // echo('<pre>');print_r($query);echo('</pre>');exit;
                $ps = pdo_query($db,$query,array());
            }
        }
        

        if($i==0){
            
            $query = "select mmseq from ess_member_base where mm_email = '{$allData[0][1]}' and mm_coseq={$mc_coseq} and mm_name='{$allData[0][0]}'";
            $ps = pdo_query($db,$query,array());
            $data = $ps->fetch(PDO::FETCH_ASSOC);
            $mm_seq = $data['mmseq'];

            $query = "INSERT INTO ess_member_code SET 
                    mc_mmseq={$mm_seq}, mc_coseq={$mc_coseq}, mc_code={$mc_code}, mc_regdate='{$allData[0][6]}', 
                    mc_commute_all = {$allData[0][20]}, 
                    mc_commute_use={$allData[0][21]}, mc_commute_remain={$allData[0][22]},
                    mc_job='{$allData[0][18]}', mc_job2='{$allData[0][19]}', mc_position_date='{$allData[0][16]}', 
                    mc_affiliate_date='{$allData[0][17]}', ";
            if(!empty($allData[0][7])){
                $query .= " mc_bepromoted_date = '{$allData[0][7]}', ";
            }
            $query .= " mc_main='T' ";
            // echo('<pre>');print_r($query);echo('</pre>');exit;
            $ps = pdo_query($db,$query,array());

        }
        // echo('<pre>');print_r($allData);echo('</pre>');exit;        
    }
    // echo('<pre>');var_dump($allData);echo('</pre>');exit;
	unlink($filename);
    page_move('/hass/humanExcel.php','임직원이 등록되었습니다.');
	
} catch(exception $exception) {
	//엑셀 파일 오류
	// echo $exception;
	page_move('/hass/humanExcel.php', '엑셀파일에 오류가 있습니다. 다시 확인하시고 업로드 해 주세요');
}
?>
