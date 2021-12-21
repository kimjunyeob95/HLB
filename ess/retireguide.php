<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
?>

<div id="wrap" class="depth03">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->

<!-- CONTENT -->
<div id="container" class="retire-process">
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>HR 안내</h2>
		<ul class="lnb">
            <li><a href="#">HR안내</a></li>
            <li><a href="/ess/notice.php">공지사항</a></li>
            <!-- <li><a href="/ess/cornotice.php">법인별 주요사항</a></li> -->
			<li><a href="/ess/retireguide.php" class="active">퇴직안내</a></li>
            <?foreach ($list_gnb_menu as $val){?>
                <li><a href="/ess/page_view.php?seq=<?=$val['tn_seq']?>"><?=$val['tn_title']?></a></li>
            <?}?>
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">퇴직안내</h2>
		<h3 class="section-title">퇴직절차 안내</h3>
		<div class="retire-intro">
			<ul class="data-list">
				<li class="list01">
						<div class="text-wrap">
						<span class="num">01</span>
						<span class="ico"></span>
						<strong>부서장에게 퇴직의사 전달</strong>
						<p>퇴직일 및 인수인계 등 면담 시행</p>
					</div>
				</li>
				<li class="list02">
						<div class="text-wrap">
						<span class="num">02</span>
						<span class="ico"></span>
						<strong>소속부서에 사직원 제출</strong>
						<p>퇴직희망일 15일 전 제출</p>
						<span class="notice">※ 인사운영팀에 사본 송부</span>
						<span class="notice">※ 결재: 팀장전결(부서장 이상: 실장)</span>
					</div>
				</li>
				<li class="list03">
						<div class="text-wrap">
						<span class="num">03</span>
						<span class="ico"></span>
						<strong>소속부서-> 인사운영팀에 통보</strong>
						<p>사직원 : 제출일 7일이내 송부(원본)</p>
						<p>협조전 : 퇴직관련 공문발송</p>
						<span class="notice">※ 협조처(인사지원팀, 주무팀)</span>
					</div>
				</li>
				<li class="list04">
						<div class="text-wrap">
						<span class="num">04</span>
						<span class="ico"></span>
						<strong>설문조사/면담 시행(인사운영팀)</strong>
						<p>퇴직 설문조사/면담 시행</p>
						<span class="notice">※ 퇴직 7일 전 방문</span>
					</div>
				</li>
				<li class="list05">
						<div class="text-wrap">
						<span class="num">05</span>
						<span class="ico"></span>
						<strong>퇴직발령(인사운영팀)</strong>
						<p>퇴직보고 후 사내 게시<br> (퇴직 전 1~3일전)</p>
						<span class="notice">※ 결재: 팀장전결(부서장 이상: 실장)</span>
					</div>
				</li>
				<li class="list06">
						<div class="text-wrap">
						<span class="num">06</span>
						<span class="ico"></span>
						<strong>퇴직금 지급(인사지원팀)</strong>
						<p>퇴직연금(IRP)계좌로 입금</p>
						<span class="notice">※ 퇴직 후 14일 이내 지급</span>
					</div>
				</li>
			</ul>
		</div>
		<h3 class="section-title first">퇴직금 수령 및 근로정산</h3>
		<h4 class="first">1. 퇴직금 수령</h4>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>퇴직금 수령 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: *" />
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">퇴직연금</th>
						<td class="left">
							<ul class="data-list">
								<li>- IRP 계좌개설 : 개인이 원하는 금융기관(은행, 증권사 등)에서 개설 가능 </li>
								<li>- IRP 계좌사본 회사제출 : 사직원 제출시 함께 제출 (또는 인사지원팀 송부)</li>
							</ul>
							<div class="notice-box">
								<strong>※ IRP계좌란?</strong>
								<p>개인형 퇴직연금(IRP: Individual Retirement Pension)으로,  <br>
                           근로자가 퇴직했을 경우 퇴직금을 바로 사용하지 않고 본인명의의 퇴직계좌에 적립 후<br>
                           연금 등 보관/운용할 수 있도록 한 퇴직전용 계좌입니다.</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">퇴직연금 수령</th>
						<td class="left">
							 - IRP 개설 금융기관 방문 후 수령방법 결정
							 <ul class="data-list">
							 	<li><span>⑴ 일시금 수령 : IRP 계좌 해지 후 1~3 영업일 뒤 인출 가능</span></li>
							 	<li><span>⑵ 연금 수령    : 수령기간 및 방법에 대해 해당 금융기관 내방 후 설정</span></li>
							 </ul>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4>2. 근로정산</h4>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>근로정산 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: *" />
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">임금성</th>
						<td class="left">
							<ul class="data-list">
								<li>- 급여 : 잔여 임금 급여계좌 송금</li>
	 							<li>- 연&middot;월차 : 미사용 연·월차(전년도 연차, 당해 월차)수당 지급</li>
	 						</ul>
	 						<span>※ 간부사원 : 연차만 해당</span>
 						</td>
					</tr>
					<tr>
						<th scope="row">병원비 학자금</th>
						<td class="left">- 퇴직 전 발생한 것 중 신청내역 限 근로정산
 						<span>※ 담당 : 총무팀 이형배SW(병원비) / 이기범SW(학자금)</span></td>
					</tr>
					<tr>
						<th scope="row">건강보험</th>
						<td class="left">
							- 당해 년도 보험료 납입총액과 총소득에 대해 정산 후 추가징수 or 환급
							<span>※ 담당 : 인사지원팀 김채영SW</span>
						</td>
					</tr>
					<tr>
						<th scope="row">연말정산</th>
						<td class="left">
							- 12/31일 퇴사자限 연말정산 가능(퇴직 전 부서에 연말정산 서류 제출必)
							<span>※ 그 외 경우 종합소득세신고(5月)시 개별적으로 시행</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h3 class="section-title">사회보험 후속조치</h3>
		<h4 class="first">1. 국민건강보험</h4>
		<ul class="data-list">
			<li>- 퇴직 후 타사 취업시 직장가입자로 자동연계,  개인사업(임대소득 포함)시 지역가입으로 전환</li>
			<li>- 그 외 경우 1)피부양자 등재, 2)지역가입, 3)임의가입 중 한가지 선택 후 재가입이 필요</li>
		</ul>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>국민건강보험 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: *" />
				</colgroup>
				<tbody>
					<tr>
						<th>피부양자<br> 등재</th>
						<td class="left">- 가족 중 직장보험가입자가 있는 경우, 해당가족 재직회사를 통해 등재신청 <br>
					   <p>: 가족범위 : 배우자, 자녀, 사위, 며느리, 손자, 손녀 </p>
					  <span>※ 가족대상이 비동거시 등재요건 확인要(국민건강보험공단 문의)</span></td>
					</tr>
					<tr>
						<th>지역가입</th>
						<td class="left">- 개인소득 있거나 직계가족 중 직장보험가입자가 없는 경우 <br>
					   <p>: 당사 퇴직처리(직장보험 자격상실시 공단 자동가입됨</p>
					  <span>※ 부동산, 자동차, 세대원수, 세대원 자산가치 등을 반영하여 보험료 산정</span></td>
					</tr>
					<tr>
						<th>임의가입</th>
						<td class="left">- 현 보험료 수준으로 보험자격 유지 가능(기납부 보험료<지역보험료 경우限) <br>
					   <p>: 국민건강보험공단에 가입신청(최대 2년)</p>
					   <span>※ 퇴직 前 3개월 평균급여로 보험료 산정 후 본인부담분만 납부가능</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<p class="em weighty">※ 국민건강보험공단 문의전화(☎) : 1577-1000</p>
		<h4>2. 국민연금</h4>
		<ul class="data-list">
			<li>- 퇴직 후 타사 취업시 직장가입자로 자동연계,  개인사업(임대소득 포함)시 지역가입으로 전환</li>
			<li>- 10년 이상 가입한 경우 만 62~65세가 되는 해부터 평생동안 지급</li>
		</ul>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>국민연금 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: 21%" />
					<col style="width: 21%" />
					<col style="width: 21%" />
					<col style="width: *" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col" rowspan="2">수급개시연령</th>
						<th scope="col" colspan="4">출생연도</th>
					</tr>
					<tr>
						<th scope="col">57~60년생</th>
						<th scope="col">61~64년생</th>
						<th scope="col">65~68년생</th>
						<th scope="col">69년생 이후</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">노령연금</th>
						<td>62세</td>
						<td>63세</td>
						<td>64세</td>
						<td>65세</td>
					</tr>
					<tr>
						<th scope="row">조기노령연금</th>
						<td>57세</td>
						<td>58세</td>
						<td>59세</td>
						<td>60세</td>
					</tr>
				</tbody>
			</table>
		</div>
		<p class="em weight">※ 국민연금 문의전화(☎) : 국번없이 1355</p>
		<h4>3. 고용보험 실업급여</h4>
		<p>- 퇴직 후 재취업 활동을 하는 기간에 소정의 급여를 지급함으로써  생활의 안정을 도와주며 재취업의 기회를 지원해주는 제도</p>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>고용보험 실업급여 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: *" />
					<col style="width: 15%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">구분</th>
						<th scope="col">세부내용</th>
						<th scope="col">비고</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">수급자격</th>
						<td class="left">
							<ul class="data-list">
								<li>- 이직일 이전 18개월간 180일 이상 고용보험 가입자</li>
								<li>- 이직사유가 비자발적 사유인 경우 수급가능</li>
							</ul>
						</td>
						<td>타사취업 및 <br> 해고시 대상제외</td>
					</tr>
					<tr>
						<th scope="row">지급액</th>
						<td class="left">- 1일 최대 지급액 : 50,000원</td>
						<td></td>
					</tr>
					<tr>
						<th scope="row">지급기간</th>
						<td class="left">- 연령 및 가입기간에 따라 최대 240일까지 수급가능
							<table class="data-table">
								<caption>data-table 기본형 가이드</caption>
								<colgroup>
									<col style="width: 16.666%" />
									<col style="width: 16.666%" />
									<col style="width: 16.666%" />
									<col style="width: 16.666%" />
									<col />
								</colgroup>
								<thead>
									<tr>
										<th scope="col">가입기간<br>/연령</th>
										<th scope="col">1년 미만</th>
										<th scope="col">1년 이상<br> 3년 미만</th>
										<th scope="col">3년 이상<br> 5년 미만</th>
										<th scope="col">5년 이상<br>10년 미만</th>
										<th scope="col">10년 이상</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th scope="row">30세 미만</th>
										<td>90일</td>
										<td>90일</td>
										<td>120일</td>
										<td>150일</td>
										<td>180일</td>
									</tr>
									<tr>
										<th scope="row">30세<br> ~49세</th>
										<td>90일</td>
										<td>120일</td>
										<td>150일</td>
										<td>180일</td>
										<td>210일</td>
									</tr>
									<tr>
										<th scope="row">50세 이상</th>
										<td>90일</td>
										<td>150일</td>
										<td>180일</td>
										<td>210일</td>
										<td>240일</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td>연령은 퇴직당시<br>만 나이</td>
					</tr>
					<tr>
						<th scope="row">실업급여<br> 신청</th>
						<td class="left">
							<ul class="data-list">
								<li>- 장소 : 거주지 관할 고용센터 방문 신청 </li>
								<li>- 시기 : 퇴직 이후 1년간 실업급여 신청가능</li>
							</ul>
						</td>
						<td></td>
					</tr>
					<tr>
						<th scope="row">이직신고<br> (이직확인서)</th>
						<td class="left">- 인사지원팀에서 퇴직일 익월 15일까지 통보 <br>
						<span>(고용지원센터 심사완료시 실업급여 수급)</span></td>
						<td>인사지원팀<br> 김채영SW</td>
					</tr>
				</tbody>
			</table>
		</div>
		<p class="em weight">※ 고용노동부 문의전화(☎) : 국번없이 1350</p>
		<h3 class="section-title">개인연금 안내</h3>
		<h4>1. 개인연금 유지/해지안내</h4>
		<p>- 직원들의 노후생활 안정을 위해 ’12년 11월부터 도입된 제도로써, 퇴직 후 1)<em>계속가입</em> 또는 2)<em>해지</em> 선택가능</p>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>고용보험 실업급여 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: 42%" />
					<col  />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">구분</th>
						<th scope="col">라이프</th>
						<th scope="col">외환은행</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">계속가입<br> (유지)</th>
						<td class="left">- 콜센터 통화
						<span>: 본인계좌 자동이체 변경신청</span></td>
						<td class="left">-  지점 내방 신청
  						<span>: 신분증 지참</span></td>
					</tr>
					<tr>
						<th scope="row">최소<br> 납입기간</th>
						<td class="left">- 10년</td>
						<td class="left">- 별도 없음</td>
					</tr>
					<tr>
						<th scope="row">보험<br> 유지기간</th>
						<td class="left">- 10년</td>
						<td class="left">- 5년</td>
					</tr>
					<tr>
						<th scope="row">연금수령</th>
						<td class="left">
							<ul class="data-list">
								<li>- 연금개시 : 만 60세부터 개시</li>
								<li>- 개인연금수령 형태
									<span>: 연금/ 일시금 중 택 1</span>
								</li>
							</ul>
						</td>
						<td class="left">
							<ul class="data-list">
								<li>- 연금개시 : 만 55세부터 개시</li>
 								<li>- 개인연금수령 형태
									<span>: 연금/ 일시금 중 택 1</span>
 								</li>
 							</ul>
 						</td>
					</tr>
					<tr>
						<th scope="row">해지</th>
						<td class="left">- 콜센터 전화 후 해지신청
  						<span>: 통장사본</span>
  						<p>※ 해약 환급금관련 라이프 문의</p></td>
						<td class="left">-  지점 내방 신청
  							<span>: 구비서류 : 신분증, 경력증명서</span></td>
					</tr>
					<tr>
						<th scope="row">문의전화</th>
                        <td class="left"></td>
                        <td class="left"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<ul class="data-list">
			<li class="em weight">※ 가입한 상품확인 : 인사지원팀 최재호DR(02-3464-5355)</li>
			<li class="em weight">※ 경력증명서 발급 : 총무팀 조기숙DR(02-3464-5247)</li>
		</ul>
		<h3 class="section-title">잔여 포인트 사용안내</h3>
		<h4>1. 명절선물비(복지포인트/사이버머니)</h4>
		<p>- 2년에 한번(홀수년) 잔여 복지포인트 정산 →  퇴직 전, 잔여포인트 사용 or 환급신청</p>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>명절선물비 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: 42%" />
					<col  />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">구분</th>
						<th scope="col">복지포인트</th>
						<th scope="col">사이버머니</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">잔여분 확인</th>
						<td class="left">- 복지카드(M2카드)소지시
   					<span>: 카드 홈페이지 포인트 조회</span></td>
						<td class="left">- 직원선물사이트
   					<span>: <a href="https://gift.e-.com" target="_blank">https://gift.e-.com</a></span></td>
					</tr>
					<tr>
						<th scope="row">환급방법</th>
						<td class="left">- 카드 고객센터로 환급신청
						<span>: 연결된 계좌로 입금 시행</span></td>
						<td class="left">- 1만원 이상시 상품권 지급(만원권)
  							<span>: 각 사업장별 담당자 개별 연락</span></td>
					</tr>
					<tr>
						<th scope="row">문의전화</th>
						<td class="left">- 카드 고객센터(1577-6000)</td>
						<td class="left">- 각 사업장 총무 주관부서 직원선물 담당자</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4>2. 주간연속 2교대포인트</h4>
		<p>- 퇴직일(당일)까지 주간연속2교대 사용가능</p>
		<span class="indent">: 퇴직일 이후 잔여 포인트 소멸됨</span>
		<p class="em weighty">※ 주간연속2교대포인트몰 : <a href="https://benecafe.co.kr" target="_blank">https://benecafe.co.kr</a></p>
		<h3 class="section-title">기타 안내</h3>
		<h4>1. 직원용차량, 우리사주, 공로메달</h4>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>직원용차량, 우리사주, 공로메달 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: *" />
					<col style="width: 20%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">구분</th>
						<th scope="col">세부내용</th>
						<th scope="col">비고</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">직원용차량</th>
						<td class="left">- 의무보유기간(2년) 내 전매 금지
   						<span>: 전매시 할인금액 변상예상</span></td>
						<td class="left">- 의무보유기간 확인
						  <span>특판팀 : 위석원CJ <br>
						   (02-510-9298)</span>
						</td>
					</tr>
					<tr>
						<th scope="row">우리사주</th>
						<td class="left">- 우리사주(20기) 대출금 상환 및 주식인출 <br>
						   <span>방법 : 한국증권금융으로 대출금 상환 후 담당자 연락  <br>
						           → HMC투자증권을 통해 주식 인출</span>
						  <p>※ 우리사주(18기,19기) 주식인출은 담당자 문의</p></td>
						<td class="left">- 담당자
							<span>: 투명경영지원팀 <br>
						   이선희SW<br>
						   (02-3464-5464)</span>
						</td>
					</tr>
					<tr>
						<th scope="row">공로메달</th>
						<td class="left">
							<ul class="data-list">
								<li>- 대상 : 20년 이상 근속 후 정상퇴직자</li>
								<li>- 내용 : 공로메달(금 5돈)</li>
							</ul>
						</td>
						<td class="left">- 담당자
							<span>: 총무팀 노회룡GJ <br>
						   (02-3464-5249)</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4>2. 시스템권한 및 사무용품 반납, 보안절차 수행</h4>
		<div class=" table-wrap">
			<table class="data-table">
				<caption>시스템권한 및 사무용품 반납, 보안절차 수행 표</caption>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: *" />
					<col style="width: 20%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">구분</th>
						<th scope="col">세부내용</th>
						<th scope="col">비고</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">인사</th>
						<td class="left">
							<ul class="data-list">
								<li>- 시스템 권한은 퇴직과 동시에 종료됨(사전 정리要)</li>
								<li>- 사원증 및 배지(Badge) 반납</li>
							</ul>
						</td>
						<td class="left"></td>
					</tr>
					<tr>
						<th scope="row">총무</th>
						<td class="left">- PC(테스크탑/노트북)반납관련 담당자에게 통보
   					<span>: 메일통보(사번, 성명, 부서명, 퇴직예정일)</span></td>
						<td class="left">- 각 사업장별 총무부서</td>
					</tr>
					<tr>
						<th scope="row" rowspan="2">보안</th>
						<td class="left">
							- 사직원 제출시 보안관련 서류 작성 제출
							<ul class="data-list">
								<li><span>: 영업비밀보호유지서약서</span></li>
								<li><span>: 업무자료반납 및 파기사실 확인서</span></li>
							</ul>
						</td>
						<td class="left">- 보안관리팀 검토</td>
					</tr>
					<tr>
						<td class="left">- OTP(하드웨어) 반납<span>(보안관리팀 김대용DR)</span></td>
						<td class="left">- 보안관리팀 김대용DR</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(3)').addClass('active');
$('.depth02:eq(3)').find('li:eq(2)').addClass('active');
</script>
