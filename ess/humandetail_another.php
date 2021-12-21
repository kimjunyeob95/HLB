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
(select  distinct crl_division as division,crl_mmseq as seq,'another' as 'page','경력사항' as 'info',crl_confirm_date as confirm_date,crl_applydate as applydate,crl_state as status from ess_career_log where crl_mmseq = {$mmseq} and crl_state <> 'C')
)T order by T.applydate desc;
";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

$career_list = get_career_list($db,$mmseq); // 경력사항
$career_list2 = get_career_log_list($db,$mmseq,$division); // 경력사항
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'another');
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
        <?if($career_list2[0]['crl_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(경력사항) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-another"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <?foreach ($career_list as $key => $val){?>
                    <table class="data-table table-form-another left">
                        <caption>정보 변경 상세(경력사항)</caption>
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

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(경력사항) 수정 후</h2>

        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(경력사항)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-another2" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <?foreach ($career_list2 as $key => $val){?>
                    <table class="data-table table-form-another left">
                        <caption>정보 변경 상세(경력사항)</caption>
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