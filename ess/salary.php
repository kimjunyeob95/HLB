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
// echo('<pre>');print_r($_SESSION);echo('</pre>');
//보여줄 급여 쿼리
$query="select * from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False' and hu_showDate <= DATE_FORMAT(NOW(), '%Y-%m-%d %H') and hu_hss_seq={$mc_coseq}
order by hu_showDate desc limit 1 ) hu
on hu.hu_salaryDate_month = hue.hue_salaryDate_month and
hu.hu_hss_code = hue.hue_hss_code and
hu.hu_salaryDate_year = hue.hue_salaryDate_year where
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
//echo('<pre>');print_r($data);echo('</pre>');

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
			<h3 class="title"><?=$data['hu_salaryDate_month']?>월 급여명세서</h3> <!-- // 180104 텍스트 수정 -->
			<!-- <div class="reward-pay">
				<p>안녕하세요, 보상담당자 인사지원팀 김기아 대리입니다.<br>미세먼지의 영향이 지속될 것으로 보이니 건강에 유의하시기 바랍니다.미세먼지의 영향이 지속될 것으로 보이니 건강에 유의하시기 바랍니다.미세먼지의 영향이 지속될 것으로 보이니 건강에 유의하시기 바랍니다.미세먼지의 영향이 지속될 것으로 보이니 건강에 유의하시기 바랍니다.미세먼지의 영향이 지속될 것으로 보이니 건강에 유의하시기 바랍니다.미세먼지의 영향이 지속될 것으로 보이니 건강에 유의하시기 바랍니다.</p>
			</div> -->
			<!-- 171207 삭제 -->
			<!-- <div class="info">
				<span class="tag type01">안내사항</span>
				<p>국민연금 기준소득 상한액 상향 조정 : 보험료 상한액 月 189,450원 -> 195,300원 <span>※ 자세한 내용은 협조전 참고</span></p>
			</div> -->
			<!-- // 171207 삭제 -->
		</div>
	</div>
	<!-- content-primary -->
	<div id="content" class="content-primary">
		<h2 class="blind">최종 지급액</h2>
		<div class="section-wrap">
			<div class="section-left">
				<h3 class="content-title"><?=$data['hu_salaryDate_month']?>월 수령액</h3>
				<span class="notice">※ <?=$data['hu_showDate']?>시 지급기준</span>
				<ul class="data-list medium">
					<li>
						<div class="title">차인지급액 (지급계 - 공제계)</div>
						<div class="em weight"><em><?=number_format($data['total_pay'])?></em><span>원</span></div>
					</li>
					<li>
						<div class="title">지급(계)</div>
						<div><?=number_format($data['pay12'])?>원</div>
					</li>
					<li>
						<div class="title">공제(계)</div>
						<div><?=number_format($data['deduct12'])?>원</div>
					</li>
				</ul>
				<!-- <p class="account">※ 입금계좌 : <span id="banka">제일은행:13131*****</span> <span class="num" id="bankn"></span></p> -->
			</div>
			<div class="section-left">
				<h3 class="content-title">’<?=substr(date('Y'),2,4)?>년 누적지급액 </h3>
				<!-- <span class="notice">※ 2017.08.31 지급기준</span> -->
				<ul class="data-list medium">
					<li>
						<div class="title">누적지급액</div>
						<div class="em weight"><em><?=number_format($total_price)?></em><span>원</span></div>
					</li>
					<!-- 180104 추가 -->
					<li>
						<div class="title">지급(계)</div>
						<div><?=number_format($total_pay)?>원</div>
					</li>
					<li>
						<div class="title">공제(계)</div>
						<div><?=number_format($total_deduct)?>원</div>
					</li>
					<!-- // 180104 추가 -->
				</ul>
				<!-- 180104 삭제 -->
				<!-- <div class="notice-box">※ 누적지급액에는 복지포인트, 주간연속2교대포인트, <br>
    			독감예방접종 등 기타 지급항목도 포함됩니다.</div> -->
    			<!-- // 180104 삭제 -->
    			<div class="btn-area">
    				<!-- <a class="btn small type01" href="#">총보상명세서 바로가기</a> -->
					<a class="btn small type01" href="/ess/salarySearch">월별 지급내역 조회</a>
    			</div>
			</div>
		</div>
		<div class="section pay-list detaile">
			<h2 class="content-title"><?=$data['hu_salaryDate_month']?>월 상세내역</h2>
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
			<div class=" table-wrap">
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
		
		
		<!-- // 월근태내역 -->
		<!-- 판매실적-->
		<!-- <div class="section">
			<h2 class="content-title">판매실적</h2>
			<div class="table-wrap">
				<table class="data-table">
					<caption>판매 능률 수당 누진 목표미달에 대한 판매실적 정보</caption>
					<colgroup>
						<col style="width: 25%" />
						<col style="width: 25%" />
						<col style="width: 25%" />
						<col style="width: 25%" />
					</colgroup>
					<thead>
						<tr>
							<th scope="col">판매능률</th>
							<th scope="col">판매수당</th>
							<th scope="col">누진</th>
							<th scope="col">목표미달</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>9</td>
							<td>10</td>
							<td>1</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div> -->
		<!-- // 판매실적 -->
		
		<!-- 급여담당자 안내 -->
		<!-- <div class="section">
			<h2 class="content-title">급여담당자 안내</h2>
			<div class="charge-wrap">
				<div class="charge-info">
					<div class="charge-list list01">
						<h3>급여일반</h3>
						<h4>인사지원팀</h4>
						<ul>
							<li>최재호 대리 (T.02-3464-0000)</li>
							<li>우정민 사원 (T.02-3464-0000)</li>
						</ul>
					</div>
					<div class="charge-list list02">
						<h3>근태/특근/휴복직</h3>
						<h4>인사지원팀</h4>
						<ul>
							<li>곽태호 사원 (T.02-3464-0000)</li>
						</ul>
					</div>
					<div class="charge-list list03">
						<h3>건강보험/국민연금</h3>
						<h4>인사지원팀</h4>
						<ul>
							<li>김채경 사원 (T.02-3464-8988)</li>
						</ul>
					</div>
					<div class="charge-list list04">
						<h3>압류</h3>
						<h4>인사지원팀</h4>
						<ul>
							<li>박진아 대리 (T.02-3464-0000)</li>
						</ul>
					</div>
				</div>
				<div class="charge-info">
					<div class="charge-list list05">
						<h3>차량할부금</h3>
						<h4>특판팀</h4>
						<ul>
							<li>박현정 대리 (T.02-3464-0000)</li>
						</ul>
					</div>
					<div class="charge-list list06">
						<h3>학자금</h3>
						<h4>해당주관부서</h4>
						<ul>
							<li>사업장별 총무팀</li>
						</ul>
					</div>
					<div class="charge-list list07">
						<h3>우리사주</h3>
						<h4>주식관리팀</h4>
						<ul>
							<li>이선희 사원 (T.02-3464-8988)</li>
						</ul>
					</div>
					<div class="charge-list list08">
						<h3>어학교육</h3>
						<h4>글로벌교육팀</h4>
						<ul>
							<li>이지숙 사원 (T.02-3464-0000)</li>
						</ul>
					</div>
				</div>
			</div>
		</div> -->
		<!-- //급여담당자 안내 -->


	</div>
	<!-- //content-primary -->
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(2)').addClass('active');
$('.depth02:eq(2)').find('li:eq(0)').addClass('active');
</script>
