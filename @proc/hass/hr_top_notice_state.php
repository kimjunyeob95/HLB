<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
@$hrTopState =  $_REQUEST['hrTopState'];
@$hrTopseq = $_REQUEST['hrTopseq'];

if(empty($hrTopseq) || empty($hrTopState)){
    echo json_encode($result);
    exit;
}
if($hrTopState=='T'){
    $title ='게시';
}else if($hrTopState =='F'){
    $title ='비게시';
}else if($hrTopState =='C'){
    $title ='삭제';
}

if($hrTopState=='T'){
    $query = "update tbl_hr_top_notice set hrTopState = 'F' where hrTopCoseq = {$coseq}";
    pdo_query($db,$query,array());

    $query = "update tbl_hr_top_notice set hrTopCoseq = {$coseq} , hrTopModdate = now() , hrTopState = '{$hrTopState}' where hrTopseq = {$hrTopseq}";
}else if($hrTopState =='F'){
    $query = "update tbl_hr_top_notice set hrTopCoseq = {$coseq} , hrTopModdate = now() , hrTopState = '{$hrTopState}' where hrTopseq = {$hrTopseq}";
}else if($hrTopState =='C'){
    $query = "update tbl_hr_top_notice set hrTopCoseq = {$coseq} , hrTopModdate = now() , hrTopDel = 'T' where hrTopseq = {$hrTopseq}";
}
pdo_query($db,$query,array());

$result['code']='TRUE';
$result['msg']= $title.' 처리되었습니다..';
$result['data'] = $data;
echo json_encode($result);


?>