<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mc_code = $_SESSION['mInfo']['mc_code'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
@$mm_last_ip = $_SERVER["REMOTE_ADDR"];

if(empty($mmseq) || empty($mc_coseq)){
	echo json_encode($result);
	exit;
}

$query=" UPDATE ess_member_base SET
					mm_applydate =now(),
					mm_status = 'A',
					mm_save_step = 4,
                    mm_last_ip = ?
					WHERE mmseq=?";
$ps = pdo_query($db,$query,array($mm_last_ip,$mmseq));

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq 
where mc_coseq = {$mc_coseq} and mm_status = 'Y' and mc_hass='T' and mm_super_admin='F' and mm_is_del = 'FALSE' ";
$ps = pdo_query($db,$query,array());
$list_hass = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_hass,$data_gnb);
}

foreach($list_hass as $index => $val){
    $to_email = $enc->decrypt($val['mm_email']);
    $mm_name = $enc->decrypt($val['mm_name']);
    sendmail_newReg_toHass($data_coperation['co_name'],$mm_name,$to_email,$mc_code);
}

$result['code']='TRUE';
$result['msg']='개인정보 등록이 신청되었습니다.';
echo json_encode($result);


?>
