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
(select  '기본사항' as 'info','기본 정보' as 'info',cli_confirmDate as confirm_date,cli_regdate as applydate,cli_status as status from ess_change_infomation_log where cli_mmseq = {$mmseq})
) T;
";
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
(select  '기본사항' as 'info','기본 정보' as 'info',cli_confirmDate as confirm_date,cli_regdate as applydate,cli_status as status from ess_change_infomation_log where cli_mmseq = {$mmseq})
) T order by T.applydate desc limit {$from} , {$rows};
";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
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
			<li><a href="/ess/timeline.php">나의 타임라인</a></li>
			<li><a href="/ess/" >인사기록카드</a></li>
			<li><a href="/ess/change.php"  class="active" >정보 변경 신청 내역</a></li>
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
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">분류</th>
                        <th scope="col">사번</th>
                        <th scope="col">성명/성별</th>
                        <th scope="col">국적</th>
                        <th scope="col">생년월일</th>
                        <th scope="col">연락처</th>
                        <th scope="col">신청일시</th>
                        <th scope="col">처리일시</th>
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
                        <tr><td colspan ='10' style='text-align:center'>내역이 없습니다.</td></tr>
                    <?}else{?>
                        <?for($i=0;$i<sizeof($list);$i++){?>
                            <tr>
                                <td><?=$numbering?></td>
                                <td class="center"><?=$list[$i]['info']?></td>
                                <td class="center">01200001</td>
                                <td><?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?></td>
                                <?if($_SESSION['mInfo']['mm_foreigner']=='FALSE'){?>
                                    <td>대한민국</td>
                                <?}else{?>
                                    <td>외국</td>
                                <?}?>
                                <td><?=substr($_SESSION['mInfo']['mm_birth'],0,10)?></td>
                                <td><?=$enc->decrypt($_SESSION['mInfo']['mm_cell_phone'])?></td>
                                <td><?=substr($list[$i]['applydate'],0,10)?></td>
                                <?if(empty($list[$i]['confirm_date'])){?>
                                    <td>-</td>
                                <?}else{?>
                                    <td><?=substr($list[$i]['confirm_date'],0,10)?></td>
                                <?}?>
                                <td><?=$status[$list[$i]['status']]?></td>
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
$('.depth02:eq(0)').find('li:eq(2)').addClass('active');
</script>