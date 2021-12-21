<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$seq = $_REQUEST['seq'];
@$type = $_REQUEST['type'];
@$mmseq = $_SESSION['mmseq'];
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($mmseq) || empty($type) || empty($seq)){
    echo json_encode($result);
    exit;
}
switch ($type){
    case 'family':
        $query = "update ess_member_base set mm_file{$seq} = '' , mm_file{$seq}_name = '' where mmseq = {$mmseq}";
        pdo_query($db,$query,array());
        break;
    case 'activity' :
        $query = "update member_activity_data set mad_file = '' , mad_file_name = '' where mad_seq = {$seq}";
        pdo_query($db,$query,array());
        break;
}

$result['code']='TRUE';
$result['msg']='파일이 성공적으로 삭제되었습니다.';
echo json_encode($result);

?>
