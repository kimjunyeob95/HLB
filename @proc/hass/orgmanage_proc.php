<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$pid = $_REQUEST['pid'];
$leader_mmseq = $_REQUEST['leader_mmseq'];
$data_date = $_REQUEST['data_date'];
//삭제된 리스트를 위해 자식 리스트 가져옴
$query = "select tg_seq from tbl_ess_group where tg_parent_seq = {$pid} and  tg_coseq ={$mc_coseq}";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}

 $query = "update tbl_ess_group set tg_mms_mmseq =  {$leader_mmseq}, tg_moddate = now() where tg_seq = {$pid}";
pdo_query($db,$query,array());
foreach ($_REQUEST['data'] as $key => $value){
    if($value[1] ==0){
        $query = "insert tbl_ess_group set tg_coseq ={$mc_coseq}, tg_title='{$value[0]}',tg_parent_seq = {$pid}, tg_regdate = now() , tg_sort_date = '{$data_date[$key]}'";
    }else{
        $query = "update tbl_ess_group set tg_title='{$value[0]}', tg_moddate = now() , tg_sort_date = '{$data_date[$key]}' where tg_seq = {$value[1]}";
    }
    foreach ($list as $key => $remove_val){ // 삭제된 리스트
        if (in_array($value[1], $remove_val)) {
            array_splice($list, $key, 1);
        }
    }
    pdo_query($db,$query,array());
}

foreach ($list as $key => $value){ // 삭제 하위조직포함 지우기
    $query = "delete from tbl_ess_group where tg_seq in 
                (SELECT tg_seq FROM
                    (SELECT tg_seq,tg_title,tg_parent_seq,tg_coseq,
                            CASE WHEN tg_seq = {$value['tg_seq']} THEN @idlist := CONCAT(tg_seq)
                                 WHEN FIND_IN_SET(tg_parent_seq,@idlist) THEN @idlist := CONCAT(@idlist,',',tg_seq)
                            END as checkId
                     FROM tbl_ess_group
                     ORDER BY tg_seq ASC) as T
                WHERE checkId IS NOT NULL and tg_coseq = {$mc_coseq})";
    pdo_query($db,$query,array());
}

// 삭제 하위조직에 포함된 구성원도 초기화 시켜줘야함
$result['code']='TRUE';
$result['msg']= ' 처리되었습니다.';
echo json_encode($result);
?>
