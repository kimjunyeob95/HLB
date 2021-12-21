<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
@$hrnContent = str_replace('"','\"',$_REQUEST['hrnContent']);
//@$hrnContent = $_REQUEST['hrnContent'];
@$hrnTitle = $_REQUEST['hrnTitle'];
@$hrnStatus =  $_REQUEST['hrnStatus'];
@$hrnseq = $_REQUEST['hrnseq'];
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$insertQuery .= " hrnTitle = '{$hrnTitle}' ,hrnStatus = '{$hrnStatus}' ,hrnLastAdmin = {$mmseq} , hrnRegdate = now() ";
$insertQuery .=  ', hrnContent = "'.$hrnContent.'" ';
if(empty($hrnseq)){
    $result['type']='I';
    $title ='등록';
    $query = "insert tbl_hr_notice set hrnCoseq = {$coseq} , hrnDel = 'F' , {$insertQuery}";
    pdo_query($db,$query,array());
}else{
    $result['type']='U';
    $title ='수정';
    $query = "update tbl_hr_notice set hrnCoseq = {$coseq} , hrnModdate = now() , {$insertQuery} where hrnseq = {$hrnseq}";
    pdo_query($db,$query,array());
}


$result['code']='TRUE';
$result['msg']= $title.'을 성공했습니다.';
$result['data'] = $data;
echo json_encode($result);


?>