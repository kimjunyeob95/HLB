<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];

$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
$step =  get_member_step($db,$mmseq);
if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}

//암호화 대상 필드
$enctArr = array('mf_name','mf_resident','mf_job_name','mf_job','mf_education','mf_birth');
foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}

$deleteQuery = "delete from member_family_data where mf_mmseq = {$mmseq}";
pdo_query($db,$deleteQuery,array());
for($i=0;$i<sizeof($_REQUEST['mf_name']);$i++){
    $insertQuery="INSERT INTO  member_family_data  SET
					mf_mmseq='".$mmseq."', 
					mf_regdate = now() ";
    $insertQuery .=  "
        , mf_name = '{$_REQUEST['mf_name'][$i]}'
        , mf_resident = '{$_REQUEST['mf_resident'][$i]}'
        , mf_birth = '{$_REQUEST['mf_birth'][$i]}'
        , mf_gender = '{$_REQUEST['mf_gender'][$i]}'
        , mf_foreigner = '{$_REQUEST['mf_foreigner'][$i]}'
        , mf_relationship ='{$_REQUEST['mf_relationship'][$i]}' 
        , mf_householder ='{$_REQUEST['mf_householder'][$i]}'
        , mf_education ='{$_REQUEST['mf_education'][$i]}'
        , mf_allowance ='{$_REQUEST['mf_allowance'][$i]}' 
        , mf_together ='{$_REQUEST['mf_together'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}
//        , mf_job_name ='{$_REQUEST['mf_job_name'][$i]}'
//        , mf_job ='{$_REQUEST['mf_job'][$i]}'

// 파일 업로드
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/vouchers';
$filesname1 = $_FILES['file1']['name'];
$filesname2 = $_FILES['file2']['name'];
$filesname3 = $_FILES['file3']['name'];

if(!empty($filesname1)){
    $tmp_file_check = file_check_fn($_FILES['file1']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname1)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['file1']['name']);
    $ext = pathinfo( $_FILES['file1']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_1_'.date('YmdHis');
    $photo = $_FILES['file1']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/vouchers/".$filename.".".$ext;
    $file_query1 = " , mm_file1 = '{$location}', mm_file1_name = '{$_FILES['file1']['name']}' ";
}
if(!empty($filesname2)){
    $tmp_file_check = file_check_fn($_FILES['file2']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname2)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['file2']['name']);
    $ext = pathinfo( $_FILES['file2']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_2_'.date('YmdHis');
    $photo = $_FILES['file2']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/vouchers/".$filename.".".$ext;
    $file_query2 = " , mm_file2 = '{$location}', mm_file2_name = '{$_FILES['file2']['name']}'";
}
if(!empty($filesname3)){
    $tmp_file_check = file_check_fn($_FILES['file3']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname3)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['file3']['name']);
    $ext = pathinfo( $_FILES['file3']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_3_'.date('YmdHis');
    $photo = $_FILES['file3']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/vouchers/".$filename.".".$ext;
    $file_query3 = " , mm_file3 = '{$location}' , mm_file3_name = '{$_FILES['file3']['name']}' ";
}
$where = "";
if(!empty($_REQUEST['mm_file_type_1'])){
    $where .= " , mm_file_type_1 = {$_REQUEST['mm_file_type_1']} ";
}
if(!empty($_REQUEST['mm_file_type_2'])){
    $where .= " , mm_file_type_2 = {$_REQUEST['mm_file_type_2']} ";
}
if(!empty($_REQUEST['mm_file_type_3'])){
    $where .= " , mm_file_type_3 = {$_REQUEST['mm_file_type_3']} ";
}
if(!empty($where) || $where!='') {
    pdo_query($db, "update  ess_member_base SET mm_last_update = now() {$where} where mmseq ='{$mmseq}'", array());
}
if(!empty($file_query1) || !empty($file_query2) || !empty($file_query3)) {
    pdo_query($db, "update  ess_member_base SET mm_last_update = now() {$file_query1} {$file_query2} {$file_query3} where mmseq ='{$mmseq}'", array());
}
if($step < 2) {
    pdo_query($db, "update  ess_member_base SET mm_save_step = 2 where mmseq ='{$mmseq}'", array());
}
$result['code']='TRUE';
$result['msg']='가족 정보가 수정 요청 되었습니다.';
echo json_encode($result);

?>
