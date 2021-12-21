<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
@$tn_status =  $_REQUEST['tn_status'];
@$tn_topshow =  $_REQUEST['tn_topshow'];
@$tn_seq = $_REQUEST['tn_seq'];

// echo('<pre>');print_r($tn_topshow);echo('</pre>');exit;
if(empty($tn_seq) || empty($tn_status)){
    echo json_encode($result);
    exit;
}
if($tn_status=='T'){
    $title ='게시';
}else if($tn_status =='F'){
    $title ='비게시';
}else if($tn_status =='C'){
    $title ='삭제';
}else if($tn_status =='tn_topshow'){
    if($tn_topshow == 'T'){
        $title ='상단고정으로';
    }else{
        $title ='상단비고정으로';
    }
    
}

$insertQuery .= "tn_status = '{$tn_status}'";

if($tn_status=='T' || $tn_status =='F'){
    $query = "update tbl_noti_page set tn_coseq = {$coseq} , tn_moddate = now() , tn_last_admin = {$mmseq} , {$insertQuery} where tn_seq = {$tn_seq}";
    pdo_query($db,$query,array());    
}else if($tn_status =='C'){
    $query = "update tbl_noti_page set tn_is_del = 'T' , tn_moddate = now()  , tn_last_admin = {$mmseq} where tn_seq = {$tn_seq}";
    pdo_query($db,$query,array());    
}else if($tn_status =='tn_topshow'){
    $query = "update tbl_noti_page set tn_moddate = now()  , tn_last_admin = {$mmseq} , tn_topshow = '{$tn_topshow}' where tn_seq = {$tn_seq}";
    pdo_query($db,$query,array());    
}

$result['code']='TRUE';
$result['msg']= $title.' 처리되었습니다..';
$result['data'] = $data;
echo json_encode($result);


?>