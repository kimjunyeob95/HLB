<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$activity_list = get_activity_list($db,$mmseq); // 교육 / 활동
$activity_list2 = get_activity_log_list($db,$mmseq,$division); // 상벌
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'activity');
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->

<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <?if($activity_list2[0]['eat_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(교육 / 활동) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-activity"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>교육 / 활동</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">구분</th>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">교육/활동명</th>
                                <th scope="col">기관명</th>
                                <th scope="col">역할</th>
                                <th scope="col">증빙서류</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($activity_list as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['mad_type'],$activity_list2[$key]['eat_type'])?>"><?=$company_type_array[$val['mad_type']]?></td>
                                <td class="insert <?=comparison($val['mad_sdate'],$activity_list2[$key]['eat_sdate'])?>"><?=substr($val['mad_sdate'],0,10)?></td>
                                <td class="insert <?=comparison($val['mad_edate'],$activity_list2[$key]['eat_edate'])?>"><?=substr($val['mad_edate'],0,10)?></td>
                                <td class="insert <?=comparison($val['mad_name'],$activity_list2[$key]['eat_name'])?>"><?=$enc->decrypt($val['mad_name'])?></td>
                                <td class="insert <?=comparison($val['mad_institution'],$activity_list2[$key]['eat_institution'])?>"><?=$enc->decrypt($val['mad_institution'])?></td>
                                <td class="insert <?=comparison($val['mad_role'],$activity_list2[$key]['eat_role'])?>"><?=$val['mad_role']?></td>
                                <td class="insert <?=comparison($val['mad_file'],$activity_list2[$key]['eat_file'])?>">
                                    <?if(!empty($val['mad_file'])){?>
                                        <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span>
                                    <?}else{?>
                                        -
                                    <?}?>
                                </td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(교육 / 활동) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(교육 / 활동)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-activity2"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>교육 / 활동</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">구분</th>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">교육/활동명</th>
                                <th scope="col">기관명</th>
                                <th scope="col">역할</th>
                                <th scope="col">증빙서류</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($activity_list2 as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($activity_list[$key]['mad_type'],$val['eat_type'])?>"><?=$company_type_array[$val['eat_type']]?></td>
                                <td class="insert <?=comparison($activity_list[$key]['mad_sdate'],$val['eat_sdate'])?>"><?=substr($val['eat_sdate'],0,10)?></td>
                                <td class="insert <?=comparison($activity_list[$key]['mad_edate'],$val['eat_edate'])?>"><?=substr($val['eat_edate'],0,10)?></td>
                                <td class="insert <?=comparison($activity_list[$key]['mad_name'],$val['eat_name'])?>"><?=$enc->decrypt($val['eat_name'])?></td>
                                <td class="insert <?=comparison($activity_list[$key]['mad_institution'],$val['eat_institution'])?>"><?=$enc->decrypt($val['eat_institution'])?></td>
                                <td class="insert <?=comparison($val['mad_role'],$activity_list2[$key]['eat_role'])?>"><?=$val['eat_role']?></td>
                                <td class="insert <?=comparison($activity_list[$key]['mad_file'],$val['eat_file'])?>">
                                    <?if(!empty($val['eat_file'])){?>
                                        <a href="<?=$val['eat_file']?>" download><?=$val['eat_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span>
                                    <?}else{?>
                                        -
                                    <?}?>
                                </td>
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
                        <?if($activity_list2[0]['eat_state']=='A'){?>
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
                hlb_fn_ajaxTransmit("/@proc/hass/member_activity_update_proc.php", data);
            }
        }
        
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='member_activity_update_proc'){
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

