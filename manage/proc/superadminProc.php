<?php
	/*************************
	파일명 : superadminProc.php
	기 능  : superAdmin 처리
	**************************/
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';

    session_start();
    // echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
    $enc = new encryption();

 	$result['code']='FALSE';
	$result['msg']='잘못된 접근입니다.';
    $result['title'] = '신규등록';

	$mmseq = $_REQUEST['mmseq'];
    $mc_code = $_REQUEST['mc_code'];
    $mm_name = $_REQUEST['mm_name'];
    $mm_password = $_REQUEST['mm_password'];
    $mm_email = $_REQUEST['mm_email'];
    $mm_last_ip = $_SERVER["REMOTE_ADDR"];

	if(empty($mc_code) || empty($mm_name) || empty($mm_password) || empty($mm_email)){
		echo json_encode($result);
		exit;
    }
    
    $query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq WHERE co_is_del='FALSE' order by co_seq asc";
    $ps2 = pdo_query($db, $query, array());
    $coperationList = array();
    while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
        array_push($coperationList, $data2);
    }
    
    if($mmseq == 'false'){
        //신규등록

        //이메일 검사(기본)
        $query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($mm_email)}' and mm_is_del = 'FALSE' ";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        if($data['cnt'] > 0){
            $result['msg']='중복된 이메일이 있습니다.';
            echo json_encode($result);
            exit;
        }

        $query = "INSERT INTO ess_member_base SET mm_name = '{$enc->encrypt($mm_name)}', mm_password = '{$enc->encrypt($mm_password)}',
        mm_regdate=now(), mm_birth=now(), mm_email = '{$enc->encrypt($mm_email)}', mm_last_ip = '{$mm_last_ip}', mm_super_admin='T', mm_hass='T', mm_status='Y' ";
        $ps = pdo_query($db,$query,array());

        $query = "SELECT LAST_INSERT_ID() as id;";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);

        foreach($coperationList as $index => $val){
            $query = "INSERT INTO ess_member_code SET
                mc_mmseq={$data['id']}, mc_coseq={$val['co_seq']}, mc_code={$mc_code}, mc_regdate=now(), mc_main='T', mc_hass='T' ";
            $ps = pdo_query($db,$query,array());
        }
    }else{
        //수정
        $result['title'] = '수정';

        //이메일 검사
        $query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc 
        on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($mm_email)}' and mm_is_del = 'FALSE' and mmseq <> {$mmseq}";
        $ps = pdo_query($db,$query,array());
        $data = $ps->fetch(PDO::FETCH_ASSOC);
        if($data['cnt'] > 1){
            $result['msg']='중복된 이메일이 있습니다.';
            echo json_encode($result);
            exit;
        }

        $query = "UPDATE ess_member_base SET mm_name = '{$enc->encrypt($mm_name)}', mm_last_ip = '{$mm_last_ip}', mm_password = '{$enc->encrypt($mm_password)}', mm_email = '{$enc->encrypt($mm_email)}' WHERE mmseq = {$mmseq}";
        $ps = pdo_query($db,$query,array());
        foreach($coperationList as $index => $val){
            $query = "UPDATE ess_member_code SET mc_code={$mc_code} WHERE mc_mmseq = {$mmseq}";
            $ps = pdo_query($db,$query,array());
        }
    }

	$result['code']='TRUE';
	$result['msg'] = $result['title']."처리 되었습니다.";
	echo json_encode($result);
		

	?>