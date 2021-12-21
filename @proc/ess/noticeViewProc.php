<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$type = $_REQUEST['type'];
@$tn_seq = $_REQUEST['tn_seq'];
@$hrnseq = $_REQUEST['hrnseq'];
@$subquery = $_REQUEST['subquery'];

// echo('<pre>');print_r($_REQUEST);echo('</pre>');

if($type=="notice"){
    //일반공지
    $query="UPDATE tbl_noti_page SET tn_views=tn_views + 1 WHERE tn_seq={$tn_seq}";
    $ps = pdo_query($db,$query,array());
    $result['seq']=$tn_seq;
}else if($type=="hrnotice"){
    //HR 공지
    $query="UPDATE tbl_hr_notice SET hrnViews=hrnViews + 1 WHERE hrnseq={$hrnseq}";
    $ps = pdo_query($db,$query,array());
    $result['seq']=$hrnseq;
}


$result['code']='TRUE';
$result['msg']='잘못된 접근입니다.';
$result['subquery']=$subquery;


echo json_encode($result);

?>
