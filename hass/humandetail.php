<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$seq = $_REQUEST['seq'];
$page = $_REQUEST['page'];

$member_info = get_member_info_v2($db,$seq); // 개인정보
$family_list = get_family_list($db,$seq); // 가족사항
$certificate_list = get_certificate_list($db,$seq); // 어학 / 자격증
$career_list = get_career_list($db,$seq); // 사외경력
$education_list = get_education_list($db,$seq); // 학력
$appointment_list = get_appointment_list($db,$seq); // 발령
$activity_list = get_activity_list($db,$seq); // 교육 / 활동
$paper_list = get_paper_list($db,$seq); // 논문 / 저서
$project_list = get_project_list($db,$seq); // 프로젝트
$group_list = get_group_list($db);
$position_list = get_position_list($db,1);
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<div id="wrap" class="depth03">

<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">인사발령(발령) 받은 상세관리</h2>
        <form id="info_form1">
		    <div class="section-wrap">
            <h3 class="section-title">기본사항</h3>
            <!-- 기본 사항 -->
            <div id="tab-information" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(기본사항)</caption>
                        <colgroup>
                            <col style="width: 140px" />
                            <col style="width: 20%" />
                            <col style="width: 140px" />
                            <col style="width: 20%" />
                            <col style="width: 140px" />
                            <col style="width: 20%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">사번</th>
                                <td><input type="text" class="input-text"  disabled value=""></td>
                                <th scope="col">성명</th>
                                <td><input type="text" class="input-text"  disabled title="성명 한글" value="<?=$enc->decrypt($member_info['mm_name'])?>"></td>
                                <th scope="col">이미지</th>
                                <td><img src="<?=$member_info['mm_profile']?>" width="100" height="130"></td>
                            </tr>
                            
                            <tr>
                                <th scope="col">생년월일</th>
                                <td><input type="text" class="input-text input-datepicker"  style="max-width:50%;"  value="<?=substr($member_info['mm_birth'],0,10)?>"></td>
                                <th scope="col">주민/외국인 번호</th>
                                <td><input class="input-text" type="number" title="주민등록번호" name="mm_serial_no" value="<?=$enc->decrypt($member_info['mm_serial_no'])?>" placeholder="-없이 입력"></td>
                                <th scope="col">국적</th>
                                <td><?=getNationTag_v2($member_info['mm_country'],'국적')?></td>
                            </tr>
                            <tr>
                                <th scope="col">우편번호</th>
                                <td><input type="text" name="mm_post" id="mm_post" title="우편번호" class="input-text" value="<?=$member_info['mm_post']?>"></td>
                                <th scope="col">주소</th>
                                <td><input type="text" name="mm_address" id="mm_address" title="주소" class="input-text" value="<?=$enc->decrypt($member_info['mm_address'])?>"></td>
                                <th scope="col">상세주소</th>
                                <td><input type="text" name="mm_address_detail" title="상세주소" class="input-text" value="<?=$enc->decrypt($member_info['mm_address_detail'])?>" placeholder="상세 주소"></td>
                            </tr>
                            <tr>
                                <th scope="col">휴대폰 번호</th>
                                <td><input type="text" class="input-text" title="휴대폰" name="mm_phone" value="<?=$enc->decrypt($member_info['mm_phone'])?>"></td>
                                <th scope="col">전화 번호</th>
                                <td><input type="text" class="input-text" title="전화 번호" name="mm_cell_phone" value="<?=$enc->decrypt($member_info['mm_cell_phone'])?>"></td>
                                <th scope="col">성별</th>
                                <td><?=getGenderTag_v2($member_info['mm_gender'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">영문명</th>
                                <td><input type="text" class="input-text" name="mm_en_name"  title="성명 영문" value="<?=$member_info['mm_en_name']?>"></td>
                                <th scope="col">이메일 주소</th>
                                <td><input type="text" class="input-text" name="mm_email"  value="<?=$enc->decrypt($member_info['mm_email'])?>"></td>
                                <th scope="col">거주국가</th>
                                <td><?=getNationTag_v3($member_info['mm_from'],'거주 국가')?></td>
                            </tr>
                            <tr>
                                <th scope="col">부서</th>
                                <td>
                                    <select name="mm_group">
                                        <?foreach ($group_list as $val){?>
                                            <option value="<?=$val['tg_seq']?>" <?if($val['tg_seq']==$member_info['mm_group']){?>selected<?}?>><?=$val['tg_title']?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <th scope="col">직책</th>
                                <td>
                                    <select name="mm_position">
                                        <?foreach ($position_list as $val){?>
                                            <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mm_position']){?>selected<?}?>><?=$val['tp_title']?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <th scope="col">비상 연락처</th>
                                <td>
                                    <div class="insert">
                                        <label class="label">관계 :</label>
                                        <input type="text" class="input-text" name="mm_prepare_relation" value="<?=$member_info['mm_prepare_relation']?>" style="width: 24%;">

                                        <label class="label">연락처 :</label>
                                        <input type="number" class="input-text" name="mm_prepare_phone" value="<?=$enc->decrypt($member_info['mm_prepare_phone'])?>" style="width: 40%;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col">입사일</th>
                                <td><input type="text" class="input-text input-datepicker"  name="mm_regdate" style="max-width:50%;" value="<?=substr($member_info['mm_regdate'],0,10)?>"></td>
                                <th scope="col">승진일</th>
                                <td><input type="text" class="input-text input-datepicker" name="mm_bepromoted_date" style="max-width:50%;" value="<?=substr($member_info['mm_bepromoted_date'],0,10)?>"></td>
                                <th scope="col">직군 / 직무</th>
                                <td><input type="text" class="input-text" name="" value="직군 / 직무"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
        </form>
        <form id="info_form2">
        <div class="section-wrap">
            <h3 class="section-title">가족사항</h3>
            <!-- 가족 사항 -->
            <div id="tab-family" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(가족)</caption>
                        <colgroup>
                            <col style="width: 140px" />
                            <col style="width: 100px" />
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
                                <?foreach ($family_list as $val){?>
                                    <tr>
                                        <th scope="col">성명</th>
                                        <td><input type="text" class="input-text" name="mf_name[]" title="성명"  value="<?=$enc->decrypt($val['mf_name'])?>" style=""></td>
                                        <th scope="col">주민번호</th>
                                        <td><input type="number" class="input-text" name="mf_resident[]" title="주민번호"  value="<?=$enc->decrypt($val['mf_resident'])?>" style="" placeholder="-없이 입력"></td>
                                        <th scope="col">생년월일</th>
                                        <td><input type="text" class="input-text input-datepicker" title="생년월일"  name="mf_birth[]"  style="max-width:80%;"  readonly value="<?=$enc->decrypt($val['mf_birth'])?>"></td>
                                        <th scope="col">성별</th>
                                        <td>
                                            <select name="mf_gender[]" >
                                                <option value='M' <?if($val['mf_gender']=='M' || empty($val['mf_gender'])){?>selected<?}?>>남성</option>
                                                <option value='F' <?if($val['mf_gender']=='F'){?>selected<?}?>>여성</option>
                                            </select>
                                        </td>
                                        <th scope="col">외국인 여부</th>
                                        <td>
                                            <select name="mf_foreigner[]">
                                                <option value='T' <?if($val['mf_foreigner']=='T' || empty($val['mf_foreigner'])){?>selected<?}?>>내국인</option>
                                                <option value='F' <?if($val['mf_foreigner']=='F'){?>selected<?}?>>외국인</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">가족관계</th>
                                        <td><input type="text" name="mf_relationship[]" title="가족관계" class="input-text"  value="<?=$enc->decrypt($val['mf_relationship'])?>" style=""></td>
                                        <th scope="col">인적공제 여부</th>
                                        <td>
                                            <select  name="mf_allowance[]" >
                                                <option value='T' <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>selected<?}?>>인적공제O</option>
                                                <option value='F' <?if($val['mf_allowance']=='F'){?>selected<?}?>>인적공제x</</option>
                                            </select>
                                        </td>
                                        <th scope="col">동거 여부</th>
                                        <td>
                                            <select  name="mf_together[]">
                                                <option value='T' <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>selected<?}?>>동거O</option>
                                                <option value='F' <?if($val['mf_together']=='F'){?>selected<?}?>>동거X</</option>
                                            </select>
                                        </td>
                                        <th scope="col">학력</th>
                                        <td colspan=3><input type="text" class="input-text" name="mf_education[]"  value="<?=$enc->decrypt($val['mf_education'])?>"></td>
                                    </tr>
                                <?}?>
                            </tbody>
                    </table>
                </div>
            </div>
            <!-- 가족 사항 -->
        </div>
        </form>
        <form id="info_form3">
        <div class="section-wrap">
            <h3 class="section-title">어학 / 자격증</h3>
            <!-- 어학 / 자격증 사항 -->
            <div id="tab-cert" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(어학 / 자격증)</caption>
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
                                <?foreach ($certificate_list as $key =>$val){?>
                                    <tr>
                                        <th scope="col">자격증 명</th>
                                        <td><input type="text" class="input-text" title="자격증 명" name="mct_cert_name[]"  value="<?=$enc->decrypt($val['mct_cert_name'])?>" style=""></td>
                                        <th scope="col">취득기관</th>
                                        <td><input type="text" class="input-text" title="취득기관" name="mct_institution[]" value="<?=$enc->decrypt($val['mct_institution'])?>" style=""></td>
                                        <th scope="col">등급</th>
                                        <td><input type="text" class="input-text" title="등급" name="mct_class[]" value="<?=$enc->decrypt($val['mct_class'])?>" style=""></td>
                                        <th scope="col">취득일</th>
                                        <td><input type="text" class="input-text input-datepicker" title="취득일" name="mct_date[]"  style="max-width:80%;"  readonly value="<?=substr($val['mct_date'],0,10)?>"></td>
                                    </tr>
                                <?}?>
                            </tbody>
                    </table>
                </div>
            </div>
            <!-- 어학 / 자격증 사항 -->
		</div>
        </form>
        <form id="info_form4">
        <div class="section-wrap">
            <h3 class="section-title">사외경력</h3>
            <!-- 사외경력 사항 -->
            <div id="tab-another" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(사외경력)</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">회사명</th>
                                <th scope="col">재직 시작일</th>
                                <th scope="col">재직 종료일</th>
                                <th scope="col">직급</th>
                                <th scope="col">직무</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($career_list as $key =>$val){?>
                            <tr>
                                <td><input class="input-text" type="text" name="mc_company[]" title='회사명' value="<?=$enc->decrypt($val['mc_company'])?>"></td>
                                <td><input type="text" class="input-text input-datepicker" value="<?=substr($val['mc_sdate'],0,10)?>" title='재직 시작일' name="mc_sdate[]" style="max-width:100%;" readonly></td>
                                <td><input type="text" class="input-text input-datepicker" value="<?=substr($val['mc_edate'],0,10)?>" title='재직 종료일' name="mc_edate[]" style="max-width:100%;" readonly></td>
                                <td><input class="input-text" type="text" name="mc_position[]" title='직급' value="<?=$enc->decrypt($val['mc_position'])?>"></td>
                                <td><input class="input-text" type="text" name="mc_duties[]" title=직무 value="<?=$enc->decrypt($val['mc_duties'])?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 사외경력 사항 -->
		</div>
        </form>
        <form id="info_form5">
        <div class="section-wrap">
            <h3 class="section-title">학력</h3>
            <!-- 학력 사항 -->
            <div id="tab-education" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(학력)</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">학교명</th>
                                <th scope="col">재직 기간</th>
                                <th scope="col">학교 등급</th>
                                <th scope="col">학위</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($education_list as $key =>$val){?>
                            <tr>
                                <td><input class="input-text" type="text" name="me_name[]" title='학교명' value="<?=$enc->decrypt($val['me_name'])?>"></td>
                                <td>
                                    <input type="text" class="input-text input-datepicker" name="me_sdate[]" title='재직 시작일' style="max-width:40%;" readonly value="<?=substr($val['me_sdate'],0,10)?>">&nbsp;-<input type="text" class="input-text input-datepicker" name="me_edate[]" title='재직 종료일'  style="max-width:40%;"  readonly value="<?=substr($val['me_edate'],0,10)?>">
                                </td>
                                <td>
                                    <select name='me_level[]'>
                                        <option value='1' <?if($val['me_level']=='1' || empty($val['me_level'])){?>selected<?}?>>고등학교</option>
                                        <option value='2' <?if($val['me_level']=='2'){?>selected<?}?>>대학교</option>
                                        <option value='3' <?if($val['me_level']=='3'){?>selected<?}?>>전문대</option>
                                        <option value='4' <?if($val['me_level']=='4'){?>selected<?}?>>대학원</option>
                                    </select>
                                </td>
                                <td><input class="input-text" type="text" name="me_degree[]" title='학위' value="<?=$enc->decrypt($val['me_degree'])?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 학력 사항 -->
		</div>
        </form>
        <form id="info_form6">
        <div class="section-wrap">
            <h3 class="section-title">발령</h3>
            <!-- 발령 사항 -->
            <div id="tab-issuance" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-issuance left">
                        <caption>발령</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">발령일자</th>
                                <th scope="col">구분</th>
                                <th scope="col">발령회사 및 부서</th>
                                <th scope="col">직위</th>
                                <th scope="col">담당직무</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($appointment_list as $key =>$val){?>
                            <tr>
                                <td><input type="text" title="발령일자" class="input-text input-datepicker" name="ma_date[]" style="max-width:80%;" readonly value="<?=substr($val['ma_date'],0,10)?>"></td>
                                <td><input type="text" title="구분"  class="input-text" name="ma_type[]"  value="<?=$val['ma_type']?>"></td>
                                <td><input type="text" title="발령회사 및 부서" class="input-text" name="ma_company[]"  value="<?=$enc->decrypt($val['ma_company'])?>"></td>
                                <td><input type="text" title="직위" class="input-text" name="ma_position[]"  value="<?=$val['ma_position']?>"></td>
                                <td><input type="text" title="담당직무" class="input-text" name="ma_job[]"  value="<?=$val['ma_job']?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 발령 사항 -->
		</div>
        </form>
        <form id="info_form7">
        <div class="section-wrap">
            <h3 class="section-title">교육 / 활동</h3>
            <!-- 교육 / 활동 사항 -->
            <div id="tab-activity" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-activity left">
                        <caption>교육 / 활동</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">구분</th>
                                <th scope="col">교육/활동명</th>
                                <th scope="col">기관명</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($activity_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><input type="text" title ="시작일" class="input-text input-datepicker" name="mad_sdate[]" style="max-width:80%;" readonly value="<?=substr($val['mad_sdate'],0,10)?>"></td>
                                <td class="insert"><input type="text" title ="종료일" class="input-text input-datepicker" name="mad_edate[]" style="max-width:80%;" readonly value="<?=substr($val['mad_edate'],0,10)?>"></td>
                                <td class="insert"><input type="text" title ="구분" class="input-text" name="mad_type[]"  value="<?=$val['mad_type']?>"></td>
                                <td scope="insert"><input type="text" title ="교육/활동명" class="input-text" name="mad_name[]"  value="<?=$enc->decrypt($val['mad_name'])?>"></td>
                                <td scope="insert"><input type="text" title ="기관명" class="input-text" name="mad_institution[]"  value="<?=$enc->decrypt($val['mad_institution'])?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 교육 / 활동 사항 -->
		</div>
        </form>
        <form id="info_form8">
        <div class="section-wrap">
            <h3 class="section-title">논문 / 저서</h3>
            <!-- 논문 / 저서 사항 -->
            <div id="tab-paper" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-paper left">
                        <caption>논문 / 저서</caption>
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
                                <td class="insert"><input type="text" title="발행일" class="input-text input-datepicker" name="mp_date[]" style="max-width:80%;" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
                                <td class="insert"><input type="text" title="논문 및 저서명" class="input-text" name="mp_name[]"  value="<?=$enc->decrypt($val['mp_name'])?>"></td>
                                <td scope="insert"><input type="text" title="발행정보" class="input-text" name="mp_institution[]"  value="<?=$enc->decrypt($val['mp_institution'])?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 논문 / 저서 사항 -->
		</div>
        </form>
        <form id="info_form9">
        <div class="section-wrap">
            <h3 class="section-title">프로젝트</h3>
            <!-- 프로젝트 사항 -->
            <div id="tab-project" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-project left">
                        <caption>프로젝트</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">프로젝트명</th>
                                <th scope="col">기여도</th>
                                <th scope="col">역할</th>
                                <th scope="col">결과</th>
                                <th scope="col">내용(배운점 등)</th>
                                <th scope="col">키워드</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($project_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><input type="text" title="시작일" class="input-text input-datepicker" name="mpd_sdate[]" style="max-width:80%;" readonly value="<?=substr($val['mpd_sdate'],0,10)?>"></td>
                                <td class="insert"><input type="text" title="종료일" class="input-text input-datepicker" name="mpd_edate[]" style="max-width:80%;" readonly value="<?=substr($val['mpd_edate'],0,10)?>"></td>
                                <td class="insert"><input type="text" title="프로젝트명" class="input-text" name="mpd_name[]" value="<?=$enc->decrypt($val['mpd_name'])?>"></td>
                                <td scope="insert"><input type="text" title="기여도" class="input-text" name="mpd_contribution[]" value="<?=$val['mpd_result']?>"></td>
                                <td scope="insert"><input type="text" title="역할" class="input-text" name="mpd_position[]" value="<?=$val['mpd_position']?>"></td>
                                <td scope="insert"><input type="text" title="결과" class="input-text" name="mpd_result[]" value="<?=$val['mpd_result']?>"></td>
                                <td scope="insert"><textarea class="text-area" title="내용(배운점 등)" rows=5 name="mpd_content[]"><?=$val['mpd_content']?></textarea></td>
                                <td scope="insert"><input type="text" title="키워드" class="input-text" name="mpd_keyword[]" value="<?=$val['mpd_keyword']?>"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 프로젝트 사항 -->
		</div>
        </form>
        <form id="info_form10">
        <div class="section-wrap">
            <h3 class="section-title">기타</h3>
            <!-- 기타 사항 -->
            <div id="tab-etc" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>기타</caption>
                        <colgroup>
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">기타내용</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="insert">
                                    <?if(empty($member_info['mm_note'])){?>
                                        <textarea class="text-area" name="mm_note" rows=5>인사담당자가 자유롭게 해당 사람에 대한 특이내용을 기재할 수 있도록 하는 란이 필요&#10;별도의 탭으로 빼도 되고, 아니면 기본사항 하단부에 노출해도 무방할 것 같음</textarea>
                                    <?}else{?>
                                        <textarea class="text-area" name="mm_note" rows=5><?=$member_info['mm_note']?></textarea>
                                    <?}?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기타 사항 -->
		</div>
        </form>
        <div style="text-align:center;margin-top:50px;">
            <div class="button-area large">
                <button type="button" data-btn="목록" onclick="location.href='/hass/humanlist?page=<?=$page?>'" class="btn type01 large">목록<span class="ico apply"></span></button>
                <button type="button" data-btn="출력" id="btn-print" class="btn type01 large btn-footer">출력<span class="ico print"></span></button>
                <button type="button" data-btn="승인" name="btn_save" data-type="T" class="btn type01 large btn-footer">승인<span class="ico check01"></span></button>
                <button type="button" data-btn="반려" name="btn_save" data-type="F" class="btn type01 large btn-footer">반려<span class="ico cancel"></span></button>
            </div>
        </div>
	</div>
    <!-- // 내용 -->
    
    <!-- 프린트 영역 -->
	<div id="content-print" class="content-primary" style="display: none;">
        <h2 class="content-title">인사발령(겸직) 상세관리</h2>
        <form id="info_form1">
		    <div class="section-wrap">
            <h3 class="section-title">기본사항</h3>
            <!-- 기본 사항 -->
            <div id="tab-information" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(기본사항)</caption>
                        <colgroup>
                            <col style="width: 140px" />
                            <col style="width: 20%" />
                            <col style="width: 140px" />
                            <col style="width: 20%" />
                            <col style="width: 140px" />
                            <col style="width: 20%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">사번</th>
                                <td></td>
                                <th scope="col">성명</th>
                                <td><?=$enc->decrypt($member_info['mm_name'])?></td>
                                <th scope="col">이미지</th>
                                <td><img src="<?=$member_info['mm_profile']?>" width="100" height="130"></td>
                            </tr>
                            
                            <tr>
                                <th scope="col">생년월일</th>
                                <td><?=substr($member_info['mm_birth'],0,10)?></td>
                                <th scope="col">주민/외국인 번호</th>
                                <td><?=$enc->decrypt($member_info['mm_serial_no'])?></td>
                                <th scope="col">국적</th>
                                <td><?=getNationTag_v2($member_info['mm_country'],'국적')?></td>
                            </tr>
                            <tr>
                                <th scope="col">우편번호</th>
                                <td><?=$member_info['mm_post']?></td>
                                <th scope="col">주소</th>
                                <td><?=$enc->decrypt($member_info['mm_address'])?></td>
                                <th scope="col">상세주소</th>
                                <td><?=$enc->decrypt($member_info['mm_address_detail'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">휴대폰 번호</th>
                                <td><?=$enc->decrypt($member_info['mm_phone'])?></td>
                                <th scope="col">전화 번호</th>
                                <td><?=$enc->decrypt($member_info['mm_cell_phone'])?></td>
                                <th scope="col">성별</th>
                                <td><?=getGenderTag_v2($member_info['mm_gender'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">영문명</th>
                                <td><?=$member_info['mm_en_name']?></td>
                                <th scope="col">이메일 주소</th>
                                <td><?=$enc->decrypt($member_info['mm_email'])?></td>
                                <th scope="col">거주국가</th>
                                <td><?=getNationTag_v3($member_info['mm_from'],'거주 국가')?></td>
                            </tr>
                            <tr>
                                <th scope="col">부서</th>
                                <td>
                                    <select name="mm_group">
                                        <?foreach ($group_list as $val){?>
                                            <option value="<?=$val['tg_seq']?>" <?if($val['tg_seq']==$member_info['mm_group']){?>selected<?}?>><?=$val['tg_title']?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <th scope="col">직책</th>
                                <td>
                                    <select name="mm_position">
                                        <?foreach ($position_list as $val){?>
                                            <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mm_position']){?>selected<?}?>><?=$val['tp_title']?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <th scope="col">비상 연락처</th>
                                <td>
                                    <div class="insert">
                                        <label class="label">관계 :</label>
                                        <?=$member_info['mm_prepare_relation']?>

                                        <label class="label">연락처 :</label>
                                        <?=$enc->decrypt($member_info['mm_prepare_phone'])?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col">입사일</th>
                                <td><?=substr($member_info['mm_regdate'],0,10)?></td>
                                <th scope="col">승진일</th>
                                <td><?=substr($member_info['mm_bepromoted_date'],0,10)?></td>
                                <th scope="col">직군 / 직무</th>
                                <td><input type="text" class="input-text" name="" value="직군 / 직무"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
        </form>
        <form id="info_form2">
        <div class="section-wrap">
            <h3 class="section-title">가족사항</h3>
            <!-- 가족 사항 -->
            <div id="tab-family" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(가족)</caption>
                        <colgroup>
                            <col style="width: 140px" />
                            <col style="width: 100px" />
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
                                <?foreach ($family_list as $val){?>
                                    <tr>
                                        <th scope="col">성명</th>
                                        <td><?=$enc->decrypt($val['mf_name'])?></td>
                                        <th scope="col">주민번호</th>
                                        <td><?=$enc->decrypt($val['mf_resident'])?></td>
                                        <th scope="col">생년월일</th>
                                        <td><?=$enc->decrypt($val['mf_birth'])?></td>
                                        <th scope="col">성별</th>
                                        <td>
                                            <?if($val['mf_gender']=='M' || empty($val['mf_gender'])){?>남성<?}?>
                                            <?if($val['mf_gender']=='F'){?>여성<?}?>
                                        </td>
                                        <th scope="col">외국인 여부</th>
                                        <td>
                                            <?if($val['mf_foreigner']=='T' || empty($val['mf_foreigner'])){?>내국인<?}?>
                                            <?if($val['mf_foreigner']=='F'){?>외국인<?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">가족관계</th>
                                        <td><?=$enc->decrypt($val['mf_relationship'])?></td>
                                        <th scope="col">인적공제 여부</th>
                                        <td>
                                            <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>인적공제O<?}?>
                                            <?if($val['mf_allowance']=='F'){?>인적공제x<?}?>
                                        </td>
                                        <th scope="col">동거 여부</th>
                                        <td>
                                            <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>동거O<?}?>
                                            <?if($val['mf_together']=='F'){?>동거X<?}?>
                                        </td>
                                        <th scope="col">학력</th>
                                        <td colspan=3><?=$enc->decrypt($val['mf_education'])?></td>
                                    </tr>
                                <?}?>
                            </tbody>
                    </table>
                </div>
            </div>
            <!-- 가족 사항 -->
        </div>
        </form>
        <form id="info_form3">
        <div class="section-wrap">
            <h3 class="section-title">어학 / 자격증</h3>
            <!-- 어학 / 자격증 사항 -->
            <div id="tab-cert" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(어학 / 자격증)</caption>
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
                                <?foreach ($certificate_list as $key =>$val){?>
                                    <tr>
                                        <th scope="col">자격증 명</th>
                                        <td><?=$enc->decrypt($val['mct_cert_name'])?></td>
                                        <th scope="col">취득기관</th>
                                        <td><?=$enc->decrypt($val['mct_institution'])?></td>
                                        <th scope="col">등급</th>
                                        <td><?=$enc->decrypt($val['mct_class'])?></td>
                                        <th scope="col">취득일</th>
                                        <td><?=substr($val['mct_date'],0,10)?></td>
                                    </tr>
                                <?}?>
                            </tbody>
                    </table>
                </div>
            </div>
            <!-- 어학 / 자격증 사항 -->
		</div>
        </form>
        <form id="info_form4">
        <div class="section-wrap">
            <h3 class="section-title">사외경력</h3>
            <!-- 사외경력 사항 -->
            <div id="tab-another" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(사외경력)</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">회사명</th>
                                <th scope="col">재직 시작일</th>
                                <th scope="col">재직 종료일</th>
                                <th scope="col">직급</th>
                                <th scope="col">직무</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($career_list as $key =>$val){?>
                            <tr>
                                <td><?=$enc->decrypt($val['mc_company'])?></td>
                                <td><?=substr($val['mc_sdate'],0,10)?></td>
                                <td><?=substr($val['mc_edate'],0,10)?></td>
                                <td><?=$enc->decrypt($val['mc_position'])?></td>
                                <td><?=$enc->decrypt($val['mc_duties'])?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 사외경력 사항 -->
		</div>
        </form>
        <form id="info_form5">
        <div class="section-wrap">
            <h3 class="section-title">학력</h3>
            <!-- 학력 사항 -->
            <div id="tab-education" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(학력)</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">학교명</th>
                                <th scope="col">재직 기간</th>
                                <th scope="col">학교 등급</th>
                                <th scope="col">학위</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($education_list as $key =>$val){?>
                            <tr>
                                <td><?=$enc->decrypt($val['me_name'])?></td>
                                <td>
                                    <?=substr($val['me_sdate'],0,10)?>&nbsp;-&nbsp;<?=substr($val['me_edate'],0,10)?>
                                </td>
                                <td>
                                    <?if($val['me_level']=='1' || empty($val['me_level'])){?>고등학교<?}?>
                                    <?if($val['me_level']=='2'){?>대학교<?}?>
                                    <?if($val['me_level']=='3'){?>전문대<?}?>
                                    <?if($val['me_level']=='4'){?>대학원<?}?>
                                </td>
                                <td><?=$enc->decrypt($val['me_degree'])?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 학력 사항 -->
		</div>
        </form>
        <form id="info_form6">
        <div class="section-wrap">
            <h3 class="section-title">발령</h3>
            <!-- 발령 사항 -->
            <div id="tab-issuance" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-issuance left">
                        <caption>발령</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">발령일자</th>
                                <th scope="col">구분</th>
                                <th scope="col">발령회사 및 부서</th>
                                <th scope="col">직위</th>
                                <th scope="col">담당직무</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($appointment_list as $key =>$val){?>
                            <tr>
                                <td><?=substr($val['ma_date'],0,10)?></td>
                                <td><?=$val['ma_type']?></td>
                                <td><?=$enc->decrypt($val['ma_company'])?></td>
                                <td><?=$val['ma_position']?></td>
                                <td><?=$val['ma_job']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 발령 사항 -->
		</div>
        </form>
        <form id="info_form7">
        <div class="section-wrap">
            <h3 class="section-title">교육 / 활동</h3>
            <!-- 교육 / 활동 사항 -->
            <div id="tab-activity" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-activity left">
                        <caption>교육 / 활동</caption>
                        <colgroup>
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 5%" />
                            <col style="width: 20%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">구분</th>
                                <th scope="col">교육/활동명</th>
                                <th scope="col">기관명</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($activity_list as $key =>$val){?>
                            <tr>
                                <td class="insert"><?=substr($val['mad_sdate'],0,10)?></td>
                                <td class="insert"><?=substr($val['mad_edate'],0,10)?></td>
                                <td class="insert"><?=$val['mad_type']?></td>
                                <td scope="insert"><?=$enc->decrypt($val['mad_name'])?></td>
                                <td scope="insert"><?=$enc->decrypt($val['mad_institution'])?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 교육 / 활동 사항 -->
		</div>
        </form>
        <form id="info_form8">
        <div class="section-wrap">
            <h3 class="section-title">논문 / 저서</h3>
            <!-- 논문 / 저서 사항 -->
            <div id="tab-paper" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-paper left">
                        <caption>논문 / 저서</caption>
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
            <!-- 논문 / 저서 사항 -->
		</div>
        </form>
        <form id="info_form9">
        <div class="section-wrap">
            <h3 class="section-title">프로젝트</h3>
            <!-- 프로젝트 사항 -->
            <div id="tab-project" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-project left">
                        <caption>프로젝트</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 3%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">시작일</th>
                                <th scope="col">종료일</th>
                                <th scope="col">프로젝트명</th>
                                <th scope="col">기여도</th>
                                <th scope="col">역할</th>
                                <th scope="col">결과</th>
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
                                <td scope="insert"><?=$val['mpd_result']?></td>
                                <td scope="insert"><?=$val['mpd_position']?></td>
                                <td scope="insert"><?=$val['mpd_result']?></td>
                                <td scope="insert"><?=$val['mpd_content']?></td>
                                <td scope="insert"><?=$val['mpd_keyword']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 프로젝트 사항 -->
		</div>
        </form>
        <form id="info_form10">
        <div class="section-wrap">
            <h3 class="section-title">기타</h3>
            <!-- 기타 사항 -->
            <div id="tab-etc" class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>기타</caption>
                        <colgroup>
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">기타내용</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="insert">
                                    <?if(empty($member_info['mm_note'])){?>
                                        인사담당자가 자유롭게 해당 사람에 대한 특이내용을 기재할 수 있도록 하는 란이 필요&#10;별도의 탭으로 빼도 되고, 아니면 기본사항 하단부에 노출해도 무방할 것 같음
                                    <?}else{?>
                                        <?=$member_info['mm_note']?>
                                    <?}?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기타 사항 -->
		</div>
        </form>
	</div>
	<!-- // 프린트 영역 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
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

    $('#aside-menu .tree-wrap>li:eq(0)').addClass('active');
    $('#mm_birth, .input-datepicker').datepicker();

    $(document).ready(function(){
        //출력 함수
        function makeHtml(){
            const obj = {html : ''}; 
            obj.html = $('#content-print').html();
            return obj;
        }
        function reportPrint(param){
            const setting = "width=1200, height=841";
            const objWin = window.open('', 'print', setting);
            objWin.document.open();
            objWin.document.write('<html><head><title>HLB</title>');
            objWin.document.write('<link rel="stylesheet" type="text/css" href="http://211.253.26.40/@resource/css/style.css"/>');
            objWin.document.write('</head><body>');
            objWin.document.write(param.html);
            objWin.document.write('</body></html>');
            objWin.focus(); 
            objWin.document.close();
        
            setTimeout(function(){objWin.print();objWin.close();}, 1000);
        }

        $('#btn-print').click(function(){
            const completeParam = makeHtml();
            reportPrint(completeParam);
            
        });
    });

    $.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // Skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // Adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // Push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // Fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // Named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };

    $('#btn_save').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#info_form1,#info_form2,#info_form3,#info_form4,#info_form5,#info_form6,#info_form7,#info_form8,#info_form9').find('.input-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');

            if(val==""){
                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }
        });

        var data1 = $('#info_form1').serializeObject();
        var data2 = $('#info_form2').serializeObject();
        var data3 = $('#info_form3').serializeObject();
        var data4 = $('#info_form4').serializeObject();
        var data5 = $('#info_form5').serializeObject();
        var data6 = $('#info_form6').serializeObject();
        var data7 = $('#info_form7').serializeObject();
        var data8 = $('#info_form8').serializeObject();
        var data9 = $('#info_form9').serializeObject();
        var data10 = $('#info_form10').serializeObject();

        var datas = {'seq':<?=$_REQUEST['seq']?>,'info':data1,'family':data2,'sert':data3,'career':data4,'edu':data5,'appoint':data6,'activity':data7,'paper':data8,'project':data9,'etc':data10};
        if(validate){
            //if(confirm("추가사항을 임시저장을 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/hass/appointment_update_proc.php", datas);
            //}
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='appointment_update_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
               alert(result.msg);
               location.reload();
            }
        }
    }
</script>

