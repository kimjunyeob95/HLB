<?php
/**
 * 타입별 선택 리스트
 */
function get_position_list_select_type($db,$seq,$code,$type){
    $query = "select * from tbl_position as A JOIN ess_member_code as B ON B.mc_position = A.tp_seq where 
    A.tp_seq = {$seq} and B.mc_code = {$code} and tp_type = {$type} ";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data;
}

function get_position_list_type($db,$type){
    $query = "select * from tbl_position where tp_coseq = {$_SESSION['mInfo']['mc_coseq']} and tp_type ={$type}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}


function get_position_title_type($db,$seq,$type){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_position where tp_coseq = {$_SESSION['mInfo']['mc_coseq']} and tp_seq = {$seq} and tp_type = {$type} ";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tp_title'];
}
function get_position_title_type_admin($db,$seq,$type,$coseq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_position where tp_coseq = {$coseq} and tp_seq = {$seq} and tp_type = {$type} ";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tp_title'];
}
/**
 * 직위 전체 리스트
 */
function get_position_list_select($db,$seq,$code){
    $query = "select * from tbl_position as A JOIN ess_member_code as B ON B.mc_position = A.tp_seq where A.tp_seq = {$seq} and B.mc_code = {$code}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data;
}

function get_position_list($db,$type){
    $query = "select * from tbl_position where tp_coseq = {$_SESSION['mInfo']['mc_coseq']} and tp_type ={$type}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_position_list_admin($db,$type,$mc_coseq){
    $query = "select * from tbl_position where tp_coseq = {$mc_coseq} and tp_type ={$type}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_position_list_all($db){
    $query = "select * from tbl_position";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}

function get_position_title($db,$seq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_position where tp_coseq = {$_SESSION['mInfo']['mc_coseq']} and tp_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tp_title'];
}
function get_position_title_admin($db,$seq,$coseq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_position where tp_coseq = {$coseq} and tp_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tp_title'];
}
/**
 * 직책
 * */
function get_position2_list($db){
    $query = "select * from tbl_position2 where tp2_coseq = {$_SESSION['mInfo']['mc_coseq']}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}

function get_position2_list_all($db){
    $query = "select * from tbl_position2";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}

function get_position2_title($db,$seq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_position2 where tp2_coseq = {$_SESSION['mInfo']['mc_coseq']} and tp2_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tp2_title'];
}

function get_position2_title_v2($db,$mc_coseq,$seq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_position2 where tp2_coseq = {$mc_coseq} and tp2_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tp2_title'];
}


?>