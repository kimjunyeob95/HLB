<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$rows = 10;
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
if(empty($page)){
    $page=1;
}
$where=' and mm_super_admin="F" and mm_is_del="False" ';
if(!empty($category) && !empty($search)){
    if($category=='mc_code'){
        $where .= " and {$category} = '{$search}' ";
    }else{
        $where .= " and {$category} = '{$enc->encrypt($search)}' ";
    }
}
if(!empty($mm_status) && $mm_status !='all' ){
    $where .= " and status = '{$mm_status}' ";
}
$query=" 
 select count(*) as cnt from ess_member_base as emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq join
(
(select distinct crl_division as division, crl_mmseq as mmseq,'경력사항' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_state <> 'C')
union
(select distinct cl_division as division, cl_mmseq as mmseq,'어학 / 자격증 / 수상' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_state <> 'C')
union
(select distinct el_division as division, el_mmseq as mmseq,'학력사항' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_state <> 'C')
union
(select distinct ml_division as division, ml_mmseq as mmseq,'가족사항' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_state <> 'C')
union
(select distinct ep_division as division,ep_mmseq as mmseq, '논문 / 저서' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_paper_log where ep_state <> 'C')
union
(select distinct eat_division as division,eat_mmseq as mmseq, '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_state <> 'C')
union
(select distinct ea_division as division,ea_mmseq as mmseq, '발령사항' as 'info',ea_confirm_date as confirm_date,ea_applydate as applydate,ea_state as status from ess_appointment_log where ea_state <> 'C')
union
(select distinct ep_division as division,ep_mmseq as mmseq, '상벌' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_punishment_log where ep_state <> 'C')
union
(select  distinct em_division as division ,em_mmseq as mmseq,'기본 사항' as 'info',em_confirm_date as confirm_date,em_applydate as applydate,em_state as status from ess_member_log where em_state <> 'C')
) T on T.mmseq = emb.mmseq where emc.mc_coseq = {$mc_coseq} and mc_main = 'T' ".$where;

$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$total_rows = $data['cnt'];
if ($total_rows > 0) {
    $total_page = ceil($total_rows / $rows);
} else {
    $total_page = 1;
}

$from = ($page - 1) * $rows;
$numbering = $total_rows - $from;

$query=" 
 select * from ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq join
(
(select distinct crl_division as division,crl_confirm_mmseq as confirm_mmseq, 'another' as type, crl_mmseq as mmseq,'경력사항' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_state <> 'C')
union
(select distinct cl_division as division, cl_confirm_mmseq as confirm_mmseq ,'cert' as type, cl_mmseq as mmseq,'어학 / 자격증 / 수상' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_state <> 'C')
union
(select distinct el_division as division,el_confirm_mmseq as confirm_mmseq,'education' as type, el_mmseq as mmseq,'학력사항' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_state <> 'C')
union
(select distinct ml_division as division,ml_confirm_mmseq as confirm_mmseq,'family' as type , ml_mmseq as mmseq,'가족사항' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_state <> 'C')
union
(select distinct ep_division as division,ep_confirm_mmseq as confirm_mmseq,'paper' as type,ep_mmseq as mmseq, '논문 / 저서' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_paper_log where ep_state <> 'C')
union
(select distinct eat_division as division,eat_confirm_mmseq as confirm_mmseq,'activity' as type,eat_mmseq as mmseq, '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_state <> 'C')
union
(select distinct ep_division as division,ep_confirm_mmseq as confirm_mmseq,'prize' as type,ep_mmseq as mmseq, '상벌' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_punishment_log where ep_state <> 'C')
union
(select distinct ea_division as division,ea_confirm_mmseq as confirm_mmseq,'appointment' as type,ea_mmseq as mmseq, '발령사항' as 'info',ea_confirm_date as confirm_date,ea_applydate as applydate,ea_state as status from ess_appointment_log where ea_state <> 'C')
union
(select  distinct em_division as division ,em_confirm_mmseq as confirm_mmseq ,'nomal' as type ,em_mmseq as mmseq,'기본 사항' as 'info',em_confirm_date as confirm_date,em_applydate as applydate,em_state as status from ess_member_log where em_state <> 'C')
) T on T.mmseq = emb.mmseq where emc.mc_coseq = {$mc_coseq} ".$where." and mc_main = 'T' order by T.applydate desc limit {$from} , {$rows}";

$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category'];
?>
<style>
    table tbody tr{cursor: pointer;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">정보 수정 신청내역</h2>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <select name="mm_status">
                <option value="all" <?if($_REQUEST['mm_status']=='all'|| empty($_REQUEST['mm_status'])){?>selected<?}?>>전체</option>
                <option value="N" <?if($_REQUEST['mm_status']=='N'){?>selected<?}?> >반려</option>
                <option value="Y" <?if($_REQUEST['mm_status']=='Y'){?>selected<?}?> >처리완료</option>
                <option value="A" <?if($_REQUEST['mm_status']=='A'){?>selected<?}?> >처리미완료</option>
            </select>
            <select name="category">
                <option value="mm_name"  <?if($_REQUEST['category']=='mm_name' || empty($_REQUEST['category'])){?>selected<?}?>>성명</option>
                <option value="mc_code" <?if($_REQUEST['category']=='mc_code'){?>selected<?}?>>사번</option>
                <option value="mm_phone" <?if($_REQUEST['category']=='mm_phone'){?>selected<?}?>>연락처</option>
            </select>
            <div class="input-search" id="" style="display: inline-block; width: 300px;">
                <input type="text" type="number" value="<?=$search?>" name="search" style="width: 298px; border:0;"/>
                <button type="submit" class="btn"  style="width: 20px;">
                    <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
                </button>
            </div>
            <button type="submit" id="" class="btn type01 small">조회</button>
        </form>
		<div class="section-wrap" style="margin-top: 30px;">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>정보 수정 신청내역</caption>
                    <colgroup>
                        <col style="width: 20px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">사번</th>
                        <th scope="col">성명</th>
                        <th scope="col">소속</th>
                        <th scope="col">구분</th>
                        <th scope="col">신청일시</th>
                        <th scope="col">처리일시</th>
                        <th scope="col">처리자</th>
                        <th scope="col">상태</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?for($i=0;$i<sizeof($list);$i++){?>
                        <tr onclick="detail_page('<?=$list[$i]['type']?>','<?=$list[$i]['mmseq']?>','<?=$list[$i]['division']?>');">
                            <td><?=$numbering?></td>
                            <td class="center"><?=$list[$i]['mc_code']?></td>
                            <td><?=$enc->decrypt($list[$i]['mm_name'])?></td>
                            <td><?=implode('<br> ',get_group_list_v2($db,$list[$i]['mmseq']))?></td>
                            <td class="center"><?=$list[$i]['info']?></td>
                            <td><?=substr($list[$i]['applydate'],0,10)?></td>
                            <td><?=null_hyphen(substr($list[$i]['confirm_date'],0,10))?></td>
                            <td class="center"><?=$enc->decrypt(get_member_info($db,$list[$i]['confirm_mmseq'])['mm_name'])?></td>
                            <?
                                if($status3[$list[$i]['status']]=='처리미완료'){
                                    echo '<td class="ing">'.$status[$list[$i]['status']].'</td>';
                                }else if($status[$list[$i]['status']]=='처리완료'){
                                    echo '<td class="complete">'.$status[$list[$i]['status']].'</td>';
                                }else if($status[$list[$i]['status']]=='반려'){
                                    echo '<td class="companion">'.$status[$list[$i]['status']].'</td>';
                                }
                            ?>
                        </tr>
                    <? 
                        $numbering--;}
                    ?>
                    </tbody>
                   
                </table>
                <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
            </div>
            <!-- //공지사항 -->
		</div>
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(0)').addClass('active');

    function detail_page(page,mmseq,division){
        location.href = 'humandetail_'+page+'.php?mmseq='+mmseq+'&division='+division+'&page='+<?=$page?>+'<?=$paging_subquery?>';
    }
</script>

