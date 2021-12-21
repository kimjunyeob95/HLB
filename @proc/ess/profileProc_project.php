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
//암호화 대상 필드
$enctArr = array('epj_name');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}

//여러개 신청이 있을경우 대비 (구분)
$query="SELECT epj_division+1 as cnt FROM ess_project_log WHERE epj_mmseq=".$mmseq." order by epj_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}
for($i=0;$i<sizeof($_REQUEST['epj_name']);$i++){
    $insertQuery="INSERT INTO  ess_project_log  SET
					epj_mmseq='".$mmseq."', 
					epj_applydate = now()  ";
    $insertQuery .=  "
        , epj_sdate = '{$_REQUEST['epj_sdate'][$i]}'
        , epj_edate = '{$_REQUEST['epj_edate'][$i]}'
        , epj_name = '{$_REQUEST['epj_name'][$i]}'
        , epj_content = '{$_REQUEST['epj_content'][$i]}'
        , epj_keyword = '{$_REQUEST['epj_keyword'][$i]}'
        , epj_Contribution = '{$_REQUEST['epj_Contribution'][$i]}'
        , epj_result = '{$_REQUEST['epj_result'][$i]}'
        , epj_position = '{$_REQUEST['epj_position'][$i]}'
        , epj_division ='{$data['cnt']}' 
    ";
    pdo_query($db,$insertQuery,array());
}
    $result['code']='TRUE';
    $result['msg']='프로젝트 정보가 수정 요청 되었습니다.';
    echo json_encode($result);

?>
