<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
@$hrnStatus =  $_REQUEST['hrnStatus'];
@$hrnseq = $_REQUEST['hrnseq'];

if(empty($hrnseq) || empty($hrnStatus)){
    echo json_encode($result);
    exit;
}
if($hrnStatus=='T'){
    $title ='게시';
}else if($hrnStatus =='F'){
    $title ='비게시';
}else if($hrnStatus =='C'){
    $title ='삭제';
}

if($hrnStatus=='T' || $hrnStatus =='F'){
    $query = "update tbl_hr_notice set hrnCoseq = {$coseq} , hrnModdate = now() , hrnStatus = '{$hrnStatus}' where hrnseq = {$hrnseq}";
}else if($hrnStatus =='C'){
    $query = "update tbl_hr_notice set hrnCoseq = {$coseq} , hrnModdate = now() , hrnDel = 'T' where hrnseq = {$hrnseq}";
}
pdo_query($db,$query,array());

$result['code']='TRUE';
$result['msg']= $title.' 처리되었습니다..';
$result['data'] = $data;
echo json_encode($result);


?>