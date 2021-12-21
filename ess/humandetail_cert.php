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
(select distinct cl_division as division,cl_mmseq as seq,'cert' as 'page', '어학 / 자격증 / 수상' as 'info',cl_confirm_date as confirm_date,cl_applydate as applydate ,cl_state as status from ess_certificate_log where cl_mmseq = {$mmseq} and cl_state <> 'C')
)T order by T.applydate desc;
";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);

$certificate_list = get_certificate_list($db,$mmseq); // 논문 / 저서
$certificate_list2 = get_certificate_log_list($db,$mmseq,$division); // 사외경력
$premier_list = get_premier_list($db,$mmseq); // 수상
$premier_list2 = get_premier_log_list($db,$mmseq,$division); // 수상
$cause_of_return =  get_cause_of_return($db,$mmseq , $division,'cert');
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
        <?if($certificate_list2[0]['cl_state']=='A' || $premier_list2[0]['cl_state']=='A'){?>
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
                        <button type="button" data-btn="목록" onclick="location.href='/ess/change?1=1<?=$paging_subquery?>'"  class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
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