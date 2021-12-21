<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
@$th_html = $_REQUEST['th_html'];

if(empty($mmseq) || empty($coseq) || empty($th_html)){
    page_move('/hass/holidayguide.php', '잘못된 접근입니다.');
    exit;
}

$query="SELECT count(*) as cnt from tbl_holiday_guide where
        th_coseq={$coseq}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($data);echo('</pre>');exit;

if($data['cnt']>0){
    //수정
    $query="UPDATE tbl_holiday_guide SET
        th_mmseq={$mmseq},
        th_html='{$th_html}',
        th_regdate=now()
        WHERE th_coseq={$coseq}";
    $ps = pdo_query($db,$query,array());
    
}else{
    //신규등록    
    $query="INSERT INTO tbl_holiday_guide SET
        th_mmseq={$mmseq},
        th_coseq={$coseq},
        th_html='{$th_html}',
        th_regdate=now()";
    $ps = pdo_query($db,$query,array());
}



// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;


page_move('/hass/holidayguide.php', '저장되었습니다.');


?>