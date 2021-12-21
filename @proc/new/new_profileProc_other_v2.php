<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq = $_SESSION['mmseq'];
$step =  get_member_step($db,$mmseq);
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
if(empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}

$enctArr = array('me_degree','mc_company','mc_position'
,'mc_duties','mct_institution','mct_class'
,'mad_name','mad_institution','ma_company','mp_name','mp_institution','mpd_name');

foreach ($_REQUEST as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST[$key][$key2] = $enc->encrypt($value2);

        }
    }
}
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
$deleteQuery4 = "delete from member_activity_data where mad_mmseq = {$mmseq}";
pdo_query($db,$deleteQuery4,array());

foreach ($_FILES['mad_file']['name'] as $f => $name) {
    $file_query ='';
    $filesname = '';
    $filesname = $_FILES['mad_file']['name'][$f];
    if (!empty($filesname)) {
        $tmp_file_check = file_check_fn($_FILES['mad_file'][$f]);
        for ($i = 0; $i < sizeof($tmp_file_check); $i++) {
            if ($tmp_file_check[$i]['result'] == 'FALSE') {
                page_move("/manage/boardcommon_view.php?seq=" . $seq, '이미지 파일이 업로드 할 수 없는 형식의 파일입니다.');
                exit;
            }
        }
    }
    if(!empty($_REQUEST['mad_file_remain'][$f])){
        $file_query = " , mad_file = '{$_REQUEST['mad_file_remain'][$f]}' , mad_file_name = '{$_REQUEST['mad_file_name_remain'][$f]}'";
    }
    if (empty($filesname) == FALSE) {
        list($_ori_filename, $ext) = explode('.', $_FILES['mad_file']['name'][$f]);
        $ext = pathinfo($_FILES['mad_file']['name'][$f], PATHINFO_EXTENSION);
        $filename = @$seq . '_1_' . date('YmdHis');
        $photo = $_FILES['mad_file']['tmp_name'][$f];
        move_uploaded_file($photo, $save_path . '/' . $filename . $f . "." . $ext);
        $location = "/data/profile/" . $filename . $f . "." . $ext;
        $file_query = " , mad_file = '{$location}' , mad_file_name = '{$filesname}'";
    }
    if(empty($_REQUEST['mad_name'])){
        continue;
    }
    if(empty($_REQUEST['mad_name'][$f])){
        continue;
    }
    $insertQuery="INSERT INTO  member_activity_data  SET
					mad_mmseq='".$mmseq."', 
					mad_regdate = now()  ";
    if(!empty($_REQUEST['mad_sdate'][$f])){
        $insertQuery .= " , mad_sdate = '{$_REQUEST['mad_sdate'][$f]}' ";
    }
    if(!empty($_REQUEST['mad_edate'][$f])){
        $insertQuery .= " , mad_edate = '{$_REQUEST['mad_edate'][$f]}' ";
    }
    $insertQuery .=  "
        , mad_name = '{$_REQUEST['mad_name'][$f]}'
        , mad_type = '{$_REQUEST['mad_type'][$f]}'
        , mad_role = '{$_REQUEST['mad_role'][$f]}'
        , mad_institution = '{$_REQUEST['mad_institution'][$f]}'
    ";
    $insertQuery .= $file_query;
    pdo_query($db,$insertQuery,array());
}


if($step < 3) {
    pdo_query($db, "update  ess_member_base SET mm_save_step = 3 where mmseq ='{$mmseq}'", array());
}
$result['code']='TRUE';
$result['msg']='추가 정보가 수정 요청 되었습니다.';
echo json_encode($result);

?>
