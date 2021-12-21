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
(select distinct ep_division as division,ep_mmseq as seq,'prize' as 'page', '상벌' as 'info',ep_confirm_date as confirm_date,ep_applydate as applydate,ep_state as status from ess_punishment_log where ep_mmseq = {$mmseq} and ep_state <> 'C')
)T order by T.applydate desc;
";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

$punishment_list = get_punishment_list($db,$mmseq); // 상벌
$punishment_list2 = get_punishment_log_list($db,$mmseq,$division); // 상벌
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'prize');
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
        <?if($punishment_list2[0]['ep_state']=='A'){?>
        <h2 class="content-title">정보 변경 상세(상벌) 수정 전</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-prize" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>상벌</caption>
                        <colgroup>
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">구분</th>
                            <th scope="col">일자</th>
                            <th scope="col">상벌명</th>
                            <th scope="col">사유 및 내용</th>
                            <th scope="col">비고</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($punishment_list as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['mp_type'],$punishment_list2[$key]['ep_type'])?>"><?=$punishment[$val['mp_type']]?></td>
                                <td class="insert <?=comparison($val['mp_date'],$punishment_list2[$key]['ep_date'])?>"><?=substr($val['mp_date'],0,10)?></td>
                                <td class="insert <?=comparison($val['mp_title'],$punishment_list2[$key]['ep_title'])?>"><?=$val['mp_title']?></td>
                                <td class="insert <?=comparison($val['mp_content'],$punishment_list2[$key]['ep_content'])?>"><?=$val['mp_content']?></td>
                                <td class="insert <?=comparison($val['mp_etc'],$punishment_list2[$key]['ep_etc'])?>"><?=$val['mp_etc']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>

        <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(상벌) 수정 후</h2>
        <?}else{?>
            <h2 class="content-title" style="margin-top:50px;">정보 변경 상세(상벌)</h2>
        <?}?>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-prize2" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>상벌</caption>
                        <colgroup>
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">구분</th>
                            <th scope="col">일자</th>
                            <th scope="col">상벌명</th>
                            <th scope="col">사유 및 내용</th>
                            <th scope="col">비고</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($punishment_list2 as $key =>$val){?>
                            <tr>
                                <td class="insert <?=comparison($val['ep_type'],$punishment_list[$key]['mp_type'])?>"><?=$punishment[$val['ep_type']]?></td>
                                <td class="insert <?=comparison($val['ep_date'],$punishment_list[$key]['mp_date'])?>"><?=substr($val['ep_date'],0,10)?></td>
                                <td class="insert <?=comparison($val['ep_title'],$punishment_list[$key]['mp_title'])?>"><?=$val['ep_title']?></td>
                                <td class="insert <?=comparison($val['ep_content'],$punishment_list[$key]['mp_content'])?>"><?=$val['ep_content']?></td>
                                <td class="insert <?=comparison($val['ep_etc'],$punishment_list[$key]['mp_etc'])?>"><?=$val['ep_etc']?></td>
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