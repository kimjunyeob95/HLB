<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$certificate_list = get_certificate_list($db,$mmseq); // 교육 / 활동
$certificate_list2 = get_certificate_log_list($db,$mmseq,$division); // 사외경력
$premier_list = get_premier_list($db,$mmseq); // 수상
$premier_list2 = get_premier_log_list($db,$mmseq,$division); // 수상
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'cert');
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");

?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->

<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <?if($certificate_list2[0]['cl_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(어학 / 자격증 / 수상) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-cert" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-cert left">
                        <caption>정보 변경 상세(어학 / 자격증 / 수상)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">자격증 명</th>
                                <th scope="col">취득일</th>
                                <th scope="col">등급/점수</th>
                                <th scope="col">취득기관</th>
                                <th scope="col">자격번호</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($certificate_list as $key =>$val){?>
                            <tr>
                                <td class="<?=comparison($val['mct_cert_name'],$certificate_list2[$key]['cl_cert_name'])?>"><?=$val['mct_cert_name']?></td>
                                <td class="<?=comparison($val['mct_date'],$certificate_list2[$key]['cl_date'])?>"><?=substr($val['mct_date'],0,10)?></td>
                                <td class="<?=comparison($val['mct_class'],$certificate_list2[$key]['cl_class'])?>"><?=$enc->decrypt($val['mct_class'])?></td>
                                <td class="<?=comparison($val['mct_institution'],$certificate_list2[$key]['cl_institution'])?>"><?=$enc->decrypt($val['mct_institution'])?></td>
                                <td class="<?=comparison($val['mct_num'],$certificate_list2[$key]['cl_num'])?>"><?=$val['mct_num']?></td>
                            </tr>
                            <?}?>
                        </tbody>
                    </table>

                    <h3 class="section-title">수상경력</h3>
                    <table class="data-table table-form-prize2 left" style="border-top:1px solid #d1d1d1;">
                        <caption>수상경력</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">일자</th>
                                <th scope="col">수상내용</th>
                                <th scope="col">수상기관</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($premier_list as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['mpd_date'],$premier_list2[$key]['epl_date'])?>"><?=substr($val['mpd_date'],0,10)?></td>
                                <td class="insert <?=comparison($val['mpd_content'],$premier_list2[$key]['epl_content'])?>"><?=$val['mpd_content']?></td>
                                <td class="insert <?=comparison($val['mpd_institution'],$premier_list2[$key]['epl_institution'])?>"><?=$val['mpd_institution']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(어학 / 자격증 / 수상) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(어학 / 자격증 / 수상)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-cert2" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-cert left">
                        <caption>정보 변경 상세(어학 / 자격증 / 수상)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">자격증 명</th>
                                <th scope="col">취득일</th>
                                <th scope="col">등급/점수</th>
                                <th scope="col">취득기관</th>
                                <th scope="col">자격번호</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($certificate_list2 as $key =>$val){?>
                            <tr>
                                <td class="<?=comparison($val['cl_cert_name'],$certificate_list[$key]['mct_cert_name'])?>"><?=$val['cl_cert_name']?></td>
                                <td class="<?=comparison($val['cl_date'],$certificate_list[$key]['mct_date'])?>"><?=substr($val['cl_date'],0,10)?></td>
                                <td class="<?=comparison($val['cl_class'],$certificate_list[$key]['mct_class'])?>"><?=$enc->decrypt($val['cl_class'])?></td>
                                <td class="<?=comparison($val['cl_institution'],$certificate_list[$key]['mct_institution'])?>"><?=$enc->decrypt($val['cl_institution'])?></td>
                                <td class="<?=comparison($val['cl_num'],$certificate_list[$key]['mct_num'])?>"><?=$val['cl_num']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>

                    <h3 class="section-title">수상경력</h3>
                    <table class="data-table table-form-prize2 left" style="border-top:1px solid #d1d1d1;">
                        <caption>수상경력</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">일자</th>
                                <th scope="col">수상내용</th>
                                <th scope="col">수상기관</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($premier_list2 as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['epl_date'],$premier_list[$key]['mpd_date'])?>"><?=substr($val['epl_date'],0,10)?></td>
                                <td class="insert <?=comparison($val['epl_content'],$premier_list[$key]['mpd_content'])?>"><?=$val['epl_content']?></td>
                                <td class="insert <?=comparison($val['epl_institution'],$premier_list[$key]['mpd_institution'])?>"><?=$val['epl_institution']?></td>
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
                        <button type="button" data-btn="목록" onclick="location.href='/hass/humanmodify?1=1<?=$paging_subquery?>'"  class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
                        <?if($certificate_list2[0]['cl_state']=='A'){?>
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
                hlb_fn_ajaxTransmit("/@proc/hass/member_certificate_update_proc.php", data);
            }
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='member_certificate_update_proc'){
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

