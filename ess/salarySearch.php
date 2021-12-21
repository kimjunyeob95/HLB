<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$auth_js = '
    var user_pw = prompt("주민번호 뒷자리를 입력하세요.");
    if(user_pw == ""){
        location.reload();
    }else if(user_pw === null){
        location.href="/";
    }else{
        $.ajax({
            url: "/@proc/ess/chkSerialNum.php",
            type: "post",
            data: {
                mm_serial_no : user_pw
            },
            dataType: "json",
            success: function(response){
                if(response.code == "FALSE"){
                    alert(response.msg);
                    location.reload();
                }else{
                    $("#wrap").removeClass("hide");
                }
            },
            fail: function(error) {
                location.reload();
            }
        });
    }
';
if(empty($_SESSION['mInfo']['salary_auth'])){
    javascript($auth_js);
}

@$mmseq = $_SESSION['mmseq'];
@$mm_code = $_SESSION['mInfo']['mc_code'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
// if(empty($_SESSION['mInfo']['salary_auth']) && $_SESSION['mInfo']['mm_super_admin']=='F'){
//     page_move("/ess/salary_auth");
// }
//보여줄 급여 쿼리
$query="select * from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False' and hu_showDate <= DATE_FORMAT(NOW(), '%Y-%c-%e %H') and hu_hss_seq={$mc_coseq}
order by hu_showDate desc limit 1 ) hu
on hu.hu_salaryDate_month = hue.hue_salaryDate_month and
hu.hu_salaryDate_year = hue.hue_salaryDate_year and
hu.hu_hss_code = hue.hue_hss_code where
hue.hue_del = 'False' and
hue.hue_ess_code =? and hue.hue_hss_seq=?";
$ps = pdo_query($db,$query,array($mm_code,$mc_coseq));
$data = $ps->fetch(PDO::FETCH_ASSOC);

//이번 년도 급여 쿼리
$query="select distinct hue.hue_salaryDate_month,hue.* from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False' and hu_salaryDate_year=?) hu
on hu.hu_salaryDate_year = hue.hue_salaryDate_year and
hu.hu_hss_code = hue.hue_hss_code where
hue.hue_del = 'False' and
hue.hue_ess_code =? and hue.hue_hss_seq=? order by hue.hue_salaryDate_month;";
$ps = pdo_query($db,$query,array(date('Y'),$mm_code,$mc_coseq));
$list = array();
while($data_salry = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data_salry);
}

//등록된 년도 쿼리
$query="select distinct hue.hue_salaryDate_year from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False') hu
on hu.hu_salaryDate_year = hue.hue_salaryDate_year and
hu.hu_hss_code = hue.hue_hss_code where
hue.hue_del = 'False' and
hue.hue_ess_code =? and hue.hue_hss_seq=? order by hue.hue_salaryDate_month;";
$ps = pdo_query($db,$query,array($mm_code,$mc_coseq));
$list_year = array();
while($data_year = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list_year, $data_year);
}
// echo('<pre>');print_r($list);echo('</pre>');

$total_price; //총 누적액
$total_pay; //총 지급액
$total_deduct; //총 공제액
foreach ($list as $val){
    $total_price+=$val['total_pay']; //총 누적액
    $total_pay+=$val['pay12']; // 총 지금액
    $total_deduct+=$val['deduct12']; //총 공제액
}
?>

<?if(empty($_SESSION['mInfo']['salary_auth'])){?>
<div id="wrap" class="depth-main hide">
<?}else{?>
    <div id="wrap" class="depth-main">
<?}?>
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="reward-pay">
    <div class="content-header">
		<div class="cont">
			<h3 class="title"><?=$data['hu_salaryDate_year']?></span>년 월별 지급내역 조회</h3> <!-- // 180104 텍스트 수정 -->
		</div>
	</div>
	<!-- content-primary -->
	<div id="content" class="content-primary" style="margin-top:0px;">
		<div class="section-wrap">
            <h2 class="content-title"></h2>
            <div class="insert">
                <select class="select" id="selectTag_year">
                    <?
                        foreach($list_year as $val){
                            if($data['hu_salaryDate_year'] == $val['hue_salaryDate_year']){
                                echo '<option selected value='.$val['hue_salaryDate_year'].'>'.$val['hue_salaryDate_year'].'</option>';    
                            }else{
                                echo '<option value='.$val['hue_salaryDate_year'].'>'.$val['hue_salaryDate_year'].'</option>';
                            }
                            
                        }
                    ?>
                </select>
                <label class="label" for="selectTag_year">년</label>
                
                <select class="select" id="selectTag">
                    <?
                        for($i=1;$i<=12;$i++){
                            if((int)$data['hu_salaryDate_month'] == $i){
                                echo '<option selected value='.$i.'>'.$i.'</option>';    
                            }else{
                                echo '<option value='.$i.'>'.$i.'</option>';
                            }
                        }
                    ?>
                </select>
                <label class="label" for="selectTag">월</label>

                <button type="button" id="btn-salary-search" class="btn type01 small">조회</button>
            </div>
		</div>
		<div class="section pay-list detaile">
			<h2 class="content-title">
                <span class="js_year"><?=$data['hu_salaryDate_year']?></span>년
                <span class="js_month"><?=$data['hu_salaryDate_month']?></span>월 급여 상세내역
            </h2>
			<!-- 정기급여 -->
			<h3 class="section-title">급여
				<!-- 171206 추가 -->
				<!-- <div class="section-aside">
					<ul class="pay-step">
						<li>호봉 : 11</li>
						<li>별도호봉 : 2,222,222</li>
					</ul>
				</div> -->
				<!-- // 171206 추가 -->
			</h3>
			<div class="table-wrap">
				<table class="data-table right">
					<caption>정기급여 지급내역 및 공제내역 정보</caption>
					<colgroup>
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
						<col style="width: 12.5%" />
					</colgroup>
					<thead>
						<tr>
							<th scope="col" colspan="4">지급내역</th>
							<th scope="col" colspan="4">공제내역</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="8"><span class="ico sum01"></span>실지급액 <span>(지급계 – 공제계) </span> :  <?=number_format($data['total_pay'])?>원</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th scope="row">기본급</th>
							<td class="right"><?=number_format($data['pay01'])?></td>
							<th scope="row">수당</th>
							<td class="right"><?=number_format($data['pay02'])?></td>
							<th scope="row">국민연금</th>
							<td class="right"><?=number_format($data['deduct01'])?></td>
							<th scope="row">건강보험</th>
							<td class="right"><?=number_format($data['deduct02'])?></td>
						</tr>
						<tr>
							<th scope="row">식대</th>
							<td class="right"><?=number_format($data['pay03'])?></td>
							<!-- <th scope="row" class="cellselected02">급여소급</th>
							<td class="cellselected02 right">200,000 <button class="ico pop_02"></button></td> -->
                            <th scope="row">차량보조금</th>
							<td class="right"><?=number_format($data['pay04'])?></td>
							<th scope="row">고용보험</th>
							<td class="right"><?=number_format($data['deduct03'])?></td>
							<th scope="row">장기요양보험료</th>
							<td class="right"><?=number_format($data['deduct04'])?></td>
						</tr>
						<tr>
							<th scope="row">학자금지원</th>
							<td class="right"><?=number_format($data['pay05'])?></td>
							<th scope="row">연차수당</th>
							<td class="right"><?=number_format($data['pay06'])?></td>
							<th scope="row">소득세</th>
							<td class="right"><?=number_format($data['deduct05'])?></td>
                            <th scope="row">지방소득세</th>
							<td class="right"><?=number_format($data['deduct06'])?></td>
						</tr>
						<tr>
							<th scope="row">육아수당</th>
							<td class="right"><?=number_format($data['pay07'])?></td>
							<th scope="row">주말근무수당</th>
							<td class="right"><?=number_format($data['pay08'])?></td>
							<th scope="row">학자금상환액</th>
							<td class="right"><?=number_format($data['deduct07'])?></td>
							<th scope="row">건강보험 정산금</th>
							<td class="right"><?=number_format($data['deduct08'])?></td>
						</tr>
						<tr>
							<th scope="row">급여 소급액</th>
							<td class="right"><?=number_format($data['pay09'])?></td>
							<th scope="row">기타수당</th>
							<td class="right"><?=number_format($data['pay10'])?></td>
							<th scope="row">요양보험 정산금</th>
							<td class="right"><?=number_format($data['deduct09'])?></td>
							<th scope="row">국민연금 소급분</th>
							<td class="right"><?=number_format($data['deduct10'])?></td>
						</tr>
						<tr>
							<th scope="row">주식매수선택권행사이익</th>
							<td class="right"><?=number_format($data['pay11'])?></td>
							<th scope="row"></th>
							<td class="right"></td>
							<th scope="row">고용보험 정산금</th>
							<td class="right"><?=number_format($data['deduct11'])?></td>
							<th scope="row"></th>
							<td class="right"></td>
						</tr>
						<tr>
							<td class="center" colspan="4"><span class="label">지급(계)  :</span>  <?=number_format($data['pay12'])?>원</td>
							<td class="center" colspan="4"><span class="label">공제(계)  :</span>  <?=number_format($data['deduct12'])?>원</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- //정기급여 -->
			
			
		</div>
		<!-- // section pay-list -->
	</div>
	<!-- //content-primary -->
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(2)').addClass('active');
$('.depth02:eq(2)').find('li:eq(1)').addClass('active');

$('#btn-salary-search').click(function(e){
    e.preventDefault();
    var $this_year = $('#selectTag_year').val();
    var $this_month = $('#selectTag').val();
    var json_data = {
        year: $this_year,
        month : $this_month
    };
    $.ajax({
        url: '/@proc/ess/salarySearchProc.php',
        type: 'post',
        data: json_data,
        dataType: 'html',
        success: function(response){
            if(response == 'false'){
                alert('조회하신 날짜에는 급여가 없습니다.');
            }else{
                $('.js_year').text($this_year);
                $('.js_month').text($this_month);
                $('.data-table tbody').children('tr').remove();
                $('.data-table tbody').append(response);
            }
        },
        fail: function(error) {
            alert('조회하신 날짜에는 급여가 없습니다.');
        }
    });
});
</script>
