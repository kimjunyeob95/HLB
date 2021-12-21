<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($obj)){
	echo json_encode($result);
	exit;
}


//암호화 대상 필드
$enctArr = array('mm_name','mm_serial_no','mm_address','mm_address_detail','mm_phone','mm_cell_phone','mm_email');

$changeCnt=0;

$insertQuery="INSERT INTO  ess_change_infomation_log  SET
					cli_mmseq='".$mmseq."', 
					cli_regdate=now()  ";



for($i=0;$i<sizeof($obj);$i++){
	
	$field = $obj[$i][0];
	$value = $obj[$i][1];



	if(in_array($field,$enctArr )){
		$value = $enc->encrypt($value);
	}


	$query="SELECT COUNT(mmseq) as cnt FROM ess_member_base WHERE ".$field." = '".$value."' and mmseq=".$mmseq;

	$ps = pdo_query($db,$query,array());
	$data = $ps->fetch(PDO::FETCH_ASSOC);

	if($data['cnt']<1){
		$changeCnt++;
		$insertQuery.=" , cli_".$field." = '".$value."' ";
	}

}



pdo_query($db,$insertQuery,array());

$result['code']='TRUE';
$result['changeCnt'] = $changeCnt;
$result['msg']=$changeCnt.'개 항목의 정보가 수정 요청 되었습니다.';
echo json_encode($result);



?>
