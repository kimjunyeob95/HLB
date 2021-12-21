<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$mmseq = $_SESSION['mmseq'];
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$query=" 
select * from(
(select distinct ml_division as division,ml_mmseq as seq,'family' as 'page', '가족사항' as 'info',ml_confirm_date as confirm_date,ml_applydate as applydate,ml_state as status from ess_family_log where ml_mmseq = {$mmseq} and ml_state <> 'C')
)T order by T.applydate desc;
";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

$member_list = get_member_info($db,$mmseq); // 가족사항
$member_log_list = get_member_log_list($db,$mmseq,$division); // 사외경력

// echo('<pre>');print_r($member_log_list['em_state']);echo('</pre>');
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'nomal');

?>
<div id="wrap" class="depth03">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="my-profile-card">
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>My Profile</h2>
		<ul class="lnb">
            <li><a href="/ess/tree" >조직도</a></li>
			<li><a href="/ess/timeline">나의 타임라인</a></li>
			<li><a href="/ess/" >인사기록카드</a></li>
			<li><a href="/ess/change"  class="active" >정보 변경 신청 내역</a></li>
			<!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">

        <?if($member_log_list['em_state']=='A'){?>
		<h2 class="content-title">정보 변경 상세(기본사항) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-information"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">

                    <table class="data-table left">
                        <caption>정보 변경 상세(기본사항)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">사번</th>
                                <td  style="background:#efefef;"><?=$member_list['mc_code']?></td>
                                <th scope="col">성명</th>
                                <td class="<?=comparison($member_list['mm_name'],$member_log_list['em_name'])?>"><?=$enc->decrypt($member_list['mm_name'])?></td>
                                <th scope="col">영문성명</th>
                                <td class="<?=comparison($member_list['mm_en_name'],$member_log_list['em_en_name'])?>"><?=$member_list['mm_en_name']?></td>
                                <th scope="col">국적</th>
                                <td class="<?=comparison($member_list['mm_country'],$member_log_list['em_country'])?>"><?=getCountryText($member_list['mm_country'],'국적')?></td>
                            </tr>
                            <tr>
                                <th scope="col">성별</th>
                                <td class="<?=comparison($member_list['mm_gender'],$member_log_list['em_gender'])?>"><?=getGenderText($member_list['mm_gender'])?></td>
                                <th scope="col">생년월일</th>
                                <td class="<?=comparison($member_list['mm_birth'],$member_log_list['em_birth'])?>"><?=substr($member_list['mm_birth'],0,10)?></td>
                                <th scope="col">주민/외국인 번호</th>
                                <td class="<?=comparison($member_list['mm_serial_no'],$member_log_list['em_serial_no'])?>"><?=$enc->decrypt($member_list['mm_serial_no'])?></td>
                                <th scope="col">연락처</th>
                                <td class="<?=comparison($member_list['mm_cell_phone'],$member_log_list['em_cell_phone'])?>"><?=$enc->decrypt($member_list['mm_cell_phone'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">이메일 주소</th>
                                <td class="<?=comparison($member_list['mm_email'],$member_log_list['em_email'])?>"><?=$enc->decrypt($member_list['mm_email'])?></td>
                                <th scope="col">우편번호</th>
                                <td class="<?=comparison($member_list['mm_post'],$member_log_list['em_post'])?>"><?=$member_list['mm_post']?></td>
                                <th scope="col">주소</th>
                                <td class="<?=comparison($member_list['mm_address'],$member_log_list['em_address'])?>"><?=$enc->decrypt($member_list['mm_address'])?></td>
                                <th scope="col">상세주소</th>
                                <td class="<?=comparison($member_list['mm_address_detail'],$member_log_list['em_address_detail'])?>"><?=$enc->decrypt($member_list['mm_address_detail'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">비상연락처</th>
                                <td class="<?=comparison($member_list['mm_prepare_relation'],$member_log_list['em_prepare_relation'])?> <?=comparison($member_list['mm_prepare_phone'],$member_log_list['em_prepare_phone'])?>">
                                    관계 : <?=$member_list['mm_prepare_relation']?><br>
                                    연락처 : <?=$enc->decrypt($member_list['mm_prepare_phone'])?>
                                </td>
                                <th scope="col">이미지</th>
                                <td colspan=5 class="<?=comparison($member_list['mm_profile'],$member_log_list['em_profile'])?>"><img src="<?=$member_list['mm_profile']?>" width="100" height="130"></td>
                            </tr>
                        </tbody>
                    </table>
                    <h3 class="section-title">병력</h3>
                    <table class="data-table table-form-army left" style="border-top:1px solid #d1d1d1;">
                        <caption>병력</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">병역구분</th>
                                <th scope="col">입대일</th>
                                <th scope="col">제대일</th>
                                <th scope="col">군별</th>
                                <th scope="col">계급</th>
                                <th scope="col">병과</th>
                                <th scope="col">사유(면제 및 기타)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="insert center <?=comparison($member_list['mm_arm_type'],$member_log_list['em_arm_type'])?>"><?=$arm_type[$member_list['mm_arm_type']]?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_reason'],$member_log_list['em_arm_reason'])?>"><?=$member_list['mm_arm_reason']?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_group'],$member_log_list['em_arm_group'])?>"><?=$arm_group[$member_list['mm_arm_group']]?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_class'],$member_log_list['em_arm_class'])?>"><?=$arm_class[$member_list['mm_arm_class']]?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_discharge'],$member_log_list['em_arm_discharge'])?>"><?=$member_list['mm_arm_discharge']?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_sdate'],$member_log_list['em_arm_sdate'])?>"><?=substr($member_list['mm_arm_sdate'],0,10)?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_edate'],$member_log_list['em_arm_edate'])?>"><?=substr($member_list['mm_arm_edate'],0,10)?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="section-title">장애정보 / 국가보훈정보</h3>
                    <table class="data-table table-form-army left" style="border-top:1px solid #d1d1d1;">
                        <caption>장애정보 / 국가보훈정보</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">장애여부</th>
                                <th scope="col">장애구분</th>
                                <th scope="col">장애등급</th>
                                <th scope="col">보훈여부</th>
                                <th scope="col">보훈구분</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="insert" class='center <?=comparison($member_list['mm_disorder_1'],$member_log_list['em_disorder_1'])?>'>
                                <?=$disorder_type_1[$member_list['mm_disorder_1']]?>
                            </td>
                            <td scope="insert" class="center <?=comparison($member_list['mm_disorder_2'],$member_log_list['em_disorder_2'])?>" >
                                <?=$disorder_type_2[$member_list['mm_disorder_2']]?>
                            </td>
                            <td scope="insert" class='center <?=comparison($member_list['mm_disorder_3'],$member_log_list['em_disorder_3'])?>' >
                                <?=$disorder_type_3[$member_list['mm_disorder_3']]?>
                            </td>
                            <td scope="insert" class='center <?=comparison($member_list['mm_nation_1'],$member_log_list['em_nation_1'])?>'>
                                <?=$nation_type_1[$member_list['mm_nation_1']]?>
                            </td>
                            <td scope="insert" class="center <?=comparison($member_list['mm_nation_2'],$member_log_list['em_nation_2'])?>">
                                <?=$nation_type_2[$member_list['mm_nation_2']]?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(기본사항) 수정 후</h2>

        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(기본사항)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-information2"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">

                    <table class="data-table left">
                        <caption>정보 변경 상세(기본사항)</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">사번</th>
                                <td style="background:#efefef;"><?=$member_list['mc_code']?></td>
                                <th scope="col">성명</th>
                                <td class="<?=comparison($member_list['mm_name'],$member_log_list['em_name'])?>"><?=$enc->decrypt($member_log_list['em_name'])?></td>
                                <th scope="col">영문성명</th>
                                <td class="<?=comparison($member_list['mm_en_name'],$member_log_list['em_en_name'])?>"><?=$member_log_list['em_en_name']?></td>
                                <th scope="col">국적</th>
                                <td class="<?=comparison($member_list['mm_country'],$member_log_list['em_country'])?>"><?=getCountryText($member_log_list['em_country'],'국적')?></td>
                            </tr>
                            <tr>
                                <th scope="col">성별</th>
                                <td class="<?=comparison($member_list['mm_gender'],$member_log_list['em_gender'])?>"><?=getGenderText($member_log_list['em_gender'])?></td>
                                <th scope="col">생년월일</th>
                                <td class="<?=comparison($member_list['mm_birth'],$member_log_list['em_birth'])?>"><?=substr($member_log_list['em_birth'],0,10)?></td>
                                <th scope="col">주민/외국인 번호</th>
                                <td class="<?=comparison($member_list['mm_serial_no'],$member_log_list['em_serial_no'])?>"><?=$enc->decrypt($member_log_list['em_serial_no'])?></td>
                                <th scope="col">연락처</th>
                                <td class="<?=comparison($member_list['mm_cell_phone'],$member_log_list['em_cell_phone'])?>"><?=$enc->decrypt($member_log_list['em_cell_phone'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">이메일 주소</th>
                                <td class="<?=comparison($member_list['mm_email'],$member_log_list['em_email'])?>"><?=$enc->decrypt($member_log_list['em_email'])?></td>
                                <th scope="col">우편번호</th>
                                <td class="<?=comparison($member_list['mm_post'],$member_log_list['em_post'])?>"><?=$member_log_list['em_post']?></td>
                                <th scope="col">주소</th>
                                <td class="<?=comparison($member_list['mm_address'],$member_log_list['em_address'])?>"><?=$enc->decrypt($member_log_list['em_address'])?></td>
                                <th scope="col">상세주소</th>
                                <td class="<?=comparison($member_list['mm_address_detail'],$member_log_list['em_address_detail'])?>"><?=$enc->decrypt($member_log_list['em_address_detail'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">비상연락처</th>
                                <td class="<?=comparison($member_list['mm_prepare_relation'],$member_log_list['em_prepare_relation'])?> <?=comparison($member_list['mm_prepare_phone'],$member_log_list['em_prepare_phone'])?>">
                                    관계 : <?=$member_log_list['em_prepare_relation']?><br>
                                    연락처 : <?=$enc->decrypt($member_log_list['em_prepare_phone'])?>
                                </td>
                                <th scope="col">이미지</th>
                                <td class="<?=comparison($member_list['mm_profile'],$member_log_list['em_profile'])?>" colspan=5><img src="<?=$member_log_list['em_profile']?>" width="100" height="130"></td>
                            </tr>
                        </tbody>
                    </table>
                    <h3 class="section-title">병력</h3>
                    <table class="data-table table-form-army left" style="border-top:1px solid #d1d1d1;">
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">병역구분</th>
                                <th scope="col">입대일</th>
                                <th scope="col">제대일</th>
                                <th scope="col">군별</th>
                                <th scope="col">계급</th>
                                <th scope="col">병과</th>
                                <th scope="col">사유(면제 및 기타)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="insert center <?=comparison($member_list['mm_arm_type'],$member_log_list['em_arm_type'])?>"><?=$arm_type[$member_log_list['em_arm_type']]?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_reason'],$member_log_list['em_arm_reason'])?>"><?=$member_log_list['em_arm_reason']?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_group'],$member_log_list['em_arm_group'])?>"><?=$arm_group[$member_log_list['em_arm_group']]?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_class'],$member_log_list['em_arm_class'])?>"><?=$arm_class[$member_log_list['em_arm_class']]?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_discharge'],$member_log_list['em_arm_discharge'])?>""><?=$member_log_list['em_arm_discharge']?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_sdate'],$member_log_list['em_arm_sdate'])?>""><?=substr($member_log_list['em_arm_sdate'],0,10)?></td>
                                <td class="insert center <?=comparison($member_list['mm_arm_edate'],$member_log_list['em_arm_edate'])?>""><?=substr($member_log_list['em_arm_edate'],0,10)?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="section-title">장애정보 / 국가보훈정보</h3>
                    <table class="data-table table-form-army left" style="border-top:1px solid #d1d1d1;">
                        <caption>장애정보 / 국가보훈정보</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">장애여부</th>
                                <th scope="col">장애구분</th>
                                <th scope="col">장애등급</th>
                                <th scope="col">보훈여부</th>
                                <th scope="col">보훈구분</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="insert" class='center <?=comparison($member_list['mm_disorder_1'],$member_log_list['em_disorder_1'])?>'>
                                <?=$disorder_type_1[$member_log_list['em_disorder_1']]?>
                            </td>
                            <td scope="insert" class="center <?=comparison($member_list['mm_disorder_2'],$member_log_list['em_disorder_2'])?>" >
                                <?=$disorder_type_2[$member_log_list['em_disorder_2']]?>
                            </td>
                            <td scope="insert" class='center <?=comparison($member_list['mm_disorder_3'],$member_log_list['em_disorder_3'])?>' >
                                <?=$disorder_type_3[$member_log_list['em_disorder_3']]?>
                            </td>
                            <td scope="insert" class='center <?=comparison($member_list['mm_nation_1'],$member_log_list['em_nation_1'])?>'>
                                <?=$nation_type_1[$member_log_list['em_nation_1']]?>
                            </td>
                            <td scope="insert" class="center <?=comparison($member_list['mm_nation_2'],$member_log_list['em_nation_2'])?>">
                                <?=$nation_type_2[$member_log_list['em_nation_2']]?>
                            </td>
                        </tr>
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
                                    <td>
                                        <? if($status[$member_log_list['em_state']]=='반려'){
                                            echo ''.$cause_of_return;
                                        }else echo '-';?>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </div>
                <div style="text-align:center;margin-top:50px;">
                    <div class="button-area large">
                        <button type="button" data-btn="목록" onclick="location.href='/ess/change?1=1<?=$paging_subquery?>'" class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
                    </div>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
	</div>
</div>
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(0)').addClass('active');
$('.depth02:eq(0)').find('li:eq(2)').addClass('active');

</script>