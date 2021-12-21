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

@$search = $_REQUEST['search'];
// @$hu_showDate_hour = $_REQUEST['hu_showDate_hour'];
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(!empty($search) && $search=='search'){
    //기존파일 검사
    $query="SELECT count(*) as cnt,hu_fileName FROM hass_upload_file WHERE hu_hss_seq=? and hu_hss_code=? and hu_salaryDate_year=? and hu_salaryDate_month=?";
    $ps = pdo_query($db,$query,array($mm_coseq,$mm_code,$hu_salaryDate_year,$hu_salaryDate_month));
    $data_check = $ps->fetch(PDO::FETCH_ASSOC);
    $total_count = $data_check['cnt'];
    if($total_count>0){
        $result['code']='true';
        echo json_encode($result);exit;
    }
}

echo json_encode($result);
?>
