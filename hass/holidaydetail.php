<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

@$tc_num = $_REQUEST['tc_num'];
@$tc_mmseq = $_REQUEST['tc_mmseq'];
@$page = $_REQUEST['page'];
if(empty($page)){
    $page=1;
}
$where = " 1 = 1 and emc.mc_coseq = {$coseq} and tc.tc_num={$tc_num} ";
$query="SELECT tc.*,emb.mm_name,emb.mm_email,emb.mm_cell_phone,emb.mm_gender,emc.mc_code,emc.mc_commute_remain FROM tbl_commute tc 
            LEFT JOIN ess_member_base emb ON tc.tc_mmseq=emb.mmseq
            LEFT JOIN ess_member_code emc ON emc.mc_mmseq = emb.mmseq
        where {$where}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($data);echo('</pre>');
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">휴가 신청 상세내역</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table left">
                    <caption>신입 사원 승인 내역</caption>
                    <colgroup>
                        <col style="width: 20%" />
                        <col style="width: *" />
                        <col style="width: 20%" />
                        <col style="width: *" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="col">사번</th>
                            <td><?=$data['mc_code']?></td>
                            <th scope="col">성명 / 성별</th>
                            <td><?=$enc->decrypt($data['mm_name'])?> / <?=$gender[$data['mm_gender']]?></td>
                        </tr>
                        <tr>
                            <th scope="col">연락처</th>
                            <td><?=$enc->decrypt($data['mm_cell_phone'])?></td>
                            <th scope="col">E-Mail</th>
                            <td><?=$enc->decrypt($data['mm_email'])?></td>
                        </tr>
                        <tr>
                            <th scope="col">휴가 종류</th>
                            <td><?=$vacation[$data['tc_div']]?></td>
                            <th scope="col">휴가 날짜</th>
                            <td><?=substr($data['tc_sdate'],0,10)?> ~ <?=substr($data['tc_edate'],0,10)?> (<?=$data['tc_vacation_count']?>일)</td>
                        </tr>
                        <tr>
                            <th scope="col">사유</th>
                            <td><?=$data['tc_content']?></td>
                            <th scope="col">남은휴가</th>
                            <td><?=$data['mc_commute_remain']?>일</td>
                        </tr>
                        <tr>
                            <th scope="col">신청일시</th>
                            <td><?=substr($data['tc_regdate'],0,10)?></td>
                            <th scope="col">상태</th>
                            <td>
                                <?
                                    if($status2[$data['tc_confirm1_state']]=='처리중'){ echo'<span class="ing">처리중</span>';}
                                    else if($status2[$data['tc_confirm1_state']]=='승인'){ echo'<span class="complete">승인</span>';}
                                    else if($status2[$data['tc_confirm1_state']]=='반려'){ echo'<span class="companion">반려</span>';}
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">첨부 파일</th>
                            <td colspan=3>
                                <a href="<?=$data['tc_file']?>" target="_blank" download><?=$data['tc_file']?>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></a>
                            </td>
                        </tr>
                    </tbody> 
                </table>
                <div class="button-area large">
                    <button data-btn="목록" class="btn type01 large data-btn">목록<span class="ico apply"></span></button>
                </div>
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
    $('#aside-menu .tree-wrap>li:eq(3)').addClass('active');

    $('.data-btn').click(function(){
        var $this_btn = $(this).data('btn');
        if($this_btn=='목록'){
            location.href='./holidaymanage.php?page=<?=$page?>';
        }
    });
</script>

