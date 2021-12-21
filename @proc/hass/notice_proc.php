<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
@$tn_content = str_replace('"','\"',$_REQUEST['tn_content']);
@$tn_title = $_REQUEST['tn_title'];
@$tn_status =  $_REQUEST['tn_status'];
@$tn_topshow =  $_REQUEST['tn_topshow'];
@$tn_regdate = $_REQUEST['tn_regdate'];
@$tn_seq = $_REQUEST['tn_seq'];
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$insertQuery .= " tn_title = '{$tn_title}' ,tn_status = '{$tn_status}' , tn_topshow = '{$tn_topshow}'  ,tn_last_admin = {$mmseq} , tn_regdate = now() ";
$insertQuery .=  ', tn_content = "'.$tn_content.'" ';


if(empty($tn_seq)){
    $result['type']='I';
    $title ='등록';

    $query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$coseq}";
    $ps = pdo_query($db,$query,array());
    $data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

    $query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mc_coseq = {$coseq} and mm_status = 'Y' ";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data_gnb);
    }
    // echo('<pre>');print_r($list);echo('</pre>');exit;
    //메일 전송
    foreach($list as $index => $val){
        $to_email = $enc->decrypt($val['mm_email']);
        sendmail_regNotice($data_coperation['co_name'],$to_email,$tn_title);
    }
    
    $query = "insert tbl_noti_page set tn_coseq = {$coseq} , tn_is_del = 'F' , {$insertQuery}";
    pdo_query($db,$query,array());
}else{
    $result['type']='U';
    $title ='수정';

    $query = "update tbl_noti_page set tn_coseq = {$coseq} , tn_moddate = now() , {$insertQuery} where tn_seq = {$tn_seq}";
    pdo_query($db,$query,array());

    
}


$result['code']='TRUE';
$result['msg']= $title.'을 성공했습니다.';
$result['data'] = $data;
echo json_encode($result);


?>