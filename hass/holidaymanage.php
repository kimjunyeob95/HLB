<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/holiday_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}

$rows = 10;
if(empty($page)){
    $page=1;
}
$where = " 1 = 1 and emc.mc_coseq = {$coseq} and tc.tc_coseq={$coseq} ";
if(!empty($status1)){
    $where .= " and tc.tc_div = {$status1} ";
}
if(!empty($search)){
    if($category=='mm_name'){
        $where .= " and emb.mm_name = '{$enc->encrypt($search)}' ";
    }else if($category=='mc_code'){
        $where .= " and emc.mc_code = '{$search}' ";
    };
}
if(!empty($tc_sdate) && !empty($tc_edate)){
    $where .= " and tc_sdate >= '{$tc_sdate}' and tc_edate < '{$tc_edate}' + INTERVAL 1 DAY ";
}
// if(!empty($tc_edate)){
//     if($category=='mm_name'){
//         $where .= " and emb.mm_name = '{$enc->encrypt($search)}' ";
//     }else if($category=='mc_code'){
//         $where .= " and emc.mc_code = '{$search}' ";
//     };
// }

$query = "select count(*) as cnt from tbl_commute tc
LEFT JOIN ess_member_base emb ON tc.tc_mmseq=emb.mmseq
LEFT JOIN ess_member_code emc ON emc.mc_mmseq = emb.mmseq
where {$where}";

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

$query="SELECT tc.*,emb.mm_name,emb.mm_gender,emc.mc_code,emc.mc_group,emb.mmseq FROM tbl_commute tc 
            LEFT JOIN ess_member_base emb ON tc.tc_mmseq=emb.mmseq
            LEFT JOIN ess_member_code emc ON emc.mc_mmseq = emb.mmseq
        where {$where} order by tc.tc_regdate desc limit {$from} , {$rows} ";
// echo('<pre>');print_r($query);echo('</pre>');
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
// echo('<pre>');print_r($list);echo('</pre>');
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
        <h2 class="content-title">휴가 신청 내역</h2>
        <form method="post" action="<?= $_SERVER['PHP_SELF']?>" >
            <select name="status1" id="status">
                <option value="">전체</option>
                <option value="1" <?if($status1 ==1){?>selected<?}?>>연차</option>
                <option value="2" <?if($status1 ==2){?>selected<?}?> >오전반차</option>
                <option value="3" <?if($status1 ==3){?>selected<?}?>>오후반차</option>
                <option value="4" <?if($status1 ==4){?>selected<?}?>>하계휴가</option>
                <option value="5" <?if($status1 ==5){?>selected<?}?>>경조휴가</option>
                <option value="6" <?if($status1 ==6){?>selected<?}?>>대체휴가</option>
                <option value="7" <?if($status1 ==7){?>selected<?}?>>보건휴가</option>
                <option value="8" <?if($status1 ==8){?>selected<?}?>>포상휴가</option>
                <option value="9" <?if($status1 ==9){?>selected<?}?>>출산휴가</option>
                <option value="10" <?if($status1 ==10){?>selected<?}?>>기타</option>
            </select>
            <select name="category" id="category">
                <option value="mm_name" <?if($category =='mm_name'){?>selected<?}?>>성명</option>
                <option value="mc_code" <?if($category =='mc_code'){?>selected<?}?>>사번</option>
            </select>
            <div class="insert" style="display: inline-block; width: 300px; margin-left:15px;">
                <input type="text" class="input-text input-datepicker" style="max-width: 45%" name="tc_sdate" readonly value=<?=$tc_sdate?>>
                -
                <input type="text" class="input-text input-datepicker" style="max-width: 45%" name="tc_edate" readonly value=<?=$tc_edate?>>
            </div>
            <div class="input-search" id="" style="display: inline-block; width: 300px;">
                <input type="text" type="number" value="<?=$search?>" name="search" style="width: 298px; border:0;"/>
                <button type="submit" class="btn"  style="width: 20px;">
                    <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
                </button>
            </div>
            <button type="submit" class="btn type01 small">조회</button>
        </form>
		<div class="section-wrap" style="margin-top:30px;">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>휴가 신청 내역</caption>
                    <colgroup>
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                        <col style="width: 8%" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">신청일자</th>
                        <th scope="col">소속</th>
                        <th scope="col">성명 / 성별</th>
                        <th scope="col">근태종류</th>
                        <th scope="col">휴가 시작날짜</th>
                        <th scope="col">휴가 종료날짜</th>
                        <th scope="col">사용일수</th>
                        <th scope="col">잔여일수</th>
                        <th scope="col">처리상태</th>
                        <th scope="col">권한처리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?foreach ($list as $val){?>
                        <tr>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=$numbering--?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=substr($val['tc_regdate'],0,10)?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=implode('<br> ',get_group_list_v2($db,$val['mmseq']))?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=$enc->decrypt($val['mm_name'])?> / <?=$gender[$val['mm_gender']]?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=$vacation[$val['tc_div']]?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=substr($val['tc_sdate'],0,10)?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=substr($val['tc_edate'],0,10)?></td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=$val['tc_vacation_count']?>일</td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');"><?=remain_holiday($db,$coseq,$val['tc_num'])?>일</td>
                            <td onclick="detail_page('<?=$val['tc_num']?>','<?=$val['tc_mmseq']?>');">
                                <?
                                if($status2[$val['tc_confirm1_state']]=='처리중'){ echo'<span class="ing">처리중</span>';}
                                else if($status2[$val['tc_confirm1_state']]=='승인'){ echo'<span class="complete">승인</span>';}
                                else if($status2[$val['tc_confirm1_state']]=='반려'){ echo'<span class="companion">반려</span>';}
                                else if($status2[$val['tc_confirm1_state']]=='취소'){ echo'<span class="companion">취소</span>';}
                                ?>
                            </td>
                            <td>
                                <?if($status2[$val['tc_confirm1_state']]=='처리중' || $status2[$val['tc_confirm1_state']]=='승인'){?>
                                <div class="insert">
                                    <button type="button" data-mmseq=<?=$val['mmseq']?> data-holidaydata="<?=substr($val['tc_sdate'],0,10)?> ~ <?=substr($val['tc_edate'],0,10)?>" data-tcnum="<?=$val['tc_num']?>" data-value="N" data-tcmmseq="<?=$val['tc_mmseq']?>"  class="btn type10 small btn_cancel">휴가취소</button>
                                </div>
                                <?}else{?>
                                -
                                <?}?>
                            </td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
                <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
            </div>
            <!-- //공지사항 -->
		</div>
	</div>
    <!-- // 내용 -->
    
    <!-- 반려사유 팝업 -->
    <div class="new-layer-popup group-popup">
        <div class="layer-wrap">
            <div class="popup-wrap">
                <button type="button" class="pop-close">닫기</button>
                <h1>반려사유</h1>
                <div class="section-wrap" style="padding-top: 28px;">
                    <div class="insert">
                        <label class="label">반려사유 :</label>
                        <input type="text" class="input-text" name="tc_return" id="tc_return" title="반려사유" value="" style="width: 80%;">
                    </div>
                </div>
                <div class="insert" style="text-align: center; padding-top: 28px;">
                    <button type="button" id="btn_chk_save" class="btn type01 large">저장<span class="ico check01"></span></button>
                </div>
                
            </div>
        </div>
    </div>
    <!-- // 반려사유 팝업 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    var type = '';
    var tc_num = '';
    $('#aside-menu .tree-wrap>li:eq(3)').addClass('active');
    function detail_page(tc_num,tc_mmseq){
        location.href='./holidaydetail.php?tc_num='+tc_num+'&tc_mmseq='+tc_mmseq+'&page='+<?=$page?>+'<?=$paging_subquery?>';
    }
    $('.input-datepicker').datepicker();

    $('.btn_cancel').click(function () {
        if(confirm('휴가취소 처리를 하시겠습니까?')) {
            $('.group-popup').addClass('active');
            $('body, html').css('height','100%');
            $('body, html').css('overflow','hidden');
            type = $(this).data('value');
            tc_num = $(this).data('tcnum');
            holidaydata = $(this).data('holidaydata');
            user_mmseq = $(this).data('mmseq');
            return false;
        }
    })
    $('#btn_chk_save').click(function(){
        tc_return = $('#tc_return').val();
        data = {'type': type, 'tc_num': tc_num, 'tc_return':tc_return,'holidaydata':holidaydata,'user_mmseq':user_mmseq};
        hlb_fn_ajaxTransmit("/@proc/ess/commute_save.php", data);
    });

    // 팝업 닫기 201209 추가
    $('.new-layer-popup .pop-close').on('click', function() {
        $(this).parents('.new-layer-popup').removeClass('active');
        $('body, html').css('height','auto');
        $('body, html').css('overflow','visible');
    });

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='commute_save'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.reload();
                //location.href="/";
            }
        }
    }

</script>


