<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$apply_mmseq = $_SESSION['mmseq'];
@$mmseq =  $_REQUEST['seq'];
@$type =  $_REQUEST['type'];
@$mc_position =  $_REQUEST['mc_position'];
@$mc_position2 =  $_REQUEST['mc_position2'];
@$mc_group =  $_REQUEST['mc_group'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$text_type =  $_REQUEST['text_type'];
@$mc_job =  $_REQUEST['mc_job'];
@$mc_job2 =  $_REQUEST['mc_job2'];
@$mc_commute_all =  $_REQUEST['mc_commute_all'];
@$mc_commute_use =  $_REQUEST['mc_commute_use'];
@$mc_commute_remain =  $_REQUEST['mc_commute_remain'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
// echo('<pre>');print_r($mmseq);echo('</pre>');exit;

if(empty($apply_mmseq) || empty($mmseq) || empty($type)){

    echo json_encode($result);
    exit;
}

//암호화 대상 필드
$updateQuery="update  ess_member_base SET mm_confirm_date = now() , mm_apply_mmseq = {$apply_mmseq} , mm_status = '{$type}' where mmseq = {$mmseq}";
pdo_query($db,$updateQuery,array());
if($type=='Y'){
    $updateQuery="update  ess_member_code SET mc_affiliate_date= '{$_REQUEST['mc_affiliate_date']}', mc_bepromoted_date= '{$_REQUEST['mc_bepromoted_date']}'
                  ,mc_regdate= '{$_REQUEST['mc_regdate']}' , mc_position = {$mc_position} , mc_position2 = {$_REQUEST['mc_position2']}
                  , mc_position3 = {$_REQUEST['mc_position3']} , mc_position4 = {$_REQUEST['mc_position4']} , mc_position5 = {$_REQUEST['mc_position5']} ,mc_job2 = '{$mc_job2}' , mc_position2 = {$mc_position2} 
                  , mc_job = '{$mc_job}' , mc_commute_all = {$mc_commute_all} , mc_commute_use = {$mc_commute_use} , mc_commute_remain = {$mc_commute_remain} , mc_commute_sdate = '{$_REQUEST['mc_commute_sdate']}' , mc_commute_edate = '{$_REQUEST['mc_commute_edate']}' 
                  where mc_mmseq = {$mmseq}";
    pdo_query($db,$updateQuery,array());
}
$insertQuery = "delete from tbl_relation_group where trg_mmseq = {$mmseq} and trg_coseq = {$mc_coseq}";
pdo_query($db,$insertQuery,array());
foreach ($_REQUEST['mm_group'] as $val){
    $insertQuery = "insert tbl_relation_group set trg_mmseq = {$mmseq} , trg_group = {$val}, trg_regdate = now(),trg_coseq = {$mc_coseq}";
    pdo_query($db,$insertQuery,array());
}

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name ,emc.mc_code, emb.mm_password from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$mmseq} ";
$ps = pdo_query($db,$query,array());
$data_user = $ps ->fetch(PDO::FETCH_ASSOC);

$password = $enc->decrypt($data_user['mm_password']);
$to_email = $enc->decrypt($data_user['mm_email']);
$mm_name = $enc->decrypt($data_user['mm_name']);

if($type=='Y'){
    sendmail_newYes($data_coperation['co_name'],$mm_name,$to_email,$data_user['mc_code'],$password);
}else{
    sendmail_newNo($data_coperation['co_name'],$mm_name,$to_email);
}



$result['code']='TRUE';
$result['msg']= $text_type.' 처리되었습니다.';
echo json_encode($result);

?>
