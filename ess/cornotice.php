<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$coseq = $_SESSION['mInfo']['mc_coseq'];

$query = "select * from tbl_co_notice where nState = 'T' and nCoseq = {$coseq} and nDel ='FALSE' order by nRegdate desc limit 1";
$ps = pdo_query($db,$query,array());
$main_notice = $ps ->fetch(PDO::FETCH_ASSOC);

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
            <li><a href="/ess/notice.php">공지사항</a></li>
            <li><a href="/ess/cornotice.php" class="active">법인별 주요사항</a></li>
			<li><a href="/ess/retireguide.php">퇴직안내</a></li>
            <?foreach ($list_gnb_menu as $val){?>
                <li><a href="/ess/page_view.php?seq=<?=$val['tn_seq']?>"><?=$val['tn_title']?></a></li>
            <?}?>
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">법인별 주요사항 상세</h2>
		<div class="section-wrap">
            <!-- 공지사항 상세 -->
            <div class="about contest">
                <div class="table-wrap">
                    <table class="data-table left">
                        <caption>법인별 주요사항 상세</caption>
                        <colgroup>
                            <col style="width: 15%" />
                            <col style="width: *" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="row">제목</th>
                                <td><?=$main_notice['nTitle']?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:40px;"><?=$main_notice['nContent']?></td>
                            </tr>
                            <tr>
                                <th scope="row">등록일시</th>
                                <td><?=substr($main_notice['nRegdate'],0,10)?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <!-- // 공지사항 상세 -->
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(3)').addClass('active');
$('.depth02:eq(3)').find('li:eq(2)').addClass('active');
</script>
