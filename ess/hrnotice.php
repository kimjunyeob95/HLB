<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$page = $_REQUEST['page'];
@$search = $_REQUEST['search'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

$rows = 10;

if(empty($page)){
    $page=1;
}
$addquery='';
if(!empty($search)){
    $addquery .= " and (hrnTitle like '%{$search}%' or hrnContent like '%{$search}%') ";
}
$query = "select count(*) as cnt from tbl_hr_notice where hrnDel = 'F' and hrnStatus = 'T' {$addquery} and hrnCoseq = {$coseq} order by hrnRegdate desc";

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

$query = "select * from tbl_hr_notice where hrnDel = 'F' and hrnStatus = 'T' {$addquery} and hrnCoseq = {$coseq} order by hrnRegdate desc limit {$from} , {$rows} ";
// echo('<pre>');print_r($query);echo('</pre>');
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
// echo('<pre>');print_r($list);echo('</pre>');exit;
$subquery="&search=".$search."&page=".$page;
$paging_subquery="&search=".$search;

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
            <li><a href="/ess/notice">공지사항</a></li>
            <li><a href="/ess/hrnotice" class="active">HR 안내</a></li>
            <!-- <li><a href="/ess/cornotice.php">법인별 주요사항</a></li> -->
			<!-- <li><a href="/ess/retireguide.php">퇴직안내</a></li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">HR 안내</h2>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="input-search" id="" style="display: inline-block; width: 300px;">
            <input type="text" type="number" value="<?=$search?>" id="" name="search" style="width: 298px; border:0;"/>
            <button type="submit" class="btn"  style="width: 20px;">
                <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
            </button>
        </div>
            <button type="submit" id="" class="btn type01 small">조회</button>
        </form>
		<div class="section-wrap">
            <!-- HR 안내 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>HR 안내</caption>
                    <colgroup>
                        <col style="width: 1%" />
                        <col style="width: 20%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 1%" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">제목</th>
                        <th scope="col">작성자</th>
                        <th scope="col">등록일시</th>
                        <th scope="col">조회수</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?if(sizeof($list)<1){?>
                        <tr><td colspan ='5' style='text-align:center'>내역이 없습니다.</td></tr>
                    <?}else{?>
                        <?for($i=0;$i<sizeof($list);$i++){?>
                            <tr onclick="move_detail_ajax(<?=$list[$i]['hrnseq']?>,'<?=$subquery?>')" style="cursor: pointer">
                                <td><?=$numbering?></td>
                                <td class="center"><?=$list[$i]['hrnTitle']?></td>
                                <td class="center"><?=$enc->decrypt(get_member_info($db,$list[$i]['hrnLastAdmin'])['mm_name'])?></td>
                                <td><?=substr($list[$i]['hrnRegdate'],0,10)?></td>
                                <td class="center"><?=$list[$i]['hrnViews']?></td>
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
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(3)').addClass('active');
$('.depth02:eq(3)').find('li:eq(1)').addClass('active');

function move_detail_ajax(seq,subquery){
    var data = { 'hrnseq' : seq ,'subquery' : subquery, 'type': 'hrnotice'};
    hlb_fn_ajaxTransmit("/@proc/ess/noticeViewProc.php", data);
}
function fn_callBack(calback_id, result, textStatus){
    if(calback_id=='noticeViewProc'){
        if(result.code=='FALSE'){
            alert(result.msg);
            return;
        }else{
            location.href="/ess/hrnoticeDetail?seq="+result.seq+result.subquery;
        }
    }
}
</script>
