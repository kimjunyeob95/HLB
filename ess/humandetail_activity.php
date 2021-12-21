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
(select distinct eat_division as division,eat_mmseq as seq,'activity' as 'page', '교육 / 활동' as 'info',eat_confirm_date as confirm_date,eat_applydate as applydate,eat_state as status from ess_activity_log where eat_mmseq = {$mmseq} and eat_state <> 'C')
)T order by T.applydate desc;
";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

$activity_list = get_activity_list($db,$mmseq); // 교육 / 활동
$activity_list2 = get_activity_log_list($db,$mmseq,$division); // 상벌
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'activity');
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
                        <button type="button" data-btn="목록"  onclick="location.href='/ess/change?1=1<?=$paging_subquery?>'"  class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
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