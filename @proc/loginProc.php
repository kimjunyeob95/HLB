<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

@$userId = $_REQUEST['userId'];
@$userPw = $_REQUEST['userPw'];
@$coseq = $_REQUEST['coseq'];
@$mm_last_ip = $_SERVER["REMOTE_ADDR"];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($userId) || empty($userPw) || empty($coseq)){
	echo json_encode($result);
	exit;
}

$userPw = $enc->encrypt($userPw);

$query="SELECT COUNT(A.mmseq) as cnt, A.*,tc.* ,emc.*
				FROM ess_member_base A 
				join ess_member_code emc on A.mmseq = emc.mc_mmseq
				join tbl_coperation tc on tc.co_seq = emc.mc_coseq
				WHERE mc_code=? and mm_password=? and co_seq = ? and mm_is_del='FALSE' ";
$ps = pdo_query($db,$query,array($userId,$userPw,$coseq));
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
    if($data['mm_login_status']=='T'){
        $result['code']='FALSE';
        $result['msg']='로그인이 잠겨있는 계정입니다. 인사담당자에게 문의해주세요.';
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
        $result['type'] = $data['mc_hass'];
    }
	echo json_encode($result);
	
}


?>