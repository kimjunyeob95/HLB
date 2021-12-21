<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$family_list = get_family_list($db,$mmseq); // 사외경력
$family_list2 = get_family_log_list($db,$mmseq,$division); // 사외경력
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'family');
?>
<style>
    .data-table:nth-child(1){border-top:none;}
    .data-table:last-child{margin-bottom: 0px;}
    .data-table{margin-bottom:30px; border-top:1px solid #d1d1d1;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->

<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <?if($family_list2[0]['ml_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(가족) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-family"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-family left">
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">성명</th>
                                <th scope="col">관계</th>
                                <th scope="col">생년월일</th>
                                <th scope="col">인적공제 여부</th>
                                <th scope="col">동거 여부</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($family_list as $key => $val){?>
                            <tr>
                                <td class="<?=comparison($val['mf_name'],$family_list2[$key]['ml_name'])?>"><?=$enc->decrypt($val['mf_name'])?></td>
                                <td class="<?=comparison($val['mf_relationship'],$family_list2[$key]['ml_relationship'])?>"><?=$family_type_array[$val['mf_relationship']]?></td>
                                <td class="<?=comparison($val['mf_birth'],$family_list2[$key]['ml_birth'])?>"><?=$enc->decrypt($val['mf_birth'])?></td>
                                <td class="<?=comparison($val['mf_allowance'],$family_list2[$key]['ml_allowance'])?>">인적공제<?=$allowance[$val['mf_allowance']]?></td>
                                <td class="<?=comparison($val['mf_together'],$family_list2[$key]['ml_together'])?>"><?=$together[$val['mf_together']]?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(가족) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(가족)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-family2"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-family left">
                        <caption>정보 변경 상세(가족사항)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">성명</th>
                                <th scope="col">관계</th>
                                <th scope="col">생년월일</th>
                                <th scope="col">인적공제 여부</th>
                                <th scope="col">동거 여부</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($family_list2 as $key => $val){?>
                            <tr>
                                <td class="<?=comparison($val['ml_name'],$family_list[$key]['mf_name'])?>"><?=$enc->decrypt($val['ml_name'])?></td>
                                <td class="<?=comparison($val['ml_relationship'],$family_list[$key]['mf_relationship'])?>"><?=$family_type_array[$val['ml_relationship']]?></td>
                                <td class="<?=comparison($val['ml_birth'],$family_list[$key]['mf_birth'])?>"><?=$enc->decrypt($val['ml_birth'])?></td>
                                <td class="<?=comparison($val['ml_allowance'],$family_list[$key]['mf_allowance'])?>">인적공제<?=$allowance[$val['ml_allowance']]?></td>
                                <td class="<?=comparison($val['ml_together'],$family_list[$key]['mf_together'])?>"><?=$together[$val['ml_together']]?></td>
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
                        <?if($family_list2[0]['ml_state']=='A'){?>
                        <button type="button" data-btn="승인" name='bnt_update' data-type="Y" class="btn type01 large btn-footer">승인<span class="ico check01"></span></button>
                        <button type="button" data-btn="반려" name='bnt_update' data-type="N" class="btn type01 large btn-footer">반려<span class="ico people"></span></button>
                        <?}?>
                    </div>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
        
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(0)').addClass('active');

    $('[name="bnt_update"]').click(function(e){
        let validation = true;
        type = $(this).data('type');
        title = $(this).data('btn');
        cause_return = $('[name="cause_return"]').val();
        if(title == '반려'){
            if(cause_return == ''){
                alert('반려시 반려 사유를 작성해주세요.');
                validation = false;
                return false; 
            }
        }
        e.preventDefault();
        if(validation){
            var data = {'type':type,'mmseq':<?=$mmseq?>,'division':<?=$division?>,'cause_return':cause_return};
            if(confirm("정보 변경을 "+title+" 하시겠습니까?")){
                hlb_fn_ajaxTransmit("/@proc/hass/member_family_update_proc.php", data);
            }
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='member_family_update_proc'){
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

