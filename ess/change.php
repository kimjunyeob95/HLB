<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
@$mmseq = $_SESSION['mmseq'];
@$page = $_REQUEST['page'];

$rows = 10;
if(empty($page)){
    $page=1;
}

$query="
select count(*) as cnt from(
(select  distinct crl_division,'경력사항' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_mmseq = {$mmseq} and crl_state <> 'C')
union
(select distinct cl_division, '어학 / 자격증 / 수상' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_mmseq = {$mmseq} and cl_state <> 'C')
union
(select distinct el_division, '학력사항' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_mmseq = {$mmseq} and el_state <> 'C')
union
(select distinct ml_division, '가족사항' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_mmseq = {$mmseq} and ml_state <> 'C')
union
(select distinct ep_division, '논문 / 저서' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_paper_log where ep_mmseq = {$mmseq} and ep_state <> 'C')
union
(select distinct eat_division, '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_mmseq = {$mmseq} and eat_state <> 'C')
union
(select distinct ea_division, '발령사항' as 'info',ea_confirm_date as confirm_date,ea_applydate as applydate,ea_state as status from ess_appointment_log where ea_mmseq = {$mmseq} and ea_state <> 'C')
union
(select distinct ep_division, '상벌' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_punishment_log where ep_mmseq = {$mmseq} and ep_state <> 'C')
union
(select distinct em_division, '기본사항' as 'info',em_confirm_date as confirm_date,em_applydate as applydate,em_state as status from ess_member_log where em_mmseq = {$mmseq} and em_state <> 'C')
) T;
";
// echo('<pre>');print_r($query);echo('</pre>');
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
select * from(
(select  distinct crl_division as division,crl_mmseq as seq,'another' as 'page','경력사항' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_mmseq = {$mmseq} and crl_state <> 'C')
union
(select distinct cl_division as division,cl_mmseq as seq,'cert' as 'page', '어학 / 자격증 / 수상' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_mmseq = {$mmseq} and cl_state <> 'C')
union
(select distinct el_division as division,el_mmseq as seq,'education' as 'page', '학력사항' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_mmseq = {$mmseq} and el_state <> 'C')
union
(select distinct ml_division as division,ml_mmseq as seq,'family' as 'page', '가족사항' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_mmseq = {$mmseq} and ml_state <> 'C')
union
(select distinct ep_division as division,ep_mmseq as seq,'paper' as 'page', '논문 / 저서' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_paper_log where ep_mmseq = {$mmseq} and ep_state <> 'C')
union
(select distinct eat_division as division,eat_mmseq as seq,'activity' as 'page', '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_mmseq = {$mmseq} and eat_state <> 'C')
union
(select distinct ea_division as division,ea_mmseq as seq,'appointment' as 'page', '발령사항' as 'info',ea_confirm_date as confirm_date,ea_applydate as applydate,ea_state as status from ess_appointment_log where ea_mmseq = {$mmseq} and ea_state <> 'C')
union
(select distinct ep_division as division,ep_mmseq as seq,'prize' as 'page', '상벌' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_punishment_log where ep_mmseq = {$mmseq} and ep_state <> 'C')
union
(select distinct em_division as division,em_mmseq as seq,'nomal' as 'page', '기본사항' as 'info',em_confirm_date as confirm_date,em_applydate as applydate,em_state as status from ess_member_log where em_mmseq = {$mmseq} and em_state <> 'C')
) T order by T.applydate desc limit {$from} , {$rows};
";

$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
// echo('<pre>');print_r($list);echo('</pre>');
?>
<style>
    table tbody tr{cursor: pointer;}
</style>
<div id="wrap" class="depth03">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="my-profile-card">
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>My Profile</h2>
		<ul class="lnb">
            <li><a href="/ess/tree" >조직도</a></li>
			<li><a href="/ess/timeline">나의 타임라인</a></li>
			<li><a href="/ess/" >인사기록카드</a></li>
			<li><a href="/ess/change"  class="active" >정보 변경 신청 내역</a></li>
			<!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">정보 변경 신청 내역</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>정보 변경 신청 내역</caption>
                    <colgroup>
                        <col style="width: 20px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">구분</th>
                        <th scope="col">신청일시</th>
                        <th scope="col">처리일시</th>
                        <th scope="col">반려사유</th>
                        <th scope="col">상태</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- 개발일때
                    <c:forEach begin="1" end="6">
                    <tr>
                        <td></td>
                        <td class="center"></td>
                        <td class="center"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </c:forEach>
                    -->
                    <?if(sizeof($list)<1){?>
                        <tr><td colspan ='7' style='text-align:center'>내역이 없습니다.</td></tr>
                    <?}else{?>
                        <?for($i=0;$i<sizeof($list);$i++){?>
                            <tr onClick="detail_page('<?=$list[$i]['page']?>',<?=$list[$i]['seq']?>,<?=$list[$i]['division']?>);">
                                <td><?=$numbering?></td>
                                <td class="center"><?=$list[$i]['info']?></td>
                                <td><?=substr($list[$i]['applydate'],0,10)?></td>
                                <td><?=null_hyphen(substr($list[$i]['confirm_date'],0,10))?></td>
                                <td>
                                <? if($status[$list[$i]['status']]=='반려'){
                                    echo ''.get_cause_of_return($db,$list[$i]['seq'] , $list[$i]['division'],$list[$i]['page']);
                                }else echo '-';?>
                                </td>
                                <?
                                    if($status[$list[$i]['status']]=='처리중'){
                                        echo '<td class="ing">'.$status[$list[$i]['status']].'</td>';
                                    }else if($status[$list[$i]['status']]=='처리완료'){
                                        echo '<td class="complete">'.$status[$list[$i]['status']].'</td>';
                                    }else if($status[$list[$i]['status']]=='반려'){
                                        echo '<td class="companion">'.$status[$list[$i]['status']].'</td>';
                                    }
                                ?>
                            </tr>
                        <?$numbering--;}?>
                    <?}?>
                    </tbody>
                </table>
                <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
            </div>
            <!-- //공지사항 -->
		</div>
	</div>
</div>
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(0)').addClass('active');
$('.depth02:eq(0)').find('li:eq(3)').addClass('active');

function detail_page(page,mmseq,division){
    location.href = 'humandetail_'+page+'?seq='+mmseq+'&division='+division;
}
</script>