<?php

//남은 휴가
function remain_holiday($db,$coseq,$tc_num){
    if(empty($coseq)){
        return '0';
    }
    $where = " 1 = 1 and emc.mc_coseq = {$coseq} and tc.tc_num={$tc_num} ";
    $query="SELECT tc.*,emb.mm_name,emb.mm_gender,emc.mc_code,emc.mc_commute_remain FROM tbl_commute tc 
                LEFT JOIN ess_member_base emb ON tc.tc_mmseq=emb.mmseq
                LEFT JOIN ess_member_code emc ON emc.mc_mmseq = emb.mmseq
            where {$where}";
    $ps = pdo_query($db,$query,array());
    $remain_holiday_data = $ps->fetch(PDO::FETCH_ASSOC);
    return $remain_holiday_data['mc_commute_remain'];
}

?>