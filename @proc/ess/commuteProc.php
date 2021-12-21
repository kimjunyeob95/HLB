<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

@$mc_commute_use = $_REQUEST['mc_commute_use'];
// if($mc_commute_use > 1){
//     echo('1보다큼');exit;
// }else{
//     echo('1보다작음');exit;
// }
// echo('<pre>');print_r($mc_commute_use);echo('</pre>');exit;
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq)){
	echo json_encode($result);
	exit;
}
if(empty($mc_commute_use)){
    $result['msg']='휴가 사용일수를 입력해주세요.';
	echo json_encode($result);
	exit;
}

if($_REQUEST['tc_div']==2 || $_REQUEST['tc_div']==3){
	$date1= new DateTime($_REQUEST['tc_sdate']);
	$date2= new DateTime($_REQUEST['tc_edate']);
	$date_format = $date1->diff($date2);
	$count = $mc_commute_use;
	if($count > 1){
		$result['msg']='날짜를 다시 선택해주세요. ';
		echo json_encode($result);
		exit;
	}
	$count = 0.5;
}else{
	$date1= new DateTime($_REQUEST['tc_sdate']);
	$date2= new DateTime($_REQUEST['tc_edate']);
	$date_format = $date1->diff($date2);
	$count = (int)$mc_commute_use;
}
// echo('<pre>');print_r(gettype($count));echo('</pre>');exit;
$query = "select * from ess_member_code where  mc_mmseq = {$mmseq} and mc_coseq = {$coseq}";
$ps = pdo_query($db,$query,array());
$cnt = $ps ->fetch(PDO::FETCH_ASSOC);
if(($cnt['mc_commute_remain']*1) - $count < 0){
	$result['code']='FALSE';
	$result['msg']='휴가가 부족합니다.';
	echo json_encode($result);
	exit;
}
// 파일 업로드
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/commute';
$filesname = $_FILES['tc_file']['name'];

if(!empty($filesname)){
	$tmp_file_check = file_check_fn($_FILES['tc_file']);
	for($i=0;$i<sizeof($tmp_file_check);$i++){
		if($tmp_file_check[$i]['result']=='FALSE'){
			$result['msg']='업로드 할 수 없는 형식의 파일입니다.';
			echo json_encode($result);
			exit;
		}
	}
}
if(empty($filesname)==FALSE){
	list($_ori_filename, $ext) = explode('.', $_FILES['tc_file']['name']);
	$ext = pathinfo( $_FILES['tc_file']['name'], PATHINFO_EXTENSION);
	$filename =  @$seq.'_1_'.date('YmdHis');
	$photo = $_FILES['tc_file']['tmp_name'];
	move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
	$location = "/data/commute/".$filename.".".$ext;
	$file_query = " , tc_file = '{$location}' ";
}

//암호화 대상 필드
$enctArr = array('tc_email','tc_phone','tc_address');
$insertQuery="INSERT INTO  tbl_commute  SET
					tc_mmseq='".$mmseq."', 
					tc_regdate = now() ".$file_query;
foreach ($_REQUEST as $key => $value){
		if(in_array($key,$enctArr)){
			$value = $enc->encrypt($value);
		}
		if($key =='mail_head' || $key =='mail_footer' || $key == 'mc_commute_use'){
			continue;
		}
		$insertQuery .= " , {$key} = '{$value}'";
}
$email = $enc->encrypt($_REQUEST['mail_head']."@".$_REQUEST['mail_footer']);

$insertQuery .= ", tc_email = '{$email}' , tc_vacation_count = '{$count}' , tc_coseq = {$coseq}";
pdo_query($db,$insertQuery,array());

$query = "update ess_member_code set 
				 mc_commute_use = mc_commute_use+{$count},
				 mc_commute_remain = mc_commute_remain-{$count} 
				 where mc_mmseq = {$mmseq} and mc_coseq = {$coseq}";

pdo_query($db,$query,array());

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select emb.mm_email, emb.mm_name from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$mmseq} ";
$ps = pdo_query($db,$query,array());
$data_user = $ps ->fetch(PDO::FETCH_ASSOC);

$mm_name = $enc->decrypt($data_user['mm_name']);

$query = "select emb.mm_email from tbl_ess_group teg join ess_member_base emb on teg.tg_mms_mmseq = emb.mmseq where teg.tg_seq in (
    select trg_group from ess_member_code emc join tbl_relation_group trg on emc.mc_mmseq = trg.trg_mmseq where emc.mc_mmseq = {$mmseq} group by trg_group)
    and tg_mms_mmseq <> '' and tg_coseq ={$coseq}";
$ps = pdo_query($db,$query,array());
$list_mss = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_mss,$data_gnb);
}

$holiday_date = $_REQUEST['tc_sdate'].' ~ '.$_REQUEST['tc_edate'];
$holiday_content = $_REQUEST['tc_content']; //사유
foreach($list_mss as $index => $val){
    $mm_email = $enc->decrypt($val['mm_email']);
    sendmail_essHoliday($data_coperation['co_name'],$mm_name,$mm_email,$holiday_date,$holiday_content);
};


$result['code']='TRUE';
$result['msg']='휴가 신청이 되었습니다.';
echo json_encode($result);

?>
