<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$obj = $_REQUEST['obj'];
@$mmseq_check = $_SESSION['mmseq'];
@$mmseq = $_REQUEST['seq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$step =  get_member_step($db,$mmseq);
$result['code']='FALSE';
$result['changeCnt']=0;
$result['msg']='잘못된 접근입니다.';
if(empty($mmseq_check) || empty($mmseq) || empty($_REQUEST)){
    echo json_encode($result);
    exit;
}
$query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($_REQUEST['mm_email'])}' and mm_is_del = 'FALSE' and mmseq <> {$mmseq}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if($data['cnt'] >0){
    $result['msg']='중복된 이메일이 있습니다.';
    echo json_encode($result);
    exit;
}


$enctArr = array('mm_name','mc_company'
,'mm_serial_no','mm_password','mm_address','mm_phone','mm_address_detail','mm_cell_phone','mm_prepare_phone','mm_email'
,'mf_name','mf_resident','mf_job_name','mf_job','mf_education','mf_birth'
,'mc_duties','mct_institution','mct_class'
,'mad_name','mad_institution','ma_company','mp_name','mp_institution','mpd_name');

$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
$filesname = $_FILES['mm_profile']['name'];

if(!empty($filesname)){
    $tmp_file_check = file_check_fn($_FILES['mm_profile']);
    for($i=0;$i<sizeof($tmp_file_check);$i++){
        if($tmp_file_check[$i]['result']=='FALSE'){
            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
            echo json_encode($result);
            exit;
        }
    }
}
if(empty($filesname)==FALSE){
    list($_ori_filename, $ext) = explode('.', $_FILES['mm_profile']['name']);
    $ext = pathinfo( $_FILES['mm_profile']['name'], PATHINFO_EXTENSION);
    $filename =  @$seq.'_1_'.date('YmdHis');
    $photo = $_FILES['mm_profile']['tmp_name'];
    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
    $location = "/data/profile/".$filename.".".$ext;
    $file_query = " , mm_profile = '{$location}' ";
}

//if(!empty($_REQUEST['mm_note'])){
//    $insertQuery_addquery = " , mm_note = '{$_REQUEST['mm_note']}' ";
//}
$insertQuery="update ess_member_base SET mm_last_update = now() ";
foreach ($_REQUEST as $key => $value){
    if(in_array($key,$enctArr)){
        $value = $enc->encrypt($value);
    }
    if($key=='mc_group' || $key=='mc_position' || $key=='mc_position2'|| $key=='mc_position3'|| $key=='mc_position4'|| $key=='mc_position5'
        || $key =='mc_job'  || $key =='seq'
        || $key =='mc_job2' || $key =='mc_commute_all' || $key =='mc_commute_use' || $key =='mc_commute_remain'
        || $key=='mc_position_date' || $key=='mc_affiliate_date' || $key=='mc_bepromoted_date' || $key=='mc_regdate'|| $key=='mm_profile'
        || $key=='mm_arm_sdate' || $key=='mm_arm_edate' || $key=='mm_group' || $key=='mc_commute_edate' || $key=='mc_commute_sdate'){
        continue;
    }

    $insertQuery .= " , {$key} = '{$value}' ";
}

//$insertQuery .= $insertQuery_addquery;
if(!empty($_REQUEST['mm_arm_sdate'])){
    $insertQuery .= " , mm_arm_sdate = '{$_REQUEST['mm_arm_sdate']}' ";
}
if(!empty($_REQUEST['mm_arm_edate'])){
    $insertQuery .= " , mm_arm_edate = '{$_REQUEST['mm_arm_edate']}' ";
}
$insertQuery .= $file_query;
$insertQuery .= " where mmseq ='{$mmseq}'";
pdo_query($db,$insertQuery,array());

$insertQuery="update ess_member_code SET 
                     mc_regdate = '{$_REQUEST['mc_regdate']}'
                    , mc_position = {$_REQUEST['mc_position']}
                    , mc_position2 = {$_REQUEST['mc_position2']}
                    , mc_position3 = {$_REQUEST['mc_position3']}
                    , mc_position4 = {$_REQUEST['mc_position4']} 
                    , mc_position5 = {$_REQUEST['mc_position5']}
                    , mc_commute_edate = '{$_REQUEST['mc_commute_edate']}'
                    , mc_commute_sdate ='{$_REQUEST['mc_commute_sdate']}'
                    , mc_job = '{$_REQUEST['mc_job']}' , mc_job2 = '{$_REQUEST['mc_job2']}' 
                    , mc_commute_all = {$_REQUEST['mc_commute_all']} , mc_commute_use = {$_REQUEST['mc_commute_use']} 
                    , mc_commute_remain = {$_REQUEST['mc_commute_remain']} 
                    , mc_bepromoted_date = '{$_REQUEST['mc_bepromoted_date']}'  , mc_affiliate_date = '{$_REQUEST['mc_affiliate_date']}' ";
$insertQuery .= " where mc_mmseq ='{$mmseq}' and mc_coseq = {$mc_coseq} ";
pdo_query($db,$insertQuery,array());


$insertQuery = "delete from tbl_relation_group where trg_mmseq = {$mmseq} and trg_coseq = {$mc_coseq}";
pdo_query($db,$insertQuery,array());
foreach ($_REQUEST['mm_group'] as $val){
    $insertQuery = "insert tbl_relation_group set trg_mmseq = {$mmseq} , trg_group = {$val}, trg_regdate = now(),trg_coseq = {$mc_coseq}";
    pdo_query($db,$insertQuery,array());
}

$result['code']='TRUE';
$result['msg']='정보가 수정 되었습니다.';
echo json_encode($result);

?>
