<?php

include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
@$mmseq = $_SESSION['mmseq'];
// 파일 업로드
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/vouchers';
$filesname1 = $_FILES['file1']['name'];
$filesname2 = $_FILES['file2']['name'];
$filesname3 = $_FILES['file3']['name'];

if(!empty($filesname1)){
    $tmp_file_check = file_check_fn($_FILES['file1']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname1)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['file1']['name']);
    $ext = pathinfo( $_FILES['file1']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_1_'.date('YmdHis');
    $photo = $_FILES['file1']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/vouchers/".$filename.".".$ext;
    $file_query1 = " , mm_file1 = '{$location}', mm_file1_name = '{$_FILES['file1']['name']}' ";
}
if(!empty($filesname2)){
    $tmp_file_check = file_check_fn($_FILES['file2']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname2)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['file2']['name']);
    $ext = pathinfo( $_FILES['file2']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_2_'.date('YmdHis');
    $photo = $_FILES['file2']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/vouchers/".$filename.".".$ext;
    $file_query2 = " , mm_file2 = '{$location}', mm_file2_name = '{$_FILES['file2']['name']}'";
}
if(!empty($filesname3)){
    $tmp_file_check = file_check_fn($_FILES['file3']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname3)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['file3']['name']);
    $ext = pathinfo( $_FILES['file3']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_3_'.date('YmdHis');
    $photo = $_FILES['file3']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/vouchers/".$filename.".".$ext;
    $file_query3 = " , mm_file3 = '{$location}' , mm_file3_name = '{$_FILES['file3']['name']}' ";
}
$where = "";
if(!empty($_REQUEST['mm_file_type_1'])){
    $where .= " , mm_file_type_1 = {$_REQUEST['mm_file_type_1']} ";
}
if(!empty($_REQUEST['mm_file_type_2'])){
    $where .= " , mm_file_type_2 = {$_REQUEST['mm_file_type_2']} ";
}
if(!empty($_REQUEST['mm_file_type_3'])){
    $where .= " , mm_file_type_3 = {$_REQUEST['mm_file_type_3']} ";
}
if(!empty($where) || $where!='') {
    pdo_query($db, "update  ess_member_base SET mm_last_update = now() {$where} where mmseq ='{$mmseq}'", array());
}
if(!empty($file_query1) || !empty($file_query2) || !empty($file_query3)) {
    pdo_query($db, "update  ess_member_base SET mm_last_update = now() {$where} {$file_query1} {$file_query2} {$file_query3} where mmseq ='{$mmseq}'", array());
}
$result['code']='TRUE';
$result['msg']='증빙서류 정보가 수정 되었습니다.';
echo json_encode($result);
?>