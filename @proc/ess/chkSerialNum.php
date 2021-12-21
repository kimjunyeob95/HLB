<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$mm_serial_no_request = $_REQUEST['mm_serial_no'];
@$mm_serial_no = substr($enc->decrypt($_SESSION['mInfo']['mm_serial_no']),6);
// echo('<pre>');print_r($mm_serial_no);echo('</pre>');
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if($mm_serial_no_request == $mm_serial_no){
    $result['code']='TRUE';
    $_SESSION['mInfo']['salary_auth'] = 'TRUE';
}else{
    $result['msg']='주민번호 정보가 다릅니다.';
}
echo json_encode($result);

?>
