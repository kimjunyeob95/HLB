<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$step =  get_member_step($db,$mmseq);
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($mc_coseq)){
	echo json_encode($result);
	exit;
}

// 파일 업로드
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
$filesname = $_FILES['mm_profile']['name'];

if(!empty($filesname)){
	$tmp_file_check = file_check_fn($_FILES['mm_profile']);
	for($i=0;$i<sizeof($tmp_file_check);$i++){
		if($tmp_file_check[$i]['result']=='FALSE'){
			$result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
			echo json_encode($result);
			exit;
		}
	}
}
if(empty($filesname)==FALSE){
	list($_ori_filename, $ext) = explode('.', $_FILES['mm_profile']['name']);
	$ext = pathinfo( $_FILES['mm_profile']['name'], PATHINFO_EXTENSION);
	$filename =  @$seq.'_1_'.date('YmdHis');
	$photo = $_FILES['mm_profile']['tmp_name'];
	move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
	$location = "/data/profile/".$filename.".".$ext;
	$file_query = " , mm_profile = '{$location}' ";
}

//암호화 대상 필드
$enctArr = array('mm_serial_no','mm_password','mm_address','mm_address_detail','mm_phone','mm_cell_phone','mm_prepare_phone');
$insertQuery="update  ess_member_base SET mm_last_update = now() ".$file_query;
foreach ($_REQUEST as $key => $value){
	if(in_array($key,$enctArr)){
		$value = $enc->encrypt($value);
	}
	if($key=='mm_re_password' || $key=='mm_arm_type' || $key=='mm_arm_reason' || $key=='mm_arm_group'
	|| $key=='mm_arm_class' || $key=='mm_arm_discharge' || $key=='mm_arm_sdate' || $key=='mm_arm_edate'){
		continue;
	}
	$insertQuery .= " , {$key} = '{$value}' ";
}
if(!empty($_REQUEST['mm_arm_type'])){
	$insertQuery .= " , mm_arm_type = '{$_REQUEST['mm_arm_type']}' ";
}
if(!empty($_REQUEST['mm_arm_reason'])){
	$insertQuery .= " , mm_arm_reason = '{$_REQUEST['mm_arm_reason']}' ";
}
if(!empty($_REQUEST['mm_arm_group'])){
	$insertQuery .= " , mm_arm_group = '{$_REQUEST['mm_arm_group']}' ";
}
if(!empty($_REQUEST['mm_arm_class'])){
	$insertQuery .= " , mm_arm_class = '{$_REQUEST['mm_arm_class']}' ";
}
if(!empty($_REQUEST['mm_arm_discharge'])){
	$insertQuery .= " , mm_arm_discharge = '{$_REQUEST['mm_arm_discharge']}' ";
}
if(!empty($_REQUEST['mm_arm_sdate'])){
	$insertQuery .= " , mm_arm_sdate = '{$_REQUEST['mm_arm_sdate']}' ";
}
if(!empty($_REQUEST['mm_arm_edate'])){
	$insertQuery .= " , mm_arm_edate = '{$_REQUEST['mm_arm_edate']}' ";
}
$insertQuery .= " where mmseq ='{$mmseq}'";
pdo_query($db,$insertQuery,array());
if(empty($step) || $step < 1) {
	pdo_query($db, "update  ess_member_base SET mm_save_step = 1 where mmseq ='{$mmseq}'", array());
}
$result['code']='TRUE';
$result['msg']='개인정보가 임시 저장되었습니다.';
echo json_encode($result);


?>
