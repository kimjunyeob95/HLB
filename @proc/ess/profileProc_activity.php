<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}
//암호화 대상 필드
$enctArr = array('eat_name','eat_institution');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}

$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
$query = "update ess_activity_log set eat_state = 'C' where eat_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT eat_division+1 as cnt FROM ess_activity_log WHERE eat_mmseq=".$mmseq." order by eat_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    $data['cnt'] = 1;
}

foreach ($_FILES['eat_file']['name'] as $f => $name) {
    $file_query ='';
    $filesname = '';
    $filesname = $_FILES['eat_file']['name'][$f];
    if (!empty($filesname)) {
        $tmp_file_check = file_check_fn($_FILES['eat_file'][$f]);
        for ($i = 0; $i < sizeof($tmp_file_check); $i++) {
            if ($tmp_file_check[$i]['result'] == 'FALSE') {
                page_move("/manage/boardcommon_view.php?seq=" . $seq, '이미지 파일이 업로드 할 수 없는 형식의 파일입니다.');
                exit;
            }
        }
    }
    if(!empty($_REQUEST['eat_file_remain'][$f])){
        $file_query = " , eat_file = '{$_REQUEST['eat_file_remain'][$f]}' , eat_file_name = '{$_REQUEST['eat_file_name_remain'][$f]}'";
    }
    if (empty($filesname) == FALSE) {
        list($_ori_filename, $ext) = explode('.', $_FILES['eat_file']['name'][$f]);
        $ext = pathinfo($_FILES['eat_file']['name'][$f], PATHINFO_EXTENSION);
        $filename = @$seq . '_1_' . date('YmdHis');
        $photo = $_FILES['eat_file']['tmp_name'][$f];
        move_uploaded_file($photo, $save_path . '/' . $filename . $f . "." . $ext);
        $location = "/data/profile/" . $filename . $f . "." . $ext;
        $file_query = " , eat_file = '{$location}' , eat_file_name = '{$filesname}'";
    }

    $insertQuery="INSERT INTO  ess_activity_log  SET
					eat_mmseq='".$mmseq."', 
					eat_applydate = now()  ";
    $insertQuery .=  "
        , eat_name = '{$_REQUEST['eat_name'][$f]}'
        , eat_sdate = '{$_REQUEST['eat_sdate'][$f]}'
        , eat_edate = '{$_REQUEST['eat_edate'][$f]}'
        , eat_type = '{$_REQUEST['eat_type'][$f]}'
        , eat_role = '{$_REQUEST['eat_role'][$f]}'
        , eat_institution = '{$_REQUEST['eat_institution'][$f]}'
        , eat_division ='{$data['cnt']}' 
    ";
    $insertQuery .= $file_query;
    pdo_query($db,$insertQuery,array());
}

//for($i=0;$i<sizeof($_REQUEST['eat_name']);$i++){
//    $insertQuery="INSERT INTO  ess_activity_log  SET
//					eat_mmseq='".$mmseq."',
//					eat_applydate = now()  ";
//    $insertQuery .=  "
//        , eat_sdate = '{$_REQUEST['eat_sdate'][$i]}'
//        , eat_edate = '{$_REQUEST['eat_edate'][$i]}'
//        , eat_type = '{$_REQUEST['eat_type'][$i]}'
//        , eat_name = '{$_REQUEST['eat_name'][$i]}'
//        , eat_institution = '{$_REQUEST['eat_institution'][$i]}'
//        , eat_division ='{$data['cnt']}'
//    ";
//    pdo_query($db,$insertQuery,array());
//}

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
    sendmail_essInfo($data_coperation['co_name'],$mm_name,$to_email,'교육 / 활동');
}

    $result['code']='TRUE';
    $result['msg']='교육 / 활동 정보가 수정 요청 되었습니다.';
    echo json_encode($result);

?>
