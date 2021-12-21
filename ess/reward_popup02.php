<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
?>
<!--  Body window-popup-->
<div class="window-popup">
	<!-- POPUP SIZE 1000 X 1545 -->
	<!-- popup-wrap -->
	<div class="popup-wrap">
		<h3 class="section-title">퇴직금 중도인출 신청 및 제출서류 안내</h3>
		<div class="tab-wrap">
			<ul class="tab">
				<li class="active"><a href="#info01">중도인출 신청 안내</a></li>
				<li><a href="#info02">제출서류 안내</a></li>
			</ul>
		</div>
		<!-- 중도인출 신청 안내 -->
		<div id="info01" class="tab-cont active">
			<h4>1. 취지</h4>
			<p class="cont">- 법정 중도인출 사유에 해당되는 직원에 한하여 퇴직금 중도인출 시행</p>
	 		<h4>2. 신청대상</h4>
	 		<div class="cont">- <span class="highlight">HBL 전 직원</span></div>
	 		<h4>3. 신청자격 제한</h4>
	 		<div class="cont">
	 			<ul>
	 				<li>가. 확정기여형 제도의 법정 중도인출 사유와 요건을 충족하지 못한 경우</li>
	 				<li>나. 퇴직기산 근속이 1년 미만인 경우 (입사 1년미만 및 중도인출 지급 후 1년이 지나지 않은 경우 등)</li>
	 				<li>다. 중간정산 규정 제9조에 해당되는 경우
	 					<p class="indent">- 중도인출 기준일 포함 이전 3개월간의 평균임금이 그 이전 3개월간의 평균임금보다 20%이상 상회할 경우 <br>
    					단, 개인평균임금이 20% 초과하는 경우, 해당 직원이 속한 반/지점의 전체 평균임금을 개인평균임금에서 뺀 후 계산)</p>
    					<span class="indent02">▶ '17년 8월 신청자의 경우 "5,6,7월"과 "2,3,4월"의 평균임금 비교</span>
    					<span class="indent03">예) 개인평균임금 30%, 반/지점 평균임금 15% 인 경우 → 30% - 15% = 15%,  중도인출 가능<br> 개인평균임금 30%, 반/지점 평균임금  5% 인 경우 → 30% - 5% = 25%,  중도인출 불가</span>
	 				</li>
	 			</ul>
	 		</div>
	 		<h4>4. 법정 중도인출 사유 (아래 사유에 한하여만 가능)</h4>
	 		<div class="cont">
		 		<ul>
		 			<li>가. 무주택자 본인의 본인명의(부부 공동명의 가능) 주택구입 (소유권 이전 등기 후 1개월까지 유효) <br>
		 			<span class="indent">- 전세금·임차보증금 가능 : 무주택자 본인이 주거를 목적으로 부담하는 경우</span></li>
		 			<li>나. 본인 또는 배우자, 부양가족(소득세법상)의 6개월 이상 장기요양</li>
		 			<li>다. 개인회생절차 및 파산선고의 결정(신청일 이전 5년이내 결정)</li>
		 			<li>라. 기타 대통령령이 정하는 천재지변(부양가족 포함)</li>
		 		</ul>
		 		<p class="highlight">※ 최종 서류심사 확정시까지 법정 중도인출 요건 및 자격 유지해야 함</p>
	 		</div>
	 		<h4>5. 중도인출 접수 및 지급일</h4>
	 		<ul class="cont">
	 			<li>가. 접수: 월 단위 접수 (매월 진행함) <br>
	 			<span class="indent">예) '17년 8월 접수 시 (8/1~8/31일 접수), 익월(9월)중순 지급, 평균임금은 신청일 기준 전월 말일 기준(5~7월)</span></li>
	 			<li>나. 지급예정일 : 매월 20일경 (은행영업일에 따라서 유동적일 수 있음)</li>
	 		</ul>
	 		<h4>6. 신청절차</h4>
	 		<ul class="cont">
	 			<li>가. 중도인출 신청서(사내용) 및 각 사유별 구비서류 작성 후 해당 인사팀으로 제출 <br>
	 			<span class="indent highlight">※ 신청서 양식 및 구비서류 안내는 첨부 참조</span></li>
	 			<li>나. 중도인출 담당자
	 				<ul class="indent">
	 					<li>- 일반/영업/기술직 : 인사지원팀 (02-3464-5345)</li>
	 					<li>- 소)생산직 : 소)인력운영팀 (02-801-3033)</li>
	 					<li>- 화)생산직 : 화)인력운영팀 (031-359-4055)</li>
	 					<li>- 광)생산직 : 광)인력운영팀 (062-370-3218)</li>
	 				</ul>
	 			</li>
	 		</ul>
 		</div>
		<!-- // 중도인출 신청 안내 -->

		<!--  제출서류 안내 -->
		<div id="info02" class="tab-cont">
			<div class="table-wrap">
				<table class="data-table">
					<caption>제출서류 안내표</caption>
					<colgroup>
						<col style="width: 6%">
						<col style="width: 8%">
						<col style="width: 30%">
						<col style="width: *">
						<col style="width: 6%">
					</colgroup>
					<thead>
						<tr>
							<th colspan="2">구분</th>
							<th>내용</th>
							<th>구비서류</th>
							<th>비고</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>공통<br> 서류</td>
							<td><span class="highlight">필수작성</span></td>
							<td class="left">서류의 굵은선 안의 내용 및 <br>
							본인 성명/사인 직접기재<br>
							: <span class="highlight">現 DB → DC → 중도인출신청 → DB 의 과정을
							진행하기 때문에 중도인출 사유에 관계없이
							공통서류는 모두 빠짐없이 작성/제출 합니다.</span>
							</td>
							<td class="left">1) 중도인출 신청서(사내용) <br>
							2) 제도변경 신청서 (DB → DC)<br>
							3) 퇴직급여 중도인출 신청서 (DC형)<br>
							4) 제도변경 신청서 (DC → DB)<br>
							5) 최초운용지시서(DC형) 및 개인(신용)정보 동의서<br>
							6) 본인 명의의 입금계좌 사본</td>
							<td>필수제출</td>
						</tr>
						<tr>
							<td rowspan="16">신청 <br>
							사유별<br>
							서류</td>
							<td rowspan="6">주택구입</td>
							<td class="left" rowspan="5">무주택자 본인이 본인소유의 주택구입 <br>
							(신청시점 : 매매계약서 체결일 ~ 소유권이전<br>
							 등기시1개월이내)<br>
							<span class="info-text">- 배우자,부모,자녀의 주택구입은 해당 없음<br>
							- 부부 공동명의로 주택구입은 가능<br>
							- 기존 주택 소유시 주택 매도 후 신청 가능<br>
							   (단, 등기부등본상 무주택기간 존재여부 확인 필수)</span></td>
							<td class="left" rowspan="5">1) 현 거주지의 주민등록등본<br>
							2) 현 거주지의 건물등기부등본 (현 거주지 주택 및 건물, <span class="highlight">"열람용" 제출 불가</span>)  <br>
							3) 재산세 과세 증명서 ("전국단위"로 발급 제출, <span class="highlight">"재산세(주택)" 내역</span>)<br>
							<span class="info-text indent">예시) "재산세(주택)" 세목에 대하여 과세(납세) 내역 상황 또는 납부없는 경우<br>
							납부 내역 없음 / 기간 : 전년도~당해년도로 발급(과세내역 있는 경우 해당 주택 <br>
							등기부등본 제출)</span>
							4) 신규 구입한 주택의 매매계약서 (명의 이전 이후 등기부등본 추후 제출)<br>
							5) <span class="highlight">신규 분양주택 명의 이전후 등기부등본 발급 불가능時</span><br>
					     <span class="indent02">-> 미납중도금 있는경우 미납내역서로 대체(건축회사발급)</span>
					     <span class="indent02">-> 미납중도금 없는경우 건축회사 작성 공문으로 대체</span>
					     <span class="indent03">*계약자 인적사항, 물건지 내역, 등기부등본 미발급 사유 기재</span>
							</td>
							<td>원본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td>사본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td class="left">주택구입 여부 확인 서류 <br>
							- 쌍방 직거래인 경우 : 부동산거래신고필증 제출<br>
							- 주택여부 확인 필요시 : 건축물관리대장 제출</td>
							<td class="left">1) 주택구입 : 부동산 매매계약서 또는 분양계약서<br>
							2) 주택신축 : 건축 설계서 또는 공사계약서(사본) 및<br>
							<span class="indent04">건축대수선 용도변경 허가서(원본)</span>
							3) 경매낙찰 : 낙찰허가결정문(원본)<br>
							4) 임대전환 : 분양전환계약서(조합원 가입에 의한 분담금 납입은 신청대상 불가)<br>
							5) 전세금&bullet;임차보증금 : 전세 및 임대차계약서</td>
							<td>사본</td>
						</tr>
						<tr>
							<td rowspan="6">전세금<br>
							임차보증금</td>
							<td rowspan="6" class="left">무주택자 본인이 주거를 목적으로 부담하는 경우<br>
							- 하나의 사업자에 근로하는 기간동안 1회 한정</td>
							<td rowspan="6" class="left">1) 현 거주지의 주민등록등본<br>
							2) 현 거주지의 건물등기부등본 (현 거주지 주택 및 건물, "열람용" 제출 불가)<br>
							3) 재산세 과세 증명서 ("전국단위"로 발급 제출, "재산세(주택)" 내역必)<br>
							4) 전세 및 임대차 계약서<br>
							5) 전세 및 임대차 주소지 건물등기부등본 ("열람용" 제출 불가)<br>
							6) 전세금 또는 임차보증금을 지급한 경우에는 지급영수증<br>
					     <span class="indent">("계약금 및 잔금송금영수증 제출")</span>
					     <span class="indent highlight">- 잔금지급후 신청시 잔금지급일로부터 1개월 이내</td></span>
							<td>원본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td>사본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td>원본</td>
						</tr>
						<tr>
							<td>6개월 이상<br> 요양</td>
							<td class="left">본인 또는 배우자<br>
							만 60세 이상 또는 만20세 이하 부양가족<br>
							(연말정산기준 준용)</td>
							<td class="left">1) 주민등록등본 또는 가족관계증명서(부양가족 대상 신청시)<br>
							2) "병명,병명코드, 6개월 이상 치료기간" 을 확인할 수 있는 서류<br>
							<span class="indent">(진단서, 의사소견서)</span>
							<span class="info-text">※ 현재 치료가 계속되고 있는 문구 확인 필요 <br>
							※ "향후 6개월 이상 치료(약물치료/요양/가료)를 요함" 이라는 문구를 반드시 포함 </span>
							</td>
							<td>원본</td>
						</tr>
						<tr>
							<td>개인회생</td>
							<td class="left"></td>
							<td class="left">1) 개인회생개시 결정문 <br>
					     <span class="indent">* 개인회생 변제계획안 : 변제기간 명시되어 있어야 함</span>
							2) 개인회생 변제계획인가 결정문 (변제계획 심사중인 경우에는 생략 가능) </td>
							<td>원본</td>
						</tr>
						<tr>
							<td>파산선고의<br> 결정</td>
							<td class="left"></td>
							<td class="left">1) 파산선고 결정문</td>
							<td>원본</td>
						</tr>
						<tr>
							<td>천재지변</td>
							<td class="left">고용노동부장관이 고시하는 경우 (현재 사례 없음)</td>
							<td class="left">1) 관공서 발행 증빙서류</td>
							<td>원본</td>
						</tr>
					</tbody>
				</table>
				<div class="highlight notice-text">※ 중도인출 신청사유별 제출 서류는 신청시점 기준 최근 3개월내 발급서류만 유효.</div>
			</div>
 		</div>
	</div>
	<!-- //popup-wrap -->
</div>
<!-- // Body window-popup-->
<!-- // WRAP -->
</body>
</html>

