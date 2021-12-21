<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

@$userName = $_REQUEST['userName'];
@$userCode = $_REQUEST['userCode'];
@$coperation = $_REQUEST['coperation'];
@$mm_last_ip = $_SERVER["REMOTE_ADDR"];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($userName) || empty($userCode) || empty($coperation)){
	echo json_encode($result);
	exit;
}

$userName = $enc->encrypt($userName);
$userml = $enc->encrypt($userml);

$query="SELECT COUNT(A.mmseq) as cnt, A.*,tc.* ,emc.*
				FROM ess_member_base A 
				join ess_member_code emc on A.mmseq = emc.mc_mmseq 
				join tbl_coperation tc on tc.co_seq = emc.mc_coseq
				WHERE mm_name=? and mc_code=? and co_seq = ? and mc_main ='T'"; //and mm_status ='S'
$ps = pdo_query($db,$query,array($userName,$userCode,$coperation));
$data = $ps->fetch(PDO::FETCH_ASSOC);

if($data['cnt']<1){
	$result['code']='FALSE';
	$result['msg']='일치하는 정보가 없습니다.';
	echo json_encode($result);
	exit;
}else if($data['mm_status'] == 'A'){
    $result['code']='FALSE';
    $result['msg']='신규정보 입력이 완료되었습니다. 인사담당자에게 문의바랍니다.';
    echo json_encode($result);
    exit;
} else if($data['mm_status'] != 'S' && $data['mm_status'] != 'N'){
    $result['code']='FALSE';
    $result['msg']='해당 로그인 접근 권한이 없습니다.';
    echo json_encode($result);
    exit;
}else{
	$_SESSION['mmseq']=$data['mmseq'];
	$_SESSION['mInfo']=$data;

	$query=" UPDATE ess_member_base SET
					mm_last_login =now(),
                    mm_last_ip = ?
					WHERE mmseq=?";
	$ps = pdo_query($db,$query,array($mm_last_ip,$data['mmseq']));
	
	$result['code']='TRUE';
	$result['msg']= $enc->decrypt($data['mm_name']).'님 환영 합니다.';
    $result['step'] = $data['mm_save_step'];
	echo json_encode($result);
	
}


?>