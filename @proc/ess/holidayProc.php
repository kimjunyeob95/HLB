<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
/**
 * 안씁니다~~~~~~~~~~~~ 안써요~~~~
 */
@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq)){
	echo json_encode($result);
	exit;
}

//암호화 대상 필드
$enctArr = array('hlm_email','hlm_phone','hlm_address');
$insertQuery="INSERT INTO  ess_holiday_log  SET
					hlmmseq='".$mmseq."', 
					hlmRegdate = now()  ";
foreach ($_REQUEST as $key => $value){
		if(in_array($key,$enctArr)){
			$value = $enc->encrypt($value);
		}
		if($key =='mail_head' || $key =='mail_footer'){
			continue;
		}
		$insertQuery .= " , {$key} = '{$value}'";
}
$email = $enc->encrypt($_REQUEST['mail_head']."@".$_REQUEST['mail_footer']);
$count = (date('Ymd',strtotime($_REQUEST['hlm_eDate'])) - date('Ymd',strtotime($_REQUEST['hlm_sDate'])))+1;
$insertQuery .= ", hlm_email = '{$email}' , hisCount = '{$count}'";
// 휴가 차감

$query = "update ess_member_code set 
				 mc_commute_all = mc_commute_all-{$count},
				 mc_commute_use = mc_commute_use+{$count},
				 mc_commute_remain = mc_commute_remain-{$count} 
				 where mc_mmseq = {$mmseq} and mc_coseq = {$coseq}";

pdo_query($db,$insertQuery,array());
$result['code']='TRUE';
$result['msg']='휴가 신청이 완료되었습니다.';
echo json_encode($result);


?>
