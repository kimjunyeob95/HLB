<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$career_list = get_career_list($db,$mmseq); // 사외경력
$career_list2 = get_career_log_list($db,$mmseq,$division); // 사외경력
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'another');
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->

<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <?if($career_list2[0]['crl_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(사외경력) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-another"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <?foreach ($career_list as $key => $val){?>
                    <table class="data-table table-form-another left">
                        <caption>정보 변경 상세(사외경력)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">시작일</th>
                                <td class="<?=comparison($val['mc_sdate'],$career_list2[$key]['crl_sdate'])?>"><?=substr($val['mc_sdate'],0,10)?></td>
                                <th scope="col">종료일</th>
                                <td class="<?=comparison($val['mc_edate'],$career_list2[$key]['crl_edate'])?>"><?=substr($val['mc_edate'],0,10)?></td>
                                <th scope="col">회사명</th>
                                <td class="<?=comparison($val['mc_company'],$career_list2[$key]['crl_company'])?>"><?=$enc->decrypt($val['mc_company'])?></td>
                                <th scope="col">근무부서</th>
                                <td class="<?=comparison($val['mc_group'],$career_list2[$key]['crl_group'])?>"><?=$val['mc_group']?></td>
                                <th scope="col">최종직위</th>
                                <td class="<?=comparison($val['mc_position'],$career_list2[$key]['crl_position'])?>"><?=$enc->decrypt($val['mc_position'])?></td>
                                <th scope="col">담당업무</th>
                                <td class="<?=comparison($val['mc_duties'],$career_list2[$key]['crl_duties'])?>"><?=$enc->decrypt($val['mc_duties'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">경력기술</th>
                                <td colspan=11 class="<?=comparison($val['mc_career'],$career_list2[$key]['crl_career'])?>"><?=$val['mc_career']?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?}?>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(사외경력) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(사외경력)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-another2" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <?foreach ($career_list2 as $key =>  $val){?>
                    <table class="data-table table-form-another left">
                        <caption>정보 변경 상세(사외경력)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">시작일</th>
                                <td class="<?=comparison($val['crl_sdate'],$career_list[$key]['mc_sdate'])?>"><?=substr($val['crl_sdate'],0,10)?></td>
                                <th scope="col">종료일</th>
                                <td class="<?=comparison($val['crl_edate'],$career_list[$key]['mc_edate'])?>"><?=substr($val['crl_edate'],0,10)?></td>
                                <th scope="col">회사명</th>
                                <td class="<?=comparison($val['crl_company'],$career_list[$key]['mc_company'])?>"><?=$enc->decrypt($val['crl_company'])?></td>
                                <th scope="col">근무부서</th>
                                <td class="<?=comparison($val['mc_group'],$career_list2[$key]['crl_group'])?>"><?=$val['crl_group']?></td>
                                <th scope="col">최종직위</th>
                                <td class="<?=comparison($val['crl_position'],$career_list[$key]['mc_position'])?>"><?=$enc->decrypt($val['crl_position'])?></td>
                                <th scope="col">담당업무</th>
                                <td class="<?=comparison($val['crl_duties'],$career_list[$key]['mc_duties'])?>"><?=$enc->decrypt($val['crl_duties'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">경력기술</th>
                                <td colspan=11 class="<?=comparison($val['crl_career'],$career_list[$key]['mc_career'])?>"><?=$val['crl_career']?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?}?>
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
                        <?if($career_list2[0]['crl_state']=='A'){?>
                        <button type="button" data-btn="승인" name='bnt_update' data-type="Y"  class="btn type01 large btn-footer">승인<span class="ico check01"></span></button>
                        <button type="button" data-btn="반려" name='bnt_update' data-type="N"  class="btn type01 large btn-footer">반려<span class="ico people"></span></button>
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
                hlb_fn_ajaxTransmit("/@proc/hass/member_career_update_proc.php", data);
            }
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='member_career_update_proc'){
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

