<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
$mmseq = $_SESSION['mmseq'];
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}

$query = "delete from member_project_data where mpd_mmseq = {$mmseq}";
$ps = pdo_query($db,$query,array());



// 프로젝트
for($i=0;$i<sizeof($_REQUEST['mpd_name']);$i++){
    $_REQUEST['mpd_name'][$i] = $enc->encrypt($_REQUEST['mpd_name'][$i]);
    $insertQuery="INSERT INTO  member_project_data  SET
					mpd_mmseq='".$mmseq."', 
					mpd_regdate = now()  ";
    $insertQuery .=  "
        , mpd_sdate = '{$_REQUEST['mpd_sdate'][$i]}'
        , mpd_edate = '{$_REQUEST['mpd_edate'][$i]}'
        , mpd_name = '{$_REQUEST['mpd_name'][$i]}'
        , mpd_content = '{$_REQUEST['mpd_content'][$i]}'
        , mpd_keyword = '{$_REQUEST['mpd_keyword'][$i]}'
        , mpd_institution = '{$_REQUEST['mpd_institution'][$i]}'
        , mpd_contribution = '{$_REQUEST['mpd_contribution'][$i]}'
        , mpd_result = '{$_REQUEST['mpd_result'][$i]}'
        , mpd_position = '{$_REQUEST['mpd_position'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}
$result['code']='TRUE';
$result['msg']= '처리되었습니다.';
echo json_encode($result);

?>
