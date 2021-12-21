<?php
/**
 * 조직 전체 리스트
 */
function get_group_list($db){
    $query = "select * from tbl_ess_group where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and tg_parent_seq <> 0";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_group_list_admin_all($db,$mc_coseq){
    $query = "select * from tbl_ess_group where tg_coseq = {$mc_coseq} and tg_parent_seq <> 0";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_group_list_all($db){
    $query = "select * from tbl_ess_group where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} order by tg_sort_date asc ";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_member_group($db,$mmseq){
    $query = "select * from tbl_relation_group join tbl_ess_group on trg_group = tg_seq where trg_mmseq = {$mmseq} and trg_coseq = {$_SESSION['mInfo']['mc_coseq']}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_member_group_admin($db,$mmseq,$mc_coseq){
    $query = "select * from tbl_relation_group join tbl_ess_group on trg_group = tg_seq where trg_mmseq = {$mmseq} and trg_coseq = {$mc_coseq}";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_group_list_all_v2($db){
    $query = "select * from tbl_ess_group";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_group_list_all_v3($db,$mc_coseq){
    $query = "select * from tbl_ess_group where tg_coseq = {$mc_coseq} ";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
    return $list;
}
function get_orgmanage_title($db,$seq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select * from tbl_ess_group where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and tg_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['tg_title'];
}

function get_group_list_v2($db,$seq){
    $query = "select tg_title from ess_member_base emb 
                            join tbl_relation_group trg on emb.mmseq = trg.trg_mmseq
                            join tbl_ess_group teg on teg.tg_seq = trg.trg_group
                            where emb.mmseq = {$seq} and trg_coseq = {$_SESSION['mInfo']['mc_coseq']}  ";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        $data = $data['tg_title'];
        array_push($list,$data);
    }
    return $list;
}
function get_group_list_admin($db,$seq,$mc_coseq){
    $query = "select tg_title from ess_member_base emb 
                            join tbl_relation_group trg on emb.mmseq = trg.trg_mmseq
                            join tbl_ess_group teg on teg.tg_seq = trg.trg_group
                            where emb.mmseq = {$seq} and trg_coseq = {$mc_coseq}  ";
    $ps = pdo_query($db,$query,array());
    $list = array();
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        $data = $data['tg_title'];
        array_push($list,$data);
    }
    return $list;
}
?>