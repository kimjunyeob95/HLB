<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
if($type=='Y') {
    delete_punishment_data($db, $mmseq, $division);
    insert_punishment_data($db, $mmseq, $division);
}
update_status_punishment_log($db,$mmseq,$division,$type); // 사외경력

if(!empty($cause_return)) {
    $query = "insert tbl_cause_of_return set tco_division = {$division}, tco_type='prize' , tco_regdate = now() , tco_text = '{$cause_return}' , tco_mmseq = {$mmseq}";
    $ps = pdo_query($db,$query,array());
}

$result['code']='TRUE';
$result['msg']= '처리되었습니다.';
echo json_encode($result);

?>
