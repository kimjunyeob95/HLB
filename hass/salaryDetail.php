<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$page = $_REQUEST['page'];
@$search = $_REQUEST['search'];
@$mmseq = $_SESSION['mmseq'];
@$mm_code = $_SESSION['mInfo']['mc_code'];
@$seq = $_REQUEST['seq'];
@$hue_salaryDate_year = $_REQUEST['y']; //급여일 년
@$hue_salaryDate_month = $_REQUEST['m']; //급여일 월

$rows = 10;
if(empty($page)){
    $page=1;
}
$where = " on uf.hu_hss_seq=ed.hue_hss_seq
where uf.hu_del='False'
and ed.hue_salaryDate_year={$hue_salaryDate_year} and ed.hue_salaryDate_month={$hue_salaryDate_month} and ed.hue_ess_code={$seq}";

$query="SELECT distinct ed.hue_salaryDate_year,ed.* FROM hass_upload_excel_data AS ed JOIN hass_upload_file AS uf ".$where;
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($query);echo('</pre>');exit;
?>
<style>
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
		<h2 class="content-title"><?=$hue_salaryDate_year?>년 <?=$hue_salaryDate_month?>월 <?=$data['hue_mm_name']?>님 급여 내역</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-information"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    
                    <table class="data-table left">
                        <caption>급여 내역 상세페이지</caption>
                        <colgroup>
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">사번</th>
                                <td style="background:#efefef;"><?=$data['hue_ess_code']?></td>
                                <th scope="col">성명</th>
                                <td><?=$data['hue_mm_name']?></td>
                                <th scope="col">부서</th>
                                <td><?=$data['hue_mm_buseo']?></td>
                                <th scope="col">직급</th>
                                <td><?=$data['hue_mm_level']?></td>
                            </tr>
                            <tr>
                                <th scope="col">등록일자</th>
                                <td colspan="3"><?=substr($data['hue_regDate'],0,10)?></td>
                                <th scope="col">총 액</th>
                                <td colspan="4"><?=number_format($data['total_pay'])?>원</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="data-table left">
                        <caption>급여 내역 상세페이지</caption>
                        <colgroup>
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th colspan="8" scope="col" style="background-color:dimgrey; color:#fff;">수당</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="col">기본급</th>
                                <td><?=number_format($data['pay01'])?>원</td>
                                <th scope="col">수당</th>
                                <td><?=number_format($data['pay02'])?>원</td>
                                <th scope="col">식대</th>
                                <td><?=number_format($data['pay03'])?>원</td>
                                <th scope="col">차량보조금</th>
                                <td><?=number_format($data['pay04'])?>원</td>
                            </tr>
                            <tr>
                                <th scope="col">학자금지원</th>
                                <td><?=number_format($data['pay05'])?>원</td>
                                <th scope="col">연차수당</th>
                                <td><?=number_format($data['pay06'])?>원</td>
                                <th scope="col">육아수당</th>
                                <td><?=number_format($data['pay07'])?>원</td>
                                <th scope="col">주말근무수당</th>
                                <td><?=number_format($data['pay08'])?>원</td>
                            </tr>
                            <tr>
                                <th scope="col">급여 소급액</th>
                                <td><?=number_format($data['pay09'])?>원</td>
                                <th scope="col">기타수당</th>
                                <td><?=number_format($data['pay10'])?>원</td>
                                <th scope="col">주식매수선택권행사이익</th>
                                <td><?=number_format($data['pay11'])?>원</td>
                                <th scope="col">지급액계</th>
                                <td><?=number_format($data['pay12'])?>원</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="data-table left">
                        <caption>급여 내역 상세페이지</caption>
                        <colgroup>
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                            <col style="width: 160px" />
                            <col style="width: 18%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th colspan="8" scope="col" style="background-color:dimgrey; color:#fff;">공제</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="col">국민연금</th>
                                <td><?=number_format($data['deduct01'])?>원</td>
                                <th scope="col">건강보험</th>
                                <td><?=number_format($data['deduct02'])?>원</td>
                                <th scope="col">고용보험</th>
                                <td><?=number_format($data['deduct03'])?>원</td>
                                <th scope="col">장기요양보험료</th>
                                <td><?=number_format($data['deduct04'])?>원</td>
                            </tr>
                            <tr>
                                <th scope="col">소득세</th>
                                <td><?=number_format($data['deduct05'])?>원</td>
                                <th scope="col">지방소득세</th>
                                <td><?=number_format($data['deduct06'])?>원</td>
                                <th scope="col">학자금상환액</th>
                                <td><?=number_format($data['deduct07'])?>원</td>
                                <th scope="col">건강보험 정산금</th>
                                <td><?=number_format($data['deduct08'])?>원</td>
                            </tr>
                            <tr>
                                <th scope="col">건강보험 정산금</th>
                                <td><?=number_format($data['deduct09'])?>원</td>
                                <th scope="col">국민연금 소급분</th>
                                <td><?=number_format($data['deduct10'])?>원</td>
                                <th scope="col">고용보험 정산금</th>
                                <td><?=number_format($data['deduct11'])?>원</td>
                                <th scope="col">공제액계</th>
                                <td><?=number_format($data['deduct12'])?>원</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align:center;margin-top:50px;">
                    <div class="button-area large">
                        <button type="button" data-btn="목록" class="btn type01 large btn-footer">목록<span class="ico apply"></span></button>
                        
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
    $('#aside-menu .tree-wrap>li:eq(2)').addClass('active');

    $('.btn-footer').click(function(e){
        var $this_btn = $(this).data('btn');
        var salaryDate_year = <?=$hue_salaryDate_year?>; //급여일 년
        var salaryDate_month = <?=$hue_salaryDate_month?>; //급여일 월
        if($this_btn=='목록'){
            location.href='./salarycheck.php?y='+salaryDate_year+'&m='+salaryDate_month;
        }
    });
</script>

