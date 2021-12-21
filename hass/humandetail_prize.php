<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$punishment_list = get_punishment_list($db,$mmseq); // 상벌
$punishment_list2 = get_punishment_log_list($db,$mmseq,$division); // 상벌
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'prize');
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->

<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 사유 및 내용 -->
	<div id="content" class="content-primary">
        <?if($punishment_list2[0]['ep_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(상벌) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-prize"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>상벌</caption>
                        <colgroup>
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">구분</th>
                            <th scope="col">일자</th>
                            <th scope="col">상벌명</th>
                            <th scope="col">사유 및 내용</th>
                            <th scope="col">비고</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($punishment_list as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['mp_type'],$punishment_list2[$key]['ep_type'])?>"><?=$punishment[$val['mp_type']]?></td>
                                <td class="insert <?=comparison($val['mp_date'],$punishment_list2[$key]['ep_date'])?>"><?=substr($val['mp_date'],0,10)?></td>
                                <td class="insert <?=comparison($val['mp_title'],$punishment_list2[$key]['ep_title'])?>"><?=$val['mp_title']?></td>
                                <td class="insert <?=comparison($val['mp_content'],$punishment_list2[$key]['ep_content'])?>"><?=$val['mp_content']?></td>
                                <td class="insert <?=comparison($val['mp_etc'],$punishment_list2[$key]['ep_etc'])?>"><?=$val['mp_etc']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(상벌) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(상벌)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-prize2" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>상벌</caption>
                        <colgroup>
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">구분</th>
                            <th scope="col">일자</th>
                            <th scope="col">상벌명</th>
                            <th scope="col">사유 및 내용</th>
                            <th scope="col">비고</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($punishment_list2 as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['ep_type'],$punishment_list[$key]['mp_type'])?>"><?=$punishment[$val['ep_type']]?></td>
                                <td class="insert <?=comparison($val['ep_date'],$punishment_list[$key]['mp_date'])?>"><?=substr($val['ep_date'],0,10)?></td>
                                <td class="insert <?=comparison($val['ep_title'],$punishment_list[$key]['mp_title'])?>"><?=$val['ep_title']?></td>
                                <td class="insert <?=comparison($val['ep_content'],$punishment_list[$key]['mp_content'])?>"><?=$val['ep_content']?></td>
                                <td class="insert <?=comparison($val['ep_etc'],$punishment_list[$key]['mp_etc'])?>"><?=$val['ep_etc']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
                <h2 class="content-title" style="margin-top:50px;">반려 사유</h2>
                <div class="table-wrap" style="border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>반려 사유</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 20%" />
                        </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">반려 사유</th>
                                    <td><input class="input-text" type="text" name="cause_return" value="<?=$cause_of_return?>"></td>
                                </tr>
                            </tbody>
                    </table>
                </div>
                <div style="text-align:center;margin-top:50px;">
                    <div class="button-area large">
                        <button type="button" data-btn="목록" onclick="location.href='/hass/humanmodify?1=1<?=$paging_subquery?>'" class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
                        <?if($punishment_list2[0]['ep_state']=='A'){?>
                        <button type="button" data-btn="승인" name='bnt_update' data-type="Y" class="btn type01 large btn-footer">승인<span class="ico check01"></span></button>
                        <button type="button" data-btn="반려" name='bnt_update' data-type="N" class="btn type01 large btn-footer">반려<span class="ico people"></span></button>
                        <?}?>
                    </div>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
	</div>
	<!-- // 사유 및 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(0)').addClass('active');

    $('[name="bnt_update"]').click(function(e){
        type = $(this).data('type');
        title = $(this).data('btn');
        cause_return = $('[name="cause_return"]').val();
        e.preventDefault();
        var data = {'type':type,'mmseq':<?=$mmseq?>,'division':<?=$division?>,'cause_return':cause_return};
        if(confirm("정보 변경을 "+title+" 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/hass/member_prize_update_proc.php", data);
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='member_prize_update_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.reload();
            }
        }
    }

</script>

