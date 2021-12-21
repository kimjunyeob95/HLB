<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';


$query = "select * from tbl_coperation where co_is_del ='FALSE'";
$ps = pdo_query($db,$query,array());
$coperation_list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($coperation_list,$data);
}
// echo('<pre>');print_r($coperation_list);echo('</pre>');exit;
foreach($coperation_list as $index => $val){
    $query = "INSERT INTO ess_member_code SET
            mc_mmseq=200, mc_coseq={$val['co_seq']}, mc_code=200001, mc_regdate=now(), mc_main='T', mc_hass='T' ";
    $ps = pdo_query($db,$query,array());
};

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';


$result['code']='TRUE';
$result['msg']='신규 사번이 생성되었습니다.';
echo json_encode($result);


?>
