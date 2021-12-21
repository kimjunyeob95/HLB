<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
$query = "select * from tbl_noti_page where tn_seq = {$_REQUEST['seq']}";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

?>

<div id="wrap" class="depth03">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->

<!-- CONTENT -->
<div id="container" class="retire-process">
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>HR 안내</h2>
		<ul class="lnb">
            <li><a href="#">HR안내</a></li>
            <li><a href="/ess/notice">공지사항</a></li>
			<li><a href="/ess/retireguide">퇴직안내</a></li>
            <?
                for($i=0;$i<sizeof($list_gnb_menu);$i++){
                    if($i==$_REQUEST['pageNum']){
            ?>
                <li><a href="/ess/page_view.php?pageNum=<?=$i?>&seq=<?=$list_gnb_menu[$i]['tn_seq']?>" class="active"><?=$list_gnb_menu[$i]['tn_title']?></a></li>
            <?}else{?>
                <li><a href="/ess/page_view.php?pageNum=<?=$i?>&seq=<?=$list_gnb_menu[$i]['tn_seq']?>"><?=$list_gnb_menu[$i]['tn_title']?></a></li>
            <?}}?>
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title"><?=$data['tn_title']?></h2>
            <div class="fr-view data-table">
		        <?=str_replace('Froala Editor','',str_replace('Powered by','',$data['tn_content']))?>
            </div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
var active_index = <?=$_REQUEST['pageNum']?>+3;
$('.header-wrap ').addClass('active');
$('.depth01:eq(3)').addClass('active');
$('.depth02:eq(3)').find('li:eq('+active_index+')').addClass('active');
</script>
