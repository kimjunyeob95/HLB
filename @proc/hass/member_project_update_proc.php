<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
if($type=='Y') {
    delete_project_data($db, $mmseq, $division);
    insert_project_data($db, $mmseq, $division);
}
update_status_project_log($db,$mmseq,$division,$type); // 사외경력
$result['code']='TRUE';
$result['msg']= '처리되었습니다.';
echo json_encode($result);

?>
