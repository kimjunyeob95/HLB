<?php
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/info_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/var.php';
$seq = $_REQUEST['seq'];
$member_info = get_member_info($db,$seq); // 개인정보
$group_list = get_group_list($db); //부서
$position_list = get_position_list($db,1); //직책
$position_list2 = get_position_list($db,2); //직위
$position_list3 = get_position_list($db,3); //직무
$position_list4 = get_position_list($db,4); //고용
$position_list5 = get_position_list($db,5); //사원
//echo('<pre>');print_r($member_info);echo('</pre>');exit;
$member_group_list = get_member_group($db,$seq);
?>
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
                <img id="presonnel-img" src="<?=$member_info['mm_profile']?>"" width="100" height="130">
            </td>
            <th scope="col">사번</th>
            <td><?=$member_info['mc_code']?></td>
            <th scope="col">성명</th>
            <td>
                <div class="insert">
                    <label class="label">한글 :</label>
                    <?=$enc->decrypt($member_info['mm_name'])?>
                    <br><br>
                    <label class="label">영문 :</label>
                    <?=$member_info['mm_en_name']?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="col">소속</th>
            <td>
                <?foreach ($member_group_list as $val){?>
                    <label class="label">소속 :</label>
                    <?=$val['tg_title']?><Br>
                <?}?>
            </td>
            <th scope="col">직위 및 직책</th>
            <td>
                <label class="label">직위 :</label>
                <?=get_position_title_type($db,$member_info['mc_position2'],2)?>
                <br>
                <label class="label">직책 :</label>
                <?=get_position_title_type($db,$member_info['mc_position'],1)?>
            </td>
            <th scope="col">직군 및 직무</th>
            <td>
                <label class="label">직무 :</label>
                <?=$member_info['mc_job']?>
                <br>
                <label class="label">직군 :</label>
                <?=get_position_title_type($db,$member_info['mc_position3'],3)?>
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