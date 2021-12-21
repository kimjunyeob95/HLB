<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/PHPExcel/Classes/PHPExcel.php';

// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mc_code = $_SESSION['mInfo']['mc_code'];
@$hue_salaryDate_year = $_REQUEST['y']; //급여일 년
@$hue_salaryDate_month = $_REQUEST['m']; //급여일 월

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mc_coseq) || empty($mc_code)){
    echo json_encode($result);
    exit;
}

$query="UPDATE hass_upload_file SET hu_del='True' WHERE hu_hss_seq=? and hu_hss_code=? and hu_salaryDate_year=? and hu_salaryDate_month=?";
$ps = pdo_query($db,$query,array($mc_coseq,$mc_code,$hue_salaryDate_year,$hue_salaryDate_month));

$query="UPDATE hass_upload_excel_data SET hue_del='True' WHERE hue_hss_seq=? and hue_hss_code=? and hue_salaryDate_year=? and hue_salaryDate_month=?";
$ps = pdo_query($db,$query,array($mc_coseq,$mc_code,$hue_salaryDate_year,$hue_salaryDate_month));
        
page_move('/hass/salarychecklist.php','삭제 되었습니다.');
?>