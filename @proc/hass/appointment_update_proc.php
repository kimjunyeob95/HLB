<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';

@$apply_mmseq = $_SESSION['mmseq'];
@$mmseq = $_REQUEST['mmseq'];
@$type = $_REQUEST['type'];
@$ta_seq = $_REQUEST['ta_seq'];
@$confirm = $_REQUEST['confirm'];
@$ta_prev_coseq = $_REQUEST['ta_prev_coseq'];
@$mc_position =  $_REQUEST['mc_position2'];
@$mc_position2 =  $_REQUEST['mc_position3'];
@$mc_group =  $_REQUEST['mc_group'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$text_type =  $_REQUEST['text_type'];
@$mc_job =  $_REQUEST['mc_job'];
@$mc_job2 =  $_REQUEST['mc_job2'];
@$mc_commute_all =  $_REQUEST['mc_commute_all'];
@$mc_commute_use =  $_REQUEST['mc_commute_use'];
@$mc_commute_remain =  $_REQUEST['mc_commute_remain'];


$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';
if(empty($type) || empty($mmseq) || empty($confirm)){
    echo json_encode($result);
    exit;
}

$query = "select right(mc_code,4) as mc_code from ess_member_code emc where mc_coseq = {$mc_coseq} order by mc_code desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$mc_code = sprintf("%06d",substr(date('Y'),2,2).sprintf("%04d",$data['mc_code'] * 1 + 1));
if($type==1){ //발령
    $updateQuery="update  ess_member_code SET mc_affiliate_date= '{$_REQUEST['mc_affiliate_date']}', mc_bepromoted_date= '{$_REQUEST['mc_bepromoted_date']}'
                  ,mc_regdate= '{$_REQUEST['mc_regdate']}' , mc_position = {$mc_position} , mc_position2 = {$_REQUEST['mc_position2']}
                  , mc_position3 = {$_REQUEST['mc_position3']} , mc_position4 = {$_REQUEST['mc_position4']} , mc_position5 = {$_REQUEST['mc_position5']} ,mc_job2 = '{$mc_job2}' , mc_position2 = {$mc_position2} 
                  , mc_job = '{$mc_job}' , mc_commute_all = {$mc_commute_all} , mc_commute_use = {$mc_commute_use} , mc_commute_remain = {$mc_commute_remain} , mc_commute_sdate = '{$_REQUEST['mc_commute_sdate']}' , mc_commute_edate = '{$_REQUEST['mc_commute_edate']}'
                  , mc_code = '{$mc_code}' ,  mc_coseq = {$mc_coseq} 
                  where mc_mmseq = {$mmseq} and mc_coseq = {$ta_prev_coseq}";
    pdo_query($db,$updateQuery,array());
    $title = '발령';
    $app_type = '8';
}else{ //겸직
    $insertQuery="insert into  ess_member_code set  mc_affiliate_date= '{$_REQUEST['mc_affiliate_date']}', mc_bepromoted_date= '{$_REQUEST['mc_bepromoted_date']}'
                  ,mc_regdate= '{$_REQUEST['mc_regdate']}' , mc_position = {$mc_position} 
                  , mc_position3 = {$_REQUEST['mc_position3']} , mc_position4 = {$_REQUEST['mc_position4']} , mc_position5 = {$_REQUEST['mc_position5']} ,mc_job2 = '{$mc_job2}' , mc_position2 = {$mc_position2} 
                  , mc_job = '{$mc_job}' , mc_commute_all = {$mc_commute_all} , mc_commute_use = {$mc_commute_use} , mc_commute_remain = {$mc_commute_remain} , mc_commute_sdate = '{$_REQUEST['mc_commute_sdate']}' , mc_commute_edate = '{$_REQUEST['mc_commute_edate']}'
                  ,mc_code = '{$mc_code}' , mc_coseq = {$mc_coseq} ,  mc_main = 'F'
                  ,mc_mmseq = {$mmseq} ";
    pdo_query($db,$insertQuery,array());
    $title = '겸직';
    $app_type = '5';
}

$insertQuery = "delete from tbl_relation_group where trg_mmseq = {$mmseq} and trg_coseq = {$mc_coseq}";
pdo_query($db,$insertQuery,array());
foreach ($_REQUEST['mm_group'] as $val){
    $insertQuery = "insert tbl_relation_group set trg_mmseq = {$mmseq} , trg_group = {$val}, trg_regdate = now(),trg_coseq = {$mc_coseq}";
    pdo_query($db,$insertQuery,array());
}

$insertQuery="update tbl_appointment set
                  ta_status = '{$confirm}', ta_confirm_date = now(), ta_confirm_seq = {$_SESSION['mmseq']}
                  where ta_mmseq = {$mmseq} and ta_seq = {$ta_seq}";
pdo_query($db,$insertQuery,array());

$get_date_query = "select tg_activity_date from tbl_appointment where ta_mmseq = {$mmseq} and ta_seq = {$ta_seq}";
$ps = pdo_query($db,$get_date_query,array());
$ma_date = $ps->fetch(PDO::FETCH_ASSOC);

$query = "select co_name,co_subname from tbl_coperation where co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$co_name = $ps->fetch(PDO::FETCH_ASSOC);
$co_name = $co_name['co_name'];
$po_name = get_position_title_type($db, $mc_position,2);
$po2_name = get_position_title_type($db, $mc_position2,3);
if(!empty($po_name) && !empty($po2_name)){
    $insertQuery = "insert member_appointment_data set ma_mmseq = {$mmseq} , ma_type = '{$app_type}' ,ma_company = '{$enc->encrypt($co_name)}' ,  ma_position2 = '{$po_name}' , ma_position3 = '{$po2_name}' , ma_regdate = now() , ma_date ='{$ma_date['tg_activity_date']}'";
    pdo_query($db,$insertQuery,array());
}

if($confirm=='Y'){
    $result['msg']='승인 완료되었습니다.';
}else{
    $result['msg']='반려 완료되었습니다.';
}
$result['code']='TRUE';

echo json_encode($result);

?>
