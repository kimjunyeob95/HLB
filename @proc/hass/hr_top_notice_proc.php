<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
//@$hrnContent = $_REQUEST['hrnContent'];
@$hrTopTitle = $_REQUEST['hrTopTitle'];
@$hrTopState =  $_REQUEST['hrTopState'];
@$hrTopseq = $_REQUEST['hrTopseq'];
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$insertQuery .= " hrTopTitle = '{$hrTopTitle}' ,hrTopState = '{$hrTopState}' ,hrTopLastAdmin = {$mmseq} , hrTopRegdate = now() ";
if(empty($hrTopseq)){
    $result['type']='I';
    $title ='등록';
    if($hrTopState=='T'){
        $query = "update tbl_hr_top_notice set hrTopState = 'F' where hrTopCoseq = {$coseq}";
        pdo_query($db,$query,array());
    }
    $query = "insert tbl_hr_top_notice set hrTopCoseq = {$coseq} , hrTopDel = 'F' , {$insertQuery}";
    pdo_query($db,$query,array());
}else{
    $result['type']='U';
    $title ='수정';
    if($hrTopState=='T'){
        $query = "update tbl_hr_top_notice set hrTopState = 'F' where hrTopCoseq = {$coseq}";
        pdo_query($db,$query,array());
    }
    $query = "update tbl_hr_top_notice set hrTopCoseq = {$coseq} , hrTopModdate = now() , {$insertQuery} where hrTopseq = {$hrTopseq}";
    pdo_query($db,$query,array());
}


$result['code']='TRUE';
$result['msg']= $title.'을 성공했습니다.';
$result['data'] = $data;
echo json_encode($result);


?>