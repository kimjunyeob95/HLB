<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$education_list = get_education_list($db,$mmseq); // 사외경력
$education_list2 = get_education_log_list($db,$mmseq,$division); // 사외경력
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'education');
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->

<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <?if($education_list2[0]['el_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(학력사항) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-education"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-education left">
                        <caption>정보 변경 상세(학력사항)</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">입학일</th>
                                <th scope="col">졸업일</th>
                                <th scope="col">학교명</th>
                                <th scope="col">학력</th>
                                <th scope="col">전공</th>
                                <th scope="col">학위</th>
                                <th scope="col">졸업구분</th>
                                <th scope="col">주야간구분</th>
                                <th scope="col">기타</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($education_list as $key =>$val){?>
                            <tr>
                                <td class="<?=comparison($val['me_sdate'],$education_list2[$key]['el_sdate'])?>"><?=substr($val['me_sdate'],0,10)?></td>
                                <td class="<?=comparison($val['me_edate'],$education_list2[$key]['el_edate'])?>"><?=substr($val['me_edate'],0,10)?></td>
                                <td class="<?=comparison($val['me_name'],$education_list2[$key]['el_name'])?>"><?=$val['me_name']?></td>
                                <td class="<?=comparison($val['me_level'],$education_list2[$key]['el_level'])?>"><?=$degree_level[$val['me_level']]?></td>
                                <td class="<?=comparison($val['me_major'],$education_list2[$key]['el_major'])?>"><?=$val['me_major']?></td>
                                <td class="<?=comparison($val['me_degree'],$education_list2[$key]['el_degree'])?>"><?=$degree_level2[$val['me_degree']]?></td>
                                <td class="<?=comparison($val['me_graduate_type'],$education_list2[$key]['el_graduate_type'])?>"><?=$graduate_type_array[$val['me_graduate_type']]?></td>
                                <td class="<?=comparison($val['me_weekly'],$education_list2[$key]['el_weekly'])?>"><?=$weekly_array[$val['me_weekly']]?></td>
                                <td class="<?=comparison($val['me_etc'],$education_list2[$key]['el_etc'])?>"><?=$val['me_etc']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(학력사항) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(학력사항)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-education2"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-education left">
                        <caption>정보 변경 상세(학력사항)</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">입학일</th>
                                <th scope="col">졸업일</th>
                                <th scope="col">학교명</th>
                                <th scope="col">학력</th>
                                <th scope="col">전공</th>
                                <th scope="col">학위</th>
                                <th scope="col">졸업구분</th>
                                <th scope="col">주야간구분</th>
                                <th scope="col">기타</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($education_list2 as $key =>$val){?>
                            <tr>
                                <td class="<?=comparison($val['el_sdate'],$education_list[$key]['me_sdate'])?>"><?=substr($val['el_sdate'],0,10)?></td>
                                <td class="<?=comparison($val['el_edate'],$education_list[$key]['me_edate'])?>"><?=substr($val['el_edate'],0,10)?></td>
                                <td class="<?=comparison($val['el_name'],$education_list[$key]['me_name'])?>"><?=$val['el_name']?></td>
                                <td class="<?=comparison($val['el_level'],$education_list[$key]['me_level'])?>"><?=$degree_level[$val['el_level']]?></td>
                                <td class="<?=comparison($val['el_major'],$education_list[$key]['me_major'])?>"><?=$val['el_major']?></td>
                                <td class="<?=comparison($val['el_degree'],$education_list[$key]['me_degree'])?>"><?=$degree_level2[$val['el_degree']]?></td>
                                <td class="<?=comparison($val['el_graduate_type'],$education_list[$key]['me_graduate_type'])?>"><?=$graduate_type_array[$val['el_graduate_type']]?></td>
                                <td class="<?=comparison($val['me_weekly'],$education_list2[$key]['el_weekly'])?>"><?=$weekly_array[$val['el_weekly']]?></td>
                                <td class="<?=comparison($val['el_etc'],$education_list[$key]['me_etc'])?>"><?=$val['el_etc']?></td>
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
                        <?if($education_list2[0]['el_state']=='A'){?>
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
                hlb_fn_ajaxTransmit("/@proc/hass/member_education_update_proc.php", data);
            }
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='member_education_update_proc'){
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

