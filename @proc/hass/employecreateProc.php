<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mm_name = $_REQUEST['mm_name'];
@$mm_email = $_REQUEST['mm_email'];

@$mm_name_real = $_REQUEST['mm_name'];
@$mm_email_real = $_REQUEST['mm_email'];

$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($mc_coseq)){
    echo json_encode($result);
    exit;
}
$query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($_REQUEST['mm_email'])}' and mm_is_del = 'FALSE' ";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if($data['cnt'] >0){
    $result['msg']='중복된 이메일이 있습니다.';
    echo json_encode($result);
    exit;
}
$query = "select right(mc_code,4) as mc_code from ess_member_code emc where mc_coseq = {$mc_coseq} order by mc_code desc limit 1";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$mc_code = sprintf("%06d",substr(date('Y'),2,2).sprintf("%04d",$data['mc_code'] * 1 + 1));

//암호화 대상 필드
$enctArr = array('mm_name','mm_email');
$insertQuery="INSERT INTO  ess_member_base  SET
					mm_regdate = now()  ";
foreach ($_REQUEST as $key => $value){
    if(in_array($key,$enctArr)){
        $value = $enc->encrypt($value);
    }
    $insertQuery .= " , {$key} = '{$value}'";
}
pdo_query($db,$insertQuery,array());
$query = "SELECT LAST_INSERT_ID() as id;";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$insertQuery="INSERT INTO  ess_member_code  SET
					mc_code ='{$mc_code}' , mc_coseq = {$mc_coseq} ,mc_mmseq = {$data['id']} , mc_regdate = now() , mc_main= 'T' ";
pdo_query($db,$insertQuery,array());

sendmail_newReg($data_coperation['co_name'],$mm_name_real,$mm_email_real,$mc_code);

$result['code']='TRUE';
$result['msg']='신규 사번이 생성되었습니다.';
echo json_encode($result);


?>
