<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$type = $_REQUEST['type'];
@$mmseq = $_REQUEST['mmseq'];
@$ta_title = $_REQUEST['ta_title'];
@$admin_mmseq = $_SESSION['mmseq'];
@$next_co_seq = $_REQUEST['co_seq'];
@$datetime = $_REQUEST['datetime'];
@$prev_co_seq = $_SESSION['mInfo']['mc_coseq'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';
if(empty($prev_co_seq) || empty($next_co_seq) || empty($admin_mmseq) || empty($type) || empty($mmseq)){
    echo json_encode($result);
    exit;
}

//중복 신청방지
$query = "select count(*) as cnt from tbl_appointment where ta_mmseq = {$mmseq} and ta_next_coseq = {$next_co_seq} and (ta_status ='Y' or ta_status ='A') and ta_type = {$type}";
$ps = pdo_query($db,$query,array());
$cnt = $ps ->fetch(PDO::FETCH_ASSOC);
if($cnt['cnt'] > 0){
    $result['code']='FALSE';
    $result['msg']='이미 신청정보가 있습니다.';
    echo json_encode($result);
    exit;
}
if($type==3){
    $updateQuery = "update ess_member_base set mm_status = 'D' , mm_retirement_date = '{$datetime}' where mmseq = {$mmseq}";
    pdo_query($db,$updateQuery,array());
    $result['msg']='퇴서 처리가 완료되었습니다.';
}else{
    if($type==1){
        $result['msg']='발령 신청이 완료되었습니다.';
    }else{
        $result['msg']='겸직 신청이 완료되었습니다.';
    }
    $insertQuery="INSERT INTO  tbl_appointment  SET
              ta_type = {$type} , ta_prev_coseq = {$prev_co_seq}, ta_next_coseq = {$next_co_seq},
              ta_admin_seq = {$admin_mmseq}, ta_title = '{$ta_title}' , ta_mmseq = {$mmseq}, ta_status = 'A' ,ta_apply_date = now() , tg_activity_date = '{$datetime}'";
    pdo_query($db,$insertQuery,array());

}


$result['code']='TRUE';

echo json_encode($result);

?>
