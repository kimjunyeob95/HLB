<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

$query ="select * from tbl_holiday_guide where th_coseq = {$coseq}";
$ps = pdo_query($db,$query,array());
$data_html = $ps ->fetch(PDO::FETCH_ASSOC);
?>

<div id="wrap" class="depth03">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="dilligence">
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>근태</h2>
		<ul class="lnb">
			<li ><a href="/ess/holiday.php" >휴가 신청</a></li>
            <li><a href="/ess/holyguide.php" class="active" >근태 가이드</a></li>
            <?if(!empty($reader)){?>
                <li><a href="/ess/mssholyday.php">휴가관리</a></li>
            <?}?>
			<!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">근태</h2>
		<div class="dilligence-intro">
			<p class="text">근무시간을 준수하고 성실하게 업무에 임하는 것은<br>
			조직 구성원 공동의 목표를 달성하기 위한 기본 원칙입니다.<br>
			출퇴근(지각/조퇴/외출 포함), 휴가/휴무/휴직, 출장/교육 등 <br>
			근무상태 일체를 뜻하는 ‘근태(勤怠)’는 근무질서 확립에 영향을 미치며,<br>
			임금 및 인력운영 등 인사부문의 기초자료로 활용되므로 정확하게 관리되어야 합니다.</p>
			<strong>올바른 근태관리, 가장 기본적인 책임이자 의무입니다.</strong>
		</div>
		<h3 class="section-title">근태 유형</h3>
		<div class="category-wrap">
			<!-- 근무 -->
			<div class="sort work">
				<h4>근무</h4>
				<div class="table-wrap">
					<table class="data-table left">
						<caption>근무표</caption>
						<colgroup>
							<col style="width: 135px;">
							<col style="width: *">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">정상근무</th>
								<td>근무, 출장, 교육등</td>
							</tr>
							<tr>
								<th scope="row">특별근무</th>
								<td>휴일근로 등</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="sort holiday">
				<h4>휴무</h4>
				<div class="table-wrap">
					<table class="data-table left">
						<caption>휴무표</caption>
						<colgroup>
							<col style="width: 135px;">
							<col style="width: *">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">휴(무)일</th>
								<td>근로자의 날, 주휴일, 공휴일 등</em>
							</tr>
							<tr>
								<th scope="row">휴가</th>
								<td>연월차휴가, 하기휴가, 생리휴가, 경조휴가, 공가 등</td>
							</tr>
							<tr>
								<th scope="row">휴직</th>
								<td>산재휴직, 공상휴직, 상병휴직, 육아휴직,  등</td>
							</tr>
							<tr>
								<th scope="row">기타</th>
								<td>지각, 유계결근 등</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<h3 class="section-title">근태 관리 유의사항</h3>
		<div class="note-wrap">
			<ul class="data-list">
				<li class="type01">
					<h4>임직원 개개인</h4>
					<div>
						근태는 임금 및 인력운영 등 인사부문의 기초자료로 활용되는 중요한 정보입니다.<br>
						본인의 근태가 정확하게 입력될 수 있도록 근태 담당자에게 요청하고  시스템에 등록된 근태 내역을 <br>
						매일 꼼꼼하게 확인하셔야 합니다.
						<span>출장 · 교육 · 휴가 · 휴직 등 특이사항이 있을 경우 사전에 팀/부서 근태 담당자에게 근태 입력을 요청하시고, <br>
						(필요 시) 증빙자료*를 기한 내 제출해주시기 바랍니다. <br>
						*근태 유형에 따라 인사팀 혹은 부서 근태 담당자에게 제출 </span>
					</div>
				</li>
				<li class="type02">
					<h4>근태 담당자</h4>
					<div>
						근태 담당자는 담당 팀/부서원들의 근무상태 일체(근무/휴무)를 파악하고,<br>
						매일의 근태 내역을 성실 · 정확 · 공정하게 시스템에 등록한 뒤,<br>
						근태 관리자/책임자에게 결재상신을 올리고, (필요 시) 증빙자료를 받아 별도 보관해야 합니다.
					</div>
				</li>
				<li class="type03">
					<h4>근태 책임자</h4>
					<div>근태 책임자는 팀/부서원 전체의 근태관리를 총괄하며 관리·감독에 대한 책임을 집니다. <br>
					일일근태, 휴일근무, 휴가, 휴직 등 부서원의 유형별 근무 현황을 일단위로 점검하여<br>
					근태 담당자가 상신한 근태문서를 결재해야 합니다.<br>
					개인별 근무 및 휴가 현황을 정기적으로 확인하여 부서 근태에 대한 정합성을 높이고,<br>
					팀/부서원에게 적절한 휴식을 부여하여 업무 몰입도/능률을 향상시킬 수 있도록 지원해주시기 바랍니다.</div>
				</li>
				<li class="type04">
					<h4>인사팀 담당자</h4>
					<div>인사팀 담당자는<br>
					현업의 근태 책임자에 의해 확정된 근태데이터를 기반으로,<br>
					담당 지역별 근태를 관리하고 급여 및 인력운영에 공정하고 정확하게 반영합니다. </div>
				</li>
			</ul>
		</div>
	</div>
</div>
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(1)').addClass('active');
$('.depth02:eq(1)').find('li:eq(1)').addClass('active');
</script>