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
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");

$member_info = get_member_info($db,$seq); // 개인정보
$family_list = get_family_list($db,$seq); // 가족사항
$certificate_list = get_certificate_list($db,$seq); // 어학 / 자격증
$punishment_list = get_punishment_list($db,$seq); // 상벌
$career_list = get_career_list($db,$seq); // 경력사항
$education_list = get_education_list($db,$seq); // 학력
$appointment_list = get_appointment_list($db,$seq); // 발령
$activity_list = get_activity_list($db,$seq); // 교육 / 활동
$paper_list = get_paper_list($db,$seq); // 논문 / 저서
$project_list = get_project_list($db,$seq); // 프로젝트
$premier_list = get_premier_list($db,$seq); // 수상
$group_list = get_group_list($db);
$member_group_list = get_member_group($db,$seq);
$position_list = get_position_list($db,1);
$position_list2 = get_position_list($db,2); //직위
$position_list3 = get_position_list($db,3); //직무
$position_list4 = get_position_list($db,4); //고용
$position_list5 = get_position_list($db,5); //사원
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category']."&page=".$page;
$group_check_list = array();
foreach ($member_group_list as $val){
    array_push($group_check_list, $val['tg_seq']);
}

// echo('<pre>');print_r($member_info);echo('</pre>');

$list = get_group_list_all($db);
$query = "select count(*) as cnt from ess_member_code em join tbl_ess_group  te on em.mc_group = te.tg_seq where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and mc_coseq = {$_SESSION['mInfo']['mc_coseq']}";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);
$group_cnt = $data['cnt'];
function has_children($rows,$id) {
    foreach ($rows as $row) {
        if ($row['tg_parent_seq'] == $id)
            return true;
    }
    return false;
}
// echo('<pre>');print_r($list);echo('</pre>');
function group_check($seq){
    global $group_check_list;
    if(in_array($seq,$group_check_list)){
        return 'checked';
    }
}
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
<style>
    table tbody tr td img{height: 100px;}
    div.checker{margin-left: 6px; margin-top: 4px;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">신입 사원 승인 상세내역</h2>
        <?if($member_info['mm_status']=='S'){?>
            <button type="button" id="btn-resend" style="vertical-align: sub;" class="btn type10 medium">메일 재전송</button>
        <?}?>
        <form id="new_info_form">
		<div class="section-wrap">
            <h3 class="section-title">기본사항</h3>
            <!-- 기본 사항 -->
            <div class="tab-cont" >
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
                                    <button type="button" id="btn-group" style="vertical-align: sub;" class="btn type10 medium">소속 선택</button>
                                    <div class="wrap_mc_group">
                                        <?foreach ($member_group_list as $val){?>
                                            <label class="label">소속 :</label>
                                            <input type="text" id="input-mc_group" class="input-text mc_group" disabled  title="소속"
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
                                <th scope="col">직군 및 직무</th>
                                <td>
                                    <div class="insert">
                                        <label class="label">직군 :</label>
                                        <input type="text" class="input-text" name="mc_job" title="직군" value="<?=$member_info['mc_job']?>" style="width: 60%;">
                                        <br>
                                        <label class="label">직무 :</label>
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
                                <td><input type="text" class="input-text input-datepicker" readonly title="그룹 입사일" name="mc_affiliate_date" style="max-width:50%;" value="<?=substr($member_info['mc_affiliate_date'],0,10)?>"></td>
                                <th scope="col">자사 입사일</th>
                                <td><input type="text" class="input-text input-datepicker" readonly title="자사 입사일" name="mc_regdate" style="max-width:50%;" value="<?=substr($member_info['mc_regdate'],0,10)?>"></td>
                                <th scope="col">최종 승진일</th>
                                <td><input type="text" class="input-text input-datepicker" readonly title="최종 승진일" name="mc_bepromoted_date" style="max-width:50%;" value="<?=substr($member_info['mc_bepromoted_date'],0,10)?>"></td>
                            </tr>
                            <tr>
                                <th scope="col">재직상태</th>
                                <td>
                                    <select name="" >
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
                                <td><?=getGenderText($member_info['mm_gender'])?></td>
                                <th scope="col">최종학력</th>
                                <td>
                                    <?=$degree_level3[$member_info['mm_education']]?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col">생년월일</th>
                                <td><?=substr($member_info['mm_birth'],0,10)?></td>
                                <th scope="col">주민/외국인 번호</th>
                                <td><?=$enc->decrypt($member_info['mm_serial_no'])?></td>
                                <th scope="col">국적</th>
                                <td><?=getCountryText($member_info['mm_country'])?></td>
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
        <?if(!empty($member_info['mm_arm_type']) || !empty($member_info['mm_arm_reason']) || !empty($member_info['mm_arm_group']) || !empty($member_info['mm_arm_class'])
        || !empty($member_info['mm_arm_discharge']) || !empty($member_info['mm_arm_sdate']) || !empty($member_info['mm_arm_edate'])){?>

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
                                <td><input type="text" class="input-text input-datepicker" title="연차적용 시작일자"  name="mc_commute_sdate" readonly value="<?=substr($member_info['mc_commute_sdate'],0,10)?>"></td>
                                <th scope="col">연차적용 종료일자</th>
                                <td><input type="text" class="input-text input-datepicker" title="연차적용 종료일자"  name="mc_commute_edate" readonly value="<?=substr($member_info['mc_commute_edate'],0,10)?>"></td>
                                <th scope="col"></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="col">전체휴가일수</th>
                                <td><input type="number" class="input-text" title="전체 휴가" name="mc_commute_all" value="<?=$member_info['mc_commute_all']?>"></td>
                                <th scope="col">사용휴가일수</th>
                                <td><input type="number" class="input-text" title="사용 휴가" name="mc_commute_use" value="<?=$member_info['mc_commute_use']?>"></td>
                                <th scope="col">잔여휴가일수</th>
                                <td><input type="number" class="input-text" title="남은 휴가" name="mc_commute_remain" value="<?=$member_info['mc_commute_remain']?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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
        </form>
        <?}?>
        <form id="info_form2">
            <div class="section-wrap">
                <h3 class="section-title">가족사항</h3>
                <!-- 가족 사항 -->
                <div id="tab-family" class="tab-cont">
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
                <!-- 가족 사항 -->
            </div>

        </form>
        <?if(!empty($member_info['mm_file1_name']) || !empty($member_info['mm_file2_name']) || !empty($member_info['mm_file3_name'])){?>
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
        <?}?>
        <form id="info_form5">
            <div class="section-wrap">
            <div class="section-wrap">
                <h3 class="section-title">학력</h3>
                <!-- 학력 사항 -->
                <div id="tab-education" class="tab-cont">
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
                                        <?if($val['me_level']=='1' || empty($val['me_level'])){?>고등학교<?}?>
                                        <?if($val['me_level']=='2'){?>대학교<?}?>
                                        <?if($val['me_level']=='3'){?>전문대<?}?>
                                        <?if($val['me_level']=='4'){?>대학원<?}?>
                                    </td>
                                    <td>
                                        <?=$val['me_major']?>
                                    </td>
                                    <td>
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
                <!-- 학력 사항 -->
            </div>
                <!-- 학력 사항 -->
            </div>
        </form>
        <?if(!empty($career_list)){?>
        <form id="info_form4">
            <div class="section-wrap">
                <h3 class="section-title">경력사항</h3>
                <!-- 경력사항 사항 -->
                <div id="tab-another" class="tab-cont" >
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
        </form>
        <?}?>
        <?if(!empty($certificate_list)){?>
        <form id="info_form3">
            <div class="section-wrap">
                <h3 class="section-title">어학 / 자격증</h3>
                <!-- 어학 / 자격증 사항 -->
                <div id="tab-cert" class="tab-cont" >
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
        </form>
        <?}?>
        <?if(!empty($premier_list)){?>
        <div class="section-wrap">
            <h3 class="section-title">수상경력</h3>
            <!-- 수상경력 -->
            <div id="tab-prize2" class="tab-cont" >
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
        <?}?>
        <?if(!empty($activity_list)){?>
        <form id="info_form7">
            <div class="section-wrap">
                <h3 class="section-title">교육 / 활동</h3>
                <!-- 교육 / 활동 사항 -->
                <div id="tab-activity" class="tab-cont">
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
        </form>
        <?}?>
        <?if(!empty($paper_list)){?>
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
        <?}?>
        <?if(!empty($project_list)){?>
        <div class="section-wrap">
            <h3 class="section-title">프로젝트</h3>
            <!-- 프로젝트 사항 -->
            <div id="tab-project" class="tab-cont">
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
        <?}?>
        <?if(!empty($punishment_list)){?>
        <form id="info_form10">
            <div class="section-wrap">
                <h3 class="section-title">상벌</h3>
                <!-- 상벌 사항 -->
                <div id="tab-etc" class="tab-cont">
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
        </form>
        <?}?>

        <div style="text-align:center;margin-top:50px;">
            <div class="button-area large">
                <a type="button" data-btn="목록" onclick="location.href='/hass/employeaccept?1=1<?=$paging_subquery?>'" class="btn type01 large btn-footer">목록<span class="ico apply"></span></a>
                <!-- <button type="button" data-btn="출력" id="btn-print" class="btn type01 large btn-footer">출력<span class="ico print"></span></button> -->
                <?if($member_info['mm_status']=='A'){?>
                <button type="button" name='btn_status' data-btn="승인" data-type="Y" class="btn type01 large btn-footer">승인<span class="ico check01"></span></button>
                <button type="button" name='btn_status' data-btn="반려" data-type="N" class="btn type01 large btn-footer">반려<span class="ico people"></span></button>
                <?}?>
            </div>
        </div>
	</div>
    <!-- // 내용 -->
    
    <!-- 프린트할 영역 -->
	<div id="content-print" class="content-primary" style="display: none;">
        
	</div>
	<!-- // 프린트할 영역 -->
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

<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>

    $('#btn-resend').click(function(){
        let data = {"mmseq" : <?=$seq?>,"mc_code": <?=$member_info['mc_code']?>, "mm_name" : "<?=$enc->decrypt($member_info['mm_name'])?>", "mm_email": "<?=$enc->decrypt($member_info['mm_email'])?>"}
        if(confirm("해당 임직원에 메일을 재발송 하시겠습니까?")){
            hlb_fn_ajaxTransmit("/@proc/hass/employe_resend_proc.php", data);
        }
    });

    $('#mm_birth, .input-datepicker').datepicker();
    $('#aside-menu .tree-wrap>li:eq(1)').addClass('active');
    var seq = '<?=$seq?>';
    $('[name="btn_status"]').click(function(e){
        e.preventDefault();
        var validate = true;
        text_type = $(this).data('btn');
        apply_type = $(this).data('type');
        if(text_type=='반려'){
            data = $('#new_info_form').serialize() + "&seq="+seq+ "&type="+apply_type+"&text_type="+text_type;
            if(confirm('신입 사원 승인 내역을 '+text_type+" 하시겠습니까?")){
                hlb_fn_ajaxTransmit("/@proc/hass/employe_accept_proc.php", data);
            }
        }else{
            $('#content').find('.input-text').each(function(e){
                var val = $.trim($(this).val());
                var txt = $(this).attr('title');

                if(val==""){
                    $(this).focus();
                    alert(  reulReturner(txt) + " 입력해 주세요");
                    validate = false;
                    return false;
                }

            });
            //new_info_form
        
            data = $('#new_info_form').serialize() + "&seq="+seq+ "&type="+apply_type+"&text_type="+text_type;
            // mc_group = $('[name="mc_group"]').val();
            // mc_position = $('[name="mc_position"] option:selected').val();
            // mc_job = $('[name="mc_job"]').val();
            // mc_position2 = $('[name="mc_position2"] option:selected'0).val();
            // mc_job2 = $('[name="mc_job2"]').val();
            // mc_commute_all = $('[name="mc_commute_all"]').val();
            // mc_commute_use = $('[name="mc_commute_use"]').val();
            // mc_commute_remain = $('[name="mc_commute_remain"]').val();
            //var data = {'seq':seq,'type':apply_type,'text_type':text_type,'mc_position2':mc_position2,'mc_position':mc_position,'mc_group':mc_group,'mc_job2':mc_job2,'mc_job':mc_job,'mc_commute_all':mc_commute_all
            //           ,'mc_commute_use':mc_commute_use,'mc_commute_remain':mc_commute_remain};
            if(validate){
                if(confirm('신입 사원 승인 내역을 '+text_type+" 하시겠습니까?")){
                    hlb_fn_ajaxTransmit("/@proc/hass/employe_accept_proc.php", data);
                }
            }
        }
    });

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='employe_accept_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.reload();
                //location.href="/";
            }
        }
        if(calback_id=='employe_resend_proc'){
            if(result.code=='FALSE'){
                return alert(result.msg);
            }else{
                return alert(result.msg);
            }
        }
    };

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
            objWin.document.write('<link rel="stylesheet" type="text/css" href="http://hrms.hlb-group.com/@resource/css/style.css"/>');
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
                        '    <input type="text" id="input-mc_group" class="input-text mc_group" disabled  title="소속"\n' +
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
</script>

