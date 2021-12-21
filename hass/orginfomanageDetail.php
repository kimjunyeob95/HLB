<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/info_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$seq = $_REQUEST['seq'];
$page = $_REQUEST['page'];

$member_info = get_member_info($db,$seq); // 개인정보
$family_list = get_family_list($db,$seq); // 가족사항
$certificate_list = get_certificate_list($db,$seq); // 어학 / 자격증
$career_list = get_career_list($db,$seq); // 사외경력
$education_list = get_education_list($db,$seq); // 학력
$appointment_list = get_appointment_list($db,$seq); // 발령
$activity_list = get_activity_list($db,$seq); // 교육 / 활동
$paper_list = get_paper_list($db,$seq); // 논문 / 저서
$project_list = get_project_list($db,$seq); // 프로젝트
$punishment_list = get_punishment_list($db,$seq); // 상벌
$evaluation_list = get_evaluation_list($db,$seq); // 인사평가
$premier_list = get_premier_list($db,$seq); // 수상
$group_list = get_group_list($db); //부서
$member_group_list = get_member_group($db,$seq);
$position_list = get_position_list($db,1); //직책
$position_list2 = get_position_list($db,2); //직위
$position_list3 = get_position_list($db,3); //직군
$position_list4 = get_position_list($db,4); //고용
$position_list5 = get_position_list($db,5); //사원
$list = get_group_list_all($db);
$query = "select count(*) as cnt from ess_member_code em join tbl_ess_group  te on em.mc_group = te.tg_seq where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and mc_coseq = {$_SESSION['mInfo']['mc_coseq']}";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);
$group_cnt = $data['cnt'];
 $group_check_list = array();
foreach ($member_group_list as $val){
    array_push($group_check_list, $val['tg_seq']);
}
function has_children($rows,$id) {
    foreach ($rows as $row) {
        if ($row['tg_parent_seq'] == $id)
            return true;
    }
    return false;
}
function group_check($seq){
    global $group_check_list;
    if(in_array($seq,$group_check_list)){
        return 'checked';
    }
}
//echo('<pre>');print_r($activity_list);echo('</pre>');
function build_menu($rows,$parent=0)
{
    global $group_check_list;
    $result='';
    foreach ($rows as $row) {
        // echo('<pre>');print_r($row);echo('</pre>');
        if ($row['tg_parent_seq'] == $parent) {
              $result .=  '<ul>';
              $result .=  '<li>';
              $result .=  '<div class="insert">';
              if($row['tg_parent_seq']==0){
                  $result .= '<label class="label">'.$row['tg_title'].'</label>';
              }else{
                  $result .= '<input type="text" class="input-text" name="" value="'.$row['tg_title'].'">';
              }

              if($row['tg_parent_seq']!=0) {
                $result .= '<input type="checkbox" name="checkbox" '.group_check($row['tg_seq'],$group_check_list).'  class="btn_view" data-title="'.$row['tg_title'].'" data-id="'.$row['tg_seq'].'">';
              }
              $result .= '<button class="btn type01 small btn-toggle minus">접기</button>';
              $result .= '</div>';
            if (has_children($rows, $row['tg_seq'])) {
                $result .= build_menu($rows, $row['tg_seq']);
            }
            $result .= "</li></ul>";
        }
    }

    return $result;
}
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<style>
    #info_form2 table{margin-top: 15px; border-top: 1px solid #d1d1d1;}
    #info_form2 table:first-child{margin-top: 0px; border-top: none;}
    .table-form-activity div.uploader{width: auto !important;}
    #content-print {display: none;}
    .section-wrap{position: relative;}
    .section-wrap .section-title{display: inline-block;}
    .section-wrap .btn-aside{left:140px; top: 35px;}
    div.checker{margin-left: 6px; margin-top: 4px;}
    table.hide{display: none;}
    td .file-wrap{padding: 8px;}
</style>
<div id="wrap" class="depth03">

<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">임직원 상세관리</h2>
        <div class="section-wrap">
            <h3 class="section-title">계정 관리</h3>
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>계정 관리</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">계정 처리</th>
                                <td scope="insert">
                                    <select id="select-login" >
                                        <option value="F" <?if($member_info['mm_login_status']=='F'){?>selected<?}?>>로그인 가능</option>
                                        <option value="T" <?if($member_info['mm_login_status']=='T'){?>selected<?}?>>로그인 불가능</option>
                                    </select>
                                    <button type="button" id="btn-login" data-type="login_status" style="margin-left:1vw;" class="btn type10 small">저장</button>
                                </td>
                                <th scope="col">비밀번호초기화</th>
                                <td scope="insert">
                                    <button type="button" id="btn-password" data-type="password_reset" style="vertical-align: sub;" class="btn type10 small">비밀번호 초기화</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
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
                                    <th scope="col">이미지</th>
                                    <td>
                                        <img id="presonnel-img" src="<?=$member_info['mm_profile']?>" width="100" height="130">
                                        <input type="file" id="input_image" name="mm_profile" accept="image/x-png,image/gif,image/jpeg">
                                    </td>
                                    <th scope="col">사번</th>
                                    <td><input type="text" class="input-text chk-text"  disabled value="<?=$member_info['mc_code']?>"></td>
                                    <th scope="col">성명</th>
                                    <td>
                                        <div class="insert">
                                            <label class="label">한글 :</label>
                                            <input type="text" class="input-text chk-text" <?=$enc->decrypt($member_info['mm_password'])?> name="mm_name" title="성명 한글" value="<?=$enc->decrypt($member_info['mm_name'])?>" style="width: 60%;">
                                            <br><br>
                                            <label class="label">영문 :</label>
                                            <input type="text" class="input-text chk-text" name="mm_en_name" title="성명 영문" value="<?=$member_info['mm_en_name']?>" style="width: 60%;">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">소속</th>
                                    <td>
                                        <button type="button" id="btn-group" style="vertical-align: sub;" class="btn type10 medium">소속 선택</button>
                                        <div class="wrap_mc_group">
                                            <?foreach ($member_group_list as $val){?>
                                            <label class="label">소속 :</label>
                                            <input type="text" class="input-text mc_group chk-text" disabled  title="소속"
                                            value="<?=$val['tg_title']?>">
                                            <input type="hidden" name="mm_group[]" value="<?=$val['tg_seq']?>">
                                            <?}?>
                                        </div>
                                    </td>
                                    <th scope="col">직위 및 직책</th>
                                    <td>
                                        <label class="label" style="vertical-align: sub;">직위 :</label>
                                        <select name="mc_position2">
                                            <?foreach ($position_list2 as $val){?>
                                                <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mc_position2']){?>selected<?}?>><?=$val['tp_title']?></option>
                                            <?}?>
                                        </select>
                                        <br>
                                        <label class="label" style="vertical-align: sub;">직책 :</label>
                                        <select name="mc_position">
                                            <?foreach ($position_list as $val){?>
                                                <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mc_position']){?>selected<?}?>><?=$val['tp_title']?></option>
                                            <?}?>
                                        </select>
                                        <br>
                                    </td>
                                    <th scope="col">직무 및 직군</th>
                                    <td>
                                        <div class="insert">
                                            <label class="label">직무 :</label>
                                            <input type="text" class="input-text" name="mc_job" title="직무" value="<?=$member_info['mc_job']?>" style="width: 60%;">
                                            <br>
                                            <label class="label">직군 :</label>
                                            <select name="mc_position3" >
                                                <?foreach ($position_list3 as $val){?>
                                                    <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mc_position3']){?>selected<?}?>><?=$val['tp_title']?></option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">그룹 입사일</th>
                                    <td><input type="text" class="input-text input-datepicker chk-text" readonly title="그룹 입사일" name="mc_affiliate_date" style="max-width:50%;" value="<?=substr($member_info['mc_affiliate_date'],0,10)?>"></td>
                                    <th scope="col">자사 입사일</th>
                                    <td><input type="text" class="input-text input-datepicker chk-text" readonly title="자사 입사일" name="mc_regdate" style="max-width:50%;" value="<?=substr($member_info['mc_regdate'],0,10)?>"></td>
                                    <th scope="col">최종 승진일</th>
                                    <td><input type="text" class="input-text input-datepicker chk-text" readonly title="최종 승진일" name="mc_bepromoted_date" style="max-width:50%;" value="<?=substr($member_info['mc_bepromoted_date'],0,10)?>"></td>
                                </tr>
                                <tr>
                                    <th scope="col">재직상태</th>
                                    <td>
                                        <select name="mm_status" >
                                            <?foreach ($member_state_v2 as $key =>$val){?>
                                                <option value="<?=$key?>" <?if($key==$member_info['mm_status']){?>selected<?}?>><?=$val?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                    <th scope="col">퇴사일</th>
                                    <td><?=substr($member_info['mm_retirement_date'],0,10)?></td>
                                    <th scope="col">고용구분</th>
                                    <td>
                                        <select name="mc_position4" >
                                            <?foreach ($position_list4 as $val){?>
                                                <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mc_position4']){?>selected<?}?>><?=$val['tp_title']?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">사원유형</th>
                                    <td>
                                        <select name="mc_position5" >
                                            <?foreach ($position_list5 as $val){?>
                                                <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$member_info['mc_position5']){?>selected<?}?>><?=$val['tp_title']?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                    <th scope="col">성별</th>
                                    <td><?=getGenderTag_v2($member_info['mm_gender'])?></td>
                                    <th scope="col">최종학력</th>
                                    <td>
                                        <select name="mm_education">
                                            <option value='1' <?if($member_info['mm_education']==1){?>selected<?}?>>고졸</option>
                                            <option value='2' <?if($member_info['mm_education']==2){?>selected<?}?>>전문학사</option>
                                            <option value='3' <?if($member_info['mm_education']==3){?>selected<?}?>>학사</option>
                                            <option value='4' <?if($member_info['mm_education']==4){?>selected<?}?>>석사</option>
                                            <option value='5' <?if($member_info['mm_education']==5){?>selected<?}?>>박사</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">생년월일</th>
                                    <td><input type="text" title="생년월일" class="input-text input-datepicker chk-text" name="mm_birth"  style="max-width:50%;"  value="<?=substr($member_info['mm_birth'],0,10)?>"></td>
                                    <th scope="col">주민/외국인 번호</th>
                                    <td><input class="input-text chk-text" type="number" title="주민등록번호" name="mm_serial_no" value="<?=$enc->decrypt($member_info['mm_serial_no'])?>" placeholder="-없이 입력"></td>
                                    <th scope="col">국적</th>
                                    <td><?=getNationTag_v2($member_info['mm_country'],'국적')?></td>
                                </tr>
                                <tr>
                                    <th scope="col">우편번호</th>
                                    <td><input type="text" name="mm_post" id="mm_post" title="우편번호" class="input-text chk-text" value="<?=$member_info['mm_post']?>"></td>
                                    <th scope="col">주소</th>
                                    <td><input type="text" name="mm_address" id="mm_address" title="주소" class="input-text chk-text" value="<?=$enc->decrypt($member_info['mm_address'])?>"></td>
                                    <th scope="col">상세주소</th>
                                    <td><input type="text" name="mm_address_detail" title="상세주소" class="input-text chk-text" value="<?=$enc->decrypt($member_info['mm_address_detail'])?>" placeholder="상세 주소"></td>
                                </tr>
                                <tr>
                                    <th scope="col">연락처</th>
                                    <td><input type="number" class="input-text chk-text" title="연락처" name="mm_cell_phone" value="<?=$enc->decrypt($member_info['mm_cell_phone'])?>"></td>
                                    <th scope="col">이메일 주소</th>
                                    <td><input type="text" class="input-text chk-text"  title="이메일" name="mm_email" value="<?=$enc->decrypt($member_info['mm_email'])?>"></td>
                                    <th scope="col">비상 연락처</th>
                                    <td>
                                        <div class="insert">
                                            <label class="label">관계 :</label>
                                            <input type="text" class="input-text chk-text" name="mm_prepare_relation" title="비상연락처 관계" value="<?=$member_info['mm_prepare_relation']?>" style="width: 24%;">
                                            <br>
                                            <label class="label">연락처 :</label>
                                            <input type="number" class="input-text chk-text" name="mm_prepare_phone" title="비상연락처 연락처" value="<?=$enc->decrypt($member_info['mm_prepare_phone'])?>" style="width: 40%;">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 기본 사항 -->
		    </div>

            <div class="section-wrap">
                <h3 class="section-title">근태사항</h3>
                <!-- 근태사항 사항 -->
                <div id="tab-holiday" class="tab-cont">
                    <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table left">
                            <caption>근태사항</caption>
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">연차적용 시작일자</th>
                                    <td><input type="text" title="연차적용시작일자" class="input-text input-datepicker chk-text" title="연차적용 시작일자"  name="mc_commute_sdate" readonly value="<?=substr($member_info['mc_commute_sdate'],0,10)?>"></td>
                                    <th scope="col">연차적용 종료일자</th>
                                    <td><input type="text" title="연차적용종료일자" class="input-text input-datepicker chk-text" title="연차적용 종료일자"  name="mc_commute_edate" readonly value="<?=substr($member_info['mc_commute_edate'],0,10)?>"></td>
                                    <th scope="col"></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="col">전체휴가일수</th>
                                    <td><input type="number" title="전체휴가일수" class="input-text chk-text" title="전체 휴가" name="mc_commute_all" value="<?=$member_info['mc_commute_all']?>"></td>
                                    <th scope="col">사용휴가일수</th>
                                    <td><input type="number" title="사용휴가일수" class="input-text chk-text" title="사용 휴가" name="mc_commute_use" value="<?=$member_info['mc_commute_use']?>"></td>
                                    <th scope="col">잔여휴가일수</th>
                                    <td><input type="number" title="잔여휴가일수" class="input-text chk-text" title="남은 휴가" name="mc_commute_remain" value="<?=$member_info['mc_commute_remain']?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 병력 사항 -->
            </div>

            <div class="section-wrap">
                <h3 class="section-title">병력</h3>
                <!-- 병력 사항 -->
                <div id="tab-army" class="tab-cont">
                    <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table left">
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
                                        <select name="mm_arm_type" >
                                            <?foreach($arm_type as $key => $val){?>
                                                <option value='<?=$key?>' <?if($key==$member_info['mm_arm_type']){?>selected<?}?>><?=$val?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                    <td class="insert"><input title = "입대일" value="<?=substr($member_info['mm_arm_sdate'],0,10)?>" type="text" class="input-text input-datepicker chk-text" name="mm_arm_sdate" readonly></td>
                                    <td class="insert"><input title = "제대일" value="<?=substr($member_info['mm_arm_edate'],0,10)?>" type="text" class="input-text input-datepicker chk-text" name="mm_arm_edate" readonly></td>
                                    <td scope="insert" class="center">
                                        <select name="mm_arm_group" >
                                            <?foreach($arm_group as $key => $val){?>
                                                <option value='<?=$key?>' <?if($key==$member_info['mm_arm_group']){?>selected<?}?>><?=$val?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                    <td scope="insert" class="center">
                                        <select name="mm_arm_class" >
                                            <?foreach($arm_class as $key => $val){?>
                                                <option value='<?=$key?>' <?if($key==$member_info['mm_arm_class']){?>selected<?}?>><?=$val?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                    <td scope="insert">
                                        <input title = "병과" value="<?=$member_info['mm_arm_discharge']?>" type="text" class="input-text" name="mm_arm_discharge">
                                    </td>
                                    <td scope="insert"><input title = "기타" value="<?=$member_info['mm_arm_reason']?>" type="text" class="input-text" name="mm_arm_reason"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 병력 사항 -->
            </div>

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
                            <select name="mm_disorder_1" >
                                <?foreach($disorder_type_1 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_disorder_1']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class="center">
                            <select name="mm_disorder_2" >
                                <?foreach($disorder_type_2 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_disorder_2']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class='center' >
                            <select name="mm_disorder_3" >
                                <?foreach($disorder_type_3 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_disorder_3']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class='center'>
                            <select  name="mm_nation_1" >
                                <?foreach($nation_type_1 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_nation_1']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class="center">
                            <select  name="mm_nation_2" >
                                <?foreach($nation_type_2 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_nation_2']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>                          
        </form>
        <form id="form_evaluation">
        <div class="section-wrap">
            <h3 class="section-title">인사평가</h3>
            <div class="btn-aside">
                <a data-btn="evaluation" class="btn type01 small btn-add-tr" href="#">추가</a>
                <a data-btn="evaluation" class="btn type01 small btn-remove-tr" href="#">삭제</a>
            </div>
            <!-- 인사평가 사항 -->
            <div id="tab-evaluation" class="tab-evaluation">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-evaluation left <?if(empty($evaluation_list)){echo 'hide';}?>"">
                        <caption>인사평가</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <thead >
                        <tr>
                            <th rowspan=2 scope="col">선택</th>
                            <th rowspan=2 scope="col">연도</th>
                            <th rowspan=2 scope="col">소속</th>
                            <th colspan=2 scope="col">1차평가</th>
                            <th rowspan=2 scope="col">평가의견</th>
                            <th colspan=2 scope="col">2차평가</th>
                            <th rowspan=2 scope="col">평가의견</th>
                            <th rowspan=2 scope="col">파일첨부</th>
                        </tr>
                        <tr>
                            <th scope="col">평가자</th>
                            <th scope="col">평가등급</th>
                            <th scope="col">평가자</th>
                            <th scope="col">평가등급</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($evaluation_list as $val){?>
                        <tr>
                            <td class="center"><input type="checkbox" name="checkbox" data-index=0></td>
                            <td><input type="text" class="input-text chk-text" title="연도"  name="me_year[]"  value="<?=$val['me_year']?>"></td>
                            <td><input type="text" class="input-text chk-text" title="소속"  name="me_group[]"  value="<?=$val['me_group']?>"></td>
                            <td><input type="text" class="input-text chk-text" title="평가자"  name="me_admin_1[]"  value="<?=$val['me_admin_1']?>"></td>
                            <td class="center">
                                <select name="me_class_1[]">
                                    <?foreach($evaluation_class_array as $key => $val2){?>
                                        <option value='<?=$key?>' <?if($key==$val['me_class_1']){?>selected<?}?>><?=$val2?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td><input type="text" class="input-text chk-text" title="평가의견"  name="me_etc1[]"  value="<?=$val['me_etc1']?>"></td>
                            <td><input type="text" class="input-text chk-text" title="평가자"  name="me_admin_2[]"  value="<?=$val['me_admin_2']?>"></td>
                            <td class="center">
                                <select name="me_class_2[]" >
                                    <?foreach($evaluation_class_array as $key => $val2){?>
                                        <option value='<?=$key?>' <?if($key==$val['me_class_2']){?>selected<?}?>><?=$val2?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td><input type="text" class="input-text chk-text" title="평가의견"  name="me_etc2[]"  value="<?=$val['me_etc2']?>"></td>
                            <td>
                                <div class="file-wrap">
                                    <a href="<?=$val['me_file_src']?>" download><?=$val['me_file_name']?></a><br>
                                </div>
                                <input type="file" class="file me_file_input" name="me_file_name[]">
                                <input type="hidden" name="me_file_remain[]" value="<?=$val['me_file_src']?>">
                                <input type="hidden" name="me_file_name_remain[]" value="<?=$val['me_file_name']?>">
                            </td>
                        </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 인사평가 사항 -->
        </div>
        </form>
        <form id="info_form2">
            <div class="section-wrap">
                <h3 class="section-title">가족사항</h3>
                <div class="btn-aside">
                    <a data-btn="family" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="family" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 가족 사항 -->
                <div id="tab-family" class="tab-cont">
                    <div id="family_form" class="table-wrap table-wrap-family" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-family left <?if(empty($family_list)){echo 'hide';}?>">
                            <caption>정보 변경 상세(가족)</caption>
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
                                    <td class="insert"><input type="text" class="input-text" name="mf_name[]" value="<?=$enc->decrypt($val['mf_name'])?>"></td>
                                    <td class="center">
                                        <select name="mf_relationship[]" >
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
                                        <input type="text" title="가족생년월일" class="input-text input-datepicker chk-text"  name="mf_birth[]" readonly value="<?=$enc->decrypt($val['mf_birth'])?>">
                                    </td>
                                    <td class="center">
                                        <select  name="mf_allowance[]" >
                                            <option value='T' <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>selected<?}?>>대상</option>
                                            <option value='F' <?if($val['mf_allowance']=='F'){?>selected<?}?>>비대상</option>
                                        </select>
                                    </td>
                                    <td class="center">
                                        <select  name="mf_together[]">
                                            <option value='T' <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>selected<?}?>>동거</option>
                                            <option value='F' <?if($val['mf_together']=='F'){?>selected<?}?>>비동거</option>
                                        </select>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 가족 사항 -->
            </div>
        </form>
        <div class="section-wrap">
            <h3 class="section-title">증빙서류</h3>
            <!-- 증빙서류 -->
            <div class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>증빙서류</caption>
                        <colgroup>
                            <col style="width: 10%" />
                            <col style="width: *" />
                        </colgroup>
                        <tbody>
                            <?if(!empty($member_info['mm_file1'])){?>
                            <tr>
                                <th scope="col">주민등록등본</th>
                                <td><a href=<?=$member_info['mm_file1']?> download="<?=$member_info['mm_file1_name']?>" target="_blank"><?=$member_info['mm_file1_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                            </tr>
                            <?}?>
                            <?if(!empty($member_info['mm_file2'])){?>
                            <tr>
                                <th scope="col">가족관계증명서</th>
                                <td><a href='<?=$member_info['mm_file2']?>' download="<?=$member_info['mm_file2_name']?>" target="_blank"><?=$member_info['mm_file2_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                            </tr>
                            <?}?>
                            <?if(!empty($member_info['mm_file3'])){?>
                            <tr>
                                <th scope="col">기타</th>
                                <td><a href='<?=$member_info['mm_file3']?>' download="<?=$member_info['mm_file3_name']?>" target="_blank"><?=$member_info['mm_file3_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                            </tr>
                            <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 증빙서류 사항 -->
        </div>

        <form id="info_form5">
            <div class="section-wrap">
                <h3 class="section-title">학력사항</h3>
                <div class="btn-aside">
                    <a data-btn="education" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="education" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 학력 사항 -->
                <div id="tab-education" class="tab-cont">
                    <div class="table-wrap" id="education_form" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-education left <?if(empty($education_list)){echo 'hide';}?>">
                            <caption>정보 변경 상세(학력사항)</caption>
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
                                    <th scope="col">기타</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($education_list as $index => $val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td><input type="text" title="입학일" class="input-text input-datepicker chk-text" name="me_sdate[]" readonly value="<?=substr($val['me_sdate'],0,10)?>"></td>
                                    <td>
                                        <input type="text" title="졸업일" class="input-text input-datepicker chk-text" name="me_edate[]" readonly value="<?=substr($val['me_edate'],0,10)?>">
                                    </td>
                                    <td class="insert"><input type="text" title="학교명" class="input-text chk-text" name="me_name[]"  value="<?=$val['me_name']?>" style=""></td>
                                    <td>
                                        <select name="me_level[]">
                                            <option value='1' <?if($val['me_level']=='1' || empty($val['me_level'])){?>selected<?}?>>고등학교</option>
                                            <option value='2' <?if($val['me_level']=='2'){?>selected<?}?>>전문대학</option>
                                            <option value='3' <?if($val['me_level']=='3'){?>selected<?}?>>대학교</option>
                                            <option value='4' <?if($val['me_level']=='4'){?>selected<?}?>>대학원</option>
                                        </select>
                                    </td>
                                    <td class="insert"><input type="text" title="전공" class="input-text chk-text" name="me_major[]" value="<?=$val['me_major']?>"></td>
                                    <td class="insert">
                                        <select name="me_degree[]">
                                            <option value='1' <?if($val['me_degree']=='1' || empty($val['me_degree'])){?>selected<?}?>>없음</option>
                                            <option value='2' <?if($val['me_degree']=='2'){?>selected<?}?>>고등학교</option>
                                            <option value='3' <?if($val['me_degree']=='3'){?>selected<?}?>>전문학사</option>
                                            <option value='4' <?if($val['me_degree']=='4'){?>selected<?}?>>학사</option>
                                            <option value='5' <?if($val['me_degree']=='5'){?>selected<?}?>>석사</option>
                                            <option value='6' <?if($val['me_degree']=='6'){?>selected<?}?>>박사</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="me_graduate_type[]">
                                            <option value='1' <?if($val['me_graduate_type']=='1'){?>selected<?}?>>졸업</option>
                                            <option value='2' <?if($val['me_graduate_type']=='2'){?>selected<?}?>>재학</option>
                                            <option value='3' <?if($val['me_graduate_type']=='3'){?>selected<?}?>>수료</option>
                                            <option value='4' <?if($val['me_graduate_type']=='4'){?>selected<?}?>>중퇴</option>
                                            <option value='5' <?if($val['me_graduate_type']=='5'){?>selected<?}?>>졸업예정</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="me_weekly[]">
                                            <option value='1'<?if($val['me_weekly']=='1'){?>selected<?}?>>해당없음</option>
                                            <option value='2'<?if($val['me_weekly']=='2'){?>selected<?}?>>주간</option>
                                            <option value='3'<?if($val['me_weekly']=='3'){?>selected<?}?>>야간</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="input-text" name="me_etc[]" title="기타" value="<?=$val['me_etc']?>">
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                <!-- 학력 사항 -->
            </div>
        </form>

        <form id="info_form4">
            <div class="section-wrap">
                <h3 class="section-title">경력사항</h3>
                <div class="btn-aside">
                    <a data-btn="another" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="another" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 경력사항 사항 -->
                <div id="tab-another" class="tab-cont" >
                    <div id="career_form" class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
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
                                        <input type="text" title="경력시작일" class="input-text input-datepicker chk-text" name="mc_sdate[]" readonly value="<?=substr($val['mc_sdate'],0,10)?>">
                                    </td>
                                    <th scope="col">종료일</th>
                                    <td>
                                        <input type="text" title="경력졸업일" class="input-text input-datepicker chk-text"  name="mc_edate[]" readonly value="<?=substr($val['mc_edate'],0,10)?>">
                                    </td>
                                    <th scope="col">회사명</th>
                                    <td class="insert"><input type="text" class="input-text"  name="mc_company[]" value="<?=$enc->decrypt($val['mc_company'])?>" style=""></td>
                                    <th scope="col">근무부서</th>
                                    <td class="insert"><input type="text" class="input-text" name="mc_group[]" value="<?=$val['mc_group']?>"></td>
                                    <th scope="col">최종직위</th>
                                    <td class="insert"><input type="text" class="input-text" name="mc_position[]" value="<?=$enc->decrypt($val['mc_position'])?>" style=""></td>
                                    <th scope="col">담당업무</th>
                                    <td class="insert"><input type="text" class="input-text" name="mc_duties[]" value="<?=$enc->decrypt($val['mc_duties'])?>" style=""></td>
                                </tr>
                                <tr> 
                                    <th scope="col">경력기술</th>
                                    <td class="insert" colspan=11><input type="text" class="input-text"  name="mc_career[]" value="<?=$val['mc_career']?>" style=""></td>
                                </tr>
                            </tbody>
                        </table>
                        <?}?>
                    </div>
                </div>
                <!-- 경력사항 사항 -->
            </div>
        </form>

        <form id="info_form3">
            <div class="section-wrap">
                <h3 class="section-title">어학 / 자격증</h3>
                <div class="btn-aside">
                    <a data-btn="cert" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="cert" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 어학 / 자격증 사항 -->
                <div id="tab-cert" class="tab-cont" >
                    <div id="cert_form" class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-cert left <?if(empty($certificate_list)){echo 'hide';}?>">
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
                                    <td class="insert"><input type="text" class="input-text" name="mct_cert_name[]"  value="<?=$val['mct_cert_name']?>" style=""></td>
                                    <td>
                                        <input type="text" title="자격증취득일" class="input-text input-datepicker chk-text" name="mct_date[]" readonly value="<?=substr($val['mct_date'],0,10)?>">
                                    </td>
                                    <td class="insert"><input type="text" class="input-text"  name="mct_class[]" value="<?=$enc->decrypt($val['mct_class'])?>" style=""></td>
                                    <td class="insert"><input type="text" class="input-text"  name="mct_institution[]" value="<?=$enc->decrypt($val['mct_institution'])?>" style=""></td>
                                    <td class="insert"><input type="text" class="input-text"  name="mct_num[]" value="<?=$val['mct_num']?>"></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 어학 / 자격증 사항 -->
            </div>
        </form>
        <form id="info_form15">
            <div class="section-wrap">
                <h3 class="section-title">수상경력</h3>
                <div class="btn-aside">
                    <a data-btn="prize2" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="prize2" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 수상경력 -->
                <div id="tab-prize2" class="tab-cont" >
                    <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-prize2 left <?if(empty($premier_list)){echo 'hide';}?>" style="border-top:1px solid #d1d1d1;">
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
                            <?foreach($premier_list as $key => $val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=0></td>
                                    <td class="insert"><input title = "수상일자" value="<?=substr($val['mpd_date'],0,10)?>" type="text" class="input-text input-datepicker chk-text" name="mpd_date[]" readonly></td>
                                    <td class="insert"><input title="수상내용" type="text" class="input-text"  name="mpd_content[]" value="<?=$val['mpd_content']?>"></td>
                                    <td class="insert"><input title="수상기관" type="text" class="input-text"  name="mpd_institution[]" value="<?=$val['mpd_institution']?>"></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 수상경력 -->
            </div>
        </form>
        <form id="info_form7">
            <div class="section-wrap">
                <h3 class="section-title">교육 / 활동</h3>
                <div class="btn-aside">
                    <a data-btn="activity" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="activity" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 교육 / 활동 사항 -->
                <div id="tab-activity" class="tab-cont">
                    <div id="activity_form" class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-activity left <?if(empty($activity_list)){echo 'hide';}?>">
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
                                            <select name='mad_type[]'>
                                                <option value='1' <?if($val['mad_type']=='1' || empty($val['mad_type'])){?>selected<?}?>>사외</option>
                                                <option value='2' <?if($val['mad_type']=='2' || empty($val['mad_type'])){?>selected<?}?>>사내</option>
                                            </select>
                                        </td>  
                                        <td class="insert"><input type="text" title="활동시작일" class="input-text input-datepicker chk-text" name="mad_sdate[]" readonly value="<?=substr($val['mad_sdate'],0,10)?>"></td>
                                        <td class="insert"><input type="text" title="활동종료일" class="input-text input-datepicker chk-text" name="mad_edate[]" readonly value="<?=substr($val['mad_edate'],0,10)?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mad_name[]"  value="<?=$enc->decrypt($val['mad_name'])?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mad_institution[]"  value="<?=$enc->decrypt($val['mad_institution'])?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mad_role[]"  value="<?=$val['mad_role']?>"></td>
                                        <td scope="insert">
                                        <?if(!empty($val['mad_file'])){?>
                                            <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span>
                                        <?}?>
                                            <input type="hidden" name="mad_file_remain[]" value="<?=$val['mad_file']?>">
                                            <input type="hidden" name="mad_file_name_remain[]" value="<?=$val['mad_file_name']?>">
                                           <!-- <input type="file" class="file-activity" name="mad_file[]">
                                           <input type="hidden" name="mad_file_remain[]" value=""><?=$val['mad_file']?>
                                           <input type="hidden" name="mad_file_name_remain[]" value=""><?$val['mad_file_name']?> -->
                                        </td>
                                    </tr>
                                <?}?>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 교육 / 활동 사항 -->
            </div>
        </form>

        <form id="info_form6">
            <div class="section-wrap">
                <h3 class="section-title">발령</h3>
                <div class="btn-aside">
                    <a data-btn="issuance" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="issuance" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 발령 사항 -->
                <div id="tab-issuance" class="tab-cont">
                    <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-issuance left">
                            <caption>발령</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 5%" />
                                <col style="width: 1%" />
                                <col style="width: 10%" />
                                <col style="width: 8%" />
                                <col style="width: 8%" />
                                <col style="width: 8%" />
                                <col style="width: 8%" />
                            </colgroup>
                            <thead class="<?if(empty($appointment_list)){echo 'hide';}?>" >
                                <tr>
                                    <th scope="col">선택</th>
                                    <th scope="col">발령일자</th>
                                    <th scope="col">발령구분</th>
                                    <th scope="col">발령회사</th>
                                    <th scope="col">직위</th>
                                    <th scope="col">담당직군</th>
                                    <th scope="col">비고</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?foreach ($appointment_list as $index =>$val){?>
                                    <tr>
                                        <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                        <td class="insert"><input type="text" title="발령일자" class="input-text input-datepicker chk-text" name="ma_date[]" style="max-width:80%;" readonly value="<?=substr($val['ma_date'],0,10)?>"></td>
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
                                        <td scope="insert"><input type="text" class="input-text" name="ma_position2[]"  value="<?=$val['ma_position2']?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="ma_position3[]"  value="<?=$val['ma_position3']?>"></td>
                                        <td scope="insert"><input title="상벌비고" type="text" class="input-text" name="ma_etc[]"  value="<?=$val['ma_etc']?>"></td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 발령 사항 -->
            </div>
        </form>

        <form id="info_form8">
            <div class="section-wrap">
                <h3 class="section-title">논문 / 저서</h3>
                <div class="btn-aside">
                    <a data-btn="paper" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="paper" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 논문 / 저서 사항 -->
                <div id="tab-paper" class="tab-cont">
                    <div id="paper_form" class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-paper left">
                            <caption>논문 / 저서</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 8%" />
                                <col style="width: 20%" />
                                <col style="width: 10%" />
                            </colgroup>
                            <thead class="<?if(empty($paper_list)){echo 'hide';}?>" >
                                <tr>
                                    <th scope="col">선택</th>
                                    <th scope="col">발행일</th>
                                    <th scope="col">논문 및 저서명</th>
                                    <th scope="col">발행정보</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($paper_list as $index =>$val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td class="insert"><input type="text" title="발행일" class="input-text input-datepicker chk-text" name="mp_date[]" style="max-width:80%;" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
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
                <div class="btn-aside">
                    <a data-btn="project" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="project" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 프로젝트 사항 -->
                <div id="tab-project" class="tab-cont">
                    <div id="project_form" class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
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
                            <thead class="<?if(empty($project_list)){echo 'hide';}?>" >
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
                            <?foreach ($project_list as $index =>$val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td class="insert"><input type="text" title="프로젝트시작일" class="input-text input-datepicker chk-text" name="mpd_sdate[]" readonly value="<?=substr($val['mpd_sdate'],0,10)?>"></td>
                                    <td class="insert"><input type="text" title="프로젝트종료일" class="input-text input-datepicker chk-text" name="mpd_edate[]" readonly value="<?=substr($val['mpd_edate'],0,10)?>"></td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 프로젝트 사항 -->
            </div>
        </form>
        
        <form id="info_form10">
            <div class="section-wrap">
                <h3 class="section-title">상벌</h3>
                <div class="btn-aside">
                    <a data-btn="prize" class="btn type01 small btn-add-tr" href="#">추가</a>
                    <a data-btn="prize" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                </div>
                <!-- 상벌 사항 -->
                <div id="tab-prize" class="tab-cont">
                    <div id="prize_form" class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                        <table class="data-table table-form-prize left">
                            <caption>상벌</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                                <col style="width: 3%" />
                                <col style="width: 8%" />
                                <col style="width: 10%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead class="<?if(empty($punishment_list)){echo 'hide';}?>">
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
                            <?foreach ($punishment_list as $index =>$val){?>
                                <tr>
                                    <td class="center"><input type="checkbox" name="checkbox" data-index=<?=$index?>></td>
                                    <td scope="insert">
                                        <select name='mp_type[]'>
                                            <option value='1' <?if($val['mp_type']=='1'){?>selected<?}?>>상</option>
                                            <option value='2' <?if($val['mp_type']=='2'){?>selected<?}?>>벌</option>
                                        </select>
                                    </td>
                                    <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker chk-text" name="mp_date[]" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
                                    <td scope="insert"><input type="text" title="상벌명" class="input-text" name="mp_title[]" value="<?=$val['mp_title']?>"></td>
                                    <td scope="insert"><input type="text" title="내용" class="input-text" name="mp_content[]" value="<?=$val['mp_content']?>"></td>
                                    <td scope="insert"><input type="text" title="비고" class="input-text" name="mp_etc[]" value="<?=$val['mp_etc']?>"></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- 상벌 사항 -->
            </div>
        </form>

        <form id="info_form12">
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
                                        <textarea class="text-area" name="mm_note" rows=5><?=$member_info['mm_note']?></textarea>
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
                <button type="button" data-btn="목록" onclick="location.href='/hass/orginfomanage?page=<?=$page?>'" class="btn type01 large">목록<span class="ico apply"></span></button>
                <button type="button" data-btn="저장" id="btn_save" class="btn type01 large btn-footer">저장<span class="ico save"></span></button>
                <button type="button" data-btn="출력" id="btn-print" class="btn type01 large btn-footer">출력<span class="ico print"></span></button>
            </div>
        </div>
	</div>
    <!-- // 내용 -->
</div>
<!-- // CONTENT -->

<!-- 부서팝업-->
<div class="new-layer-popup group-popup">
    <div class="layer-wrap">
        <div class="popup-wrap">
            <button type="button" class="pop-close">닫기</button>
            <h1>소속 관리</h1>
            <button type="button" id="btn_chk_save" class="btn type01 large">선택<span class="ico check01"></span></button>
            <div class="section-wrap" style="padding-top: 28px;">
                <div class="add-wrap">
                    <?=build_menu($list);?>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- // 부서팝업 -->

<!-- 프린트 영역 -->        
<div id="content-print" class="content-primary" style="display: none;">
    <div class="print-wrap">
        <h2 class="content-title">임직원 상세</h2>
        <div class="section-wrap">
            <h3 class="section-title">기본사항</h3>
            <!-- 기본 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>정보 변경 상세(기본사항)</caption>
                        <colgroup>
                            <col style="width: 80px" />
                            <col style="width: 20%" />
                            <col style="width: 80px" />
                            <col style="width: 20%" />
                            <col style="width: 80px" />
                            <col style="width: 20%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">이미지</th>
                                <td class="left"><img src="<?=$member_info['mm_profile']?>" width="100" height="130"></td>
                                <th scope="col">사번</th>
                                <td><?=$member_info['mc_code']?></td>
                                <th scope="col">성명</th>
                                <td>
                                    <div class="insert">
                                        <label class="label">한글 :</label>
                                        <?=$enc->decrypt($member_info['mm_name'])?>
                                        <br>
                                        <label class="label">영문 :</label>
                                        <?=$member_info['mm_en_name']?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col">소속</th>
                                <td>
                                    <?foreach ($member_group_list as $val){?>
                                        <?=$val['tg_title']?>
                                    <?}?>
                                </td>
                                <th scope="col">직위 및 직책</th>
                                <td>
                                    <label class="label">직위 :</label>
                                    <?=get_position_title_type($db,$member_info['mc_position2'],2)?>
                                    <br>
                                    <label class="label">직책 :</label>
                                    <?=get_position_title($db,$member_info['mc_position'])?>
                                    <br>
                                </td>
                                <th scope="col">직군 / 직무</th>
                                <td>
                                    <div class="insert">
                                        <label class="label">직군 :</label>
                                        <?=$member_info['mc_job']?>
                                        <br>
                                        <label class="label">직무 :</label>
                                        <?=get_position_title_type($db,$member_info['mc_position3'],3)?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col">그룹 입사일</th>
                                <td><?=substr($member_info['mc_affiliate_date'],0,10)?></td>
                                <th scope="col">자사 입사일</th>
                                <td><?=substr($member_info['mc_regdate'],0,10)?></td>
                                <th scope="col">최종 승진일</th>
                                <td><?=substr($member_info['mc_bepromoted_date'],0,10)?></td>
                            </tr>
                            <tr>
                                <th scope="col">재직상태</th>
                                <td><?=$member_state_v2[$member_info['mm_status']]?></td>
                                <th scope="col">퇴사일</th>
                                <td><?=substr($member_info['mm_retirement_date'],0,10)?></td>
                                <th scope="col">고용구분</th>
                                <td><?=get_position_title_type($db,$member_info['mc_position4'],4)?></td>
                            </tr>
                            <tr>
                                <th scope="col">사원유형</th>
                                <td><?=get_position_title_type($db,$member_info['mc_position5'],5)?></td>
                                <th scope="col">성별</th>
                                <td><?=getGenderText($member_info['mm_gender'])?></td>
                                <th scope="col">최종학력</th>
                                <td><?=$degree_level3[$member_info['mm_education']]?></td>
                            </tr>
                            <tr>
                                <th scope="col">생년월일</th>
                                <td><?=substr($member_info['mm_birth'],0,10)?></td>
                                <th scope="col">주민/외국인 번호</th>
                                <td><?=$enc->decrypt($member_info['mm_serial_no'])?></td>
                                <th scope="col">국적</th>
                                <td><?=text_country($db,$member_info['mm_country'])?></td>
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
                                <td><?=$enc->decrypt($member_info['mm_cell_phone'])?></td>
                                <th scope="col">이메일 주소</th>
                                <td><?=$enc->decrypt($member_info['mm_email'])?></td>
                                <th scope="col">비상 연락처</th>
                                <td>
                                    <div class="insert">
                                        <label class="label">관계 :</label>
                                        <?=$member_info['mm_prepare_relation']?>
                                        <br>
                                        <label class="label">연락처 :</label>
                                        <?=$enc->decrypt($member_info['mm_prepare_phone'])?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기본 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">근태사항</h3>
            <!-- 근태사항 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>근태사항</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">연차적용 시작일자</th>
                                <td><?=substr($member_info['mc_commute_sdate'],0,10)?></td>
                                <th scope="col">연차적용 종료일자</th>
                                <td><?=substr($member_info['mc_commute_edate'],0,10)?></td>
                                <th scope="col"></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="col">전체휴가일수</th>
                                <td><?=$member_info['mc_commute_all']?></td>
                                <th scope="col">사용휴가일수</th>
                                <td><?=$member_info['mc_commute_use']?></td>
                                <th scope="col">잔여휴가일수</th>
                                <td><?=$member_info['mc_commute_remain']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 근태사항 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">병력</h3>
            <!-- 병력 사항 -->
            <div class="tab-cont">
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
                                <td class="insert"><?=substr($member_info['mm_arm_sdate'],0,10)?></td>
                                <td class="insert"><?=substr($member_info['mm_arm_edate'],0,10)?></td>
                                <td scope="insert" class="center">
                                    <?=$arm_group[$member_info['mm_arm_group']]?>
                                </td>
                                <td scope="insert" class="center">
                                    <?=$arm_class[$member_info['mm_arm_class']]?>
                                </td>
                                <td scope="insert">
                                    <?=$member_info['mm_arm_discharge']?>
                                </td>
                                <td scope="insert"><?=$member_info['mm_arm_reason']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 병력 사항 -->
        </div>
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
        <div class="section-wrap">
            <h3 class="section-title">인사평가</h3>
            <!-- 인사평가 사항 -->
            <div class="tab-evaluation">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-evaluation left <?if(empty($evaluation_list)){echo 'hide';}?>"">
                        <caption>인사평가</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead >
                        <tr>
                            <th rowspan=2 scope="col">연도</th>
                            <th rowspan=2 scope="col">소속</th>
                            <th colspan=2 scope="col">1차평가</th>
                            <th rowspan=2 scope="col">평가의견</th>
                            <th colspan=2 scope="col">2차평가</th>
                            <th rowspan=2 scope="col">평가의견</th>
                            <th rowspan=2 scope="col">파일첨부</th>
                        </tr>
                        <tr>
                            <th scope="col">평가자</th>
                            <th scope="col">평가등급</th>
                            <th scope="col">평가자</th>
                            <th scope="col">평가등급</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($evaluation_list as $val){?>
                        <tr>
                            <td class="center"><?=$val['me_year']?></td>
                            <td class="center"><?=$val['me_group']?></td>
                            <td class="center"><?=$val['me_admin_1']?></td>
                            <td class="center">
                                <?
                                    foreach($evaluation_class_array as $key => $val2){
                                        if($key==$val['me_class_1']){ echo ''.$val2; };
                                    }
                                ?>
                            </td>
                            <td class="center"><?=$val['me_etc1']?></td>
                            <td class="center"><?=$val['me_admin_2']?></td>
                            <td class="center">
                                <?foreach($evaluation_class_array as $key => $val2){
                                   if($key==$val['me_class_2']){ echo ''.$val2; };
                                }?>
                            </td>
                            <td class="center"><?=$val['me_etc2']?></td>
                            <td class="center">
                                <div class="file-wrap">
                                    <a href="<?=$val['me_file_src']?>" download><?=$val['me_file_name']?></a><br>
                                </div>
                            </td>
                        </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 인사평가 사항 -->
        </div>                          
        <div class="section-wrap">
            <h3 class="section-title">가족사항</h3>
            <!-- 가족 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-family-list left">
                        <caption>정보 변경 상세(가족)</caption>
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
                                <td><?=$family_type_array[$val['mf_relationship']]?></td>
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
            <!-- 가족 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">증빙서류</h3>
            <!-- 증빙서류 -->
            <div class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-family-list left">
                        <caption>증빙서류</caption>
                        <colgroup>
                            <col style="width: 10%" />
                            <col style="width: *" />
                        </colgroup>
                        <tbody>
                            <?if(!empty($member_info['mm_file1'])){?>
                            <tr>
                                <th scope="col">
                                    <?if($member_info['mm_file_type_1']==1){?>가족관계증명서<?}?>
                                    <?if($member_info['mm_file_type_1']==2){?>주민등록등본<?}?>
                                </th>
                                <td><a href=<?=$member_info['mm_file1']?> download="<?=$member_info['mm_file1_name']?>" target="_blank"><?=$member_info['mm_file1_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                            </tr>
                            <?}?>
                            <?if(!empty($member_info['mm_file2'])){?>
                            <tr>
                                <th scope="col">
                                    <?if($member_info['mm_file_type_2']==1){?>가족관계증명서<?}?>
                                    <?if($member_info['mm_file_type_2']==2){?>주민등록등본<?}?>
                                </th>
                                <td><a href='<?=$member_info['mm_file2']?>' download="<?=$member_info['mm_file2_name']?>" target="_blank"><?=$member_info['mm_file2_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                            </tr>
                            <?}?>
                            <?if(!empty($member_info['mm_file3'])){?>
                            <tr>
                                <th scope="col">
                                    <?if($member_info['mm_file_type_3']==1){?>가족관계증명서<?}?>
                                    <?if($member_info['mm_file_type_3']==2){?>주민등록등본<?}?>
                                </th>
                                <td><a href='<?=$member_info['mm_file3']?>' download="<?=$member_info['mm_file3_name']?>" target="_blank"><?=$member_info['mm_file3_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                            </tr>
                            <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 증빙서류 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">학력</h3>
            <!-- 학력 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-education left">
                        <caption>정보 변경 상세(학력)</caption>
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
                                <th scope="col">학력</th>
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
                                <td>
                                    <?=substr($val['me_sdate'],0,10)?>
                                </td>
                                <td>
                                    <?=substr($val['me_edate'],0,10)?>
                                </td>
                                <td><?=$val['me_name']?></td>
                                <td>
                                    <?=$degree_level[$val['me_level']]?>
                                </td>
                                <td>
                                    <?=$val['me_major']?>
                                </td>
                                <td><?=$degree_level2[$val['me_degree']]?></td>
                                <td>
                                    <?=$graduate_type_array[$val['me_graduate_type']]?>
                                </td>
                                <td>
                                    <?foreach($weekly_array as $key => $val2){?>
                                        <?if($key==$val['me_weekly']){echo $val2;};?>
                                    <?}?>
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
            <!-- 학력 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">경력사항</h3>
            <!-- 경력사항 사항 -->
            <div class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                <?foreach ($career_list as $key =>$val){?>
                    <table class="data-table table-form-another left">
                        <caption>정보 변경 상세(경력사항)</caption>
                        <colgroup>
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
                                <th scope="col">시작일</th>
                                <td><?=substr($val['mc_sdate'],0,10)?></td>
                                <th scope="col">종료일</th>
                                <td><?=substr($val['mc_edate'],0,10)?></td>
                                <th scope="col">회사명</th>
                                <td><?=$enc->decrypt($val['mc_company'])?></td>
                                <th scope="col">근무부서</th>
                                <td><?=$val['mc_group']?></td>
                                <th scope="col">최종직위</th>
                                <td><?=$enc->decrypt($val['mc_position'])?></td>
                                <th scope="col">담당업무</th>
                                <td><?=$enc->decrypt($val['mc_duties'])?></td>
                            </tr>
                            <tr>
                                <th scope="col">경력기술</th>
                                <td colspan=11><?=$val['mc_career']?></td>
                            </tr>
                        </tbody>
                    </table>
                <?}?>
                </div>
            </div>
            <!-- 경력사항 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">어학 / 자격증</h3>
            <!-- 어학 / 자격증 사항 -->
            <div class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                
                    <table class="data-table table-form-cert left">
                        <caption>정보 변경 상세(어학 / 자격증)</caption>
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
                                    <td><?=$val['mct_cert_name']?></td>
                                    <td><?=substr($val['mct_date'],0,10)?></td>
                                    <td><?=$enc->decrypt($val['mct_class'])?></td>
                                    <td><?=$enc->decrypt($val['mct_institution'])?></td>
                                    <td><?=$val['mct_num']?></td>
                                </tr>
                                <?}?>
                            </tbody>
                    </table>
                </div>
            </div>
            <!-- 어학 / 자격증 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">수상경력</h3>
            <!-- 수상경력 -->
            <div class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
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
                                <td class="insert"><?=substr($val['mpd_date'],0,10)?></td>
                                <td class="insert"><?=$val['mpd_content']?></td>
                                <td class="insert"><?=$val['mpd_institution']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 수상경력 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">교육 / 활동</h3>
            <!-- 교육 / 활동 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
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
                        <?foreach ($activity_list as $key =>$val){?>
                            <tr>
                                <td class="insert">
                                    <?if($val['mad_type']=='1'){ echo '사외';}else{echo '사내';}?>
                                </td>
                                <td class="insert"><?=substr($val['mad_sdate'],0,10)?></td>
                                <td class="insert"><?=substr($val['mad_edate'],0,10)?></td>
                                <td scope="insert"><?=$enc->decrypt($val['mad_name'])?></td>
                                <td scope="insert"><?=$enc->decrypt($val['mad_institution'])?></td>
                                <td scope="insert"><?=$val['mad_role']?></td>
                                <td scope="insert">
                                    <?if(!empty($val['mad_file'])){?>
                                        <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>&nbsp;<span class="ico down"></span>
                                    <?}else{?>
                                        -
                                    <?}?>
                                </td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 교육 / 활동 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">논문 / 저서</h3>
            <!-- 논문 / 저서 사항 -->
            <div class="tab-cont">
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
        <div class="section-wrap">
            <h3 class="section-title">프로젝트</h3>
            <!-- 프로젝트 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-project left">
                        <caption>프로젝트</caption>
                        <colgroup>
                            <col style="width: 4%" />
                            <col style="width: 4%" />
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
                                <td class="insert" style="text-align: center;"><?=$enc->decrypt($val['mpd_name'])?></td>
                                <td scope="insert" style="text-align: center;"><?=$val['mpd_institution']?></td>
                                <td scope="insert" style="text-align: center;"><?=$val['mpd_contribution']?></td>
                                <td scope="insert" style="text-align: center;">
                                    <?if($val['mpd_result']=="1"){?>진행중<?}?>
                                    <?if($val['mpd_result']=="2"){?>완료<?}?>
                                    <?if($val['mpd_result']=="3"){?>보류<?}?>
                                    <?if($val['mpd_result']=="4"){?>취소<?}?>
                                </td>
                                <td scope="insert" style="text-align: center;"><?=$val['mpd_position']?></td>
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
        <div class="section-wrap">
            <h3 class="section-title">발령</h3>
            <!-- 발령 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table table-form-issuance left">
                        <caption>발령</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                            <col style="width: 8%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">발령일자</th>
                                <th scope="col">발령구분</th>
                                <th scope="col">발령회사</th>
                                <th scope="col">직위</th>
                                <th scope="col">담당직무</th>
                                <th scope="col">비고</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($appointment_list as $key =>$val){?>
                            <tr>
                                <td><?=substr($val['ma_date'],0,10)?></td>
                                <td>
                                    <?if($val['ma_type']=='1'){?>입사<?}?>
                                    <?if($val['ma_type']=='2'){?>보임<?}?>
                                    <?if($val['ma_type']=='3'){?>승진<?}?>
                                    <?if($val['ma_type']=='4'){?>강등<?}?>
                                    <?if($val['ma_type']=='5'){?>겸직<?}?>
                                    <?if($val['ma_type']=='6'){?>전직<?}?>
                                    <?if($val['ma_type']=='7'){?>전근<?}?>
                                    <?if($val['ma_type']=='8'){?>전출<?}?>
                                    <?if($val['ma_type']=='9'){?>전적<?}?>
                                    <?if($val['ma_type']=='10'){?>전보<?}?>
                                </td>
                                <td><?=$enc->decrypt($val['ma_company'])?></td>
                                <td><?=$val['ma_position2']?></td>
                                <td><?=$val['ma_position3']?></td>
                                <td><?=$val['ma_etc']?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 발령 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">상벌</h3>
            <!-- 상벌 사항 -->
            <div class="tab-cont">
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1;">
                    <table class="data-table left">
                        <caption>기타</caption>
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
            <!-- 상벌 사항 -->
        </div>
        <div class="section-wrap">
            <h3 class="section-title">기타</h3>
            <!-- 기타 사항 -->
            <div class="tab-cont">
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
                                    <?=$member_info['mm_note']?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 기타 사항 -->
        </div>
    </div>
</div>
<!-- // 프린트 영역 -->

<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $(document).on('change','.me_file_input',function(){
        $(this).parent().siblings('.file-wrap').remove();;
    });
    $('#btn-login, #btn-password').click(function(){
        if(confirm('정말로 처리하시겠습니까?')){
            var login_status = $('#select-login').val();
            var type = $(this).data('type');
            if(type !='password_reset'){
                data = {
                    'type' : type,
                    'mmseq': <?=$_REQUEST['seq']?>,
                    'login_status' : login_status
                };
            }else{
                data = {
                    'type' : type,
                    'mmseq': <?=$_REQUEST['seq']?>
                };
            }
            
            $.ajax({
                url : "/@proc/hass/member_infoProc.php",
                data : data,
                dataType : "json",
                method : 'POST',
                success : function(response){
                    alert('처리되었습니다.');
                    location.reload();
                }
            })
        }
    })

    $('#input_image').change(function(){
        readURL(this);
    })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var fileName = $("#input_image").val();
                fileName = fileName.slice(fileName.indexOf(".") + 1).toLowerCase();
                if(fileName != "jpg" && fileName != "png" &&  fileName != "gif" &&  fileName != "bmp"){
                    alert("이미지 파일은 (jpg, png, gif, bmp) 형식만 등록 가능합니다.");
                    return false;
                }else{
                    $('#presonnel-img').attr('src',e.target.result);  
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

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
            objWin.document.write('<html><head><title></title>');
            objWin.document.write('<link rel="stylesheet" type="text/css" href="https://hrms.hlb-group.com/@resource/css/style.css"/>');
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

        // 팝업 열기 201209 추가
        $('#btn-group').on('click', function() {
            $('.group-popup').addClass('active');

            $('body, html').css('height','100%');
            $('body, html').css('overflow','hidden');
        });
        // 팝업 닫기 201209 추가
        $('.new-layer-popup .pop-close').on('click', function() {
            $(this).parents('.new-layer-popup').removeClass('active');
            $('body, html').css('height','auto');
            $('body, html').css('overflow','visible');
        });
        $('.btn-toggle').click(function(){
            if($(this).parent().next().length<1) return;
            if($(this).hasClass('minus')){
                $(this).removeClass('minus');
            }else{
                $(this).addClass('minus');
            }
            $(this).parent().siblings('ul').toggle();
        });

        $('#btn_chk_save').click(function(){
            $('.new-layer-popup').removeClass('active');
            $('body, html').css('height','auto');
            $('body, html').css('overflow','visible');
            html = '';
            $('input:checkbox.btn_view').each(function() {
                if(this.checked){//checked 처리된 항목의 값
                    html += '<label class="label">소속 :</label>\n' +
                        '    <input type="text" class="input-text mc_group" disabled  title="소속"\n' +
                        '    value="'+$(this).data('title')+'">' +
                        '    <input type="hidden" name="mm_group[]" value="'+$(this).data('id')+'"> ';
                }
            });
            $('.wrap_mc_group').html(html);
        })
        // $('.btn_view').click(function(){
        //     $('#input-mc_group').val($(this).prev().val());
        //     $('#input-mc_group_hide').val($(this).data('id'));
        // });
    });
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

    $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');
    $('#mm_birth, .input-datepicker').datepicker();

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


    //입력
    $('#btn_save').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#info_form1,#info_form2,#info_form3,#info_form4,#info_form5,#info_form7,#info_form8,#info_form9,#info_form10,#info_form15').find('.chk-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');
            if(val=="" && (txt!='병역구분' && txt!='면제(미필사유)'&& txt!='군별'&& txt!='계급'&& txt!='제대구분'&& txt!='입대일' && txt!='제대일' && txt!='기타'&& txt!='병과'&& txt!='기타'&& txt!='비고' )){

                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }
        });
        if(!validate){
            return false;
        }else{
            if(chk_email($('input[name="mm_email"]').val())==false){
                $('input[name="mm_email"]').focus();
                alert('올바르지 않은 이메일 형식입니다.');
                validate = false;
                return false;
            };
        }

        var form = $('#info_form1')[0];
        var data = new FormData(form);
        data.append('seq', <?=$_REQUEST['seq']?>);
        data.append('mm_note', $('[name="mm_note"]').val());
        if(validate) {
            hlb_fn_file_ajaxTransmit("/@proc/hass/employe_info_proc_v2.php", data);
        }
    })
    function info__data(){
        //var data1 = $('#info_form1').serializeObject(); // ,'info':data1
        var data2 = $('#info_form2').serializeObject();
        var data3 = $('#info_form3').serializeObject();
        var data4 = $('#info_form4').serializeObject();
        var data5 = $('#info_form5').serializeObject();
        var data6 = $('#info_form6').serializeObject();
        var data7 = $('#info_form7').serializeObject();
        var data8 = $('#info_form8').serializeObject();
        var data9 = $('#info_form9').serializeObject();
        var data10 = $('#info_form10').serializeObject();
        var data15 = $('#info_form15').serializeObject();
        var data12 = $('#info_form12').serializeObject();

        var datas = {'seq':<?=$_REQUEST['seq']?>,'family':data2,'sert':data3,'career':data4,'edu':data5,'appoint':data6,'activity':data7,'paper':data8,'project':data9,'punishment':data10,'premier':data15};
        hlb_fn_ajaxTransmit("/@proc/hass/employe_info_proc.php", datas);
    }
    function info__evaluation(){
        var form = $('#form_evaluation')[0];
        var data = new FormData(form);
        data.append('seq', <?=$_REQUEST['seq']?>);
        hlb_fn_file_ajaxTransmit("/@proc/hass/employe_info_evaluation_proc_v2.php", data);
    }

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='employe_info_proc_v2'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                info__evaluation();
                info__data();
               //alert(result.msg);
               //location.reload();
            }
        }
        if(calback_id=='employe_info_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.reload();
            }
        }
        if(calback_id=='employe_info_evaluation_proc_v2'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }
        }
    }

    //tr 추가
$('.btn-add-tr').click(function(e){
    e.preventDefault();
    
    var $this = $(this).data('btn');
    $('#content .table-form-'+$this).removeClass('hide');
    let $chk_index = $('#content .table-form-'+$this).length;
    if($this=='family'|| $this=='evaluation' || $this=='education' || $this=='cert' || $this=='prize2' || $this=='activity' || $this=='paper' || $this=='project' || $this=='prize' || $this =='army' || $this=='issuance'){
        $(this).parent().next().find('thead').removeClass('hide');
        $chk_index = $('#content .table-form-'+$this+' tbody tr').length;
    }
    var text_evaluation =
    '<tr>\n'+
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td><input type="text" class="input-text" title="연도"  name="me_year[]"  value=""></td>\n'+
    '    <td><input type="text" class="input-text" title="소속"  name="me_group[]"  value=""></td>\n'+
    '    <td><input type="text" class="input-text" title="평가자"  name="me_admin_1[]"  value=""></td>\n'+
    '    <td class="center">\n'+
    '        <select name="me_class_1[]" >\n'+
    '            <option value="S">S</option>\n'+
    '            <option value="A">A</option>\n'+
    '            <option value="B">B</option>\n'+
    '            <option value="C">C</option>\n'+
    '            <option value="D">D</option>\n'+
    '        </select>\n'+
    '    </td>\n'+
    '    <td><input type="text" class="input-text" title="평가의견"  name="me_etc1[]"  value=""></td>\n'+
    '    <td><input type="text" class="input-text" title="평가자"  name="me_admin_2[]"  value=""></td>\n'+
    '    <td class="center">\n'+
    '        <select name="me_class_2[]" >\n'+
    '            <option value="S">S</option>\n'+
    '            <option value="A">A</option>\n'+
    '            <option value="B">B</option>\n'+
    '            <option value="C">C</option>\n'+
    '            <option value="D">D</option>\n'+
    '        </select>\n'+
    '    </td>\n'+
    '    <td><input type="text" class="input-text" title="평가의견"  name="me_etc2[]"  value=""></td>\n'+
    '    <td><input type="file" class="file" name="me_file_name[]"></td>\n'+
    '</tr>';
    var text_family =
        '<tr>\n'+
        '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="mf_name[]" value=""></td>\n'+
        '    <td class="center">\n'+
        '        <select name="mf_relationship[]" >\n'+
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
        '        <input type="text" title="가족생년월일" class="input-text input-datepicker chk-text"  name="mf_birth[]" readonly value="">\n'+
        '    </td>\n'+
        '    <td class="center">\n'+
        '        <select  name="mf_allowance[]" >\n'+
        '            <option value="T">대상</option>\n'+
        '            <option value="F">비대상</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td class="center">\n'+
        '        <select  name="mf_together[]">\n'+
        '            <option value="T">동거</option>\n'+
        '            <option value="F">비동거</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '</tr>';

    var text_cert =
        '<tr>\n'+
        '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="mct_cert_name[]"  value="" style=""></td>\n'+
        '    <td>\n'+
        '        <input type="text" title="자격증취득일" class="input-text input-datepicker chk-text" name="mct_date[]" readonly value="">\n'+
        '    </td>\n'+
        '    <td class="insert"><input type="text" class="input-text"  name="mct_class[]" value="" style=""></td>\n'+
        '    <td class="insert"><input type="text" class="input-text"  name="mct_institution[]" value="" style=""></td>\n'+
        '    <td class="insert"><input type="text" class="input-text"  name="mct_num[]" value=""></td>\n'+
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
        '                <input type="text" title="경력시작일" class="input-text input-datepicker chk-text" name="mc_sdate[]" readonly value="">\n'+
        '            </td>\n'+
        '            <th scope="col">종료일</th>\n'+
        '            <td>\n'+
        '                <input type="text" title="경력종료일" class="input-text input-datepicker chk-text"  name="mc_edate[]" readonly value="">\n'+
        '            </td>\n'+
        '            <th scope="col">회사명</th>\n'+
        '            <td class="insert"><input type="text" class="input-text"  name="mc_company[]" value="" style=""></td>\n'+
        '            <th scope="col">근무부서</th>\n'+
        '            <td class="insert"><input type="text" class="input-text" name="mc_group[]" value=""></td>\n'+
        '            <th scope="col">최종직위</th>\n'+
        '            <td class="insert"><input type="text" class="input-text" name="mc_position[]" value="" style=""></td>\n'+
        '            <th scope="col">담당업무</th>\n'+
        '            <td class="insert"><input type="text" class="input-text" name="mc_duties[]" value="" style=""></td>\n'+
        '        </tr>\n'+
        '        <tr> \n'+
        '            <th scope="col">경력기술</th>\n'+
        '            <td class="insert" colspan=11><input type="text" class="input-text"  name="mc_career[]" value="" style=""></td>\n'+
        '        </tr>\n'+
        '    </tbody>\n'+
        '</table>';
    var text_education =
        '<tr>\n'+
        '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
        '    <td><input type="text" title="입학일" class="input-text input-datepicker chk-text" name="me_sdate[]" readonly value=""></td>\n'+
        '    <td>\n'+
        '        <input type="text" title="종료일" class="input-text input-datepicker chk-text" name="me_edate[]" readonly value="">\n'+
        '    </td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="me_name[]"  value="" style=""></td>\n'+
        '    <td>\n'+
        '        <select name="me_level[]">\n'+
        '            <option value="1">고등학교</option>\n'+
        '            <option value="2">전문대학</option>\n'+
        '            <option value="3">대학교</option>\n'+
        '            <option value="4">대학원</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td class="insert"><input type="text" class="input-text" name="me_major[]" value=""></td>\n'+
        '    <td class="insert">\n'+
        '        <select name="me_degree[]">\n'+
        '            <option value="1">없음</option>\n'+
        '            <option value="2">고등학교</option>\n'+
        '            <option value="3">전문학사</option>\n'+
        '            <option value="4">학사</option>\n'+
        '            <option value="5">석사</option>\n'+
        '            <option value="6">박사</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td>\n'+
        '        <select name="me_graduate_type[]">\n'+
        '            <option value="1">졸업</option>\n'+
        '            <option value="2">재학</option>\n'+
        '            <option value="3">수료</option>\n'+
        '            <option value="4">중퇴</option>\n'+
        '            <option value="5">졸업예정</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td>\n'+
        '        <select name="me_weekly[]">\n'+
        '            <option value="1">해당없음</option>\n'+
        '            <option value="2">주간</option>\n'+
        '            <option value="3">야간</option>\n'+
        '        </select>\n'+
        '    </td>\n'+
        '    <td>\n'+
        '        <input type="text" class="input-text" name="me_etc[]" value="">\n'+
        '    </td>\n'+
        '</tr>';
    var text_issuance = 
    '<tr>\n'+
    '<td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert"><input type="text" title="발령일자" class="input-text input-datepicker chk-text" name="ma_date[]" style="max-width:80%;" readonly value=""></td>\n'+
    '    <td class="insert">\n'+
    '        <select name="ma_type[]">\n'+
    '            <option value="1">입사</option>\n'+
    '            <option value="2">보임</option>\n'+
    '            <option value="3">승진</option>\n'+
    '            <option value="4">강등</option>\n'+
    '            <option value="5">겸직</option>\n'+
    '            <option value="6">전직</option>\n'+
    '            <option value="7">전근</option>\n'+
    '            <option value="8">전출</option>\n'+
    '            <option value="9">전적</option>\n'+
    '            <option value="10">전보</option>\n'+
    '        </select>\n'+
    '    </td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="ma_company[]"  value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="ma_position2[]"  value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="ma_position3[]"  value=""></td>\n'+
    '    <td scope="insert"><input title="상벌비고" type="text" class="input-text" name="ma_etc[]"  value=""></td>\n'+
    '</tr>';
    // var text_activity=
    // '<tr>\n'+
    // '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    // '    <td class="insert">\n'+
    // '        <select name="mad_type[]">\n'+
    // '            <option value="1">사외</option>\n'+
    // '            <option value="2">사내</option>\n'+
    // '        </select>\n'+
    // '    </td>\n'+
    // '    <td class="insert"><input type="text" class="input-text input-datepicker chk-text" name="mad_sdate[]" readonly value=""></td>\n'+
    // '    <td class="insert"><input type="text" class="input-text input-datepicker chk-text" name="mad_edate[]" readonly value=""></td>\n'+
    // '    <td scope="insert"><input type="text" class="input-text" name="mad_name[]"  value=""></td>\n'+
    // '    <td scope="insert"><input type="text" class="input-text" name="mad_institution[]"  value=""></td>\n'+
    // '    <td scope="insert"><input type="text" class="input-text" name="mad_role[]"  value=""></td>\n'+
    // '    <td scope="insert">\n'+
    // '        <input type="file" class="file-activity" name="mad_file[]">\n'+
    // '    </td>\n'+
    // '</tr>';
    var text_activity=
    '<tr>\n'+
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert">\n'+
    '        <select name="mad_type[]">\n'+
    '            <option value="1">사외</option>\n'+
    '            <option value="2">사내</option>\n'+
    '        </select>\n'+
    '    </td>\n'+
    '    <td class="insert"><input type="text" title="활동시작일" class="input-text input-datepicker chk-text" name="mad_sdate[]" readonly value=""></td>\n'+
    '    <td class="insert"><input type="text" title="활동종료일" class="input-text input-datepicker chk-text" name="mad_edate[]" readonly value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="mad_name[]"  value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="mad_institution[]"  value=""></td>\n'+
    '    <td scope="insert"><input type="text" class="input-text" name="mad_role[]"  value=""></td>\n'+
    '    <td scope="insert">\n'+
    '    </td>\n'+
    '</tr>';
    var text_paper=
    '<tr>\n'+
    '<td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '<td class="insert"><input type="text" title="발행일" class="input-text input-datepicker chk-text" name="mp_date[]" style="max-width:80%;" readonly value=""></td>\n'+
    '<td class="insert"><input type="text" class="input-text" name="mp_name[]"  value=""></td>\n'+
    '<td scope="insert"><input type="text" class="input-text" name="mp_institution[]"  value=""></td>\n'+
    '</tr>';
    var text_project=
    ' <tr>\n'+
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert"><input type="text" title="프로젝트시작일" class="input-text input-datepicker chk-text" name="mpd_sdate[]" readonly value=""></td>\n'+
    '    <td class="insert"><input type="text" title="프로젝트종료일" class="input-text input-datepicker chk-text" name="mpd_edate[]" readonly value=""></td>\n'+
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
        '                                    <select name=\'mp_type[]\'>\n' +
        '                                        <option value=\'1\'>상</option>\n' +
        '                                        <option value=\'2\'>벌</option>\n' +
        '                                    </select>\n' +
        '                                </td>\n' +
        '                                <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker chk-text" name="mp_date[]" readonly></td>\n' +
        '                                <td scope="insert"><input type="text" title="상벌명" class="input-text" name="mp_title[]" ></td>\n' +
        '                                <td scope="insert"><input type="text" title="내용" class="input-text" name="mp_content[]" ></td>\n' +
        '                                <td scope="insert"><input type="text" title="비고" class="input-text" name="mp_etc[]" ></td>\n' +
        '                            </tr>';
    var text_prize2=
    '<tr>\n' +
    '    <td class="center"><input type="checkbox" name="checkbox" data-index='+$chk_index+'></td>\n'+
    '    <td class="insert"><input title="수상일자" value="" type="text" class="input-text input-datepicker chk-text" name="mpd_date[]" readonly></td>\n'+
    '    <td class="insert"><input title="수상내용" type="text" class="input-text"  name="mpd_content[]" value=""></td>\n'+
    '    <td class="insert"><input title="수상기관" type="text" class="input-text"  name="mpd_institution[]" value=""></td>\n'+
    '</tr>';
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
        $('.table-form-activity tbody').append(text_activity);
    }else if($this == 'paper'){
        $('#tab-paper tbody').append(text_paper);
    }else if($this == 'project'){
        $('#tab-project tbody').append(text_project);
    }else if($this == 'prize'){
        $('#tab-prize tbody').append(text_prize);
    }else if($this == 'evaluation'){
        $('.table-form-evaluation tbody').append(text_evaluation);
    }else if($this == 'prize2'){
        $('#tab-prize2 tbody').append(text_prize2);
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
    let $this_index = $('#content .table-form-'+$this+' input[type="checkbox"]:checked');
    let $this_table = $('#content .table-form-'+$this);
    let $this_table_length = $('#content .table-form-'+$this).length;
    if($this=='family' || $this=='evaluation' || $this=='education' || $this=='cert' || $this=='prize2' || $this=='activity' || $this=='paper' || $this=='project' || $this=='prize' || $this =='army' || $this=='issuance'){
        $this_table = $('#content .table-form-'+$this+' tbody tr');
        $this_table_length = $('#content .table-form-'+$this+' tbody tr').length;
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
        if($this_index_array.length>=$this_table_length){
            $(this).parent().next().find('thead').addClass('hide');
        }
    }
    
    for(let i=0;i<$this_index_array.length;i++){
        $this_table.eq($this_index_array[i]).remove();
    }
    $this_table = $('#content .table-form-'+$this+' input[type="checkbox"]');
    if($this=='family' || $this=='evaluation' || $this=='education' || $this=='cert' || $this=='prize2' || $this=='activity' || $this=='paper' || $this=='project' || $this=='prize' || $this =='army' || $this=='issuance'){
        $this_table = $('#content .table-form-'+$this+' tbody tr input[type="checkbox"]');
    }
    $this_table.each(function(i,e){
        $(this).attr('data-index',i);
    });
});
</script>

