<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$step =  get_member_step($db,$mmseq);
$member_profile = get_member_info($db,$mmseq);
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($mc_coseq)){
	echo json_encode($result);
	exit;
}

$query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($_REQUEST['em_email'])}' and mm_is_del = 'FALSE' and mmseq <> {$mmseq} ";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if($data['cnt'] >0){
	$result['msg']='중복된 이메일이 있습니다.';
	echo json_encode($result);
	exit;
}

// 파일 업로드
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
$filesname = $_FILES['em_profile']['name'];

if(!empty($filesname)){
	$tmp_file_check = file_check_fn($_FILES['em_profile']);
	for($i=0;$i<sizeof($tmp_file_check);$i++){
		if($tmp_file_check[$i]['result']=='FALSE'){
			$result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
			echo json_encode($result);
			exit;
		}
	}
}
if(empty($filesname)==FALSE){
	list($_ori_filename, $ext) = explode('.', $_FILES['em_profile']['name']);
	$ext = pathinfo( $_FILES['em_profile']['name'], PATHINFO_EXTENSION);
	$filename =  @$seq.'_1_'.date('YmdHis');
	$photo = $_FILES['em_profile']['tmp_name'];
	move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
	$location = "/data/profile/".$filename.".".$ext;
	$_REQUEST['em_profile'] = $location;
	//$file_query = " , em_profile = '{$location}' ";
}else{
	$_REQUEST['em_profile'] = $member_profile['mm_profile'];
	//$file_query = " , em_profile = '{$member_profile['mm_profile']}' ";
}

$query = "update ess_member_log set em_state = 'C' where em_mmseq=".$mmseq;
$ps = pdo_query($db,$query,array());
//여러개 신청이 있을경우 대비 (구분)
$query="SELECT em_division+1 as cnt FROM ess_member_log WHERE em_mmseq=".$mmseq." order by em_division desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
	$data['cnt'] = 1;
}

//암호화 대상 필드
$enctArr = array('em_serial_no','em_password','em_name','em_email','em_address','em_address_detail','em_phone','em_cell_phone','em_prepare_phone');
$insertQuery = "insert ess_member_log SET em_applydate = now() ".$file_query;
foreach ($_REQUEST as $key => $value){
	if($key =='em_arm_sdate' || $key =='em_arm_edate'){
		continue;
	}
	if(in_array($key,$enctArr)){
		$value = $enc->encrypt($value);
	}
	$insertQuery .= " , {$key} = '{$value}' ";
}

if(!empty($_REQUEST['em_arm_sdate'])){
	$insertQuery .= " , em_arm_sdate = '{$_REQUEST['em_arm_sdate']}' ";
}
if(!empty($_REQUEST['em_arm_edate'])){
	$insertQuery .= " , em_arm_edate = '{$_REQUEST['em_arm_edate']}' ";
}
$insertQuery .= " , em_mmseq = {$mmseq} 
        , em_division ='{$data['cnt']}' ";
pdo_query($db,$insertQuery,array());

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
$result['msg']='기본정보 수정 요청되었습니다.';
echo json_encode($result);


?>
