<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mmseq = $_SESSION['mmseq'];
@$active = $_REQUEST['active'];

$data =  get_member_info($db,$mmseq); //기본정보 
$family_list = get_family_list($db,$mmseq); // 가족사항
$certificate_list = get_certificate_list($db,$mmseq); // 어학 / 자격증
$career_list = get_career_list($db,$mmseq); // 사외경력
$education_list = get_education_list($db,$mmseq); // 학력
$appointment_list = get_appointment_list($db,$mmseq); // 발령
$premier_list = get_premier_list($db,$mmseq); // 수상
$activity_list = get_activity_list($db,$mmseq); // 교육 / 활동
$paper_list = get_paper_list($db,$mmseq); // 논문 / 저서
$project_list = get_project_list($db,$mmseq); // 프로젝트
$punishment_list = get_punishment_list($db,$mmseq); // 상벌

$position_list2 = get_position_list($db,2); //직급

?>
<style>
    table tbody td textarea{resize: none; width:100%; height:100%;}
    #family_form table{margin-top: 15px; border-top: 1px solid #d1d1d1;}
    #family_form table:first-child{margin-top: 0px; border-top: none;}
    #tab-family table div.uploader{width: auto;}
    #tab-family table div.uploader span.filename{width: 550px;}

    .table-form-cert.hide,
    .table-form-prize2.hide{display: none;}

    .emergency{display: inline-block; width: 25%;}
    .emergency.tel{display: inline-block; width: 50%;}
    .emergency span{vertical-align: sub;}
    .emergency .input-text{max-width: 80%;}
    .emergency.tel .input-text{max-width: 60%;}
    input.hidden{display: none;}

    span.filename{display: none !important;}

    
    /* .table-form-activity div.uploader{width: 266px !important} */

    
</style>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
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
			<li><a href="/ess/timeline"  >나의 타임라인</a></li>
			<li><a href="/ess/"  class="active">인사기록카드</a></li>
			<li><a href="/ess/change"  >정보 변경 신청 내역</a></li>
			
			<!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">인사기록카드</h2>
		<!-- 프로필 -->
		    <div class="personnel-info">
			<div class="thumb">
                <span class="thumb-image"><img id="presonnel-img" src="<?=$data['mm_profile']?>" alt="프로필 사진"></span>
                <?if($data['mc_main']=='T'){?>
                <input type="file" id="input-file" name="em_profile" class="hidden" accept="image/x-png,image/gif,image/jpeg">
				<button id="btn-file" class="btn small type01" type="button"><span class="ico camera"></span> 사진변경</button>
                <?}?>
			</div>
			<div class="table-wrap">
				<table class="data-table left">
					<caption>인사기록카드정보</caption>
					<colgroup>
						<col style="width: 5%" />
						<col style="width: 10%" />
						<col style="width: 5%" />
						<col style="width: 10%" />
						<col style="width: 5%" />
                        <col style="width: 10%" />
                        <col style="width: 5%" />
						<col style="width: 10%" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="col">사번</th>
							<td><?=$data['mc_code']?></td>
							<th scope="col">성명/성별</th>
							<td><?=$enc->decrypt($data['mm_name'])?> / <?=getGender($data['mm_gender'])?></td>
							<th scope="col">고용구분</th>
                            <td><?=get_position_title_type($db,$data['mc_position4'],4)?></td>
                            <th scope="col">사원유형</th>
							<td><?=get_position_title_type($db,$data['mc_position5'],5)?></td>
						</tr>
						<tr>
							<th scope="col">소속</th>
							<td ><?=implode('/ ',get_group_list_v2($db,$data['mmseq']))?></td>
							<th scope="col">직책</th>
                            <td><?=get_position_title_type($db,$data['mc_position'],1)?></td>
                            <th scope="col">직위</th>
							<td>
                                <?=get_position_title_type($db,$data['mc_position2'],2)?>
                            </td>
                            <th scope="col">직무</th>
                            <td><?=$data['mc_job']?></td>
                        </tr>
                        <tr>
							<th scope="col">재직상태</th>
							<td>
                                <?=$member_state[$data['mm_status']]?>
                            </td>
							<th scope="col">그룹입사일</th>
                            <td><?=substr($data['mc_affiliate_date'],0,10)?></td>
                            <th scope="col">자사입사일</th>
							<td><?=substr($data['mc_regdate'],0,10)?></td>
                            <th scope="col">최종승진일</th>
                            <td><?=substr($data['mc_bepromoted_date'],0,10)?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- //프로필 -->
		<div class="profile-section">
			<div class="tab-wrap">
				<ul class="tab">
					<li class="<?if(empty($active)){echo 'active';}?>"><a href="#tab-information">기본사항</a></li>
                    <li class="<?if($active=='family'){echo 'active';}?>"><a href="#tab-family">가족사항</a></li>
                    <li><a href="#tab-education">학력사항</a></li>
                    <li><a href="#tab-another">경력사항</a></li>
					<li><a href="#tab-cert">어학 / 자격증 / 수상</a></li>
                    <li><a href="#tab-activity">교육 / 활동</a></li>
                    <li><a href="#tab-paper">논문 / 저서</a></li>
                    <li class="<?if($active=='project'){echo 'active';}?>"><a href="#tab-project">프로젝트</a></li>
                    <li><a href="#tab-issuance">발령사항</a></li>
                    <li><a href="#tab-prize">상벌</a></li>
<!--                    <li><a href="#tab-etc">기타</a></li>-->
				</ul>
			</div>
		</div>

        <!-- 기본 사항 -->

		<div id="tab-information"class="tab-cont">
			<div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="info_form">
				<table class="data-table left">
					<caption>인사기록카드정보</caption>
					<colgroup>
						<col style="width: 140px" />
						<col style="width: *" />
						<col style="width: 140px" />
						<col style="width: *" />
						<col style="width: 140px" />
                        <col style="width: *" />
                        <col style="width: 140px" />
						<col style="width: *" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="col">성명</th>
                            <td class="insert "><input type="text" id="mm_name" name="em_name" class="input-text"   value="<?=$enc->decrypt($data['mm_name'])?>" ></td>
                            <th scope="col">영문성명</th>
                            <td><input type="text" class="input-text" id="mm_en_name" name="em_en_name" value="<?=$data['mm_en_name']?>"></td>
							<th scope="col">국적</th>
                            <td><?=getNationTag_log($data['mm_country'])?></td>
                            <th scope="col">성별</th>
							<td ><?=getGenderTag_log($data['mm_gender'])?></td>
						</tr>
						
						<tr>
							<th scope="col">생년월일</th>
							<td><input type="text" class="input-text " id="mm_birth" name="em_birth"  style="max-width:80%;"  readonly value="<?=dateTextFormat($data['mm_birth'],3)?>">
							</td>
							<th scope="col">주민(외국인) 번호</th>
                            <td><input type="hidden" class="input-text" id="mm_serial_no" name="em_serial_no" value="<?=$enc->decrypt($data['mm_serial_no'])?>" placeholder="-없이 입력">
                            <?
                            $jumin = $enc->decrypt($data['mm_serial_no']);
                            $jumin = substr($jumin,0,6);
                            echo ''.$jumin.'*******';?>
                            </td>
                            <th scope="col">연락처</th>
                            <td><input type="number"  class="input-text"  id="mm_cell_phone" name="em_cell_phone" placeholder="-없이 입력" value="<?=$enc->decrypt($data['mm_cell_phone'])?>"></td>
                            <th scope="col">이메일 주소</th>
                            <td><input type="text" class="input-text"  id="mm_email" name="em_email"   value="<?=$enc->decrypt($data['mm_email'])?>"></td>
						</tr>
						<tr>
							<th scope="col">우편번호</th>
							<td><input type="text"  class="input-text" id="mm_post" name="em_post"  readonly value="<?=$data['mm_post']?>"></td>
							<th scope="col">현주소</th>
							<td ><input type="text" id="mm_address" name="em_address" readonly class="input-text" value="<?=$enc->decrypt($data['mm_address'])?>">
							<th scope="col">상세주소</th>
							<td colspan=3><input type="text" id="mm_address_detail" name="em_address_detail"  class="input-text" value="<?=$enc->decrypt($data['mm_address_detail'])?>" placeholder="상세 주소"></td>
						</tr>
						<tr>
                            <th scope="col">비상 연락처</th>
                            <td colspan=7>
                                <div class="emergency"><span>관계</span> <input type="text" class="input-text" name="em_prepare_relation" value="<?=$data['mm_prepare_relation']?>" title="비상 연락처 관계"></div>
                                <div class="emergency tel"><span>연락처</span> <input type="number" class="input-text" placeholder="-없이 입력" name="em_prepare_phone" value="<?=$enc->decrypt($data['mm_prepare_phone'])?>"  title="비상 연락처 휴대폰"></div>
                            </td>
						</tr>
					</tbody>
				</table>
                    <h3 class="section-title">병력</h3>
                    <table class="data-table table-form-army left" style="border-top:1px solid #d1d1d1;">
                        <caption>병력</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
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
                                    <select name="em_arm_type" >
                                        <?foreach($arm_type as $key => $val){?>
                                            <option value='<?=$key?>' <?if($key==$data['mm_arm_type']){?>selected<?}?>><?=$val?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td class="insert"><input title = "입대일" value="<?=substr($data['mm_arm_sdate'],0,10)?>" type="text" class="input-text input-datepicker" name="em_arm_sdate" readonly></td>
                                <td class="insert"><input title = "제대일" value="<?=substr($data['mm_arm_edate'],0,10)?>" type="text" class="input-text input-datepicker" name="em_arm_edate" readonly></td>
                                <td scope="insert" class="center">
                                    <select name="em_arm_group" class="arm_select">
                                        <?foreach($arm_group as $key => $val){?>
                                            <option value='<?=$key?>' <?if($key==$data['mm_arm_group']){?>selected<?}?>><?=$val?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td scope="insert" class="center">
                                    <select name="em_arm_class" class="arm_select">
                                        <?foreach($arm_class as $key => $val){?>
                                            <option value='<?=$key?>' <?if($key==$data['mm_arm_class']){?>selected<?}?>><?=$val?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td scope="insert">
                                    <input title = "병과" value="<?=$data['mm_arm_discharge']?>" type="text" class="input-text" name="em_arm_discharge">
                                </td>
                                <td scope="insert"><input title = "사유(면제 및 기타)" value="<?=$data['mm_arm_reason']?>" type="text" class="input-text" name="em_arm_reason"></td>
                            </tr>
                        </tbody>
                    </table>

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
                                <select  name="em_disorder_1" >
                                    <?foreach($disorder_type_1 as $key => $val){?>
                                        <option value='<?=$key?>' <?if($key==$data['mm_disorder_1']){?>selected<?}?>><?=$val?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td scope="insert" class="center">
                                <select  name="em_disorder_2" >
                                    <?foreach($disorder_type_2 as $key => $val){?>
                                        <option value='<?=$key?>' <?if($key==$data['mm_disorder_2']){?>selected<?}?>><?=$val?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td scope="insert" class='center' >
                                <select  name="em_disorder_3" >
                                    <?foreach($disorder_type_3 as $key => $val){?>
                                        <option value='<?=$key?>' <?if($key==$data['mm_disorder_3']){?>selected<?}?>><?=$val?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td scope="insert" class='center'>
                                <select  name="em_nation_1" >
                                    <?foreach($nation_type_1 as $key => $val){?>
                                        <option value='<?=$key?>' <?if($key==$data['mm_nation_1']){?>selected<?}?>><?=$val?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td scope="insert" class="center">
                                <select  name="em_nation_2" >
                                    <?foreach($nation_type_2 as $key => $val){?>
                                        <option value='<?=$key?>' <?if($key==$data['mm_nation_2']){?>selected<?}?>><?=$val?></option>
                                    <?}?>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                </form>
			</div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-my-info" class="btn type01 large">기본정보 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
		</div>
		<!-- 기본 사항 -->

		<!-- 가족 사항 -->
		<div id="tab-family" class="tab-cont">
			<div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="family_form">
                    <div class="family_form_div">
                        <table class="data-table table-form-family left">
                            <caption>가족관계</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 10%" />
                                <col style="width: 1%" />
                                <col style="width: 10%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">선택</th>
                                    <th scope="col">성명</th>
                                    <th scope="col">관계</th>
                                    <th scope="col">생년월일</th>
                                    <th scope="col">인적공제 여부</th>
                                    <th scope="col">동거 여부</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($family_list as $index => $val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td class="insert"><input type="text" class="input-text" name="ml_name[]" value="<?=$enc->decrypt($val['mf_name'])?>"></td>
                                    <td class="center">
                                        <select name="ml_relationship[]" >
                                            <option value="1" <?if($val['mf_relationship']=='1'){?>selected<?}?>>부</option>
                                            <option value="2" <?if($val['mf_relationship']=='2'){?>selected<?}?>>모</option>
                                            <option value="3" <?if($val['mf_relationship']=='3'){?>selected<?}?>>형제</option>
                                            <option value="4" <?if($val['mf_relationship']=='4'){?>selected<?}?>>자매</option>
                                            <option value="5" <?if($val['mf_relationship']=='5'){?>selected<?}?>>조모</option>
                                            <option value="6" <?if($val['mf_relationship']=='6'){?>selected<?}?>>조부</option>
                                            <option value="7" <?if($val['mf_relationship']=='7'){?>selected<?}?>>외조모</option>
                                            <option value="8" <?if($val['mf_relationship']=='8'){?>selected<?}?>>외조부</option>
                                            <option value="9" <?if($val['mf_relationship']=='9'){?>selected<?}?>>배우자</option>
                                            <option value="10" <?if($val['mf_relationship']=='10'){?>selected<?}?>>자녀</option>
                                        </select>
                                    </td>
                                    <td class="insert">
                                        <input type="text" class="input-text input-datepicker"  name="ml_birth[]" readonly value="<?=$enc->decrypt($val['mf_birth'])?>">
                                    </td>
                                    <td class="center">
                                        <select  name="ml_allowance[]" >
                                            <option value='T' <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>selected<?}?>>대상</option>
                                            <option value='F' <?if($val['mf_allowance']=='F'){?>selected<?}?>>비대상</option>
                                        </select>
                                    </td>
                                    <td class="center">
                                        <select  name="ml_together[]">
                                            <option value='T' <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>selected<?}?>>동거</option>
                                            <option value='F' <?if($val['mf_together']=='F'){?>selected<?}?>>비동거</option>
                                        </select>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="family" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button" class="btn type01 medium btn-add-tr" data-btn="family" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-family" class="btn type01 large">가족 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}else{?>
                <div style="text-align:center;margin-top:50px;">
			    </div>
            <?}?>
            <h3 class="section-title" style="margin-top:0">증빙서류 첨부</h3>
            <div class="btn-aside">
                <!-- <a class="btn type01 small add-file-family" href="#">파일추가</a>
                <a class="btn type01 small remove-file-family" href="#">파일삭제</a> -->
                <!-- <a class="btn type01 small" href="#">파일첨부</a> -->
                
            </div>
            <form id="family_file_form">
            <div class="table-wrap">
                <table class="data-table">
                    <caption>가족사항 입력표</caption>
                    <colgroup>
                        <col style="width: 2%" />
                        <col style="width: 14%" />
                    <thead>
                        <tr>
                            <th scope="col">구분</th>
                            <th scope="col">증빙서류</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>주민등록등본</td>
                            <td class="left">
                                <a href="<?=$data['mm_file1']?>" download><?=$data['mm_file1_name']?></a>
                                <?if($data['mc_main']=='T'){?>
                                    <input type="file" class="file-activity" name="file1">
                                    <button type="button" class="btn type12 small delete_file" style="" data-seq="1" >파일삭제</button>
                                <?}?>
                            </td>
                        </tr>
                        <tr>
                            <td>가족관계증명서</td>
                            <td class="left">
                                <a href="<?=$data['mm_file2']?>" download><?=$data['mm_file2_name']?></a>
                                <?if($data['mc_main']=='T'){?>
                                    <input type="file" class="file-activity" name="file2">
                                    <button type="button" class="btn type12 small delete_file" style="" data-seq="2">파일삭제</button>
                                <?}?>
                            </td>
                        </tr>
                        <tr>
                            <td>기타</td>
                            <td class="left">
                                <a href="<?=$data['mm_file3']?>" download><?=$data['mm_file3_name']?></a>
                                <?if($data['mc_main']=='T'){?>
                                    <input type="file" class="file-activity" name="file3">
                                    <button type="button" class="btn type12 small delete_file" style="" data-seq="3">파일삭제</button>
                                <?}?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?if($data['mc_main']=='T'){?>
                <div style="text-align:center;margin-top:50px;">
                    <p class="info-text">※ 증빙서류는 인사담당자 승인없이 바로 저장됩니다.</p>
                    <div class="button-area large">    
                        <button type="button" id="btn-set-family-file" class="btn type01 large">증빙서류 저장<span class="ico apply"></span></button>
                    </div>
                </div>
                <?}?>
            </div>
            </form>
		</div>
		<!-- 가족 사항 -->

		<!-- 어학 / 자격증 / 수상 -->
		<div id="tab-cert" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="cert_form" >
                
				<table class="data-table table-form-cert left">
					<caption>어학 / 자격증</caption>
					<colgroup>
						<col style="width: 1%" />
						<col style="width: 5%" />
						<col style="width: 3%" />
						<col style="width: 5%" />
						<col style="width: 5%" />
						<col style="width: 5%" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">선택</th>
                            <th scope="col">자격증 명</th>
                            <th scope="col">취득일</th>
                            <th scope="col">등급/점수</th>
                            <th scope="col">취득기관</th>
                            <th scope="col">자격번호</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?foreach ($certificate_list as $index =>$val){?>
                        <tr>
                            <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                            <td class="insert"><input type="text" class="input-text" name="cl_cert_name[]"  value="<?=$val['mct_cert_name']?>" style=""></td>
                            <td>
                                <input type="text" class="input-text input-datepicker" name="cl_date[]" readonly value="<?=substr($val['mct_date'],0,10)?>">
                            </td>
                            <td class="insert"><input type="text" class="input-text"  name="cl_class[]" value="<?=$enc->decrypt($val['mct_class'])?>" style=""></td>
                            <td class="insert"><input type="text" class="input-text"  name="cl_institution[]" value="<?=$enc->decrypt($val['mct_institution'])?>" style=""></td>
                            <td class="insert"><input type="text" class="input-text"  name="cl_num[]" value="<?=$val['mct_num']?>"></td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="cert" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="cert" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
                <h3 class="section-title">수상경력</h3>
                    <table class="data-table table-form-prize2 left" style="border-top:1px solid #d1d1d1;">
                        <caption>수상경력</caption>
                        <colgroup>
                            <col style="width: .5%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">선택</th>
                                <th scope="col">일자</th>
                                <th scope="col">수상내용</th>
                                <th scope="col">수상기관</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach($premier_list as $val){?>
                            <tr>
                                <td class="center"><input type="checkbox" name="checkbox" data-index=0></td>
                                <td class="insert"><input title = "일자" value="<?=substr($val['mpd_date'],0,10)?>" type="text" class="input-text input-datepicker" name="epl_date[]" readonly></td>
                                <td class="insert"><input title="수상내용" type="text" class="input-text"  name="epl_content[]" value="<?=$val['mpd_content']?>"></td>
                                <td class="insert"><input title="수상기관" type="text" class="input-text"  name="epl_institution[]" value="<?=$val['mpd_institution']?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>

                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="prize2" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="prize2" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-cert" class="btn type01 large">어학/자격증/수상 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
        </div>
		<!-- 어학 / 자격증 / 수상 -->

        <!-- 경력사항 -->
		<div id="tab-another" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="career_form" class="<?if(empty($career_list)){echo 'hide';}?>">
                <?foreach ($career_list as $index =>$val){?>
                    <table class="data-table table-form-another left">
                        <caption>경력사항</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 1%" />
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
                                <th scope="col" rowspan=2 class="center">선택</th>
                                <td class="center" rowspan=2><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                <th scope="col">시작일</th>
                                <td>
                                    <input type="text" class="input-text input-datepicker" name="crl_sdate[]" readonly value="<?=substr($val['mc_sdate'],0,10)?>">
                                </td>
                                <th scope="col">종료일</th>
                                <td>
                                    <input type="text" class="input-text input-datepicker"  name="crl_edate[]" readonly value="<?=substr($val['mc_edate'],0,10)?>">
                                </td>
                                <th scope="col">회사명</th>
                                <td class="insert"><input type="text" class="input-text"  name="crl_company[]" value="<?=$enc->decrypt($val['mc_company'])?>" style=""></td>
                                <th scope="col">근무부서</th>
                                <td class="insert"><input type="text" class="input-text" name="crl_group[]" value="<?=$val['mc_group']?>"></td>
                                <th scope="col">최종직위</th>
                                <td class="insert"><input type="text" class="input-text" name="crl_position[]" value="<?=$enc->decrypt($val['mc_position'])?>" style=""></td>
                                <th scope="col">담당업무</th>
                                <td class="insert"><input type="text" class="input-text" name="crl_duties[]" value="<?=$enc->decrypt($val['mc_duties'])?>" style=""></td>
                            </tr>
                            <tr> 
                                <th scope="col">경력기술</th>
                                <td class="insert" colspan=11><input type="text" class="input-text"  name="crl_career[]" value="<?=$val['mc_career']?>" style=""></td>
                            </tr>
                        </tbody>
                    </table>
                    <?}?>
                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="another" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="another" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-career" class="btn type01 large">사외경력 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
        </div>
        <!-- 경력사항 -->

        <!-- 학력사항 -->
		<div id="tab-education" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="education_form">
                
                    <table class="data-table table-form-education left">
                        <caption>학력사항</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">선택</th>
                                <th scope="col">입학일</th>
                                <th scope="col">졸업일</th>
                                <th scope="col">학교명</th>
                                <th scope="col">학력</th>
                                <th scope="col">전공</th>
                                <th scope="col">학위</th>
                                <th scope="col">졸업구분</th>
                                <th scope="col">주야간구분</th>
                                <th scope="col">비고</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($education_list as $index =>$val){?>
                            <tr>
                                <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                <td><input type="text" class="input-text input-datepicker" name="el_sdate[]" readonly value="<?=substr($val['me_sdate'],0,10)?>"></td>
                                <td>
                                    <input type="text" class="input-text input-datepicker" name="el_edate[]" readonly value="<?=substr($val['me_edate'],0,10)?>">
                                </td>
                                <td class="insert"><input type="text" class="input-text" name="el_name[]"  value="<?=$val['me_name']?>" style=""></td>
                                <td>
                                    <select name="el_level[]">
                                        <option value='1' <?if($val['me_level']=='1' || empty($val['me_level'])){?>selected<?}?>>고등학교</option>
                                        <option value='2' <?if($val['me_level']=='2'){?>selected<?}?>>전문대학</option>
                                        <option value='3' <?if($val['me_level']=='3'){?>selected<?}?>>대학교</option>
                                        <option value='4' <?if($val['me_level']=='4'){?>selected<?}?>>대학원</option>
                                    </select>
                                </td>
                                <td class="insert"><input type="text" class="input-text" name="el_major[]" value="<?=$val['me_major']?>"></td>
                                <td class="insert">
                                    <select name="el_degree[]">
                                        <option value='1' <?if($val['me_degree']=='1' || empty($val['me_degree'])){?>selected<?}?>>없음</option>
                                        <option value='2' <?if($val['me_degree']=='2'){?>selected<?}?>>고등학교</option>
                                        <option value='3' <?if($val['me_degree']=='3'){?>selected<?}?>>전문학사</option>
                                        <option value='4' <?if($val['me_degree']=='4'){?>selected<?}?>>학사</option>
                                        <option value='5' <?if($val['me_degree']=='5'){?>selected<?}?>>석사</option>
                                        <option value='6' <?if($val['me_degree']=='6'){?>selected<?}?>>박사</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="el_graduate_type[]">
                                        <option value='1' <?if($val['me_graduate_type']=='1'){?>selected<?}?>>졸업</option>
                                        <option value='2' <?if($val['me_graduate_type']=='2'){?>selected<?}?>>재학</option>
                                        <option value='3' <?if($val['me_graduate_type']=='3'){?>selected<?}?>>수료</option>
                                        <option value='4' <?if($val['me_graduate_type']=='4'){?>selected<?}?>>중퇴</option>
                                        <option value='5' <?if($val['me_graduate_type']=='5'){?>selected<?}?>>졸업예정</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="el_weekly[]">
                                        <?foreach($weekly_array as $key => $val2){?>
                                            <option value='<?=$key?>' <?if($key==$val['me_weekly']){?>selected<?}?>><?=$val2?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td>
                                    <input title="비고" type="text" class="input-text" name="el_etc[]" value="<?=$val['me_etc']?>">
                                </td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                    
                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="education" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="education" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-education" class="btn type01 large">학력 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
        </div>
        <!-- 학력 -->

        <!-- 발령 -->
		<div id="tab-issuance" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="issuance_form" class="<?if(empty($appointment_list)){echo 'hide';}?>">
                    <table class="data-table table-form-issuance left">
                        <caption>발령</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 10%" />
                            <col style="width: 8%" />
                            <!-- <col style="width: 8%" /> -->
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">발령일자</th>
                                <th scope="col">발령구분</th>
                                <th scope="col">발령회사</th>
<!--                                <th scope="col">부서</th>-->
                                <th scope="col">직위</th>
                                <th scope="col">담당직무</th>
                                <th scope="col">비고</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?if(empty($appointment_list)){?>

                            <?}else{?>
                                <?foreach ($appointment_list as $index =>$val){?>
                                    <tr>
                                        <!-- <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td> -->
                                        <td class="insert"><input type="text" class="input-text input-datepicker" name="ma_date[]" style="max-width:80%;" readonly value="<?=substr($val['ma_date'],0,10)?>"></td>
                                        <td class="insert">
                                            <select name='ma_type[]'>
                                                <option value='1' <?if($val['ma_type']=='1' || empty($val['ma_type'])){?>selected<?}?>>입사</option>
                                                <option value='2' <?if($val['ma_type']=='2'){?>selected<?}?>>보임</option>
                                                <option value='3' <?if($val['ma_type']=='3'){?>selected<?}?>>승진</option>
                                                <option value='4' <?if($val['ma_type']=='4'){?>selected<?}?>>강등</option>
                                                <option value='5' <?if($val['ma_type']=='5'){?>selected<?}?>>겸직</option>
                                                <option value='6' <?if($val['ma_type']=='6'){?>selected<?}?>>전직</option>
                                                <option value='7' <?if($val['ma_type']=='7'){?>selected<?}?>>전근</option>
                                                <option value='8' <?if($val['ma_type']=='8'){?>selected<?}?>>전출</option>
                                                <option value='9' <?if($val['ma_type']=='9'){?>selected<?}?>>전적</option>
                                                <option value='10' <?if($val['ma_type']=='10'){?>selected<?}?>>전보</option>
                                            </select>
                                        </td>
                                        <td scope="insert"><input type="text" class="input-text" name="ma_company[]"  value="<?=$enc->decrypt($val['ma_company'])?>"></td>
                                        <!--                                        <td scope="insert"><input type="text" class="input-text" name="ma_position[]"  value="--><?//=$val['ma_position']?><!--"></td>-->
                                        <td scope="insert"><input type="text" class="input-text" name="ma_position2[]"  value="<?=$val['ma_position2']?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="ma_position3[]"  value="<?=$val['ma_position3']?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="ma_etc[]"  value="<?=$val['ma_etc']?>"></td>
                                    </tr>
                                <?}?>
                            <?}?>
                        </tbody>
                    </table>
                </form>
                <!-- <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="issuance" style="float: right; margin-top: 10px;">발령 삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="issuance" style="float: right; margin-top: 10px; margin-right: 10px;">발령 추가<span class="ico plus"></span></button>
			    <?}?> -->
            </div>
            <!-- <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-issuance" class="btn type01 large">발령 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?> -->
        </div>
        <!-- 발령 -->

        <!-- 교육/활동 -->
		<div id="tab-activity" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="activity_form" class="<?if(empty($activity_list)){echo 'hide';}?>">
                    <table class="data-table table-form-activity left">
                        <caption>교육 / 활동</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 14%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">선택</th>
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
                        <?if(empty($activity_list)){?>
                            
                        <?}else{?>
                            <?foreach ($activity_list as $index =>$val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td class="insert">
                                        <select name='eat_type[]'>
                                            <option value='1' <?if($val['mad_type']=='1' || empty($val['mad_type'])){?>selected<?}?>>사외</option>
                                            <option value='2' <?if($val['mad_type']=='2' || empty($val['mad_type'])){?>selected<?}?>>사내</option>
                                        </select>
                                    </td>  
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="eat_sdate[]" readonly value="<?=substr($val['mad_sdate'],0,10)?>"></td>
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="eat_edate[]" readonly value="<?=substr($val['mad_edate'],0,10)?>"></td>
                                    <td scope="insert"><input type="text" class="input-text" name="eat_name[]"  value="<?=$enc->decrypt($val['mad_name'])?>"></td>
                                    <td scope="insert"><input type="text" class="input-text" name="eat_institution[]"  value="<?=$enc->decrypt($val['mad_institution'])?>"></td>
                                    <td scope="insert"><input type="text" class="input-text" name="eat_role[]"  value="<?=$val['mad_role']?>"></td>
                                    <td scope="insert">
                                    <?if(!empty($val['mad_file'])){?>
                                        <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>
                                    <?}?>
                                        <?if($data['mc_main']=='T'){?>
                                            <input type="file" class="file-activity" name="eat_file[]">
                                            <button type="button" class="btn type12 small file_delete_activity" style="" data-seq="<?=$val['mad_seq']?>">파일삭제</button>
                                        <?}?>
                                        <input type="hidden" name="eat_file_remain[]" value="<?=$val['mad_file']?>">
                                        <input type="hidden" name="eat_file_name_remain[]" value="<?=$val['mad_file_name']?>">
                                    </td>
                                </tr>
                            <?}?>
                        <?}?>
                        </tbody>
                    </table>
                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="activity" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="activity" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-activity" class="btn type01 large">교육/활동 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
        </div>
        <!-- 교육/활동 -->

        <!-- 논문/저서 -->
		<div id="tab-paper" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="paper_form" class="<?if(empty($paper_list)){echo 'hide';}?>">
                    <table class="data-table table-form-paper left">
                        <caption>논문 / 저서</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">선택</th>
                                <th scope="col">발행일</th>
                                <th scope="col">논문 및 저서명</th>
                                <th scope="col">발행정보</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?if(empty($paper_list)){?>
                            <?}else{?>
                                <?foreach ($paper_list as $index =>$val){?>
                                    <tr>
                                        <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                        <td class="insert"><input type="text" class="input-text input-datepicker" name="ep_date[]" style="max-width:80%;" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
                                        <td class="insert"><input type="text" class="input-text" name="ep_name[]"  value="<?=$enc->decrypt($val['mp_name'])?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="ep_institution[]"  value="<?=$enc->decrypt($val['mp_institution'])?>"></td>
                                    </tr>
                                <?}?>
                            <?}?>
                        </tbody>
                    </table>
                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="paper" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="paper" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-paper" class="btn type01 large">논문/저서 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
        </div>
        <!-- 논문/저서 -->

        <!-- 프로젝트 -->
		<div id="tab-project" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="project_form" class="<?if(empty($project_list)){echo 'hide';}?>">
                    <table class="data-table table-form-project left">
                        <caption>프로젝트</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 4%" />
                            <col style="width: 4%" />
                            <col style="width: 10%" />
                            <col style="width: 8%" />
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: .5%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">선택</th>
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
                        <?if(empty($project_list)){?>

                        <?}else{?>
                            <?foreach ($project_list as $index =>$val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_sdate[]" readonly value="<?=substr($val['mpd_sdate'],0,10)?>"></td>
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_edate[]" readonly value="<?=substr($val['mpd_edate'],0,10)?>"></td>
                                    <td class="insert"><input type="text" class="input-text" name="mpd_name[]"  value="<?=$enc->decrypt($val['mpd_name'])?>"></td>
                                    <td class="insert"><input type="text" class="input-text" name="mpd_institution[]"  value="<?=$val['mpd_institution']?>"></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mpd_contribution[]"  value="<?=$val['mpd_contribution']?>"></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mpd_position[]"  value="<?=$val['mpd_position']?>"></td>
                                    <td scope="insert">
                                        <select name="mpd_result[]">
                                            <option value="1" <?if($val['mpd_result']=="1"){?>selected<?}?>>진행중</option>
                                            <option value="2" <?if($val['mpd_result']=="2"){?>selected<?}?>>완료</option>
                                            <option value="3" <?if($val['mpd_result']=="3"){?>selected<?}?>>보류</option>
                                            <option value="4" <?if($val['mpd_result']=="4"){?>selected<?}?>>취소</option>
                                        </select>
                                    </td>
                                    <td scope="insert"><textarea class="text-area" rows=5 name="mpd_content[]"><?=$val['mpd_content']?></textarea></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mpd_keyword[]"  value="<?=$val['mpd_keyword']?>"></td>
                                </tr>
                            <?}?>
                        <?}?>

                        </tbody>
                    </table>
                </form>
                <?if($data['mc_main']=='T'){?>
                <button type="button" class="btn type12 medium btn-remove-tr" data-btn="project" style="float: right; margin-top: 10px;">삭제<span class="ico minus"></span></button>
                <button type="button"  class="btn type01 medium btn-add-tr" data-btn="project" style="float: right; margin-top: 10px; margin-right: 10px;">추가<span class="ico plus"></span></button>
			    <?}?>
            </div>
            <?if($data['mc_main']=='T'){?>
			<div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 프로젝트 정보는 인사 담당자의 검토/승인 없이 바로 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-project" class="btn type01 large">프로젝트 수정<span class="ico apply"></span></button>
				</div>
			</div>
            <?}?>
        </div>
        <!-- 프로젝트 -->

        <!-- 상벌 -->
		<div id="tab-prize" class="tab-cont">
            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <form id="prize_form" class="<?if(empty($punishment_list)){echo 'hide';}?>">
                    <table class="data-table table-form-prize left">
                        <caption>상벌</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 3%" />
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">선택</th>
                                <th scope="col">구분</th>
                                <th scope="col">일자</th>
                                <th scope="col">상벌명</th>
                                <th scope="col">사유 및 내용</th>
                                <th scope="col">비고</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?if(empty($punishment_list)){?>

                        <?}else{?>
                            <?foreach ($punishment_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$key?>></td>
                                    <td scope="insert">
                                        <select name='ep_type[]'>
                                            <option value='1' <?if($val['mp_type']=='1'){?>selected<?}?>>상</option>
                                            <option value='2' <?if($val['mp_type']=='2'){?>selected<?}?>>벌</option>
                                        </select>
                                    </td>
                                    <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker" name="ep_date[]" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
                                    <td scope="insert"><input type="text" title="상벌명" class="input-text" name="ep_title[]" value="<?=$val['mp_title']?>"></td>
                                    <td scope="insert"><input type="text" title="내용" class="input-text" name="ep_content[]" value="<?=$val['mp_content']?>"></td>
                                    <td scope="insert"><input type="text" title="비고" class="input-text" name="ep_etc[]" value="<?=$val['mp_etc']?>"></td>
                                </tr>
                            <?}?>
                        <?}?>
                        </tbody>
                    </table>
                </form>
                <!-- <?if($data['mc_main']=='T'){?>
                    <button type="button" class="btn type12 medium btn-remove-tr" data-btn="prize" style="float: right; margin-top: 10px;">상벌 삭제<span class="ico minus"></span></button>
                    <button type="button"  class="btn type01 medium btn-add-tr" data-btn="prize" style="float: right; margin-top: 10px; margin-right: 10px;">상벌 추가<span class="ico plus"></span></button>
			    <?}?> -->
            </div>
            <?if($data['mc_main']=='T'){?>
			<!-- <div style="text-align:center;margin-top:50px;">
				
				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
				<div class="button-area large">
					<button type="button" id="btn-set-prize" class="btn type01 large">상벌 수정 요청<span class="ico apply"></span></button>
				</div>
			</div>-->
            <?}?> 
        </div>
        <!-- 상벌 -->


        <!-- 기타 -->
<!--		<div id="tab-etc" class="tab-cont">-->
<!--            <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">-->
<!--                <form id="etc_form">-->
<!--                    <table class="data-table left">-->
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
<!--                                <td scope="insert">-->
<!--                                    <textarea class="text-area" rows=5>인사담당자가 자유롭게 해당 사람에 대한 특이내용을 기재할 수 있도록 하는 란이 필요&#10;별도의 탭으로 빼도 되고, 아니면 기본사항 하단부에 노출해도 무방할 것 같음</textarea>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!--                </form>-->
<!--			</div>-->
<!--			<div style="text-align:center;margin-top:50px;">-->
<!--				-->
<!--				<p class="info-text">※ 입력하신 정보는 인사 담당자의 검토/승인 후 반영 됩니다. <br/>입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>-->
<!--				<div class="button-area large">-->
<!--					<button type="button" id="btn-set-etc" class="btn type01 large">기타 수정 요청<span class="ico apply"></span></button>-->
<!--				</div>-->
<!--			</div>-->
<!--        </div>-->
        <!-- 기타 -->
        
	</div>
</div>
<!-- // CONTENT -->



<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(0)').addClass('active');
$('.depth02:eq(0)').find('li:eq(2)').addClass('active');

// 프로필 이미지 사진변경 함수
$('#btn-file').click(function(){
    $('#input-file').click();
});
$('#input-file').change(function(e){
    readURL(this);
    
});

$('select[name="em_arm_type"]').change(function(){
    if($(this).val()==1){
        $('.table-form-army').find('.input-text').each(function(e){
            $(this).val('');
            $(this).attr('disabled',true);
        });
        $('.table-form-army').find('.arm_select').each(function(e){
            $(this).siblings('span').text('없음');
            $(this).val(0);
            $(this).attr('disabled',true);
        });
    }else{
        $('.table-form-army').find('.input-text').each(function(e){
            $(this).attr('disabled',false);
        });
        $('.table-form-army').find('.arm_select').each(function(e){
            $(this).attr('disabled',false);
        });
    }
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var fileName = $("#input-file").val();
            fileName = fileName.slice(fileName.indexOf(".") + 1).toLowerCase();
            if(fileName != "jpg" && fileName != "png" &&  fileName != "gif" &&  fileName != "bmp"){
                alert("이미지 파일은 (jpg, png, gif, bmp) 형식만 등록 가능합니다.");
                $("#input-file").val("");
                return false;
            }else{
                $('#presonnel-img').attr('src',e.target.result);  
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('#mm_post, #mm_address').click(function(){
	new daum.Postcode({
        oncomplete: function(data) {
            // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분입니다.
            // 예제를 참고하여 다양한 활용법을 확인해 보세요.
			var zonecode = data.zonecode;
			var address = data.roadAddress;
			console.log(data);
			$('#mm_post').val(zonecode);
			$('#mm_address').val(address);
			$('#mm_address_detail').focus();
        }
    }).open();
});

//기본사항
$('#btn-set-my-info').click(function(e){
	e.preventDefault();
	var validate = true;
	var obj =[];
	//var mm_name = $.trim($('#mm_name').val());
	$('#tab-information').find('.input-text').each(function(e){
		var val = $.trim($(this).val());
		var id = $(this).attr('id');
        var name = $(this).attr('name');
		
		var txt = $(this).parent().prev('th').text();
		if(val==""&& (name!='em_arm_type' && name!='em_arm_reason' && name!='em_arm_group' && name!='em_arm_class' && name!='em_arm_discharge' && name!='em_arm_sdate' && name!='em_arm_edate'  )){
            $(this).focus();
			alert(  reulReturner(txt) + " 입력해 주세요");
			validate = false;
			return false;
        }
        if(chk_email($('#mm_email').val())==false){
            alert('올바르지 않은 이메일 형식입니다.');
            validate = false;
            return false;
        };
		obj.push( [id,val]);
	});

	// 	$('#tab-information').find('select').each(function(e){
	// 		var val = $.trim($(this).val());
	// 		var id = $(this).attr('id');
	// 		obj.push( [id,val]);
	// 	});
    //
	// var data = { obj  : obj };
    var form = $('#info_form')[0];
    var data = new FormData(form);
    data.append("em_profile", $("#input-file")[0].files[0]);
    //var data = $('#info_form').serialize();
	if(validate){
		if(confirm("본인의 기본사항 정보를 수정 요청 하시겠습니까?")){
            hlb_fn_file_ajaxTransmit("/@proc/ess/profileProc_v2.php", data);
		}
	}

});

/**
 *  어학 / 자격증 입력
 */
$('#btn-set-cert').click(function(e){
    e.preventDefault();
    var validate = true;
    var obj =[];
    //var mm_name = $.trim($('#mm_name').val());
    $('#tab-cert').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#cert_form').serialize();

    if(validate){
        if(confirm("본인의 어학/자격증을 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_certificate.php", data);
        }
    }

});
// ------------- 어학 / 자격 증 끝 --------------- //

/**
 * 가족 입력 btn-set-family
 */
$('#btn-set-family').click(function(e){
    e.preventDefault();
    var validate = true;
    var obj =[];
    //var mm_name = $.trim($('#mm_name').val());
    $('#tab-family').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#family_form').serialize();
    console.log(data);
    if(validate){
        if(confirm("본인의 가족을 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_family.php", data);
        }
    }

});

/**
 * 가족 입력 btn-set-family-file
 */
$('#btn-set-family-file').click(function(e){
    e.preventDefault();
    var validate = true;

    var form = $('#family_file_form')[0];
    var data = new FormData(form);
    console.log(data);
    if(validate){
        if(confirm("증빙서류를 저장하시겠습니까?")){
            hlb_fn_file_ajaxTransmit("/@proc/ess/profile_family_file.php", data);
        }
    }

});
// ------------- 가족 입력 끝 --------------- //

/**
 * 사외경력 입력 btn-set-career
 */
$('#btn-set-career').click(function(e){
    e.preventDefault();
    var validate = true;
    var obj =[];
    //var mm_name = $.trim($('#mm_name').val());
    $('#tab-another').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#career_form').serialize();
    console.log(data);
    if(validate){
        if(confirm("본인의 사회경력을 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_career.php", data);
        }
    }

});
// ------------- 사외경력 입력 끝 --------------- //

/**
 * 학력 입력 btn-set-career
 */
$('#btn-set-education').click(function(e){
    e.preventDefault();
    var validate = true;
    var obj =[];
    //var mm_name = $.trim($('#mm_name').val());
    $('#tab-education').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');
        var title = $(this).attr('title');
        
        var txt = $(this).parent().prev('th').text();
        if(val==""){
            if(title=='비고'){
                return true;
            }else{
                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }
        }
    });

    var data = $('#education_form').serialize();
    console.log(data);
    if(validate){
        if(confirm("본인의 학력을 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_education.php", data);
        }
    }

});
// ------------- 학력 입력 끝 --------------- //

/**
 * 발령 입력 btn-set-career
 */
$('#btn-set-issuance').click(function(e){
    e.preventDefault();
    var validate = true;
    $('#tab-issuance').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#issuance_form').serialize();
    if(validate){
        if(confirm("발령 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_appointment.php", data);
        }
    }

});
// ------------- 발령 입력 끝 --------------- //

/**
 * 교육 / 활동 입력 btn-set-career
 */
$('#btn-set-activity').click(function(e){
    e.preventDefault();
    var validate = true;
    $('#tab-activity').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var form = $('#activity_form')[0];
    var data = new FormData(form);
    // data = $('#activity_form').serialize();
    if(validate){
        if(confirm("교육 / 활동 수정 요청 하시겠습니까?")){
            hlb_fn_file_ajaxTransmit("/@proc/ess/profileProc_activity.php", data);
        }
    }

});
// ------------- 교육 / 활동 입력 끝 --------------- //
/**
 * 논문 / 저서 입력 btn-set-career
 */
$(document).on('change','.file-activity',function(e){
    e.preventDefault();
    var $file_text = $(this).next().text();
    let $a_mark = $(this).parent().parent().find('a');
    
    if($a_mark.length > 0){
        $(this).parent().parent().find('a').text($file_text);
    }else{
        $(this).parent().parent().prepend('<a style="width:150px; padding:10px; display: block;">'+$file_text+'</a>');
    }
    
})
$('#btn-set-paper').click(function(e){
    e.preventDefault();
    var validate = true;
    $('#tab-paper').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#paper_form').serialize();
    if(validate){
        if(confirm("논문 / 저서 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_paper.php", data);
        }
    }

});
// ------------- 논문 / 저서 입력 끝 --------------- //
/**
 * 프로젝트 입력 btn-set-career
 */
$('#btn-set-project').click(function(e){
    e.preventDefault();
    var validate = true;
    $('#tab-project').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#project_form').serialize();
    if(validate){
        if(confirm("프로젝트 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/member_project_update_proc.php", data);
        }
    }

});
// ------------- 프로젝트 입력 끝 --------------- //
$('#mm_birth, .input-datepicker').datepicker();
function fn_callBack(calback_id, result, textStatus){
		if(result.code=='FALSE'){
			alert(result.msg);
			return;
		}else{
			alert(result.msg);
			location.reload();
			//location.href="/";
		}
}

/**
 * 상벌
 * */
$('#btn-set-prize').click(function(e){
    e.preventDefault();
    var validate = true;
    $('#tab-prize').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var id = $(this).attr('name');

        var txt = $(this).parent().prev('th').text();
        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    var data = $('#prize_form').serialize();
    if(validate){
        if(confirm("상벌 수정 요청 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/ess/profileProc_prize.php", data);
        }
    }

});

//tr 추가
$('.btn-add-tr').click(function(e){
    e.preventDefault();
    var $this = $(this).data('btn');
    let $chk_index = $('.table-form-'+$this).length;
    if($this=='family' || $this=='education' || $this=='cert' || $this=='prize2' || $this=='activity' || $this=='paper' || $this=='project' || $this=='prize' || $this =='army'){
        $chk_index = $('.table-form-'+$this+' tbody tr').length;
    }
    $('#tab-'+$this).find('form').removeClass('hide');
    var text_family =
        '<tr>\n'+
        '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="ml_name[]" value=""></td>\n'+
        '    <td class="center">\n'+
        '        <select name="ml_relationship[]" >\n'+
        '            <option value="1">부</option>\n'+
        '            <option value="2">모</option>\n'+
        '            <option value="3">형제</option>\n'+
        '            <option value="4">자매</option>\n'+
        '            <option value="5">조모</option>\n'+
        '            <option value="6">조부</option>\n'+
        '            <option value="7">외조모</option>\n'+
        '            <option value="8">외조부</option>\n'+
        '            <option value="9">배우자</option>\n'+
        '            <option value="10">자녀</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td class="insert">\n'+
        '        <input type="text" class="input-text input-datepicker"  name="ml_birth[]" readonly value="">\n'+
        '    </td>\n'+
        '    <td class="center">\n'+
        '        <select name="ml_allowance[]" >\n'+
        '            <option value="T">대상</option>\n'+
        '            <option value="F">비대상</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td class="center">\n'+
        '        <select name="ml_together[]">\n'+
        '            <option value="T">동거</option>\n'+
        '            <option value="F">비동거</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '</tr>';

    var text_cert =
        '<tr>\n'+
        '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="cl_cert_name[]" value=""></td>\n'+
        '    <td>\n'+
        '        <input type="text" class="input-text input-datepicker" name="cl_date[]" readonly value="">\n'+
        '    </td>\n'+
        '    <td class="insert"><input type="text" class="input-text"  name="cl_class[]" value="" style=""></td>\n'+
        '    <td class="insert"><input type="text" class="input-text"  name="cl_institution[]" value="" style=""></td>\n'+
        '    <td class="insert"><input type="text" class="input-text"  name="cl_num[]" value=""></td>\n'+
        '</tr>';
    var text_anoter =
        '<table class="data-table table-form-another left">\n'+
        '    <caption>경력사항</caption>\n'+
        '    <colgroup>\n'+
        '        <col style="width: 3%" />\n'+
        '        <col style="width: 1%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 8%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '        <col style="width: 5%" />\n'+
        '    </colgroup>\n'+
        '    <tbody>\n'+
        '        <tr>\n'+
        '            <th scope="col" rowspan=2 class="center">선택</th>\n'+
        '            <td class="center" rowspan=2><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '            <th scope="col">시작일</th>\n'+
        '            <td>\n'+
        '                <input type="text" class="input-text input-datepicker" name="crl_sdate[]" readonly value="">\n'+
        '            </td>\n'+
        '            <th scope="col">종료일</th>\n'+
        '            <td>\n'+
        '                <input type="text" class="input-text input-datepicker"  name="crl_edate[]" readonly value="">\n'+
        '            </td>\n'+
        '            <th scope="col">회사명</th>\n'+
        '            <td class="insert"><input type="text" class="input-text"  name="crl_company[]" value="" style=""></td>\n'+
        '            <th scope="col">근무부서</th>\n'+
        '            <td class="insert"><input type="text" class="input-text" name="crl_group[]" value=""></td>\n'+
        '            <th scope="col">최종직위</th>\n'+
        '            <td class="insert"><input type="text" class="input-text" name="crl_position[]" value="" style=""></td>\n'+
        '            <th scope="col">담당업무</th>\n'+
        '            <td class="insert"><input type="text" class="input-text" name="crl_duties[]" value="" style=""></td>\n'+
        '        </tr>\n'+
        '        <tr> \n'+
        '            <th scope="col">경력기술</th>\n'+
        '            <td class="insert" colspan=11><input type="text" class="input-text"  name="crl_career[]" value="" style=""></td>\n'+
        '        </tr>\n'+
        '    </tbody>\n'+
        '</table>';
    var text_education =
        '<tr>\n'+
        '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td><input type="text" class="input-text input-datepicker" name="el_sdate[]" readonly value=""></td>\n'+
        '    <td>\n'+
        '        <input type="text" class="input-text input-datepicker" name="el_edate[]" readonly value="">\n'+
        '    </td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="el_name[]"  value="" style=""></td>\n'+
        '    <td>\n'+
        '        <select name="el_level[]">\n'+
        '            <option value="1">고등학교</option>\n'+
        '            <option value="2">전문대학</option>\n'+
        '            <option value="3">대학교</option>\n'+
        '            <option value="4">대학원</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="el_major[]" value=""></td>\n'+
        '    <td class="insert">\n'+
        '        <select name="el_degree[]">\n'+
        '            <option value="1">없음</option>\n'+
        '            <option value="2">고등학교</option>\n'+
        '            <option value="3">전문학사</option>\n'+
        '            <option value="4">학사</option>\n'+
        '            <option value="5">석사</option>\n'+
        '            <option value="6">박사</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td>\n'+
        '        <select name="el_graduate_type[]">\n'+
        '            <option value="1">졸업</option>\n'+
        '            <option value="2">재학</option>\n'+
        '            <option value="3">수료</option>\n'+
        '            <option value="4">중퇴</option>\n'+
        '            <option value="5">졸업예정</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td>\n'+
        '        <select name="el_weekly[]">\n'+
        '            <option value="1">해당없음</option>\n'+
        '            <option value="2">주간</option>\n'+
        '            <option value="3">야간</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td>\n'+
        '        <input type="text" class="input-text" name="el_etc[]" value="">\n'+
        '    </td>\n'+
        '</tr>';
    var text_issuance = 
    '<tr>\n'+
    '<td><input type="text" class="input-text input-datepicker" name="ea_date[]" style="max-width:80%;" readonly value=""></td>\n'+
    '<td><input type="text" class="input-text" name="ea_type[]" value=""></td>\n'+
    '<td><input type="text" class="input-text" name="ea_company[]" value=""></td>\n'+
    '<td><input type="text" class="input-text" name="ea_position[]" value=""></td>\n'+
    '<td><input type="text" class="input-text" name="ea_job[]" value=""></td>\n'+
    '</tr>';
    var text_prize2=
    '<tr>\n'+
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert"><input title = "일자" value="" type="text" class="input-text input-datepicker" name="epl_date[]" readonly></td>\n'+
    '    <td class="insert"><input title="수상내용" type="text" class="input-text"  name="epl_content[]" value=""></td>\n'+
    '    <td class="insert"><input title="수상기관" type="text" class="input-text"  name="epl_institution[]" value=""></td>\n'+
    '</tr>';
    var text_activity=
    '<tr>\n'+
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert">\n'+
    '        <select name="eat_type[]">\n'+
    '            <option value="1">사외</option>\n'+
    '            <option value="2">사내</option>\n'+
    '        </select>\n'+
    '    </td>\n'+
    '    <td class="insert"><input type="text" class="input-text input-datepicker" name="eat_sdate[]" readonly value=""></td>\n'+
    '    <td class="insert"><input type="text" class="input-text input-datepicker" name="eat_edate[]" readonly value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="eat_name[]" value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="eat_institution[]" value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="eat_role[]" value=""></td>\n'+
    '    <td scope="insert">\n'+
    '        <input type="file" class="file-activity" name="eat_file[]">\n'+
    '        <button type="button" class="btn type12 small" style="">파일삭제</button>\n'+
    '    </td>\n'+
    '</tr>';
    var text_paper=
    '<tr>\n'+
    '<td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '<td class="insert"><input type="text" class="input-text input-datepicker" name="ep_date[]" style="max-width:80%;" readonly value=""></td>\n'+
    '<td class="insert"><input type="text" class="input-text" name="ep_name[]"  value=""></td>\n'+
    '<td scope="insert"><input type="text" class="input-text" name="ep_institution[]"  value=""></td>\n'+
    '</tr>';
    var text_project=
    '<tr>\n'+
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_sdate[]" readonly value=""></td>\n'+
    '    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_edate[]" readonly value=""></td>\n'+
    '    <td class="insert"><input type="text" class="input-text" name="mpd_name[]"  value=""></td>\n'+
    '    <td class="insert"><input type="text" class="input-text" name="mpd_institution[]"  value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="mpd_contribution[]"  value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="mpd_position[]"  value=""></td>\n'+
    '    <td scope="insert">\n'+
    '        <select name="mpd_result[]">\n'+
    '            <option value="1">진행중</option>\n'+
    '            <option value="2">완료</option>\n'+
    '            <option value="3">보류</option>\n'+
    '            <option value="4">취소</option>\n'+
    '        </select>\n'+
    '    </td>\n'+
    '    <td scope="insert"><textarea class="text-area" rows=5 name="mpd_content[]"></textarea></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="mpd_keyword[]"  value=""></td>\n'+
    '</tr>';
    var text_prize=
    '<tr>\n' +
        '<td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '                                <td scope="insert">\n' +
        '                                    <select name=\'ep_type[]\'>\n' +
        '                                        <option value=\'1\'>상</option>\n' +
        '                                        <option value=\'2\'>벌</option>\n' +
        '                                    </select>\n' +
        '                                </td>\n' +
        '                                <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker" name="ep_date[]" readonly></td>\n' +
        '                                <td scope="insert"><input type="text" title="상벌명" class="input-text" name="ep_title[]" ></td>\n' +
        '                                <td scope="insert"><input type="text" title="내용" class="input-text" name="ep_content[]" ></td>\n' +
        '                                <td scope="insert"><input type="text" title="비고" class="input-text" name="ep_etc[]" ></td>\n' +
        '                            </tr>';
    if($this == 'family'){
        $('.table-form-family tbody').append(text_family);
    }else if($this == 'cert'){
        $('.table-form-cert tbody').append(text_cert);
    }else if($this == 'another'){
        $('#career_form').append(text_anoter);
    }else if($this == 'education'){
        $('.table-form-education tbody').append(text_education);
    }else if($this == 'issuance'){
        $('#tab-issuance tbody').append(text_issuance);
    }else if($this == 'activity'){
        $('#tab-activity tbody').append(text_activity);
    }else if($this == 'paper'){
        $('#tab-paper tbody').append(text_paper);
    }else if($this == 'project'){
        $('#tab-project tbody').append(text_project);
    }else if($this == 'prize'){
        $('#tab-prize tbody').append(text_prize);
    }else if($this == 'prize2'){
        $('.table-form-prize2 tbody').append(text_prize2);
    }
    $(document).find('.input-datepicker').removeAttr('id').removeClass('hasDatepicker').datepicker();

    /* 동적생성시 form 재설정 함수 */
    $('select, input[type=radio], input[type=checkbox], input[type=file]').uniform({
		fileDefaultHtml: '',
		fileButtonHtml: '파일첨부'
    });
    // select
	$('.select-label').click(function () {
		$(this).parents('.data-down').toggleClass('active');
	});
	$('.select-list li').click(function () {
		$(this).parents('.select-list').siblings('.select-label').text($(this).text());
		$(this).parents('.data-down').removeClass('active');
	});
})
//tr 삭제
$('.btn-remove-tr').click(function(e){
    e.preventDefault();
    let $this = $(this).data('btn');
    let $this_index = $('.table-form-'+$this+' input[type="checkbox"]:checked');
    let $this_table = $('.table-form-'+$this);
    let $this_table_length = $('.table-form-'+$this).length;
    if($this=='family' || $this=='education' || $this=='cert' || $this=='prize2' || $this == 'activity' || $this=='paper' || $this=='project' || $this=='prize'){
        $this_table = $('.table-form-'+$this+' tbody tr');
        $this_table_length = $('.table-form-'+$this+' tbody tr').length;
    }
    var $this_index_array=[];
    $this_index.each(function(){
        $this_index_array.push($(this).data('index'));
    });
    if($this == 'family' || $this == 'education'){
        if($this_index_array.length>=$this_table_length){
            alert('모두 삭제하실 수 없습니다.'); return false;
        }
    }else{
        if($this == 'cert' || $this == 'prize2'){  
            // if($this_index_array.length>=$this_table_length){
            //     $('.table-form-'+$this).addClass('hide');
            // }
        }else{
            if($this_index_array.length>=$this_table_length){
                $('#tab-'+$this).find('form').addClass('hide');
            }
        }
    }
    for(let i=0;i<$this_index_array.length;i++){
        $this_table.eq($this_index_array[i]).remove();
    }
    $this_table = $('.table-form-'+$this+' input[type="checkbox"]');
    if($this == 'activity' || $this=='paper' || $this=='project' || $this=='prize'){
        $this_table = $('.table-form-'+$this+' tbody tr input[type="checkbox"]');
    }
    $this_table.each(function(i,e){
        $(this).attr('data-index',i);
    });
});

$('.delete_file').click(function(){
    seq = $(this).data('seq');
    if(confirm('정말로 삭제하시겠습니까?')) {
        $.ajax({
            url: '/@proc/ess/file_delete.php',
            data: {'seq': seq,'type':'family'},
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.code == 'TRUE') {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    }
})

    $('.file_delete_activity').click(function(){
        seq = $(this).data('seq');
        if(confirm('정말로 삭제하시겠습니까?')) {
            $.ajax({
                url: '/@proc/ess/file_delete.php',
                data: {'seq': seq,'type':'activity'},
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.code == 'TRUE') {
                        alert(data.msg);
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    })
</script>
