<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$nseq=$_REQUEST['nseq'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($nseq)){
    echo json_encode($result);
    exit;
}

$query ="select * from tbl_template_page where nseq = {$nseq}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);

$result['code']='TRUE';
$result['msg']='데이터를 성공적으로 가져왔습니다.';
$result['data'] = $data;
echo json_encode($result);

?>