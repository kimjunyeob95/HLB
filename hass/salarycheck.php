<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$page = $_REQUEST['page'];
@$search = $_REQUEST['search'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$mm_code = $_SESSION['mInfo']['mc_code'];
@$hue_salaryDate_year = $_REQUEST['y']; //급여일 년
@$hue_salaryDate_month = $_REQUEST['m']; //급여일 월

$rows = 10;
if(empty($page)){
    $page=1;
}
$where = " where uf.hu_hss_seq={$mc_coseq} and uf.hu_hss_code={$mm_code} 
and uf.hu_del='False' and uf.hu_hss_seq=ed.hue_hss_seq and uf.hu_hss_code=ed.hue_hss_code
and uf.hu_salaryDate_year={$hue_salaryDate_year} and uf.hu_salaryDate_month={$hue_salaryDate_month}
and ed.hue_salaryDate_year={$hue_salaryDate_year} and ed.hue_salaryDate_month={$hue_salaryDate_month}";

if(!empty($search)){
    $where .= " and hue_mm_name like '%{$search}%' or hue_ess_seq like '%{$search}%' ";
}

$query="SELECT count(*) as cnt FROM hass_upload_excel_data AS ed JOIN hass_upload_file AS uf".$where;
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

$query="SELECT * FROM hass_upload_excel_data AS ed JOIN hass_upload_file AS uf ".$where." order by ed.hue_regDate desc,ed.hue_mm_name asc limit ".$from .",".$rows;
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
//총 계산
$query="SELECT * FROM hass_upload_excel_data AS ed JOIN hass_upload_file AS uf ".$where." order by ed.hue_regDate desc,ed.hue_mm_name";
$ps = pdo_query($db,$query,array());
$list_total = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list_total, $data);
}

// echo('<pre>');print_r($list_total);echo('</pre>');exit;
$total_pay_all;
$total_pay;
$total_deduct;
foreach($list_total as $index => $val){
    $total_pay_all += $val['total_pay'];
    $total_pay += $val['pay12'];
    $total_deduct += $val['deduct12'];
}
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
        <h2 class="content-title"><?=$hue_salaryDate_year?>년 <?=$hue_salaryDate_month?>월 급여 내역</h2>
        <h2 class="section-title">지급대상: <?=$total_rows?>명 | 총 합계: <?=number_format($total_pay_all)?>원 | 수당 합계: <?=number_format($total_pay)?>원 | 공제 합계: <?=number_format($total_deduct)?>원</h2>
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
                        <col style="width: 80px" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">사번</th>
                        <th scope="col">성명</th>
                        <th scope="col">부서</th>
                        <th scope="col">직급</th>
                        <th scope="col">총 액</th>
                        <th scope="col">등록일시</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?if(sizeof($list)<1){?>
                            <tr><td colspan ='7' style='text-align:center'>내역이 없습니다.</td></tr>
                        <?}else{?>
                            <?for($i=0;$i<sizeof($list);$i++){?>
                                <tr onclick="detail_page(<?=$list[$i]['hue_ess_code']?>,<?=$hue_salaryDate_year?>,<?=$hue_salaryDate_month?>)">
                                    <td><?=$numbering?></td>
                                    <td class="center"><?=$list[$i]['hue_ess_code']?></td>
                                    <td class="center"><?=$list[$i]['hue_mm_name']?></td>
                                    <td class="center"><?=$list[$i]['hue_mm_buseo']?></td>
                                    <td class="center"><?=$list[$i]['hue_mm_level']?></td>
                                    <td class="center"><?=number_format($list[$i]['total_pay'])?>원</td>
                                    <td><?=substr($list[$i]['hue_regDate'],0,10)?></td>
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

    function detail_page(seq,year,month){
        location.href='salaryDetail.php?seq='+seq+'&y='+year+'&m='+month;
    }
</script>

