<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq_check = $_SESSION['mmseq'];
@$mmseq = $_REQUEST['seq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
if(empty($mmseq_check) || empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}


$deleteQuery10 = "delete from member_evaluation where me_mmseq = {$mmseq}";
pdo_query($db,$deleteQuery10,array());
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
foreach ($_FILES['me_file_name']['name'] as $f => $name) {
    $file_query ='';
    $filesname = '';
    $filesname = $_FILES['me_file_name']['name'][$f];
    if (!empty($filesname)) {
        $tmp_file_check = file_check_fn($_FILES['me_file_name'][$f]);
        for ($i = 0; $i < sizeof($tmp_file_check); $i++) {
            if ($tmp_file_check[$i]['result'] == 'FALSE') {
                page_move("/hass/orginfomanageDetail.php?seq=" . $seq, '이미지 파일이 업로드 할 수 없는 형식의 파일입니다.');
                exit;
            }
        }
    }
    if(!empty($_REQUEST['me_file_name_remain'][$f])){
        $file_query = " , me_file_name = '{$_REQUEST['me_file_name_remain'][$f]}' , me_file_src = '{$_REQUEST['me_file_remain'][$f]}'";
    }
    if (empty($filesname) == FALSE) {
        list($_ori_filename, $ext) = explode('.', $_FILES['me_file_name']['name'][$f]);
        $ext = pathinfo($_FILES['me_file_name']['name'][$f], PATHINFO_EXTENSION);
        $filename = @$seq . '_1_' . date('YmdHis');
        $photo = $_FILES['me_file_name']['tmp_name'][$f];
        move_uploaded_file($photo, $save_path . '/' . $filename . $f . "." . $ext);
        $location = "/data/profile/" . $filename . $f . "." . $ext;
        $file_query = " , me_file_src = '{$location}' , me_file_name = '{$filesname}'";
    }

    $insertQuery="INSERT INTO  member_evaluation  SET
					me_mmseq='".$mmseq."', 
					me_regdate = now()  ";
    $insertQuery .=  "
        , me_year = '{$_REQUEST['me_year'][$f]}'
        , me_group = '{$_REQUEST['me_group'][$f]}'
        , me_admin_1 = '{$_REQUEST['me_admin_1'][$f]}'
        , me_class_1 = '{$_REQUEST['me_class_1'][$f]}'
        , me_etc1 = '{$_REQUEST['me_etc1'][$f]}'
        , me_etc2 = '{$_REQUEST['me_etc2'][$f]}'
        , me_admin_2 = '{$_REQUEST['me_admin_2'][$f]}'
        , me_class_2 = '{$_REQUEST['me_class_2'][$f]}'
    ";
    $insertQuery .= $file_query;
    pdo_query($db,$insertQuery,array());
}


$result['code']='TRUE';
$result['msg']='정보가 수정 되었습니다.';
echo json_encode($result);

?>
