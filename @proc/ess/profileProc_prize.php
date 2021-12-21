<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}

$query = "update ess_punishment_log set ep_state = 'C' where ep_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT ep_division+1 as cnt FROM ess_punishment_log WHERE ep_mmseq=".$mmseq." order by ep_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}
for($i=0;$i<sizeof($_REQUEST['ep_title']);$i++){
    $insertQuery="INSERT INTO  ess_punishment_log  SET
					ep_mmseq='".$mmseq."', 
					ep_applydate = now()  ";
    $insertQuery .=  "
        , ep_type = '{$_REQUEST['ep_type'][$i]}'
        , ep_date = '{$_REQUEST['ep_date'][$i]}'
        , ep_title = '{$_REQUEST['ep_title'][$i]}'
        , ep_etc = '{$_REQUEST['ep_etc'][$i]}'
        , ep_content = '{$_REQUEST['ep_content'][$i]}'
        , ep_division ='{$data['cnt']}' 
    ";
    pdo_query($db,$insertQuery,array());
}
    $result['code']='TRUE';
    $result['msg']='상벌 정보가 수정 요청 되었습니다.';
    echo json_encode($result);

?>
