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

$family_list = get_family_list($db,$mmseq); // 가족사항사항
$family_list2 = get_family_log_list($db,$mmseq,$division); // 사외경력
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'family');
// echo('<pre>');print_r($data);echo('</pre>');
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

        <?if($family_list2[0]['ml_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(가족사항) 수정 전</h2>
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

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(가족사항) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(가족사항)</h2>
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