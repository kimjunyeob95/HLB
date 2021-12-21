<?

include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/mail_model.php';

/* 랜덤 문자열 생성 */
function GenerateString($length)  
{  
    $characters  = "0123456789";  
    $characters .= "abcdefghijklmnopqrstuvwxyz";  
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
    $characters .= "_";  
      
    $string_generated = "";  
      
    $nmr_loops = $length;  
    while ($nmr_loops--)  
    {  
        $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];  
    }  
    return $string_generated;  
}  

@$mmseq = $_REQUEST['mmseq'];
@$login_status = $_REQUEST['login_status'];
@$type = $_REQUEST['type'];
@$mm_admin_memo = $_REQUEST['mm_admin_memo'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];

$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

$reset_password = GenerateString(8);
$encrtpy_password = $enc->encrypt($reset_password);

if(empty($mmseq) || empty($type) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}
if($type == 'password_reset'){
    $query = "UPDATE ess_member_base SET
        mm_password = '{$encrtpy_password}'
        where mmseq = {$mmseq}";
    $ps = pdo_query($db,$query,array());

    $query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq = {$mc_coseq}";
    $ps = pdo_query($db,$query,array());
    $data_coperation = $ps ->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM ess_member_base WHERE mmseq = {$mmseq}";
        // echo('<pre>');print_r($query);echo('</pre>');exit;
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);

    $to_email = $enc->decrypt($data['mm_email']);
    $mm_name = $enc->decrypt($data['mm_name']);

    sendmail_pwReset($data_coperation['co_name'],$mm_name,$to_email,$reset_password);
}else if($type == 'login_status'){
    $query = "UPDATE ess_member_base SET
        mm_login_status = '{$login_status}'
        where mmseq = {$mmseq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}else if($type == 'remove_user'){
    $query = "UPDATE ess_member_base SET
        mm_is_del = 'TRUE'
        where mmseq = {$mmseq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}else if($type == 'memo_save'){
    $query = "UPDATE ess_member_base SET
        mm_admin_memo = '{$mm_admin_memo}'
        where mmseq = {$mmseq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}

$result['code']='TRUE';
echo json_encode($result);
?>
