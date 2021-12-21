<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';

$search = $enc->encrypt($_REQUEST['search']);
$coseq = $_SESSION['mInfo']['mc_coseq'];
$query = " select mmseq,mm_name,mc_code,mm_status,mc_position from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq 
           where mc_coseq = {$coseq} and  mm_name = '{$search}' and mm_status='Y' and mm_super_admin='F' ";
           
$ps = pdo_query($db,$query,array());
$employe_list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($employe_list,$data);
}

// echo('<pre>');print_r($employe_list);echo('</pre>');exit;
if(empty($employe_list)){
    $employe_list[0] = '사원명을 정확하게 입력해주세요.';
}else {
    foreach($employe_list as $index => $val){
        $employe_list[$index]['position'] = get_position_title($db,$val['mc_position']);
        $employe_list[$index]['group'] = implode(' / ',get_group_list_v2($db,$val['mmseq']));
        $employe_list[$index]['mm_name']= $enc->decrypt($val['mm_name']);
        
        // $val['mm_name'] = 123;
    }
    // $result['mm_name'] = $enc->decrypt($data['mm_name']) . "(" . $data['mc_code'] . ")";
}
// $result['mmseq'] = $data['mmseq'];
echo json_encode($employe_list);
?>