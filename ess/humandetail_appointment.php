<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$mmseq = $_SESSION['mmseq'];
@$page = $_REQUEST['page'];

$rows = 10;
if(empty($page)){
    $page=1;
}

$query="
select count(*) as cnt from(
(select  distinct crl_division,'사외경력' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_mmseq = {$mmseq})
union
(select distinct cl_division, '어학 / 자격증' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_mmseq = {$mmseq})
union
(select distinct el_division, '학력' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_mmseq = {$mmseq})
union
(select distinct ml_division, '가족' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_mmseq = {$mmseq})
union
(select distinct ep_division, '논문 / 저서' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_paper_log where ep_mmseq = {$mmseq})
union
(select distinct eat_division, '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_mmseq = {$mmseq})
union
(select distinct ea_division, '발령' as 'info',ea_confirm_date as confirm_date,ea_applydate as applydate,ea_state as status from ess_appointment_log where ea_mmseq = {$mmseq})
union
(select distinct epj_division, '프로젝트' as 'info',epj_confirm_date as confirm_date,epj_applydate as applydate,epj_state as status from ess_project_log where epj_mmseq = {$mmseq})
union
(select distinct em_division, '기본정보' as 'info',em_confirm_date as confirm_date,em_applydate as applydate,em_state as status from ess_member_log where em_mmseq = {$mmseq})
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
(select  distinct crl_division,'사외경력' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_mmseq = {$mmseq})
union
(select distinct cl_division, '어학 / 자격증' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_mmseq = {$mmseq})
union
(select distinct el_division, '학력' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_mmseq = {$mmseq})
union
(select distinct ml_division, '가족' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_mmseq = {$mmseq})
union
(select distinct ep_division, '논문 / 저서' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_paper_log where ep_mmseq = {$mmseq})
union
(select distinct eat_division, '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_mmseq = {$mmseq})
union
(select distinct ea_division, '발령' as 'info',ea_confirm_date as confirm_date,ea_applydate as applydate,ea_state as status from ess_appointment_log where ea_mmseq = {$mmseq})
union
(select distinct epj_division, '프로젝트' as 'info',epj_confirm_date as confirm_date,epj_applydate as applydate,epj_state as status from ess_project_log where epj_mmseq = {$mmseq})
union
(select distinct em_division, '기본정보' as 'info',em_confirm_date as confirm_date,em_applydate as applydate,em_state as status from ess_member_log where em_mmseq = {$mmseq})
) T order by T.applydate desc limit {$from} , {$rows};
";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
// echo('<pre>');print_r($status[$list[0]['status']]);echo('</pre>');
?>
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
        <h2 class="content-title">정보 변경 상세(발령) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-appointment"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>발령</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">발령일자</th>
                            <th scope="col">구분</th>
                            <th scope="col">발령회사 및 부서</th>
                            <th scope="col">직위</th>
                            <th scope="col">담당직무</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($appointment_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><?=substr($val['ea_date'],0,10)?></td>
                                <td class="insert"><?=$val['ea_type']?></td>
                                <td class="insert"><?=$enc->decrypt($val['ea_company'])?></td>
                                <td scope="insert"><?=$val['ea_position']?></td>
                                <td scope="insert"><?=$val['ea_job']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(발령) 수정 후</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-appointment2"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>발령</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">발령일자</th>
                            <th scope="col">구분</th>
                            <th scope="col">발령회사 및 부서</th>
                            <th scope="col">직위</th>
                            <th scope="col">담당직무</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($appointment_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><?=substr($val['ea_date'],0,10)?></td>
                                <td class="insert"><?=$val['ea_type']?></td>
                                <td class="insert"><?=$enc->decrypt($val['ea_company'])?></td>
                                <td scope="insert"><?=$val['ea_position']?></td>
                                <td scope="insert"><?=$val['ea_job']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
                <h2 class="content-title" style="margin-top:50px;">반려 사유</h2>
                <div class="table-wrap" style="border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>반려 사유</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 20%" />
                        </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">반려 사유</th>
                                    <td>반려사유 데이터필요</td>
                                </tr>
                            </tbody>
                    </table>
                </div>
                <div style="text-align:center;margin-top:50px;">
                    <div class="button-area large">
                        <button type="button" data-btn="목록" onclick="location.href='/ess/change?1=1<?=$paging_subquery?>'" class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
                    </div>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
	</div>
</div>
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(0)').addClass('active');
$('.depth02:eq(0)').find('li:eq(2)').addClass('active');

</script>