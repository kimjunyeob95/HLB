<?php
/**
 * 개인정보 view
 **/
function get_member_info($db,$seq){
    if(empty($seq)){
        return '-';
    }
    $query = "select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$seq} and mc_coseq = {$_SESSION['mInfo']['mc_coseq']}";
    $ps = pdo_query($db,$query,array());
    $member_info = $ps->fetch(PDO::FETCH_ASSOC);
    return $member_info;
}

function get_member_birth($db,$seq){
    $beforedate_Array  = array(date('md',strtotime('+1 day')),date('md',strtotime('+2 day')),date('md',strtotime('+3 day')),
        date('md',strtotime('+4 day')),date('md',strtotime('+5 day')),date('md',strtotime('+6 day'))
    ,date('md',strtotime('+7 day')),date('md',strtotime('+8 day')),date('md',strtotime('+9 day')),date('md',strtotime('+10 day'))
    ,date('md',strtotime('+11 day')),date('md',strtotime('+12 day')),date('md',strtotime('+13 day')),date('md',strtotime('+14 day')),date('md',strtotime('+15 day')),date('md'));
    $beforedate = implode('\',\'',$beforedate_Array);
    $query = "select count(*) as cnt from ess_member_base where DATE_FORMAT(mm_birth,'%m%d') in ('{$beforedate}') and mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $member_cnt = $ps->fetch(PDO::FETCH_ASSOC);
    return $member_cnt['cnt'];
}

function get_member_info_admin($db,$seq,$mc_coseq){
    if(empty($seq)){
        return '-';
    }
    $query = "select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$seq} and mc_coseq = {$mc_coseq}";
    $ps = pdo_query($db,$query,array());
    $member_info = $ps->fetch(PDO::FETCH_ASSOC);
    return $member_info;
}
/**
 * 개인정보 view 발령,겸직
 **/
function get_member_info_v2($db,$seq){
    if(empty($seq)){
        return '-';
    }
    $query = "select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $member_info = $ps->fetch(PDO::FETCH_ASSOC);
    return $member_info;
}
function get_member_step($db,$seq){
    $query = "select mm_save_step from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where mmseq = {$seq} and mc_coseq = {$_SESSION['mInfo']['mc_coseq']}";
    $ps = pdo_query($db,$query,array());
    $member_info = $ps->fetch(PDO::FETCH_ASSOC);
    return $member_info['mm_save_step'];
}
/**
 * 개인정보 list
 **/
function get_member_list($db,$where_query = array(),$type='',$start='',$end=''){
    $enc = new encryption();
    $where = '';
    if($where_query['where']['new'] =='Y'){
        //$where .= " and (mm_status ='A' or mm_status ='N' or mm_status ='Y') ";
        $where .= " and (mm_status ='A' or mm_status ='N' or mm_status ='Y' or mm_status ='S')  and mm_super_admin='F' ";
    }else{
        $where .= " and (mm_status ='Y' or mm_status ='D') and mm_super_admin='F' ";
    }
    if(!empty($where_query['where']['mc_code'])){
        $where .= " and mc_code = {$where_query['where']['mc_code']} ";
    }
    if(!empty($where_query['where']['mc_coseq'])){
        $where .= " and mc_coseq = '{$where_query['where']['mc_coseq']}' ";
    }
    if(!empty($where_query['where']['name_code'])){
        $where .=" and (mm_name = '{$enc->encrypt($where_query['where']['mm_name'])}' or mc_coseq = '{$where_query['where']['mc_coseq']}')";
    }
    if(!empty($where_query['where']['mm_name'])){
        $where .= " and mm_name = '{$enc->encrypt($where_query['where']['mm_name'])}' ";
    }
    if(!empty($where_query['where']['mm_email'])){
        $where .= " and mm_email = '{$enc->encrypt($where_query['where']['mm_email'])}' ";
    }
    if(!empty($where_query['where']['mm_phone'])){
        $where .= " and (mm_phone = '{$enc->encrypt($where_query['where']['mm_phone'])}' or mm_cell_phone = '{$enc->encrypt($where_query['where']['mm_phone'])}')";
    }
    if(!empty($where_query['where']['mm_status']) && $where_query['where']['mm_status'] != 'all' ){
        $where .= " and mm_status = '{$where_query['where']['mm_status']}' ";
    }
    if(!empty($where_query['where']['mc_group']) && $where_query['where']['mc_group'] != 'all' ){
        //$where .= " and mc_group = '{$where_query['where']['mc_group']}' ";
        $where .= " and mmseq in (select trg_mmseq from tbl_relation_group where trg_coseq = {$_SESSION['mInfo']['mc_coseq']} and trg_group = {$where_query['where']['mc_group']})  ";
    }
    if(!empty($where_query['where']['mc_position']) && $where_query['where']['mc_position'] != 'all' ){
        $where .= " and mc_position = '{$where_query['where']['mc_position']}' ";
    }
    //order by 절
    if(!empty($where_query['orderby']['mm_status'])){
        $orderby = " order by mm_status ='Y' , mm_regdate desc ";
    }else{
        $orderby = ' order by mm_regdate desc ';
    }
    if($where_query['where']['new'] =='Y'){
        $orderby = "order by mm_status ='S' desc , mm_status ='A' desc , mm_status ='Y' desc , mm_regdate desc, mc_code desc";
    }
    //limit 절
    if((!empty($start) || $start ==0) && !empty($end)){
        $limit = " limit {$start} , {$end} ";
    }

    if($type =='cnt'){
        $query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq  where 1=1 and mc_coseq = {$_SESSION['mInfo']['mc_coseq']} and mm_is_del='FALSE' ".$where;
        $ps = pdo_query($db,$query,array());
        $member_cnt = $ps->fetch(PDO::FETCH_ASSOC);
        return $member_cnt['cnt'];
    }else{
        $query = "select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq where 1=1 and mc_coseq = {$_SESSION['mInfo']['mc_coseq']} and mm_is_del='FALSE' ".$where." {$orderby} {$limit} ";
        // echo('<pre>');print_r($query);echo('</pre>');
        $ps = pdo_query($db,$query,array());
        $member_list = array();
        while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
            array_push($member_list,$data);
        }
        return $member_list;
    }
}

/**
 * 개인정보 조직 join
 **/
function get_member_join_co_list($db,$where_query = array(),$type='',$start='',$end=''){
    $enc = new encryption();
    $where = '';
    if($where_query['where']['new'] =='Y'){
        $where .= " and (mm_status ='A' or mm_status ='N') ";
    }else{
        $where .= " and (mm_status ='Y' or mm_status ='D')";
    }
    if(!empty($where_query['where']['name_code'])){
        $where .=" and (mm_name = '{$enc->encrypt($where_query['where']['name_code'])}' or mc_code = '{$where_query['where']['name_code']}')";
    }
    if(!empty($where_query['where']['mc_coseq'])){
        $where .= " and mc_coseq = {$where_query['where']['mc_coseq']} ";
    }
    if(!empty($where_query['where']['mm_name'])){
        $where .= " and mm_name = '{$enc->encrypt($where_query['where']['mm_name'])}' ";
    }
    if(!empty($where_query['where']['mm_email'])){
        $where .= " and mm_email = '{$enc->encrypt($where_query['where']['mm_email'])}' ";
    }
    if(!empty($where_query['where']['mm_phone'])){
        $where .= " and (mm_phone = '{$enc->encrypt($where_query['where']['mm_phone'])}' or mm_cell_phone = '{$enc->encrypt($where_query['where']['mm_phone'])}')";
    }
    if(!empty($where_query['where']['mm_status']) && $where_query['where']['mm_status'] != 'all' ){
        $where .= " and mm_status = '{$where_query['where']['mm_status']}' ";
    }
    if(!empty($where_query['where']['keyword'])){
        $where .= "and ((select count(*) from member_project_data mpd where mpd_keyword like '%{$where_query['where']['keyword']}%' and mpd_mmseq = emb.mmseq ) >0 
                         or emc.mc_code = '{$where_query['where']['keyword']}'
                         or emb.mm_name = '{$enc->encrypt($where_query['where']['keyword'])}'
                         or (select count(*) from member_education_data me where (me_name like '%{$where_query['where']['keyword']}%' or me_major like '%{$where_query['where']['keyword']}%') and me_mmseq = emb.mmseq ) >0 
                         or (select count(*) from member_certificate_data mcd where mct_cert_name like '%{$where_query['where']['keyword']}%' and mct_mmseq = emb.mmseq ) >0
                         or (select count(*) from tbl_position tp where tp_title like '%{$where_query['where']['keyword']}%' and emc.mc_position  = tp_seq ) >0 
                         or (select count(*) from tbl_position tp where tp_title like '%{$where_query['where']['keyword']}%' and emc.mc_position2  = tp_seq ) >0 
                         or (select count(*) from tbl_position tp where tp_title like '%{$where_query['where']['keyword']}%' and emc.mc_position3  = tp_seq ) >0 
                         or (select count(*) from tbl_position tp where tp_title like '%{$where_query['where']['keyword']}%' and emc.mc_position4  = tp_seq ) >0 
                         or (select count(*) from tbl_position tp where tp_title like '%{$where_query['where']['keyword']}%' and emc.mc_position5  = tp_seq ) >0 
                         or (select count(*) from tbl_relation_group trg join tbl_ess_group tg on trg_group = tg_seq where tg_title like '%{$where_query['where']['keyword']}%' and trg_mmseq = emb.mmseq) >0 ) ";
    }
    if($type =='cnt'){
        $query = "select count(*) as cnt from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq join tbl_coperation co on emc.mc_coseq = co_seq  where 1=1  and mm_super_admin='F' and mm_is_del='FALSE' ".$where;
        $ps = pdo_query($db,$query,array());
        $member_cnt = $ps->fetch(PDO::FETCH_ASSOC);
        return $member_cnt['cnt'];
    }else if($type == 'all'){
        $query = "select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq join tbl_coperation co on emc.mc_coseq = co_seq where 1=1 and mm_super_admin='F' and mm_is_del='FALSE' ".$where." ";
        $ps = pdo_query($db,$query,array());
        $member_list = array();
        while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
            array_push($member_list,$data);
        }
        return $member_list;
    }else{
        $query = "select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq join tbl_coperation co on emc.mc_coseq = co_seq where 1=1 and mm_super_admin='F' and mm_is_del='FALSE' ".$where." limit {$start} , {$end}";
        $ps = pdo_query($db,$query,array());
        $member_list = array();
        while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
            array_push($member_list,$data);
        }
        return $member_list;
    }
}

function get_member_log_list($db,$seq,$division){ //
    $query = "select * from ess_member_log where em_mmseq = {$seq} and em_division = {$division}";
    $ps = pdo_query($db,$query,array());
    return  $ps ->fetch(PDO::FETCH_ASSOC);
}

//반려사유
function get_cause_of_return($db,$mmseq,$division,$type){
    $query = "select tco_text from tbl_cause_of_return where tco_mmseq = {$mmseq} and tco_division = {$division} and tco_type = '{$type}'";
    $ps = pdo_query($db,$query,array());
    return  $ps ->fetch(PDO::FETCH_ASSOC)['tco_text'];
}

function update_member_info_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_member_base a , ess_member_log b  
    set a.mm_name = b.em_name, a.mm_foreigner = b.em_foreigner , a.mm_country = b.em_country, a.mm_from = b.em_from,
        a.mm_serial_no = b.em_serial_no , a.mm_birth = b.em_birth, a.mm_gender = b.em_gender , a.mm_post = b.em_post,
        a.mm_address = b.em_address, a.mm_address_detail = b.em_address_detail , a.mm_phone = b.em_phone,
        a.mm_cell_phone = b.em_cell_phone , a.mm_en_name = b.em_en_name , a.mm_profile = b.em_profile,
        a.mm_email = b.em_email, a.mm_prepare_relation = b.em_prepare_relation , a.mm_prepare_phone = b.em_prepare_phone,
        em_confirm_date = now() , em_state = '{$state}' , em_confirm_mmseq = {$_SESSION['mmseq']} 
        , a.mm_arm_type = b.em_arm_type, a.mm_arm_reason = b.em_arm_reason, a.mm_arm_group = b.em_arm_group
        , a.mm_arm_class = b.em_arm_class, a.mm_arm_discharge = b.em_arm_discharge, a.mm_arm_sdate = b.em_arm_sdate
        , a.mm_arm_edate = b.em_arm_edate , a.mm_disorder_1 = b.em_disorder_1 , a.mm_disorder_2 = b.em_disorder_2
        , a.mm_disorder_3 = b.em_disorder_3 , a.mm_nation_1 = b.em_nation_1 , a.mm_nation_2 = b.em_nation_2
    where em_mmseq = {$seq} and em_division = {$division} and a.mmseq = b.em_mmseq ";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}

function update_status_member_log($db,$seq,$division,$state){ //가족사항 상태값 변경
    $query = "update ess_member_log set em_confirm_date = now() , em_state = '{$state}' , em_confirm_mmseq = {$_SESSION['mmseq']} 
    where em_mmseq = {$seq} and em_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}

/**
 *  사외경력 관련
 **/
function get_career_log_list($db,$seq,$division){ // 사외경력 정보변경 상세
    $query = "select * from ess_career_log where crl_mmseq = {$seq} and crl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $career_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($career_list,$data);
    }
    return $career_list;
}

function get_career_list($db,$seq,$where = array()){
    $query = "select * from member_career_data where mc_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $career_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($career_list,$data);
    }
    return $career_list;
}
function insert_career_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_career_data (mc_mmseq,mc_company,mc_sdate,mc_edate
                ,mc_position,mc_duties,mc_regdate,mc_career,mc_group) 
               select crl_mmseq as mc_mmseq, crl_company as mc_company, crl_sdate as mc_sdate, crl_edate as mc_edate
                ,crl_position as mc_position,crl_duties as mc_duties,crl_applydate as mc_regdate,crl_career as mc_career,crl_group as mc_group
               from ess_career_log where crl_mmseq = {$seq} and crl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_career_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_career_data where mc_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_career_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_career_log set crl_confirm_date = now() , crl_state = '{$state}' , crl_confirm_mmseq = {$_SESSION['mmseq']} 
    where crl_mmseq = {$seq} and crl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
/**
 * 학력 관련
 **/
function get_education_log_list($db,$seq,$division){ //학력 정보변경 상세
    $query = "select * from ess_education_log where el_mmseq = {$seq} and el_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $education_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($education_list,$data);
    }
    return $education_list;
}

function get_education_list($db,$seq){
    $query = "select * from member_education_data where me_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $education_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($education_list,$data);
    }
    return $education_list;
}
function get_education_list_admin($db,$seq){
    $query = "select * from member_education_data where me_mmseq = {$seq} order by me_edate desc limit 1";
    $ps = pdo_query($db,$query,array());
    $education_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($education_list,$data);
    }
    return $education_list;
}
function insert_education_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_education_data (me_mmseq,me_name,me_sdate,me_edate
                ,me_level,me_degree,me_regdate,me_major,me_graduate_type,me_etc,me_weekly) 
               select el_mmseq as me_mmseq, el_name as me_name, el_sdate as me_sdate, el_edate as me_edate
                ,el_level as me_level,el_degree as me_degree,el_applydate as me_regdate
                ,el_major as me_major,el_graduate_type as me_graduate_type,el_etc as me_etc, el_weekly as me_weekly
               from ess_education_log where el_mmseq = {$seq} and el_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_education_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_education_data where me_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_education_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_education_log set el_confirm_date = now() , el_state = '{$state}' , el_confirm_mmseq = {$_SESSION['mmseq']} 
    where el_mmseq = {$seq} and el_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}

/**
 * 자격증관련
 **/
function get_certificate_log_list($db,$seq,$division){ //자격증 정보변경 상세
    $query = "select * from ess_certificate_log where cl_mmseq = {$seq} and cl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $certificate_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($certificate_list,$data);
    }
    return $certificate_list;
}

function get_certificate_list($db,$seq){
    $query = "select * from member_certificate_data where mct_mmseq = {$seq} order by mct_date desc";
    $ps = pdo_query($db,$query,array());
    $certificate_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($certificate_list,$data);
    }
    return $certificate_list;
}
function get_certificate_list_admin($db,$seq){
    $query = "select * from member_certificate_data where mct_mmseq = {$seq} order by mct_date desc limit 3";
    $ps = pdo_query($db,$query,array());
    $certificate_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($certificate_list,$data);
    }
    return $certificate_list;
}
function insert_certificate_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_certificate_data (mct_mmseq,mct_cert_name,mct_institution,mct_class
                ,mct_date,mct_regdate,mct_num) 
               select cl_mmseq as mct_mmseq, cl_cert_name as mct_cert_name, cl_institution as mct_institution, cl_class as mct_class
                ,cl_date as mct_date,cl_applydate as mct_regdate,cl_num as mct_num
               from ess_certificate_log where cl_mmseq = {$seq} and cl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_certificate_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_certificate_data where mct_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_certificate_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_certificate_log set cl_confirm_date = now() , cl_state = '{$state}' , cl_confirm_mmseq = {$_SESSION['mmseq']} 
    where cl_mmseq = {$seq} and cl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
/**
 * 발령 관련
 */
function get_appointment_list($db,$seq){
    $query = "select * from member_appointment_data where ma_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_appointment_log_list($db,$seq,$division){ //학력 정보변경 상세
    $query = "select * from ess_appointment_log where ea_mmseq = {$seq} and ea_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function insert_appointment_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_appointment_data (ma_mmseq,ma_type,ma_company,ma_position
                ,ma_job,ma_date) 
               select ea_mmseq as ma_mmseq, ea_type as ma_type, ea_company as ma_company, ea_position as ma_position
                ,ea_job as ma_job,ea_date as ma_date
               from ess_appointment_log where ea_mmseq = {$seq} and ea_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_appointment_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_appointment_data where ma_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_appointment_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_appointment_log set ea_confirm_date = now() , ea_state = '{$state}' , ea_confirm_mmseq = {$_SESSION['mmseq']} 
    where ea_mmseq = {$seq} and ea_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
/**
 * 교육활동 관련
 */
function get_activity_list($db,$seq){
    $query = "select * from member_activity_data where mad_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_activity_log_list($db,$seq,$division){ //학력 정보변경 상세
    $query = "select * from ess_activity_log where eat_mmseq = {$seq} and eat_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function insert_activity_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_activity_data (mad_mmseq,mad_sdate,mad_edate,mad_type
                ,mad_name,mad_institution,mad_file,mad_file_name,mad_role) 
               select eat_mmseq as mad_mmseq, eat_sdate as mad_sdate, eat_edate as mad_edate, eat_type as mad_type
                ,eat_name as mad_name,eat_institution as mad_institution,eat_file as mad_file,eat_file_name as mad_file_name,eat_role as mad_role
               from ess_activity_log where eat_mmseq = {$seq} and eat_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_activity_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_activity_data where mad_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_activity_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_activity_log set eat_confirm_date = now() , eat_state = '{$state}' , eat_confirm_mmseq = {$_SESSION['mmseq']} 
    where eat_mmseq = {$seq} and eat_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
/**
 * 논문 / 저서 관련
 */
function get_paper_list($db,$seq){
    $query = "select * from member_paper_data where mp_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_paper_log_list($db,$seq,$division){ //학력 정보변경 상세
    $query = "select * from ess_paper_log where ep_mmseq = {$seq} and ep_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function insert_paper_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_paper_data (mp_mmseq,mp_date,mp_name,mp_institution) 
               select ep_mmseq as mp_mmseq, ep_date as mp_date, ep_name as mp_name, ep_institution as mp_institution
               from ess_paper_log where ep_mmseq = {$seq} and ep_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_paper_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_paper_data where mp_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_paper_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_paper_log set ep_confirm_date = now() , ep_state = '{$state}' , ep_confirm_mmseq = {$_SESSION['mmseq']} 
    where ep_mmseq = {$seq} and ep_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
/**
 * 프로젝트 관련
 */
function get_project_list($db,$seq){
    $query = "select * from member_project_data where mpd_mmseq = {$seq} order by mpd_edate desc";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_project_list_v2($db,$seq){
    $query = "select * from member_project_data where mpd_mmseq = {$seq} order by mpd_edate desc limit 3";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_project_log_list($db,$seq,$division){ //학력 정보변경 상세
    $query = "select * from ess_project_log where epj_mmseq = {$seq} and epj_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function insert_project_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_project_data (mpd_mmseq,mpd_sdate,mpd_edate,mpd_name,mpd_content,mpd_keyword,mpd_contribution,mpd_result,mpd_position) 
               select epj_mmseq as mpd_mmseq, epj_sdate as mpd_sdate, epj_edate as mpd_edate, epj_name as mpd_name ,epj_content as mpd_content,
               epj_keyword as mpd_keyword , epj_contribution as mpd_contribution , epj_result as mpd_result , epj_position as mpd_position
               from ess_project_log where epj_mmseq = {$seq} and epj_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_project_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_project_data where mpd_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_project_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_project_log set epj_confirm_date = now() , epj_state = '{$state}' , epj_confirm_mmseq = {$_SESSION['mmseq']} 
    where epj_mmseq = {$seq} and epj_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
/**
 * 패밀리 관련
 */

function get_family_list($db,$seq){
    $query = "select * from member_family_data where mf_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $family_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($family_list,$data);
    }
    return $family_list;
}
function insert_family_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_family_data (mf_mmseq,mf_name,mf_resident,mf_birth
                ,mf_gender,mf_foreigner,mf_relationship,mf_householder,mf_job_name
                ,mf_job,mf_education,mf_allowance,mf_together,mf_regdate) 
               select ml_mmseq as mf_mmseq, ml_name as mm_name, ml_resident as mf_resident, ml_birth as mf_birth
                ,ml_gender as mf_gender,ml_foreigner as mf_foreigner,ml_relationship as mf_relationship,ml_householder as mf_householder,ml_job_name as mf_job_name
                ,ml_job as mf_job,ml_education as mf_education,ml_allowance as mf_allowance,ml_together as mf_together,ml_confirm_date as mf_regdate
               from ess_family_log where ml_mmseq = {$seq} and ml_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_family_data($db,$seq){ //가족사항 데이터 삭제
    $query = "delete from member_family_data where mf_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_family_log($db,$seq,$division,$state){ //가족사항 상태값 변경
    $query = "update ess_family_log set ml_confirm_date = now() , ml_state = '{$state}' , ml_confirm_mmseq = {$_SESSION['mmseq']} 
    where ml_mmseq = {$seq} and ml_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}

function get_family_log_list($db,$seq,$division){ //가족사항 정보변경 상세 리스트
    $query = "select * from ess_family_log where ml_mmseq = {$seq} and ml_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $family_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($family_list,$data);
    }
    return $family_list;
}

//birth list
function get_birth_list($db){
    $beforedate_Array  = array(date('md',strtotime('+1 day')),date('md',strtotime('+2 day')),date('md',strtotime('+3 day')),
        date('md',strtotime('-1 day')),date('md',strtotime('-2 day')),date('md',strtotime('-3 day'))
    ,date('md',strtotime('-4 day')),date('md',strtotime('-5 day')),date('md',strtotime('-6 day')),date('md',strtotime('-7 day')),date('md'));
    $beforedate = implode('\',\'',$beforedate_Array);
    $query = "select mm_name,mc_position , mm_profile, substring(mm_birth,6,5)  as mm_birth from 
            ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq
           where mc_coseq = {$_SESSION['mInfo']['mc_coseq']} and mm_status ='Y' and mm_super_admin = 'F' and mm_is_del = 'FALSE' 
           and DATE_FORMAT(mm_birth,'%m%d') in ('{$beforedate}')
          order by substring(mm_birth,6,5) desc 
          limit 12";
    $ps = pdo_query($db,$query,array());
    $birth_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($birth_list,$data);
    }
    return $birth_list;
}

//birth list2
function get_birth_list2($db){
    $query = "select mm_name , mm_profile, mm_birth  as mm_birth from 
            ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq 
           where mc_coseq = {$_SESSION['mInfo']['mc_coseq']} and mm_status ='Y'
          order by mm_birth desc 
          limit 12";
    $ps = pdo_query($db,$query,array());
    $birth_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($birth_list,$data);
    }
    return $birth_list;
}

/**
 * 상벌 관련
 */
function get_punishment_list($db,$seq){
    $query = "select * from member_punishment_data where mp_mmseq = {$seq} order by mp_date desc";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}

/**
 * 인사평가 관련
 */
function get_evaluation_list($db,$seq){
    $query = "select * from member_evaluation where me_mmseq = {$seq} order by me_year desc";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_evaluation_list_admin($db,$seq){
    $query = "select * from member_evaluation where me_mmseq = {$seq} order by me_year desc limit 3";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
/**
 * 상벌 관련
 */
function get_punishment_log_list($db,$seq,$division){
    $query = "select * from ess_punishment_log where ep_mmseq = {$seq} and ep_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function insert_punishment_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_punishment_data (mp_mmseq,mp_type,mp_date,mp_title,mp_content
                ,mp_etc) 
               select ep_mmseq as mp_mmseq, ep_type as mp_type, ep_date as mp_date, ep_title as mp_title, ep_content as mp_content
                ,ep_etc as mp_etc
               from ess_punishment_log where ep_mmseq = {$seq} and ep_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_punishment_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_punishment_data where mp_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_punishment_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_punishment_log set ep_confirm_date = now() , ep_state = '{$state}' , ep_confirm_mmseq = {$_SESSION['mmseq']} 
    where ep_mmseq = {$seq} and ep_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}

//수상
function get_premier_list($db,$seq,$where = array()){
    $query = "select * from member_premier_data where mpd_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $career_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($career_list,$data);
    }
    return $career_list;
}
/**
 * 자격증관련
 **/
function get_premier_log_list($db,$seq,$division){ //자격증 정보변경 상세
    $query = "select * from ess_premier_log where epl_mmseq = {$seq} and epl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    $premier_list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($premier_list,$data);
    }
    return $premier_list;
}

function insert_premier_data($db,$seq,$division){ //변경 입력
    $query = " insert into member_premier_data (mpd_mmseq,mpd_date,mpd_content,mpd_institution,mpd_regdate) 
               select epl_mmseq as mpd_mmseq, epl_date as mpd_date , epl_content as mpd_content , epl_institution as mpd_institution , epl_confirm_date as mpd_regdate  
               from ess_premier_log where epl_mmseq = {$seq} and epl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
function delete_premier_data($db,$seq){ // 데이터 삭제
    $query = "delete from member_premier_data where mpd_mmseq = {$seq}";
    $ps = pdo_query($db,$query,array());
}
function update_status_premier_log($db,$seq,$division,$state){ // 상태값 변경
    $query = "update ess_premier_log set epl_confirm_date = now() , epl_state = '{$state}' , epl_confirm_mmseq = {$_SESSION['mmseq']} 
    where epl_mmseq = {$seq} and epl_division = {$division}";
    $ps = pdo_query($db,$query,array());
    if($ps){
        return true;
    }else{
        return false;
    }
}
?>