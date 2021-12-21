<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

@$mc_seq = $_REQUEST['mc_seq'];
@$mm_seq = $_SESSION['mmseq'];
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($mc_seq) || empty($mm_seq)){
	echo json_encode($result);
	exit;
}

$userPw = $enc->encrypt($userPw);

$query="SELECT COUNT(A.mmseq) as cnt, A.*,tc.* ,emc.*
				FROM ess_member_base A 
				join ess_member_code emc on A.mmseq = emc.mc_mmseq
				join tbl_coperation tc on tc.co_seq = emc.mc_coseq
				WHERE A.mmseq =? and emc.mc_seq=?";
$ps = pdo_query($db,$query,array($mm_seq,$mc_seq));
$data = $ps->fetch(PDO::FETCH_ASSOC);

if($data['cnt']<1){
	$result['code']='FALSE';
	$result['msg']='일치하는 정보가 없습니다.';
	echo json_encode($result);
	exit;
}else if($data['mm_status'] != 'Y'){
    $result['code']='FALSE';
    $result['msg']='정식직원이 아닙니다.';
    echo json_encode($result);
    exit;
}else{
	$_SESSION['mmseq']=$data['mmseq'];
	$_SESSION['mInfo']=$data;

	$query=" UPDATE ess_member_base SET
					mm_last_login =now()
					WHERE mmseq=?";
	$ps = pdo_query($db,$query,array($data['mmseq']));
	
	$result['code']='TRUE';
	$result['msg']= $enc->decrypt($data['mm_name']).'님 환영 합니다.';
    $result['type'] = $data['mc_hass'];
	echo json_encode($result);
	
}


?>