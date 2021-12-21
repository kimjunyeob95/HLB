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
(select distinct el_division as division,el_mmseq as seq,'education' as 'page', '학력사항' as 'info',el_confirm_date as confirm_date,el_applydate as applydate,el_state as status from ess_education_log where el_mmseq = {$mmseq} and el_state <> 'C')
)T order by T.applydate desc;
";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

$education_list = get_education_list($db,$mmseq); // 학력사항
$education_list2 = get_education_log_list($db,$mmseq,$division); // 사외경력
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'education');
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
                                <th scope="col">비고</th>
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
                                <th scope="col">비고</th>
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
                                    <td>
                                        <? if($status[$data['status']]=='반려'){
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