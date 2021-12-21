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
	<?=$data_html['th_html']?>
</div>
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(1)').addClass('active');
$('.depth02:eq(1)').find('li:eq(1)').addClass('active');
</script>