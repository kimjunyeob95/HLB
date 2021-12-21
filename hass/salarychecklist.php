<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$page = $_REQUEST['page'];
@$search = $_REQUEST['search'];
@$mmseq = $_SESSION['mmseq'];
@$mm_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mm_code = $_SESSION['mInfo']['mc_code'];

$enc = new encryption();

$rows = 10;
if(empty($page)){
    $page=1;
}
$where = " where hu_hss_seq={$mm_coseq} and hu_hss_code={$mm_code} and hu_del='False'";
if(!empty($search)){
    $where .= " and hue_mm_name like '%{$search}%' or hue_ess_seq like '%{$search}%' ";
}
$query="SELECT count(*) as cnt FROM hass_upload_file ".$where;

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

$query="SELECT * FROM hass_upload_file ".$where." order by hu_salaryDate_year desc, hu_salaryDate_month desc limit ".$from .",".$rows;
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
// echo('<pre>');print_r($list);echo('</pre>');exit;
$subquery="&search=".$search."&page=".$page;
$paging_subquery="&search=".$search;



?>
<style>
    table tbody tr{cursor: pointer;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">급여 내역</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>급여 내역</caption>
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
                        <th scope="col">기준월</th>
                        <th scope="col">급여 전송 일시</th>
                        <th scope="col">급여 등록 일시</th>
                        <th scope="col">작업자</th>
                        <th scope="col">파일</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        <?if(sizeof($list)<1){?>
                            <tr><td colspan ='6' style='text-align:center'>내역이 없습니다.</td></tr>
                        <?}else{?>
                            <?for($i=0;$i<sizeof($list);$i++){?>
                                <tr>
                                    <td onclick="detail_page(<?=$list[$i]['hu_salaryDate_year']?>,<?=$list[$i]['hu_salaryDate_month']?>)"><?=$numbering?></td>
                                    <td onclick="detail_page(<?=$list[$i]['hu_salaryDate_year']?>,<?=$list[$i]['hu_salaryDate_month']?>)">
                                        <?=$list[$i]['hu_salaryDate_year']?>-<?if($list[$i]['hu_salaryDate_month']<10){echo '0'.$list[$i]['hu_salaryDate_month'];}else{ echo ''.$list[$i]['hu_salaryDate_month'];}?>
                                    </td>
                                    <td onclick="detail_page(<?=$list[$i]['hu_salaryDate_year']?>,<?=$list[$i]['hu_salaryDate_month']?>)" class="center"><?=$list[$i]['hu_regDate']?></td>
                                    <td onclick="detail_page(<?=$list[$i]['hu_salaryDate_year']?>,<?=$list[$i]['hu_salaryDate_month']?>)" class="center"><?=$list[$i]['hu_showDate'].' '.$list[$i]['hu_showDate_hour'].'시'?></td>
                                    <td onclick="detail_page(<?=$list[$i]['hu_salaryDate_year']?>,<?=$list[$i]['hu_salaryDate_month']?>)" class="center">
                                    <?
                                        $data=get_member_info($db,$list[$i]['hu_mm_seq']);
                                        echo ''.$enc->decrypt($data['mm_name']);
                                    ?>
                                    </td>
                                    <td class="center"><a href=<?=$list[$i]['hu_fileName']?> target="_blank"><button type="button" data-type="다운로드" data-seq=<?=$mmseq?> data-code=<?=$list[$i]['hu_hss_code']?> class="btn type10 medium">다운로드</button></a></td>
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
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(2)').addClass('active');

    function detail_page(year,month){
        location.href='salarycheck?y='+year+'&m='+month;
    }

    $('.btn-proc').click(function(e){
        e.preventDefault();
        var $this_type = $(this).data('type');
        var $this_seq = $(this).data('seq');
        var $this_code = $(this).data('code');
        var $this_year = $(this).data('year');
        var $this_month = $(this).data('month');
        if($this_type=='삭제'){
            if(confirm('정말 삭제하시겠습니까?')){
                location.href='../@proc/hass/salary_delete_proc.php?seq='+$this_seq+'&code='+$this_code+'&y='+$this_year+'&m='+$this_month;
            }else return;
        }
    })
</script>

