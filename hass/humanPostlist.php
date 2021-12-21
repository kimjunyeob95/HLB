<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/info_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$page = $_REQUEST['page'];
$rows = 10;
if(empty($page)){
    $page=1;
}

$query = "select count(*) as cnt from tbl_appointment ta join ess_member_base emb on ta.ta_mmseq = emb.mmseq where 
            ta_prev_coseq = {$mc_coseq}";
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

$query = "select * from tbl_appointment ta join ess_member_base emb on ta.ta_mmseq = emb.mmseq where 
            ta_prev_coseq = {$mc_coseq} order by ta_status ='A' desc, ta_apply_date desc limit {$from} , {$rows} ";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
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

		<h2 class="content-title">인사 발령 보낸 내역</h2>
        <div class="tab-wrap">
            <ul class="tab">
                <li><a href="./humanlist">받은 내역</a></li>
                <li class="active"><a href="#">보낸 내역</a></li>
            </ul>
        </div>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>인사 발령 보낸 내역</caption>
                    <colgroup>
                        <col style="width: 20px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 200px" />
                        <col style="width: 120px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <!--                        <col style="width: 80px" />-->
                        <!--                        <col style="width: 80px" />-->
                        <!--                        <col style="width: 80px" />-->
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">분류</th>
                        <th scope="col">발령 법인</th>
                        <th scope="col">제목</th>
                        <th scope="col">성명/성별</th>
<!--                        <th scope="col">국적</th>-->
<!--                        <th scope="col">생년월일</th>-->
<!--                        <th scope="col">연락처</th>-->
                        <th scope="col">접수일시</th>
                        <th scope="col">처리일시</th>
                        <th scope="col">처리자</th>
                        <th scope="col">상태</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?if(empty($list)){?>
                        <tr><td colspan="10">내역이 없습니다.</td></tr>
                    <?}else{?>
                        <?foreach ($list as $val){?>
                        <tr onclick="detail_page(<?=$val['ta_mmseq']?>,<?=$val['ta_type']?>);">
                            <td><?=$numbering--?></td>
                            <td class="center"><?=$appointment[$val['ta_type']]?></td>
                            <td class="center"><?=title_coperation($db,$val['ta_next_coseq'])?></td>
                            <td><?=$val['ta_title']?></td>
                            <td><?=$enc->decrypt($val['mm_name'])?> / <?=$gender[$val['mm_gender']]?></td>
<!--                            <td>--><?//=text_country($db,$val['mm_country'])?><!--</td>-->
<!--                            <td>--><?//=substr($val['mm_birth'],0,10)?><!--</td>-->
<!--                            <td>--><?//=$enc->decrypt($val['mm_phone'])?><!--</td>-->
                            <td><?=substr($val['ta_apply_date'],0,10)?></td>
                            <td><?=substr($val['ta_confirm_date'],0,10) != ''?  substr($val['ta_confirm_date'],0,10) : '-'?></td>
                            <td><?=$enc->decrypt(get_member_info($db,$val['ta_confirm_seq'])['mm_name']) != ''?  $enc->decrypt(get_member_info($db,$val['ta_confirm_seq'])['mm_name']) : '-'?></td>
                            <?
                            if($status3[$val['ta_status']]=='처리미완료'){ echo'<td class="ing">처리미완료</td>';}
                            else if($status3[$val['ta_status']]=='처리완료'){ echo'<td class="complete">처리완료</td>';}
                            else if($status3[$val['ta_status']]=='반려'){ echo'<td class="companion">반려</td>';}
                            ?>
                        </tr>
                        <?}?>
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
    $('#aside-menu .tree-wrap>li:eq(0)').addClass('active');

    function detail_page(seq,type){
        location.href='./humanPostdetail.php?seq='+seq+'&type='+type+'&page=<?=$page?>';
    }

</script>

