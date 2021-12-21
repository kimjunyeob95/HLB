<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$mmseq = $_SESSION['mmseq'];
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$step = get_member_step($db,$mmseq);
$member_info = get_member_info($db,$mmseq); // 개인정보
$family_list = get_family_list($db,$mmseq); // 가족사항
$certificate_list = get_certificate_list($db,$mmseq); // 어학 / 자격증
$career_list = get_career_list($db,$mmseq); // 경력사항
$education_list = get_education_list($db,$mmseq); // 학력사항
$appointment_list = get_appointment_list($db,$mmseq); // 발령
$activity_list = get_activity_list($db,$mmseq); // 교육 / 활동
$premier_list = get_premier_list($db,$mmseq); // 수상
$paper_list = get_paper_list($db,$mmseq); // 논문 / 저서
$project_list = get_project_list($db,$mmseq); // 프로젝트
$punishment_list = get_punishment_list($db,$mmseq); // 상벌
$title = '개인정보';
$teb_seq = 4;
// echo('<pre>');print_r($member_info);echo('</pre>');
?>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<style>
    .emergency{display: inline-block; width: 20%;}
    .emergency.tel{display: inline-block; width: 50%;}
    .emergency span{vertical-align: sub;}
    .emergency .input-text{max-width: 80%;}
    .emergency.tel .input-text{max-width: 50%;}
</style>
<!-- WRAP -->
<div id="wrap" class="depth-main newcomer">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/new_head.php'; ?>
<!-- CONTENT -->
<div id="container" class="newcomer-info">
	<div id="content" class="content-primary">
        
		<!-- 개인정보 -->
		<div class="personal-info">
            <? include $_SERVER['DOCUMENT_ROOT'].'/new/info_link.php'; ?>
			<!-- 180108 추가 -->
			<div class="alert-text">
				<strong>* 표시는 필수 입력항목입니다.</strong>
				<!-- <p><span class="ico info02"></span>표시에 마우스를 올리면 정보를 보실 수 있습니다.</p> -->
			</div>
			<!-- // 180108 추가 -->
			<div class="table-wrap">
				<table class="data-table left">
					<caption>개인정보 입력사항표</caption>
					<colgroup>
						<col style="width: 12%" />
						<col style="width: 7%" />
						<col style="width: 14%" />
						<col style="width: 7%" />
						<col style="width: 14%" />
						<col style="width: *" />
					</colgroup>
					<tbody>
                        <tr>
							<th scope="row">사원번호</th>
							<td colspan="4"><input type="text" class="input-text" name="" disabled value="<?=$member_info['mc_code']?>"></td>
							<td><div class="notice">*사원번호는 로그인시 필요하니 잊지않도록 주의해 주세요</div></td>
						</tr>
						<tr>
							<th scope="row">성명(한글)</th>
							<td colspan="4"><?=$enc->decrypt($member_info['mm_name'])?></td>
							<td><div class="notice">*성명은 인사기록카드의 기초자료로 사용되오니 정확히 기재해 주세요</div></td>
						</tr>
						<tr>
							<th scope="row">성명(영문)</th>
							<td colspan="4"><?=$member_info['mm_en_name']?></td>
							<td><div class="notice">*여권과 동일하게(띄어쓰기, 대소문자 구분 포함) 입력해 주시기 바랍니다
								<!-- 말풍선 -->
								<!-- <div class="balloon-word">
									<span class="ico-info"></span>
									<div class="text-box">
										<p>입사 후 명함제작, 인사기록카드 <br>
										및 해외 출장 신청을 위한 기초자료로<br>
										사용됩니다. <br><br>

										여권이 없는 경우에는 입력하신<br>
										영문 성명을 추후 여권을 발급하실 때<br>
										동일하게 입력하시기 바랍니다.</p>
									</div>
								</div> -->
								<!-- // 말풍선 -->
							</div></td>
						</tr>
						<tr class="attach">
							<th scope="row">사진</th>
							<td colspan="4">
								<div>
									<div class="thumb"><img src="<?=$member_info['mm_profile']?>"></div>
								</div>
							</td>
							<td>
								<ul class="data-list">
									<li>*사원증 제작, 인사기록카드 등에 활용되오니 단정한 차림의 사진을 업로드해주세요</li>
									<li>*사진은 여권용 사이즈로 촬영하여 jpg, jpeg 파일 형태로 업로드해주시길 바랍니다</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th scope="row">생일(실제)</th>
							<td colspan="4"><?=substr($member_info['mm_birth'],0,10)?></td>
							<td><div></div></td>
						</tr>
						<tr>
							<th scope="row">주민등록번호</th>
							<td colspan="4"><?=$enc->decrypt($member_info['mm_serial_no'])?></td>
							<td><div></div></td>
						</tr>
						<tr>
							<th scope="row">국적</th>
							<td colspan="4"><?=getCountryText($member_info['mm_country'])?></td>
							<td><div></div></td>
                        </tr>
                        <tr>
							<th scope="row">거주 국가</th>
							<td colspan="4"><?=getCountryText($member_info['mm_from'])?></td>
							<td><div></div></td>
                        </tr>
                        <tr>
							<th scope="row">우편번호</th>
							<td colspan="5"><?=$member_info['mm_post']?></td>
                        </tr>
                        <tr>
							<th scope="row">주소</th>
							<td colspan="5"><?=$enc->decrypt($member_info['mm_address'])?></td>
                        </tr>
                        <tr>
							<th scope="row">상세주소</th>
							<td colspan="5"><?=$enc->decrypt($member_info['mm_address_detail'])?></td>
						</tr>
						<tr>
							<th scope="row">휴대폰</th>
							<td colspan="4"><?=$enc->decrypt($member_info['mm_cell_phone'])?></td>
							<td><div class="notice">*휴대폰은 입사 후 명함제작 및 인사기록카드의 기초자료로 사용됩니다
								<!-- 말풍선 -->
                                <!-- <div class="balloon-word">
									<span class="ico-info"></span>
									<div class="text-box">
										<p>입사 후 휴대폰 번호를 <br>
										변경하신다면, 새로운 번호를<br>
										재등록 하시기 바랍니다.</p>
									</div>
								</div> -->
								<!-- // 말풍선 -->
							</div></td>
						</tr>
                        <tr>
							<th scope="col">성별</th>
							<td colspan="5"><?=$gender[$member_info['mm_gender']]?></td>
                        </tr>
                        <tr>
							<th scope="row">이메일 주소</th>
							<td colspan="4"><?=$enc->decrypt($member_info['mm_email'])?></td>
							<td><div class="notice"></div></td>
                        </tr>
                        <tr>
							<th scope="row">비상 연락처</th>
							<td colspan="5">
                                관계 : <?=$member_info['mm_prepare_relation']?>&nbsp;&nbsp;&nbsp;
                                연락처 : <?=$enc->decrypt($member_info['mm_prepare_phone'])?>
							</td>
						</tr>
					</tbody>
				</table>
                <?if(!empty($member_info['mm_arm_type']) || !empty($member_info['mm_arm_reason']) || !empty($member_info['mm_arm_group']) || !empty($member_info['mm_arm_class'])
                || !empty($member_info['mm_arm_discharge']) || !empty($member_info['mm_arm_sdate']) || !empty($member_info['mm_arm_edate'])){?>
                <div class="section-wrap">
                    <h3 class="section-title">병력</h3>
                    <!-- 병력 사항 -->
                    <div id="tab-army" class="tab-cont">
                        <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                            <table class="data-table left">
                                <caption>병력</caption>
                                <colgroup>
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col">병역구분</th>
                                        <th scope="col">입대일</th>
                                        <th scope="col">제대일</th>
                                        <th scope="col">군별</th>
                                        <th scope="col">계급</th>
                                        <th scope="col">병과</th>
                                        <th scope="col">사유(면제 및 기타)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="insert" class="center">
                                            <?=$arm_type[$member_info['mm_arm_type']]?>
                                        </td>
                                        <td scope="insert" class="center"><?=substr($member_info['mm_arm_sdate'],0,10)?></td>
                                        <td  scope="insert" class="center"><?=substr($member_info['mm_arm_edate'],0,10)?></td>
                                        <td scope="insert" class="center">
                                            <?=$arm_group[$member_info['mm_arm_group']]?>
                                        </td>
                                        <td scope="insert" class="center">
                                            <?=$arm_class[$member_info['mm_arm_class']]?>
                                        </td>
                                        <td scope="insert" class="center">
                                            <?=$member_info['mm_arm_discharge']?>
                                        </td>
                                        <td scope="insert" class="center"><?=$member_info['mm_arm_reason']?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 병력 사항 -->
                </div>
                <?}?>

                <div class="section-wrap">
                    <h3 class="section-title">장애정보 / 국가보훈정보</h3>
                    <table class="data-table left" style="border-top:1px solid #d1d1d1;">
                        <caption>장애정보 / 국가보훈정보</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">장애여부</th>
                                <th scope="col">장애구분</th>
                                <th scope="col">장애등급</th>
                                <th scope="col">보훈여부</th>
                                <th scope="col">보훈구분</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="insert" class='center'>
                                <?=$disorder_type_1[$member_info['mm_disorder_1']]?>
                            </td>
                            <td scope="insert" class="center">
                                <?=$disorder_type_2[$member_info['mm_disorder_2']]?>
                            </td>
                            <td scope="insert" class='center' >
                                <?=$disorder_type_3[$member_info['mm_disorder_3']]?>
                            </td>
                            <td scope="insert" class='center'>
                                <?=$nation_type_1[$member_info['mm_nation_1']]?>
                            </td>
                            <td scope="insert" class="center">
                                <?=$nation_type_2[$member_info['mm_nation_2']]?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>                          
			</div>
		</div>
		<!-- // 개인정보 -->

        <?if(!empty($family_list)){?>
        <!-- 가족정보 -->
		<div class="personal-info">
			<h2 class="content-title">가족사항</h2>
			<div class="section">
				<h3 class="section-title">가족사항</h3>
				<div class="table-wrap family">
                    <table class="data-table table-family-list">
                            <caption>가족사항 입력표</caption>
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
                            <?foreach ($family_list as $val){?>
                                <tr>
                                    <td><?=$enc->decrypt($val['mf_name'])?></td>
                                    <td>
                                        <?if($val['mf_relationship']=='1'){?>부<?}?>
                                        <?if($val['mf_relationship']=='2'){?>모<?}?>
                                        <?if($val['mf_relationship']=='3'){?>형제<?}?>
                                        <?if($val['mf_relationship']=='4'){?>자매<?}?>
                                        <?if($val['mf_relationship']=='5'){?>조모<?}?>
                                        <?if($val['mf_relationship']=='6'){?>조부<?}?>
                                        <?if($val['mf_relationship']=='7'){?>외조모<?}?>
                                        <?if($val['mf_relationship']=='8'){?>외조부<?}?>
                                        <?if($val['mf_relationship']=='9'){?>배우자<?}?>
                                        <?if($val['mf_relationship']=='10'){?>자녀<?}?>
                                    </td>
                                    <td><?=$enc->decrypt($val['mf_birth'])?></td>
                                    <td>
                                        <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>대상<?}?>
                                        <?if($val['mf_allowance']=='F'){?>비대상<?}?>
                                    </td>
                                    <td>
                                        <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>동거<?}?>
                                        <?if($val['mf_together']=='F'){?>비동거<?}?>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
				</div>
			</div>
            <?}?>
            <?if(!empty($member_info['mm_file1_name']) || !empty($member_info['mm_file2_name']) || !empty($member_info['mm_file3_name'])){?>
			<div class="section">
				<h3 class="section-title">증빙서류 첨부 <span>(가족관계증명서, 진료비 자동신청 동의서)</span></h3>
				<div class="btn-aside">
                    <!-- <a class="btn type01 small add-file-family" href="#">파일추가</a>
                    <a class="btn type01 small remove-file-family" href="#">파일삭제</a> -->
					<!-- <a class="btn type01 small" href="#">파일첨부</a> -->
				</div>
				<div class="table-wrap">
					<table class="data-table">
						<caption>가족사항 입력표</caption>
						<colgroup>
							<col style="width: *" />
						<thead>
							<tr>
								<th scope="col">파일명</th>
							</tr>
						</thead>
						<tbody>
                            <tr>
                                <td>주민등록등본</td>
								<td class="left"><?=$member_info['mm_file1_name']?></td>
							</tr>
                            <tr>
                                <td>가족관계증명서</td>
								<td class="left"><?=$member_info['mm_file2_name']?></td>
							</tr>
                            <tr>
                                <td>기타</td>
								<td class="left"><?=$member_info['mm_file3_name']?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
            <?}?>
			<ul class="data-list">
				<li>- 가족사항은 입사 후 당사에서 제공하는 급여/복리후생지원(가족수당, 진료비 지원, 연말정산 등)을 위한 기초자료입니다. <br>
 				 반드시 필요한 정보이므로 첨부하는 본인 기준 가족관계증명서와 일치하는 정보만 정확히 작성하여 주시길 바랍니다. (가족관계증명서 본인 기준 外 가족은 입사 후에 개별 등록 바랍니다.)</li>
				<li>- 인적공제 여부는 하단의 [인적공제가족 등록 기준 안내]를 참고하여 작성해주시기 바랍니다. <br>
  				동거 및 인적공제 여부는 연말정산 기초자료로서 현재 기준으로 작성하여 주시고 차후 변경 시에는 그룹 인사정보시스템을 통해 수정이 가능합니다.</li>
				<li>- 가족관계 증명서 첨부는 [온라인 민원24(<a href="" target="_blank">www.minwon.go.kr</a>) > 가족관계 증명서 검색 > 출력] 에서 확인하실 수 있습니다.</li>
				<!-- <li>- 진료비 자동신청 동의서 동의 체크 시 저장 후 동의서 양식 출력 후 신청 대상 (가족) 본인의 자필 서명 후 연수 교육 시 제출 바랍니다.</li> -->
			</ul>
			<div class="notice-box">
				<!-- <h4>[가족진료비 지원 신청 안내]</h4>
				<ul>
					<li> - 진료비는 부모, 배우자, 자녀에 한하여 지원됩니다.</li>
					<li>- 진료비 자동 동의 신청을 하실 경우 회사에서 규정한 복지 규정에 의해 대상 가족 분들의 진료비 지원분은 건강보험공단에서 자동으로 계산되어<br> 분기 1회 급여와  함께 지급됩니다. <br> 자동동의를 하지 않으신 분들도 진료비를 정산 받으실 수 있지만 본인 및 해당 가족 분들의 모든 진료비 영수증을 매월 제출하셔야지만 정산이 가능합니다</li>
					<li>- 진료비 지원 관련 세부 규정은 아래 진료비 지원 규정을 참고하시길 바랍니다.</li>
				</ul> -->
				<h4>[인적공제 대상 등록 기준 안내]</h4>
				<p> - 인적공제 대상자는 생계를 같이하는 가족으로 연간소득합계액이 100만원 이하이며 장애자를 제외한 만 20세 이하 만 60세 이상 나이요건을 갖춘 사람에 한합니다.</p>
			</div>
		</div>
		<!-- // 가족정보 -->

        <!-- 추가사항 -->
		<div class="personal-info">
            <h2 class="content-title">추가사항</h2>
            <?if(!empty($education_list)){?>
            <div class="section">
				<h3 class="section-title">학력사항</h3>
				<div class="table-wrap">
					<table class="data-table table-form-education">
						<caption>학력사항 입력표</caption>
						<colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">입학일</th>
                                <th scope="col">졸업일</th>
                                <th scope="col">학교명</th>
                                <th scope="col">학교등급</th>
                                <th scope="col">전공</th>
                                <th scope="col">학위</th>
                                <th scope="col">졸업구분</th>
                                <th scope="col">주야간구분</th>
                                <th scope="col">기타</th>
                            </tr>
                        </thead>
						<tbody>
                            <?foreach ($education_list as $key =>$val){?>
                                <tr>
                                    <td><?=substr($val['me_sdate'],0,10)?></td>
                                    <td>
                                        <?=substr($val['me_edate'],0,10)?>
                                    </td>
                                    <td class="insert"><?=$val['me_name']?></td>
                                    <td>
                                        <?if($val['me_level']=='1' || empty($val['me_level'])){?>고등학교<?}?>
                                        <?if($val['me_level']=='2'){?>전문대학<?}?>
                                        <?if($val['me_level']=='3'){?>대학교<?}?>
                                        <?if($val['me_level']=='4'){?>대학원<?}?>
                                    </td>
                                    <td class="insert"><?=$val['me_major']?></td>
                                    <td class="insert">
                                        <?if($val['me_degree']=='1'){?>없음<?}?>
                                        <?if($val['me_degree']=='2'){?>고등학교<?}?>
                                        <?if($val['me_degree']=='3'){?>전문학사<?}?>
                                        <?if($val['me_degree']=='4'){?>학사<?}?>
                                        <?if($val['me_degree']=='5'){?>석사<?}?>
                                        <?if($val['me_degree']=='6'){?>박사<?}?>
                                    </td>
                                    <td>
                                        <?=$graduate_type_array[$val['me_graduate_type']]?>
                                    </td>
                                    <td>
                                        <?if($val['me_weekly']=='1'){?>해당없음<?}?>
                                        <?if($val['me_weekly']=='2'){?>주간<?}?>
                                        <?if($val['me_weekly']=='3'){?>야간<?}?>
                                    </td>
                                    <td>
                                        <?=$val['me_etc']?>
                                    </td>
                                </tr>
                            <?}?>
						</tbody>
                    </table>
				</div>
            </div>
            <?}?>
            <?if(!empty($career_list)){?>
            <div class="section">
				<h3 class="section-title">경력사항</h3>
				<div class="table-wrap">
                <?foreach ($career_list as $key =>$val){?>
					<table class="data-table table-form-another">
                        <caption>경력사항 입력표</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 8%" />
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
                                <td>
                                    <?=substr($val['mc_sdate'],0,10)?>
                                </td>
                                <th scope="col">종료일</th>
                                <td>
                                    <?=substr($val['mc_edate'],0,10)?>
                                </td>
                                <th scope="col">회사명</th>
                                <td class="insert"><?=$enc->decrypt($val['mc_company'])?></td>
                                <th scope="col">근무부서</th>
                                <td class="insert"><?=$val['mc_group']?></td>
                                <th scope="col">최종직위</th>
                                <td class="insert"><?=$enc->decrypt($val['mc_position'])?></td>
                                <th scope="col">담당업무</th>
                                <td class="insert"><?=$enc->decrypt($val['mc_duties'])?></td>
                            </tr>
                            <tr> 
                                <th scope="col">경력기술</th>
                                <td class="insert" colspan=11><?=$val['mc_career']?></td>
                            </tr>
                        </tbody>
                    </table>
                <?}?>
				</div>
			</div>
            <?}?>
            <?if(!empty($certificate_list)){?>
			<div class="section">
				<h3 class="section-title">어학 / 자격증</h3>
				<div class="table-wrap family">
					<table class="data-table table-cert">
						<caption>어학 / 자격증</caption>
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
                                <td class="insert"><?=$val['mct_cert_name']?></td>
                                <td>
                                    <?=substr($val['mct_date'],0,10)?>
                                </td>
                                <td class="insert"><?=$enc->decrypt($val['mct_class'])?></td>
                                <td class="insert"><?=$enc->decrypt($val['mct_institution'])?></td>
                                <td class="insert"><?=$val['mct_num']?></td>
                            </tr>
                        <?}?>
                        </tbody>
					</table>
				</div>
			</div>
            <?}?>
            <?if(!empty($premier_list)){?>
            <div class="section">
                <h3 class="section-title">수상</h3>
                <div class="table-wrap family">
                    <table class="data-table table-cert">
                        <caption>수상</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
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
                                <td class="insert"><?=substr($val['mpd_date'],0,10)?></td>
                                <td class="insert"><?=$val['mpd_content']?></td>
                                <td class="insert"><?=$val['mpd_institution']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?}?>


            <?if(!empty($activity_list)){?>
			<div class="section">
                <h3 class="section-title">교육 / 활동</h3>
                <div class="table-wrap">
                    <table class="data-table table-form-activity left">
                        <caption>교육 / 활동</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 14%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">구분</th>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">교육 및 활동명</th>
                                <th scope="col">기관명</th>
                                <th scope="col">역할</th>
                                <th scope="col">증빙서류</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?foreach ($activity_list as $index =>$val){?>
                                <tr>
                                    <td class="insert">
                                        <?if($val['mad_type']=='1'){?>사외<?}?>
                                        <?if($val['mad_type']=='2'){?>사내<?}?>
                                    </td>
                                    <td class="insert"><?=substr($val['mad_sdate'],0,10)?></td>
                                    <td class="insert"><?=substr($val['mad_edate'],0,10)?></td>
                                    <td scope="insert"><?=$enc->decrypt($val['mad_name'])?></td>
                                    <td scope="insert"><?=$enc->decrypt($val['mad_institution'])?></td>
                                    <td scope="insert"><?=$val['mad_role']?></td>
                                    <td scope="insert">
                                    <?if(!empty($val['mad_file'])){?>
                                        <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span>
                                    <?}?>
                                    </td>
                                </tr>
                            <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?}?>
            <?if(!empty($paper_list)){?>
            <div class="section">
                <h3 class="section-title">논문 / 저서</h3>
                <div class="table-wrap">
                    <table class="data-table table-form-paper left">
                        <caption>논문/저서</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">발행일</th>
                                <th scope="col">논문 및 저서명</th>
                                <th scope="col">발행정보</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($paper_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><?=substr($val['mp_date'],0,10)?></td>
                                <td class="insert"><?=$enc->decrypt($val['mp_name'])?></td>
                                <td scope="insert"><?=$enc->decrypt($val['mp_institution'])?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?}?>
            <?if(!empty($project_list)){?>
            <div class="section">
                <h3 class="section-title">프로젝트</h3>
                <div class="table-wrap">
                    <table class="data-table table-project">
                        <caption>프로젝트</caption>
                        <colgroup>
                            <col style="width: 6%" />
                            <col style="width: 6%" />
                            <col style="width: 10%" />
                            <col style="width: 8%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">프로젝트명</th>
                                <th scope="col">프로젝트 기관</th>
                                <th scope="col">기여도</th>
                                <th scope="col">역할</th>
                                <th scope="col">완료여부</th>
                                <th scope="col">내용(배운점 등)</th>
                                <th scope="col">키워드</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($project_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><?=substr($val['mpd_sdate'],0,10)?></td>
                                <td class="insert"><?=substr($val['mpd_edate'],0,10)?></td>
                                <td class="insert"><?=$enc->decrypt($val['mpd_name'])?></td>
                                <td class="insert"><?=$val['mpd_institution']?></td>
                                <td scope="insert"><?=$val['mpd_contribution']?></td>
                                <td scope="insert"><?=$val['mpd_position']?></td>
                                <td scope="insert">
                                    <?if($val['mpd_result']=="1"){?>진행중<?}?>
                                    <?if($val['mpd_result']=="2"){?>완료<?}?>
                                    <?if($val['mpd_result']=="3"){?>보류<?}?>
                                    <?if($val['mpd_result']=="4"){?>취소<?}?>
                                </td>
                                <td scope="insert"><?=$val['mpd_content']?></td>
                                <td scope="insert"><?=$val['mpd_keyword']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?}?>
            <!-- <?if(!empty($punishment_list)){?>
            <div class="section-wrap">
                <h3 class="section-title">상벌</h3>
                <div id="tab-etc" class="tab-cont">
                    <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table left">
                            <caption>상벌</caption>
                            <colgroup>
                                <col style="width: 2%" />
                                <col style="width: 2%" />
                                <col style="width: 8%" />
                                <col style="width: 10%" />
                                <col style="width: 5%" />
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
                                    <td scope="insert"><?=$punishment[$val['mp_type']]?></td>
                                    <td scope="insert"><?=substr($val['mp_date'],0,10)?></td>
                                    <td scope="insert"><?=$val['mp_title']?></td>
                                    <td scope="insert"><?=$val['mp_content']?></td>
                                    <td scope="insert"><?=$val['mp_etc']?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?}?> -->
<!--            <div class="section">-->
<!--                <h3 class="section-title">기타</h3>-->
<!--                <div class="table-wrap">-->
<!--                    <table class="data-table table-etc">-->
<!--                        <caption>기타</caption>-->
<!--                        <colgroup>-->
<!--                            <col style="width: 5%" />-->
<!--                        </colgroup>-->
<!--                        <thead>-->
<!--                            <tr>-->
<!--                                <th scope="col">기타내용</th>-->
<!--                            </tr>-->
<!--                        </thead>-->
<!--                        <tbody>-->
<!--                            <tr>-->
<!--                                <td></td>-->
<!--                            </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!--                </div>-->
<!--            </div>-->
			<div class="btn-area">
				<a class="btn type02 large" href="./recruitStep03.php"><span class="ico prev"></span>이전</a>
				<a class="btn type03 large" id="btn_confirm">완료<span class="ico next"></span></a>
			</div>
		</div>
		<!-- // 추가사항 -->
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<!-- // WRAP -->
<script>
    $(function (){
        $('.ico-info').mouseover(function() {
            $(this).siblings('.text-box').addClass('active');
        }).mouseout(function() {
            $(this).siblings('.text-box').removeClass('active');
        });
    });

    $('#btn_confirm').click(function(e){
        e.preventDefault();
        var data = {'mm_status':'S'};
        if(confirm("개인정보 등록 신청을 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/new/new_profile_apply.php", data);
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='new_profile_apply'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.href="/";
            }
        }
    }

</script>