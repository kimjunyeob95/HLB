<?php
	/*************************
	파일명 : memberAuth_proc.php
	기 능  : 권한 처리
	**************************/
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';
    // echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;

 	$result['code']='FALSE';
    $result['msg']='잘못된 접근입니다.';
    $result['title']='ess';

	$mmseq = $_REQUEST['mmseq'];
    $mc_coseq = $_REQUEST['mc_coseq'];
    $authority = $_REQUEST['authority'];

	if(empty($mmseq) || empty($mc_coseq) || empty($authority)){
		echo json_encode($result);
		exit;
    }

    $query = "update ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq set emc.mc_hass = '{$authority}' where mmseq = {$mmseq} and mc_coseq = {$mc_coseq}";
    $ps = pdo_query($db,$query,array());
    
    if($authority=='T'){
        $result['title']='hass';
    }else{
        $result['title']='ess';
    }


	$result['code']='TRUE';
	$result['msg'] = $result['title']."권한으로 저장되었습니다.";
	echo json_encode($result);
		

	?>