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
$type = $_REQUEST['type'];

$member_info = get_member_info_v2($db,$seq); // 개인정보
$family_list = get_family_list($db,$seq); // 가족사항
$certificate_list = get_certificate_list($db,$seq); // 어학 / 자격증
$career_list = get_career_list($db,$seq); // 경력사항
$education_list = get_education_list($db,$seq); // 학력
$appointment_list = get_appointment_list($db,$seq); // 발령
$activity_list = get_activity_list($db,$seq); // 교육 / 활동
$paper_list = get_paper_list($db,$seq); // 논문 / 저서
$project_list = get_project_list($db,$seq); // 프로젝트
$premier_list = get_premier_list($db,$seq); // 수상
$group_list = get_group_list($db);
$position_list = get_position_list($db,1);
$punishment_list = get_punishment_list($db,$seq); // 상벌
$position_list2 = get_position_list($db,2); //직위
$position_list3 = get_position_list($db,3); //직무
$position_list4 = get_position_list($db,4); //고용
$position_list5 = get_position_list($db,5); //사원
$member_group_list = get_member_group($db,$seq);
if($type==1){
    $title = '발령';
}else{
    $title = '겸직';
}
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
function build_menu($rows,$parent=0)
{
    
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
                $result .= '<button class="btn type01 small btn_view" data-id="'.$row['tg_seq'].'">선택</button>';
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
<div id="wrap" class="depth03">

<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">인사발령(<?=$title?>) 보낸내역 상세관리</h2>
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
        </form>
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
            <!-- 병력 사항 -->
        </div>

        <form id="info_form11">
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
        </form>
        <form id="info_form5">
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
        </form>
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
        </form>
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
        <div style="text-align:center;margin-top:50px;">
            <div class="button-area large">
                <button type="button" data-btn="목록" onclick="location.href='/hass/humanPostlist?page=<?=$page?>'" class="btn type01 large">목록<span class="ico apply"></span></button>
                <!-- <button type="button" data-btn="출력" id="btn-print" class="btn type01 large btn-footer">출력<span class="ico print"></span></button> -->
                <?if($data['ta_status']=='A'){?>
                <button type="button" data-btn="승인" name="btn_save" data-type="Y" class="btn type01 large btn-footer">승인<span class="ico check01"></span></button>
                <button type="button" data-btn="반려" name="btn_save" data-type="N" class="btn type01 large btn-footer">반려<span class="ico cancel"></span></button>
                <?}?>
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
            <h1>조직 관리</h1>
            <div class="section-wrap">
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
            objWin.document.write('<html><head><title></title>');
            objWin.document.write('<link rel="stylesheet" type="text/css" href="http://211.253.26.40/@resource/css/style.css"/>');
            objWin.document.write('</head><body>');
            objWin.document.write(param.html);
            objWin.document.write('</body></html>');
            objWin.focus(); 
            objWin.document.close();
        
            setTimeout(function(){objWin.print();objWin.close();}, 1000);
            //setTimeout(function(){objWin.open();}, 1000);
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
        $('.btn_view').click(function(){
            $('#input-mc_group').val($(this).prev().val());
            $('#input-mc_group_hide').val($(this).data('id'));
            $('.new-layer-popup').removeClass('active');
            $('body, html').css('height','auto');
            $('body, html').css('overflow','visible');
        });
    });

</script>

