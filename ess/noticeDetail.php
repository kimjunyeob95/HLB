<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
$seq = $_REQUEST['seq'];
@$search = $_REQUEST['search'];
@$page = $_REQUEST['page'];
if(empty($page)){
    $page = 1;
}
if(empty($seq)){
    echo '<script>alert("잘못된 접근입니다.")</script>';
    echo '<script>location.href="/ess/notice.php"</script>';
}else{
    $query="SELECT *
				FROM tbl_noti_page as A 
				WHERE tn_seq = '{$seq}'";
    $ps = pdo_query($db,$query,array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}
$subquery="?search=".urlencode($search)."&page=".$page;
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
            <li><a href="/ess/notice" class="active">공지사항</a></li>
            <li><a href="/ess/hrnotice">HR 안내</a></li>
            
            <!-- <li><a href="/ess/cornotice.php">법인별 주요사항</a></li> -->
			<!-- <li><a href="/ess/retireguide.php">퇴직안내</a></li>
            <?foreach ($list_gnb_menu as $val){?>
                <li><a href="/ess/page_view.php?seq=<?=$val['tn_seq']?>"><?=$val['tn_title']?></a></li>
            <?}?> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">공지사항 상세</h2>
        <div class="notice-wrap">
            <div class="title-wrap">
                <label class="label-title">제목</label> <span class="span-title"><?=$data['tn_title']?></span>
            </div>
            <div class="title-wrap viewNum">
                <label class="label-title">조회수</label> <span class="span-title"><?=$data['tn_views']?></span>
            </div>
            <div class="footer-wrap">
                <label class="label-title">등록일시</label> <span class="span-title"><?=substr($data['tn_regdate'],0,10)?></span>
            </div>
            <div class="contents-wrap">
                <?=$data['tn_content']?>
            </div>
            <div class="btn-wrap">
                <a href ="/ess/notice<?=$subquery?>" class="btn type01 medium" data-btn="family">목록으로<span class="ico browser02"></span></a>
            </div>
        </div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(3)').addClass('active');
$('.depth02:eq(3)').find('li:eq(0)').addClass('active');
</script>
