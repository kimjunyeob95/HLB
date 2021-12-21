<?php
	/*************************
	파일명 : login_proc.php
	기 능  : 로그인 처리
	**************************/
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';

	session_start();
	foreach($_SESSION as $key => $val) {
		$_SESSION[$key] = '';
	}
    $enc = new encryption();
 	$result['code']='FALSE';
	$result['msg']='잘못된 접근입니다.';

	$id = $_REQUEST['id'];
	$pwd = $_REQUEST['pwd'];
    $mm_last_ip = $_SERVER["REMOTE_ADDR"];

	if(empty($id) || empty($pwd)){
		echo json_encode($result);
		exit;
	}

    $pwd = $enc->encrypt($pwd);

	$query="SELECT COUNT(*) as cnt,mem.* 
			FROM tbl_manager_member as mem 
			WHERE mem.mm_id=? and mem.mm_pwd=?";
	$ps = pdo_query($db,$query,array($id,$pwd));
	$data = $ps->fetch(PDO::FETCH_ASSOC);

	if($data['cnt']<1){
		$result['msg']='일치하는 정보가 없습니다.';
		echo json_encode($result);
		exit;
    }
    
    
    $query = "UPDATE tbl_manager_member SET mm_last_ip='{$mm_last_ip}', mm_last_login=now() WHERE mm_seq = '{$data['mm_seq']}' ";
    $ps = pdo_query($db,$query,array());
	/*
		$ip = getUserIP();
		if($ip!="121.138.71.31" && $ip!="112.220.91.42" && $ip!="115.142.197.74" && $ip!="125.129.44.233" &&  $ip!="1.239.137.220" && $ip!="168.154.231.232" && $ip!="125.143.153.61" && $ip!="27.1.107.119"){
			$result['msg']='허가되지 않은 IP 입니다.';
			echo json_encode($result);
			exit;
		}
	*/

	$_SESSION['admin_idx'] = $data['mm_seq'];
	$_SESSION['admin_name'] = $data['mm_name'];
	$_SESSION['mm_auth_num'] = $data['mm_auth_num'];
	$_SESSION['admin_info'] = $data;

	$result['code']='TRUE';
	$result['msg'] =$data['mm_name']."님 환영합니다.";
	echo json_encode($result);
		

	?>