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
				FROM tbl_co_notice as A 
				WHERE nseq = '{$seq}'";
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
            <li><a href="#">HR안내</a></li>
            <li><a href="/ess/notice.php" class="active">공지사항</a></li>
			<li><a href="/ess/retireguide.php">퇴직안내</a></li>
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">공지사항 상세</h2>
		<div class="section-wrap">
            <!-- 공지사항 상세 -->
            <div class="about contest">
                <div class="table-wrap">
                    <table class="data-table left">
                        <caption>공지사항 상세</caption>
                        <colgroup>
                            <col style="width: 15%" />
                            <col style="width: *" />
                        </colgroup>
                        <tbody>
                      
                            <tr>
                                <th scope="row">제목</th>
                                <td><?=$data['nTitle']?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:40px;"><?=$data['nContent']?></td>
                            </tr>
                            <tr>
                                <th scope="row">등록일시</th>
                                <td><?=substr($data['nRegdate'],0,10)?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a href ="/ess/notice.php<?=$subquery?>" class="btn type01 medium" data-btn="family" style="float: right; margin-top: 10px;">목록으로<span class="ico browser02"></span></a>
            </div>
            <!-- // 공지사항 상세 -->
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(4)').addClass('active');
$('.depth02:eq(3)').find('li:eq(1)').addClass('active');
</script>
