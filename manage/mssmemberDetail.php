<?php 
    include $_SERVER['DOCUMENT_ROOT'].'/manage/include/header.php'; 
    include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/info_model.php';
?>

<?
/*****************************************
	파 일 명 : mssmemberDetail.php
	설     명  : mss 회원 관리
	 작성일 : 2020-08-07
*****************************************/

@$page = $_REQUEST['page'];				//페이지
@$mc_coseq = $_REQUEST['mccoseq'];
@$mmseq = $_REQUEST['mmseq'];
$enc = new encryption();
if(empty($mmseq)){
	page_move('/manage/',"잘못된 접근입니다.");
	exit;
}

if(!is_numeric($mmseq)){
	page_move('/manage/',"잘못된 접근입니다.");
	exit;
}

$member_info = get_member_info_admin($db,$mmseq,$mc_coseq); // 개인정보
$family_list = get_family_list($db,$mmseq); // 가족사항
$certificate_list = get_certificate_list($db,$mmseq); // 어학 / 자격증
$career_list = get_career_list($db,$mmseq); // 사외경력
$education_list = get_education_list($db,$mmseq); // 학력
$appointment_list = get_appointment_list($db,$mmseq); // 발령
$activity_list = get_activity_list($db,$mmseq); // 교육 / 활동
$paper_list = get_paper_list($db,$mmseq); // 논문 / 저서
$project_list = get_project_list($db,$mmseq); // 프로젝트
$punishment_list = get_punishment_list($db,$mmseq); // 상벌
$evaluation_list = get_evaluation_list($db,$mmseq); // 인사평가
$premier_list = get_premier_list($db,$mmseq); // 수상

$group_list = get_group_list_admin_all($db,$mc_coseq); //부서
$member_group_list = get_member_group_admin($db,$mmseq,$mc_coseq);
$position_list = get_position_list_admin($db,1,$mc_coseq); //직책
$position_list2 = get_position_list_admin($db,2,$mc_coseq); //직위
$position_list3 = get_position_list_admin($db,3,$mc_coseq); //직군
$position_list4 = get_position_list_admin($db,4,$mc_coseq); //고용
$position_list5 = get_position_list_admin($db,5,$mc_coseq); //사원
$list = get_group_list_all_v3($db,$mc_coseq);

$query = "select count(*) as cnt from ess_member_code em join tbl_ess_group  te on em.mc_group = te.tg_seq where tg_coseq = {$mc_coseq} and mc_coseq = {$mc_coseq}";
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
// echo('<pre>');print_r($member_info);echo('</pre>');

?>

<style>
th{background:#eee;}
.itext{
	font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
	height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
	border-radius: 4px;
}

input{
border: 1px solid #E5E7E9;
    border-radius: 6px;
    height: 40px;
    padding: 2px;
    outline: none;
}


</style>
<section class="body">

	<!-- start: header -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_header.php'; ?>
	<!-- end: header -->

	<div class="inner-wrapper">
		<!-- start: sidebar -->
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_gnb_menu.php'; ?>
		<!-- end: sidebar -->

		<section role="main" class="content-body">
            <header class="page-header">
                <h2> <i class="fas fa fa-user-circle"></i>  MSS 사원 관리</h2>
                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="/manage/main.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        
                        <li><span>MSS 사원 관리</span></li>
                    </ol>
                </div>
            </header>
			<!-- start: page -->
            <div class="row ">
				<section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title">권한 부여</h2>
                    </header>
				    <div class="panel-body  col-md-12 " style="display: block;">
						<form class="form-horizontal" id="form_proccess" action="./proc/memberAuth_proc.php" method="post">
                            <input type="hidden" name="mmseq" value=<?=$mmseq?>>
                            <input type="hidden" name="mc_coseq" value=<?=$mc_coseq?>>
                            <div class="form-group col-md-12">
                                <label class="col-md-1 control-label" style="line-height: 30px;">권한 선택</label>
                                <div class="col-md-2 control-label">
                                    <div class="input-daterange input-group col-md-3"  >
                                        <select class="form-control mb-md" name="authority">
                                            <option value="F" <?if($member_info['mc_hass']=='F'){?>selected<?}?>>ESS 회원</option>
                                            <option value="T" <?if($member_info['mc_hass']=='T'){?>selected<?}?>>HASS 회원</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding: 7px 0 0 0;">
                                    <button id="btn-setAuth" tabindex="-1" class="btn btn-primary" type="submit">저장</button>
                                </div>
						    </div>
					    </form>
				    </div>
			    </section>
		    </div>
			<div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">기본사항</h2>
                        </div>
                        <table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">이미지</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><img src="<?=$member_info['mm_profile']?>" width="100" height="130"></td>
                                <th class="col-sm-1" style="text-align: center !important;">사번</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$member_info['mc_code']?></td>
                                <th class="col-sm-1" style="text-align: center !important;">성명</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <label style="vertical-align: sub;">한글 :
                                    <?=$enc->decrypt($member_info['mm_name'])?>
                                    <br>
                                    <label style="vertical-align: sub;">영문 :
                                    <?=$member_info['mm_en_name']?>
                                </td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">소속</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <?foreach ($member_group_list as $val){?>
                                        <?=$val['tg_title']?><br>
                                    <?}?>
                                </td>
                                <th class="col-sm-1" style="text-align: center !important;">직위 및 직책</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <label style="vertical-align: sub;">직위 : 
                                        <?foreach ($position_list2 as $val){?>
                                            <?if($val['tp_seq']==$member_info['mc_position2']){echo ''.$val['tp_title'];}?>
                                        <?}?>
                                    </label> 
                                    <br>
                                    <label style="vertical-align: sub;">직책 :
                                        <?foreach ($position_list as $val){?>
                                            <?if($val['tp_seq']==$member_info['mc_position']){echo ''.$val['tp_title'];}?>
                                        <?}?>
                                    </label>
                                </td>
                                <th class="col-sm-1" style="text-align: center !important;">직무 및 직군</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <label style="vertical-align: sub;">직무 : 
                                        <?=$member_info['mc_job']?>
                                    </label> 
                                    <br>
                                    <label style="vertical-align: sub;">직군 :
                                        <?foreach ($position_list3 as $val){?>
                                            <?if($val['tp_seq']==$member_info['mc_position3']){echo ''.$val['tp_title'];}?>
                                        <?}?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">그룹 입사일</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mc_affiliate_date'],0,10)?></td>
                                <th class="col-sm-1" style="text-align: center !important;">자사 입사일</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mc_regdate'],0,10)?></td>
                                <th class="col-sm-1" style="text-align: center !important;">최종 승진일</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mc_bepromoted_date'],0,10)?></td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">재직상태</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <?foreach ($member_state_v2 as $key =>$val){?>
                                        <?if($key==$member_info['mm_status']){echo ''.$val;}?>
                                    <?}?>
                                </td>
                                <th class="col-sm-1" style="text-align: center !important;">퇴사일</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mm_retirement_date'],0,10)?></td>
                                <th class="col-sm-1" style="text-align: center !important;">고용구분</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <?foreach ($position_list4 as $val){?>
                                        <?if($val['tp_seq']==$member_info['mc_position4']){echo ''.$val['tp_title'];}?>
                                    <?}?>
                                </td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">사원유형</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <?foreach ($position_list5 as $val){?>
                                        <?if($val['tp_seq']==$member_info['mc_position5']){echo ''.$val['tp_title'];}?>
                                    <?}?>
                                </td>
                                <th class="col-sm-1" style="text-align: center !important;">성별</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=getGenderText($member_info['mm_gender'])?></td>
                                <th class="col-sm-1" style="text-align: center !important;">최종학력</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <?=$degree_level3[$member_info['mm_education']]?>
                                </td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">생년월일</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mm_birth'],0,10)?></td>
                                <th class="col-sm-1" style="text-align: center !important;">주민/외국인 번호</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$enc->decrypt($member_info['mm_serial_no'])?></td>
                                <th class="col-sm-1" style="text-align: center !important;">국적</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=getCountryText($member_info['mm_country'])?></td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">우편번호</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$member_info['mm_post']?></td>
                                <th class="col-sm-1" style="text-align: center !important;">주소</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$enc->decrypt($member_info['mm_address'])?></td>
                                <th class="col-sm-1" style="text-align: center !important;">상세주소</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$enc->decrypt($member_info['mm_address_detail'])?></td>
                            </tr>
                            <tr>
                                <th class="col-sm-1" style="text-align: center !important;">휴대폰 번호</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$enc->decrypt($member_info['mm_cell_phone'])?></td>
                                <th class="col-sm-1" style="text-align: center !important;">이메일 주소</th>
                                <td class="col-sm-2"  style="text-align: center !important;"><?=$enc->decrypt($member_info['mm_email'])?></td>
                                <th class="col-sm-1" style="text-align: center !important;">비상 연락처</th>
                                <td class="col-sm-2"  style="text-align: center !important;">
                                    <label style="vertical-align: sub;">관계 : 
                                        <?=$member_info['mm_prepare_relation']?>
                                    </label> 
                                    <br>
                                    <label style="vertical-align: sub;">연락처 :
                                        <?=$enc->decrypt($member_info['mm_prepare_phone'])?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">근태사항</h2>
                        </div>
                        <table class="table table-bordered table-hover mb-none" style="table-layout:fixed ">
                            <!-- <caption>근태사항</caption> -->
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
                                    <th class="col-sm-1" style="text-align: center !important;">연차적용 시작일자</th>
                                    <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mc_commute_sdate'],0,10)?></td>
                                    <th class="col-sm-1" style="text-align: center !important;">연차적용 종료일자</th>
                                    <td class="col-sm-2"  style="text-align: center !important;"><?=substr($member_info['mc_commute_edate'],0,10)?></td>
                                    <th class="col-sm-1" style="text-align: center !important;"></th>
                                    <td class="col-sm-2"  style="text-align: center !important;"></td>
                                </tr>
                                <tr>
                                    <th class="col-sm-1" style="text-align: center !important;">전체휴가일수</th>
                                    <td class="col-sm-2"  style="text-align: center !important;"><?=$member_info['mc_commute_all']?></td>
                                    <th class="col-sm-1" style="text-align: center !important;">사용휴가일수</th>
                                    <td class="col-sm-2"  style="text-align: center !important;"><?=$member_info['mc_commute_use']?></td>
                                    <th class="col-sm-1" style="text-align: center !important;">잔여휴가일수</th>
                                    <td class="col-sm-2"  style="text-align: center !important;"><?=$member_info['mc_commute_remain']?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">벙력</h2>
                        </div>
                        <table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
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
                                <th scope="col" class="center">병역구분</th>
                                <th scope="col" class="center">입대일</th>
                                <th scope="col" class="center">제대일</th>
                                <th scope="col" class="center">군별</th>
                                <th scope="col" class="center">계급</th>
                                <th scope="col" class="center">병과</th>
                                <th scope="col" class="center">사유(면제 및 기타)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="center"><?=$arm_type[$member_info['mm_arm_type']]?></td>
                                <td class="center"><?=substr($member_info['mm_arm_sdate'],0,10)?></td>
                                <td class="center"><?=substr($member_info['mm_arm_edate'],0,10)?></td>
                                <td class="center"><?=$arm_group[$member_info['mm_arm_group']]?></td>
                                <td class="center"><?=$arm_class[$member_info['mm_arm_class']]?></td>
                                <td class="center"><?=$member_info['mm_arm_discharge']?></td>
                                <td class="center"><?=$member_info['mm_arm_reason']?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">장애정보 / 국가보훈정보</h2>
                        </div>
                        <table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col" class="center">장애여부</th>
                                <th scope="col" class="center">장애구분</th>
                                <th scope="col" class="center">장애등급</th>
                                <th scope="col" class="center">보훈여부</th>
                                <th scope="col" class="center">보훈구분</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="center"><?=$disorder_type_1[$member_info['mm_disorder_1']]?></td>
                                <td class="center"><?=$disorder_type_2[$member_info['mm_disorder_2']]?></td>
                                <td class="center"><?=$disorder_type_3[$member_info['mm_disorder_3']]?></td>
                                <td class="center"><?=$nation_type_1[$member_info['mm_nation_1']]?></td>
                                <td class="center"><?=$nation_type_2[$member_info['mm_nation_2']]?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">인사평가</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th rowspan=2 scope="col" class="center">연도</th>
                                    <th rowspan=2 scope="col" class="center">소속</th>
                                    <th colspan=2 scope="col" class="center">1차평가</th>
                                    <th rowspan=2 scope="col" class="center">평가의견</th>
                                    <th colspan=2 scope="col" class="center">2차평가</th>
                                    <th rowspan=2 scope="col" class="center">평가의견</th>
                                    <th rowspan=2 scope="col" class="center">파일첨부</th>
                                </tr>
                                <tr>
                                    <th scope="col" class="center">평가자</th>
                                    <th scope="col" class="center">평가등급</th>
                                    <th scope="col" class="center">평가자</th>
                                    <th scope="col" class="center">평가등급</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($evaluation_list as $val){?>
                                <tr>
                                    <td class="center"><?=$val['me_year']?></td>
                                    <td class="center"><?=$val['me_group']?></td>
                                    <td class="center"><?=$val['me_admin_1']?></td>
                                    <td class="center">
                                        <?foreach($evaluation_class_array as $key => $val2){?>
                                           <?if($key==$val['me_class_1']){echo $val2;};?>
                                        <?}?>
                                    </td>
                                    <td class="center"><?=$val['me_etc1']?></td>
                                    <td class="center"><?=$val['me_admin_2']?></td>
                                    <td class="center">
                                        <?foreach($evaluation_class_array as $key => $val2){?>
                                           <?if($key==$val['me_class_2']){echo $val2;};?>
                                        <?}?>
                                    </td>
                                    <td class="center"><?=$val['me_etc2']?></td>
                                    <td class="center"><a href="<?=$val['me_file_src']?>" download><?=$val['me_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span><br></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">가족사항</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col" class="center">성명</th>
                                    <th scope="col" class="center">관계</th>
                                    <th scope="col" class="center">생년월일</th>
                                    <th scope="col" class="center">인적공제 여부</th>
                                    <th scope="col" class="center">동거 여부</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($family_list as $val){?>
                                <tr>
                                    <td class="center"><?=$enc->decrypt($val['mf_name'])?></td>
                                    <td class="center"><?=$family_type_array[$val['mf_relationship']]?></td>
                                    <td class="center"><?=$enc->decrypt($val['mf_birth'])?></td>
                                    <td class="center">
                                        <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>대상<?}?>
                                        <?if($val['mf_allowance']=='F'){?>비대상<?}?>
                                    </td>
                                    <td class="center">
                                        <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>동거<?}?>
                                        <?if($val['mf_together']=='F'){?>비동거<?}?>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">증빙서류</h2>
                        </div>
                        <table class="table table-bordered table-hover mb-none" style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 10%" />
                                <col style="width: *" />
                            </colgroup>
                            <tbody>
                                <?if(!empty($member_info['mm_file1'])){?>
                                <tr>
                                    <th scope="col" class="center">주민등록등본</th>
                                    <td><a href=<?=$member_info['mm_file1']?> download="<?=$member_info['mm_file1_name']?>" target="_blank"><?=$member_info['mm_file1_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                                </tr>
                                <?}?>
                                <?if(!empty($member_info['mm_file2'])){?>
                                <tr>
                                    <th scope="col" class="center">가족관계증명서</th>
                                    <td><a href='<?=$member_info['mm_file2']?>' download="<?=$member_info['mm_file2_name']?>" target="_blank"><?=$member_info['mm_file2_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                                </tr>
                                <?}?>
                                <?if(!empty($member_info['mm_file3'])){?>
                                <tr>
                                    <th scope="col" class="center">기타</th>
                                    <td><a href='<?=$member_info['mm_file3']?>' download="<?=$member_info['mm_file3_name']?>" target="_blank"><?=$member_info['mm_file3_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span></td>
                                </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">학력사항</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
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
                                    <th scope="col" class="center">입학일</th>
                                    <th scope="col" class="center">졸업일</th>
                                    <th scope="col" class="center">학교명</th>
                                    <th scope="col" class="center">학교등급</th>
                                    <th scope="col" class="center">전공</th>
                                    <th scope="col" class="center">학위</th>
                                    <th scope="col" class="center">졸업구분</th>
                                    <th scope="col" class="center">주야간구분</th>
                                    <th scope="col" class="center">기타</th>
                                </tr>
                            </thead>
                            <tbody >
                            <?foreach ($education_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=substr($val['me_sdate'],0,10)?></td>
                                    <td class="center"><?=substr($val['me_edate'],0,10)?></td>
                                    <td class="center"><?=$val['me_name']?></td>
                                    <td class="center"><?=$degree_level[$val['me_level']]?></td>
                                    <td class="center"><?=$val['me_major']?></td>
                                    <td class="center"><?=$degree_level2[$val['me_degree']]?></td>
                                    <td class="center"><?=$graduate_type_array[$val['me_graduate_type']]?></td>
                                    <td class="center">
                                        <?foreach($weekly_array as $key => $val2){?>
                                           <?if($key==$val['me_weekly']){echo $val2;};?>
                                        <?}?>
                                    </td>
                                    <td class="center"><?=$val['me_etc']?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">경력사항</h2>
                        </div>
                        <?foreach ($career_list as $key =>$val){?>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
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
                                    <th scope="col" class="center">시작일</th>
                                    <td class="center"><?=substr($val['mc_sdate'],0,10)?></td>
                                    <th scope="col" class="center">종료일</th>
                                    <td class="center"><?=substr($val['mc_edate'],0,10)?></td>
                                    <th scope="col" class="center">회사명</th>
                                    <td class="center"><?=$enc->decrypt($val['mc_company'])?></td>
                                    <th scope="col" class="center">근무부서</th>
                                    <td class="center"><?=$val['mc_group']?></td>
                                    <th scope="col" class="center">최종직위</th>
                                    <td class="center"><?=$enc->decrypt($val['mc_position'])?></td>
                                    <th scope="col" class="center">담당업무</th>
                                    <td class="center"><?=$enc->decrypt($val['mc_duties'])?></td>
                                </tr>
                                <tr>
                                    <th scope="col" class="center">경력기술</th>
                                    <td colspan=11 class="center"><?=$val['mc_career']?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?}?>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">어학 / 자격증</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 3%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col" class="center">자격증 명</th>
                                    <th scope="col" class="center">취득일</th>
                                    <th scope="col" class="center">등급/점수</th>
                                    <th scope="col" class="center">취득기관</th>
                                    <th scope="col" class="center">자격번호</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?foreach ($certificate_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=$val['mct_cert_name']?></td>
                                    <td class="center"><?=substr($val['mct_date'],0,10)?></td>
                                    <td class="center"><?=$enc->decrypt($val['mct_class'])?></td>
                                    <td class="center"><?=$enc->decrypt($val['mct_institution'])?></td>
                                    <td class="center"><?=$val['mct_num']?></td>
                                </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">수상경력</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col" class="center">일자</th>
                                    <th scope="col" class="center">수상내용</th>
                                    <th scope="col" class="center">수상기관</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?foreach ($premier_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=$enc->decrypt($val['mpd_date'])?></td>
                                    <td class="center"><?=$val['mpd_content']?></td>
                                    <td class="center"><?=$val['mpd_institution']?></td>
                                </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">교육 / 활동</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
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
                                    <th scope="col" class="center">구분</th>
                                    <th scope="col" class="center">시작일</th>
                                    <th scope="col" class="center">종료일</th>
                                    <th scope="col" class="center">교육 및 활동명</th>
                                    <th scope="col" class="center">기관명</th>
                                    <th scope="col" class="center">역할</th>
                                    <th scope="col" class="center">증빙서류</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($activity_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=$company_type_array[$val['mad_type']]?></td>
                                    <td class="center"><?=substr($val['mad_sdate'],0,10)?></td>
                                    <td class="center"><?=substr($val['mad_edate'],0,10)?></td>
                                    <td class="center"><?=$enc->decrypt($val['mad_name'])?></td>
                                    <td class="center"><?=$enc->decrypt($val['mad_institution'])?></td>
                                    <td class="center"><?=$val['mad_role']?></td>
                                    <td class="center">
                                        <?if(!empty($val['mad_file'])){?>
                                            <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span>
                                        <?}else{?>
                                            -
                                        <?}?>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">논문 / 저서</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 8%" />
                                <col style="width: 20%" />
                                <col style="width: 10%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col" class="center">발행일</th>
                                    <th scope="col" class="center">논문 및 저서명</th>
                                    <th scope="col" class="center">발행정보</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($paper_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=substr($val['mp_date'],0,10)?></td>
                                    <td class="center"><?=$enc->decrypt($val['mp_name'])?></td>
                                    <td class="center"><?=$enc->decrypt($val['mp_institution'])?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">프로젝트</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
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
                                    <th class="center">시작일</th>
                                    <th class="center">종료일</th>
                                    <th class="center">프로젝트명</th>
                                    <th class="center">프로젝트 기관</th>
                                    <th class="center">기여도</th>
                                    <th class="center">역할</th>
                                    <th class="center">완료여부</th>
                                    <th class="center">내용(배운점 등)</th>
                                    <th class="center">키워드</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($project_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=substr($val['mpd_sdate'],0,10)?></td>
                                    <td class="center"><?=substr($val['mpd_edate'],0,10)?></td>
                                    <td class="center"><?=$enc->decrypt($val['mpd_name'])?></td>
                                    <td class="center"><?=$val['mpd_institution']?></td>
                                    <td class="center"><?=$val['mpd_contribution']?></td>
                                    <td class="center"><?=$result_array[$val['mpd_result']]?></td>
                                    <td class="center"><?=$val['mpd_position']?></td>
                                    <td class="center"><?=$val['mpd_content']?></td>
                                    <td class="center"><?=$val['mpd_keyword']?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">발령</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 10%" />
                                <col style="width: 8%" />
                                <col style="width: 8%" />
                                <col style="width: 8%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col" class="center">발령일자</th>
                                    <th scope="col" class="center">발령구분</th>
                                    <th scope="col" class="center">발령회사</th>
                                    <th scope="col" class="center">직위</th>
                                    <th scope="col" class="center">담당직무</th>
                                    <th scope="col" class="center">비고</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($appointment_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=substr($val['ma_date'],0,10)?></td>
                                    <td class="center"><?=$appointment_type[$val['ma_type']]?></td>
                                    <td class="center"><?=$enc->decrypt($val['ma_company'])?></td>
                                    <td class="center"><?=$val['ma_position']?></td>
                                    <td class="center"><?=$val['ma_position2']?></td>
                                    <td class="center"><?=$val['ma_etc']?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">상벌</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 3%" />
                                <col style="width: 8%" />
                                <col style="width: 10%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="center">구분</th>
                                    <th class="center">일자</th>
                                    <th class="center">상벌명</th>
                                    <th class="center">사유 및 내용</th>
                                    <th class="center">비고</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($punishment_list as $key =>$val){?>
                                <tr>
                                    <td class="center"><?=$punishment[$val['mp_type']]?></td>
                                    <td class="center"><?=substr($val['mp_date'],0,10)?></td>
                                    <td class="center"><?=$val['mp_title']?></td>
                                    <td class="center"><?=$val['mp_content']?></td>
                                    <td class="center"><?=$val['mp_etc']?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row ">
				<section class="panel col-sm-12">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2 class="panel-title">기타</h2>
                        </div>
                        <table class="table table-bordered table-hover " style="table-layout:fixed ">
                            <colgroup>
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="center">기타내용</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">
                                        <?if(empty($member_info['mm_note'])){?>
                                            -
                                        <?}else{?>
                                            <?=$member_info['mm_note']?>
                                        <?}?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div style="text-align:center;margin-top:20px;">
                <button class="btn  btn-go-list btn-primary"><i class="fas fa fa-list"></i> 목록으로</button>
            </div>
	    </section>
	</div>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>
var $subquery = "<?=$subquery?>";
var page = "<?=$page?>";

jQuery.viewImage({
  'target': '.onHonverImg'
});

$('.btn-go-list').click(function(){
	location.href="/manage/mssmember.php?page="+page+$subquery;
});

$('#btn-setAuth').click(function(e){
	e.preventDefault();
    $.ajax({
        url : "/manage/proc/memberAuth_proc.php",
        data : { 
            'mc_coseq' : <?=$mc_coseq?>,
            'mmseq' : <?=$mmseq?>,
            'authority' : $('select[name="authority"]').val(),
        },
        dataType :"json",
        method : "post",
        success : function(result){
            if(result.code=='FALSE'){
                alert(result.msg);
            }else{
                alert(result.msg);
                location.reload();
            }
        }
    })
});
</script>