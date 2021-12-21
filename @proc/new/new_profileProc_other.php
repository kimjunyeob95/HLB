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

/**
 *  초기화
 */
$deleteQuery1 = "delete from member_certificate_data where mct_mmseq = {$mmseq}";
$deleteQuery2 = "delete from member_career_data where mc_mmseq = {$mmseq}";
$deleteQuery3 = "delete from member_education_data where me_mmseq = {$mmseq}";
$deleteQuery6 = "delete from member_paper_data where mp_mmseq = {$mmseq}";
$deleteQuery7 = "delete from member_project_data where mpd_mmseq = {$mmseq}";
$deleteQuery8 = "delete from member_punishment_data where mp_mmseq = {$mmseq}";
$deleteQuery9 = "delete from member_premier_data where mpd_mmseq = {$mmseq}";
pdo_query($db,$deleteQuery1,array());
pdo_query($db,$deleteQuery2,array());
pdo_query($db,$deleteQuery3,array());
pdo_query($db,$deleteQuery6,array());
pdo_query($db,$deleteQuery7,array());
pdo_query($db,$deleteQuery8,array());
pdo_query($db,$deleteQuery9,array());

$enctArr = array('mc_company','mc_position'
,'mc_duties','mct_institution','mct_class'
,'mad_name','mad_institution','ma_company','mp_name','mp_institution','mpd_name');
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

//어학 / 자격증
for($i=0;$i<sizeof($_REQUEST['sert']['mct_cert_name']);$i++){
    if(empty($_REQUEST['sert']['mct_cert_name'][$i])){
        continue;
    }
    $insertQuery_sert="INSERT INTO  member_certificate_data  SET
					mct_mmseq='".$mmseq."', 
					mct_regdate = now()  ";
    if(!empty($_REQUEST['sert']['mct_date'][$i])){
        $insertQuery_sert .= " , mct_date = '{$_REQUEST['sert']['mct_date'][$i]}' ";
    }
    $insertQuery_sert .=  "
        , mct_cert_name = '{$_REQUEST['sert']['mct_cert_name'][$i]}'
        , mct_institution = '{$_REQUEST['sert']['mct_institution'][$i]}'
        , mct_class = '{$_REQUEST['sert']['mct_class'][$i]}'
        , mct_num = '{$_REQUEST['sert']['mct_num'][$i]}'
    ";
    pdo_query($db,$insertQuery_sert,array());
}

// 사외경력
for($i=0;$i<sizeof($_REQUEST['career']['mc_company']);$i++){
    if(empty($_REQUEST['career']['mc_company'][$i])){
        continue;
    }
    $insertQuery="INSERT INTO  member_career_data  SET
					mc_mmseq='".$mmseq."', 
					mc_regdate = now()  ";
    if(!empty($_REQUEST['career']['mc_sdate'][$i])){
        $insertQuery .= " , mc_sdate = '{$_REQUEST['career']['mc_sdate'][$i]}' ";
    }
    if(!empty($_REQUEST['career']['mc_edate'][$i])){
        $insertQuery .= " , mc_edate = '{$_REQUEST['career']['mc_edate'][$i]}' ";
    }
    $insertQuery .=  "
        , mc_company = '{$_REQUEST['career']['mc_company'][$i]}'
        , mc_position = '{$_REQUEST['career']['mc_position'][$i]}'
        , mc_group = '{$_REQUEST['career']['mc_group'][$i]}'
        , mc_duties = '{$_REQUEST['career']['mc_duties'][$i]}'
        , mc_career = '{$_REQUEST['career']['mc_career'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//학력
for($i=0;$i<sizeof($_REQUEST['edu']['me_name']);$i++){
    if(empty($_REQUEST['edu']['me_name'][$i])){
        continue;
    }
    $insertQuery="INSERT INTO  member_education_data  SET
					me_mmseq='".$mmseq."', 
					me_regdate = now()  ";
    if(!empty($_REQUEST['edu']['me_sdate'][$i])){
        $insertQuery .= " , me_sdate = '{$_REQUEST['edu']['me_sdate'][$i]}' ";
    }
    if(!empty($_REQUEST['edu']['me_edate'][$i])){
        $insertQuery .= " , me_edate = '{$_REQUEST['edu']['me_edate'][$i]}' ";
    }
    $insertQuery .=  "
        , me_name = '{$_REQUEST['edu']['me_name'][$i]}'
        , me_level = '{$_REQUEST['edu']['me_level'][$i]}'
        , me_weekly = '{$_REQUEST['edu']['me_weekly'][$i]}'
        , me_degree = '{$_REQUEST['edu']['me_degree'][$i]}'
        , me_major = '{$_REQUEST['edu']['me_major'][$i]}'
        , me_graduate_type = '{$_REQUEST['edu']['me_graduate_type'][$i]}'
        , me_etc = '{$_REQUEST['edu']['me_etc'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

//논문 저서
for($i=0;$i<sizeof($_REQUEST['paper']['mp_name']);$i++){
    if(empty($_REQUEST['paper']['mp_name'][$i])){
        continue;
    }
    $insertQuery="INSERT INTO  member_paper_data  SET
					mp_mmseq='".$mmseq."', 
					mp_regdate = now()  ";
    if(!empty($_REQUEST['paper']['mp_date'][$i])){
        $insertQuery .= " , mp_date = '{$_REQUEST['paper']['mp_date'][$i]}' ";
    }
    $insertQuery .=  "
        , mp_name = '{$_REQUEST['paper']['mp_name'][$i]}'
        , mp_institution = '{$_REQUEST['paper']['mp_institution'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}

// 프로젝트
for($i=0;$i<sizeof($_REQUEST['project']['mpd_name']);$i++){
    if(empty($_REQUEST['project']['mpd_name'][$i])){
        continue;
    }
    $insertQuery="INSERT INTO  member_project_data  SET
					mpd_mmseq='".$mmseq."', 
					mpd_regdate = now()  ";
    if(!empty($_REQUEST['project']['mpd_sdate'][$i])){
        $insertQuery .= " , mpd_sdate = '{$_REQUEST['project']['mpd_sdate'][$i]}' ";
    }
    if(!empty($_REQUEST['project']['mpd_edate'][$i])){
        $insertQuery .= " , mpd_edate = '{$_REQUEST['project']['mpd_edate'][$i]}' ";
    }
    $insertQuery .=  "
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
    if(empty($_REQUEST['punishment']['mp_title'][$i])){
        continue;
    }
    $insertQuery="INSERT INTO  member_punishment_data  SET
					mp_mmseq='".$mmseq."', 
					mp_regdate = now()  ";
    if(!empty($_REQUEST['punishment']['mp_date'][$i])){
        $insertQuery .= " , mp_date = '{$_REQUEST['punishment']['mp_date'][$i]}' ";
    }
    $insertQuery .=  "
        , mp_type = '{$_REQUEST['punishment']['mp_type'][$i]}'
        , mp_title = '{$_REQUEST['punishment']['mp_title'][$i]}'
        , mp_content = '{$_REQUEST['punishment']['mp_content'][$i]}'
        , mp_etc = '{$_REQUEST['punishment']['mp_etc'][$i]}'
    ";
    pdo_query($db,$insertQuery,array());
}
// 프로젝트
for($i=0;$i<sizeof($_REQUEST['premier']['mpd_date']);$i++){
    if(empty($_REQUEST['premier']['mpd_date'][$i])){
        continue;
    }
    $insertQuery="INSERT INTO  member_premier_data  SET
					mpd_mmseq='".$mmseq."', 
					mpd_regdate = now()  ";
    if(!empty($_REQUEST['premier']['mpd_date'][$i])){
        $insertQuery .= " , mpd_date = '{$_REQUEST['premier']['mpd_date'][$i]}' ";
    }
    $insertQuery .=  "
        , mpd_content = '{$_REQUEST['premier']['mpd_content'][$i]}'
        , mpd_institution = '{$_REQUEST['premier']['mpd_institution'][$i]}' 
    ";
    pdo_query($db,$insertQuery,array());
}
$result['code']='TRUE';
$result['msg']='추가 정보가 수정 요청 되었습니다.';
echo json_encode($result);

?>
