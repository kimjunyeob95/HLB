<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}
//암호화 대상 필드
// $enctArr = array('el_name');
// foreach ($_REQUEST as $key => $value){
//     foreach ($value as $key2 =>$value2){
//         if(in_array($key,$enctArr )){
//             $_REQUEST[$key][$key2] = $enc->encrypt($value2);

//         }
//     }
// }
$query = "update ess_education_log set el_state = 'C' where el_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT el_division+1 as cnt FROM ess_education_log WHERE el_mmseq=".$mmseq." order by el_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}
for($i=0;$i<sizeof($_REQUEST['el_name']);$i++){
    $insertQuery="INSERT INTO  ess_education_log  SET
					el_mmseq='".$mmseq."', 
					el_applydate = now()  ";
    $insertQuery .=  "
        , el_name = '{$_REQUEST['el_name'][$i]}'
        , el_sdate = '{$_REQUEST['el_sdate'][$i]}'
        , el_edate = '{$_REQUEST['el_edate'][$i]}'
        , el_weekly = '{$_REQUEST['el_weekly'][$i]}'
        , el_level = '{$_REQUEST['el_level'][$i]}'
        , el_degree = '{$_REQUEST['el_degree'][$i]}'
        , el_division ='{$data['cnt']}' 
        , el_major = '{$_REQUEST['el_major'][$i]}'
        , el_graduate_type = '{$_REQUEST['el_graduate_type'][$i]}'
        , el_etc ='{$_REQUEST['el_etc'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$mmseq} ";
$ps = pdo_query($db,$query,array());
$data_user = $ps ->fetch(PDO::FETCH_ASSOC);

$mm_name = $enc->decrypt($data_user['mm_name']);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mc_coseq = {$mc_coseq} and mm_status = 'Y' and mc_hass='T' ";
$ps = pdo_query($db,$query,array());
$list_hass = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_hass,$data_gnb);
}
foreach($list_hass as $index => $val){
    $to_email = $enc->decrypt($val['mm_email']);
    sendmail_essInfo($data_coperation['co_name'],$mm_name,$to_email,'학력사항');
}

    $result['code']='TRUE';
    $result['msg']='학력사항 정보가 수정 요청 되었습니다.';
    echo json_encode($result);

?>
