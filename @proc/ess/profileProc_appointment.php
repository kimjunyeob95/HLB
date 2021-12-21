<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}
//암호화 대상 필드
$enctArr = array('ea_company');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}
$query = "update ess_appointment_log set ea_state = 'C' where ea_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT ea_division+1 as cnt FROM ess_appointment_log WHERE ea_mmseq=".$mmseq." order by ea_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}
for($i=0;$i<sizeof($_REQUEST['ea_company']);$i++){
    $insertQuery="INSERT INTO  ess_appointment_log  SET
					ea_mmseq='".$mmseq."', 
					ea_applydate = now()  ";
    $insertQuery .=  "
        , ea_company = '{$_REQUEST['ea_company'][$i]}'
        , ea_position = '{$_REQUEST['ea_position'][$i]}'
        , ea_job = '{$_REQUEST['ea_job'][$i]}'
        , ea_date = '{$_REQUEST['ea_date'][$i]}'
        , ea_type = '{$_REQUEST['ea_type'][$i]}'
        , ea_division ='{$data['cnt']}' 
    ";
    pdo_query($db,$insertQuery,array());
}
    $result['code']='TRUE';
    $result['msg']='발령 정보가 수정 요청 되었습니다.';
    echo json_encode($result);

?>
