<?php

//거주국가
function text_country($db,$code){
    if(empty($code)){
        return '없음';
    }
    $query = "select nationCodeKr from tbl_nation_code where nationCode = {$code}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['nationCodeKr'];
}

function title_coperation($db,$seq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select co_name from tbl_coperation where co_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['co_name'];
}
function title_coperation_subtitle($db,$seq){
    if(empty($seq)){
        return '없음';
    }
    $query = "select co_subname from tbl_coperation where co_seq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
    return $data['co_subname'];
}
?>