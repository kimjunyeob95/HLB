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
$query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mm_email = '{$enc->encrypt($_REQUEST['info']['mm_email'])}' and mm_is_del = 'FALSE' and mmseq <> {$mmseq}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
if($data['cnt'] >0){
    $result['msg']='중복된 이메일이 있습니다.';
    echo json_encode($result);
    exit;
}
/**
 *  초기화
 */
$deleteQuery2 = "delete from member_family_data where mf_mmseq = {$mmseq}";
$deleteQuery3 = "delete from member_certificate_data where mct_mmseq = {$mmseq}";
$deleteQuery4 = "delete from member_career_data where mc_mmseq = {$mmseq}";
$deleteQuery5 = "delete from member_education_data where me_mmseq = {$mmseq}";
$deleteQuery6 = "delete from member_activity_data where mad_mmseq = {$mmseq}";
$deleteQuery7 = "delete from member_appointment_data where ma_mmseq = {$mmseq}";
$deleteQuery8 = "delete from member_paper_data where mp_mmseq = {$mmseq}";
$deleteQuery9 = "delete from member_project_data where mpd_mmseq = {$mmseq}";
$deleteQuery10 = "delete from member_punishment_data where mp_mmseq = {$mmseq}";
$deleteQuery15 = "delete from member_premier_data where mpd_mmseq = {$mmseq}";
pdo_query($db,$deleteQuery2,array());
pdo_query($db,$deleteQuery3,array());
pdo_query($db,$deleteQuery4,array());
pdo_query($db,$deleteQuery5,array());
pdo_query($db,$deleteQuery6,array());
pdo_query($db,$deleteQuery7,array());
pdo_query($db,$deleteQuery8,array());
pdo_query($db,$deleteQuery9,array());
pdo_query($db,$deleteQuery10,array());
pdo_query($db,$deleteQuery15,array());

$enctArr = array('mc_company','mc_position'
,'mm_serial_no','mm_password','mm_address','mm_phone','mm_address_detail','mm_cell_phone','mm_prepare_phone','mm_email'
,'mf_name','mf_resident','mf_job_name','mf_job','mf_education','mf_birth'
,'mc_duties','mct_institution','mct_class'
,'mad_name','mad_institution','ma_company','mp_name','mp_institution','mpd_name');
foreach ($_REQUEST['family'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['family'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['sert'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['sert'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['career'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['career'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['edu'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['edu'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['appoint'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['appoint'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['activity'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['activity'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['paper'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['paper'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['project'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['project'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['punishment'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['punishment'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
foreach ($_REQUEST['premier'] as $key => $value){
    foreach ($value as $key2 =>$value2){
        if(empty($value2)){
            continue;
        }
        if(in_array($key,$enctArr )){
            $_REQUEST['premier'][$key][$key2] = $enc->encrypt($value2);

        }
    }
}
//$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/profile';
//$filesname = $_FILES['mm_profile']['name'];
//
//if(!empty($filesname)){
//    $tmp_file_check = file_check_fn($_FILES['mm_profile']);
//    for($i=0;$i<sizeof($tmp_file_check);$i++){
//        if($tmp_file_check[$i]['result']=='FALSE'){
//            $result['msg']='이미지 파일이 업로드 할 수 없는 형식의 파일입니다.';
//            echo json_encode($result);
//            exit;
//        }
//    }
//}
//if(empty($filesname)==FALSE){
//    list($_ori_filename, $ext) = explode('.', $_FILES['mm_profile']['name']);
//    $ext = pathinfo( $_FILES['mm_profile']['name'], PATHINFO_EXTENSION);
//    $filename =  @$seq.'_1_'.date('YmdHis');
//    $photo = $_FILES['mm_profile']['tmp_name'];
//    move_uploaded_file($photo, $save_path.'/'.$filename.".".$ext);
//    $location = "/data/profile/".$filename.".".$ext;
//    $file_query = " , mm_profile = '{$location}' ";
//}
//
//if(!empty($_REQUEST['etc']['mm_note'])){
//    $insertQuery_addquery = " , mm_note = '{$_REQUEST['etc']['mm_note']}' ";
//}
//$insertQuery="update ess_member_base SET mm_last_update = now() ";
//foreach ($_REQUEST['info'] as $key => $value){
//    if(in_array($key,$enctArr)){
//        $value = $enc->encrypt($value);
//    }
//    if($key=='mc_group' || $key=='mc_position' || $key=='mc_position2' || $key =='mc_job'
//        || $key =='mc_job2' || $key =='mc_commute_all' || $key =='mc_commute_use' || $key =='mc_commute_remain'
//        || $key=='mc_position_date' || $key=='mc_affiliate_date' || $key=='mc_bepromoted_date' || $key=='mc_regdate'|| $key=='mm_profile'){
//        continue;
//    }
//
//    $insertQuery .= " , {$key} = '{$value}' ";
//}
//
//$insertQuery .= $insertQuery_addquery;
//$insertQuery .= $file_query;
//$insertQuery .= " where mmseq ='{$mmseq}'";
//pdo_query($db,$insertQuery,array());
//
//$insertQuery="update ess_member_code SET  mc_group = {$_REQUEST['info']['mc_group']}, mc_regdate = '{$_REQUEST['info']['mc_regdate']}',
//                     mc_position = {$_REQUEST['info']['mc_position']}, mc_position2 = {$_REQUEST['info']['mc_position2']},
//                     mc_job = '{$_REQUEST['info']['mc_job']}' , mc_job2 = '{$_REQUEST['info']['mc_job2']}'
//                     , mc_commute_all = {$_REQUEST['info']['mc_commute_all']} , mc_commute_use = {$_REQUEST['info']['mc_commute_use']}
//                     , mc_commute_remain = {$_REQUEST['info']['mc_commute_remain']}  , mc_position_date = '{$_REQUEST['info']['mc_position_date']}'
//                     , mc_bepromoted_date = '{$_REQUEST['info']['mc_bepromoted_date']}'  , mc_affiliate_date = '{$_REQUEST['info']['mc_affiliate_date']}' ";
//$insertQuery .= " where mc_mmseq ='{$mmseq}' and mc_coseq = {$mc_coseq} ";
//pdo_query($db,$insertQuery,array());

for($i=0;$i<sizeof($_REQUEST['family']['mf_name']);$i++){
    $insertQuery="INSERT INTO  member_family_data  SET
					mf_mmseq='".$mmseq."', 
					mf_regdate = now() ";
    $insertQuery .=  "
        , mf_name = '{$_REQUEST['family']['mf_name'][$i]}'
        , mf_resident = '{$_REQUEST['family']['mf_resident'][$i]}'
        , mf_birth = '{$_REQUEST['family']['mf_birth'][$i]}'
        , mf_gender = '{$_REQUEST['family']['mf_gender'][$i]}'
        , mf_foreigner = '{$_REQUEST['family']['mf_foreigner'][$i]}'
        , mf_relationship ='{$_REQUEST['family']['mf_relationship'][$i]}' 
        , mf_householder ='{$_REQUEST['family']['mf_householder'][$i]}'
        , mf_education ='{$_REQUEST['family']['mf_education'][$i]}'
        , mf_allowance ='{$_REQUEST['family']['mf_allowance'][$i]}' 
        , mf_together ='{$_REQUEST['family']['mf_together'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//어학 / 자격증
for($i=0;$i<sizeof($_REQUEST['sert']['mct_cert_name']);$i++){
    $insertQuery_sert="INSERT INTO  member_certificate_data  SET
					mct_mmseq='".$mmseq."', 
					mct_regdate = now()  ";
    $insertQuery_sert .=  "
        , mct_cert_name = '{$_REQUEST['sert']['mct_cert_name'][$i]}'
        , mct_institution = '{$_REQUEST['sert']['mct_institution'][$i]}'
        , mct_class = '{$_REQUEST['sert']['mct_class'][$i]}'
        , mct_date = '{$_REQUEST['sert']['mct_date'][$i]}'
        , mct_num = '{$_REQUEST['sert']['mct_num'][$i]}'
    ";
    pdo_query($db,$insertQuery_sert,array());
}

// 사외경력
for($i=0;$i<sizeof($_REQUEST['career']['mc_company']);$i++){
    $insertQuery="INSERT INTO  member_career_data  SET
					mc_mmseq='".$mmseq."', 
					mc_regdate = now()  ";
    $insertQuery .=  "
        , mc_company = '{$_REQUEST['career']['mc_company'][$i]}'
        , mc_group = '{$_REQUEST['career']['mc_group'][$i]}'
        , mc_sdate = '{$_REQUEST['career']['mc_sdate'][$i]}'
        , mc_edate = '{$_REQUEST['career']['mc_edate'][$i]}'
        , mc_position = '{$_REQUEST['career']['mc_position'][$i]}'
        , mc_duties = '{$_REQUEST['career']['mc_duties'][$i]}'
        , mc_career = '{$_REQUEST['career']['mc_career'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//학력
for($i=0;$i<sizeof($_REQUEST['edu']['me_name']);$i++){
    $insertQuery="INSERT INTO  member_education_data  SET
					me_mmseq='".$mmseq."', 
					me_regdate = now()  ";
    $insertQuery .=  "
        , me_name = '{$_REQUEST['edu']['me_name'][$i]}'
        , me_sdate = '{$_REQUEST['edu']['me_sdate'][$i]}'
        , me_edate = '{$_REQUEST['edu']['me_edate'][$i]}'
        , me_level = '{$_REQUEST['edu']['me_level'][$i]}'
        , me_degree = '{$_REQUEST['edu']['me_degree'][$i]}'
        , me_major = '{$_REQUEST['edu']['me_major'][$i]}'
        , me_weekly = '{$_REQUEST['edu']['me_weekly'][$i]}'
        , me_graduate_type = '{$_REQUEST['edu']['me_graduate_type'][$i]}'
        , me_etc = '{$_REQUEST['edu']['me_etc'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//발령
for($i=0;$i<sizeof($_REQUEST['appoint']['ma_company']);$i++){
    $insertQuery="INSERT INTO  member_appointment_data  SET
					ma_mmseq='".$mmseq."', 
					ma_regdate = now()  ";
    if(!empty($_REQUEST['appoint']['ma_date'][$i])){
        $insertQuery .= " , ma_date = '{$_REQUEST['appoint']['ma_date'][$i]}' ";
    }
    $insertQuery .=  "
        , ma_company = '{$_REQUEST['appoint']['ma_company'][$i]}'
        , ma_type = '{$_REQUEST['appoint']['ma_type'][$i]}'
        , ma_position2 = '{$_REQUEST['appoint']['ma_position2'][$i]}'
        , ma_position3 = '{$_REQUEST['appoint']['ma_position3'][$i]}'
        , ma_etc = '{$_REQUEST['appoint']['ma_etc'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

// 교육 / 활동
for($i=0;$i<sizeof($_REQUEST['activity']['mad_name']);$i++){
    $insertQuery="INSERT INTO  member_activity_data  SET
					mad_mmseq='".$mmseq."', 
					mad_regdate = now()  ";
    $insertQuery .=  "
        , mad_name = '{$_REQUEST['activity']['mad_name'][$i]}'
        , mad_sdate = '{$_REQUEST['activity']['mad_sdate'][$i]}'
        , mad_edate = '{$_REQUEST['activity']['mad_edate'][$i]}'
        , mad_type = '{$_REQUEST['activity']['mad_type'][$i]}'
        , mad_role = '{$_REQUEST['activity']['mad_role'][$i]}'
        , mad_institution = '{$_REQUEST['activity']['mad_institution'][$i]}'
        , mad_file = '{$_REQUEST['activity']['mad_file_remain'][$i]}'
        , mad_file_name = '{$_REQUEST['activity']['mad_file_name_remain'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//논문 저서
for($i=0;$i<sizeof($_REQUEST['paper']['mp_name']);$i++){
    $insertQuery="INSERT INTO  member_paper_data  SET
					mp_mmseq='".$mmseq."', 
					mp_regdate = now()  ";
    $insertQuery .=  "
        , mp_date = '{$_REQUEST['paper']['mp_date'][$i]}'
        , mp_name = '{$_REQUEST['paper']['mp_name'][$i]}'
        , mp_institution = '{$_REQUEST['paper']['mp_institution'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

// 프로젝트
for($i=0;$i<sizeof($_REQUEST['project']['mpd_name']);$i++){
    $insertQuery="INSERT INTO  member_project_data  SET
					mpd_mmseq='".$mmseq."', 
					mpd_regdate = now()  ";
    $insertQuery .=  "
        , mpd_sdate = '{$_REQUEST['project']['mpd_sdate'][$i]}'
        , mpd_edate = '{$_REQUEST['project']['mpd_edate'][$i]}'
        , mpd_name = '{$_REQUEST['project']['mpd_name'][$i]}'
        , mpd_content = '{$_REQUEST['project']['mpd_content'][$i]}'
        , mpd_keyword = '{$_REQUEST['project']['mpd_keyword'][$i]}'
        , mpd_contribution = '{$_REQUEST['project']['mpd_contribution'][$i]}'
        , mpd_institution = '{$_REQUEST['project']['mpd_institution'][$i]}'
        , mpd_result = '{$_REQUEST['project']['mpd_result'][$i]}'
        , mpd_position = '{$_REQUEST['project']['mpd_position'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//논문 저서
for($i=0;$i<sizeof($_REQUEST['punishment']['mp_title']);$i++){
    $insertQuery="INSERT INTO  member_punishment_data  SET
					mp_mmseq='".$mmseq."', 
					mp_regdate = now()  ";
    $insertQuery .=  "
        , mp_type = '{$_REQUEST['punishment']['mp_type'][$i]}'
        , mp_date = '{$_REQUEST['punishment']['mp_date'][$i]}'
        , mp_title = '{$_REQUEST['punishment']['mp_title'][$i]}'
        , mp_content = '{$_REQUEST['punishment']['mp_content'][$i]}'
        , mp_etc = '{$_REQUEST['punishment']['mp_etc'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}
//논문 저서
for($i=0;$i<sizeof($_REQUEST['premier']['mpd_date']);$i++){
    $insertQuery="INSERT INTO  member_premier_data  SET
					mpd_mmseq='".$mmseq."', 
					mpd_regdate = now()  ";
    $insertQuery .=  "
        , mpd_date = '{$_REQUEST['premier']['mpd_date'][$i]}'
        , mpd_content = '{$_REQUEST['premier']['mpd_content'][$i]}'
        , mpd_institution = '{$_REQUEST['premier']['mpd_institution'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}
$result['code']='TRUE';
$result['msg']='정보가 수정 되었습니다.';
echo json_encode($result);

?>
