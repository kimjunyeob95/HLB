<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$password_origin = $_REQUEST['password_origin'];
@$mm_password = $_REQUEST['mm_password'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($mm_password) || empty($password_origin)){
	page_move('/login.php','잘못된 접근입니다.');
}

//암호화 대상 필드
$mm_password = $enc->encrypt($mm_password);
$password_origin = $enc->encrypt($password_origin);

if(!empty($password_origin)){
    $query="SELECT count(*) as cnt FROM ess_member_base WHERE mmseq=? AND mm_password=? ";
    $ps = pdo_query($db,$query,array($mmseq,$password_origin));
    $data = $ps->fetch(PDO::FETCH_ASSOC);
    if($data['cnt']<1){
        return page_move('/include/changepw.php','기존 비밀번호가 다릅니다.');;
    }
}
$query="UPDATE ess_member_base SET mm_password=? WHERE mmseq=?";
$ps = pdo_query($db,$query,array($mm_password,$mmseq));

//메일 발송
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM ess_member_base WHERE mmseq = {$mmseq}";
    // echo('<pre>');print_r($query);echo('</pre>');exit;
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);

$to_email = $enc->decrypt($data['mm_email']);
$mm_name = $enc->decrypt($data['mm_name']);

sendmail_pwChange($data_coperation['co_name'],$mm_name,$to_email);

page_move('/@proc/logout.php','비밀번호가 변경되었습니다.');

?>
