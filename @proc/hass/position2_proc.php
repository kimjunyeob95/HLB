<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$query = "select tp2_seq from tbl_position2 where tp2_coseq ={$mc_coseq}";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}

foreach ($_REQUEST['data'] as $key => $value){
    if($value[1] ==0){
        $query = "insert tbl_position2 set tp2_coseq ={$mc_coseq}, tp2_title='{$value[0]}', tp2_regdate = now()";
    }else{
        $query = "update tbl_position2 set tp2_title='{$value[0]}', tp2_moddate = now() where tp2_seq = {$value[1]}";
    }
    foreach ($list as $key => $remove_val){ // 삭제된 리스트
        if (in_array($value[1], $remove_val)) {
            array_splice($list, $key, 1);
        }
    }
    pdo_query($db,$query,array());
}
foreach ($list as $key => $value){
    $query = "delete from tbl_position2 where tp2_seq  = {$value['tp_seq']}";
    pdo_query($db,$query,array());
}

// 삭제 하위조직에 포함된 구성원도 초기화 시켜줘야함
$result['code']='TRUE';
$result['msg']= ' 처리되었습니다.';
echo json_encode($result);
?>