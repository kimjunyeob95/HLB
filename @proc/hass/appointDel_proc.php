<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$ta_seq = $_REQUEST['ta_seq'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($ta_seq)){
    echo json_encode($result);
    exit;
}

//중복 신청방지
$query = "UPDATE tbl_appointment SET ta_hide='T' WHERE ta_seq ={$ta_seq}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);

$result['code']='TRUE';
$result['msg']='삭제되었습니다.';

echo json_encode($result);

?>
