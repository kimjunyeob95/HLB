<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$mmseq = $_SESSION['mmseq'];
/**
 * 개인정보 view
 **/
$data =  get_member_info($db,$mmseq);
$certificate_list = get_certificate_list($db,$mmseq); // 어학 / 자격증
$project_list = get_project_list($db,$mmseq); // 프로젝트
$career_list2 = get_career_list($db,$mmseq); // 사외경력
// echo('<pre>');print_r($data);echo('</pre>');
$query = "
select * from (
(select mc_company as name , mc_regdate as regdate , mc_sdate as sdate , mc_edate as edate , '1' as 'info','1' as 'level' from member_career_data where mc_mmseq = {$mmseq})
union
(select me_name as name,me_regdate as regdate, me_sdate as sdate , me_edate as edate , '2' as 'info',me_level as 'level' from member_education_data where me_mmseq = {$mmseq})
) T order by sdate desc";
$ps = pdo_query($db,$query,array());
$career_list = array();
while($datas = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($career_list,$datas);
}
//만 나이 계산
function getManNai($data){
    $birth_year = substr((int)$data['mm_birth'],0,4);
    $birth_month = substr((int)$data['mm_birth'],5,2);
    $brith_day = substr((int)$data['mm_birth'],8,2);

    $now_year = date("Y");
    $now_month = date("m");
    $now_day = date("d");

    if($birth_month < $now_month){
       $age = $now_year - $birth_year;
    }else if($birth_month == $now_month){
     if($brith_day <= $now_day)
      $age = $now_year - $birth_year;
     else
      $age = $now_year - $birth_year -1;
    }else{
       $age = $now_year - $birth_year-1;
    }
    return $age;
}
// echo('<pre>');print_r($data);echo('</pre>');

//사외경력 날짜 계산
$date_array_career=array();
$date_start = $career_list2[0]['mc_sdate'];
$date_end = $career_list2[0]['mc_edate'];
foreach($career_list2 as $index => $val){
    if(strtotime($date_start) >= strtotime($career_list2[$index+1]['mc_sdate'])){
        if(empty($career_list2[$index+1])){
            $date_start=$date_start;    
        }else{
            $date_start=$career_list2[$index+1]['mc_sdate'];
        }
    }else{
        $date_start=$date_start;
    }
    if(strtotime($date_end) <= strtotime($career_list2[$index+1]['mc_edate'])){
        if(empty($career_list2[$index+1])){
            $date_end=$date_end;    
        }else{
            $date_end=$career_list2[$index+1]['mc_edate'];
        }
    }else{
        $date_end=$date_end;
    }
    
    $start =  date_create(substr($val['mc_sdate'],0,10));
    $end = date_create(substr($val['mc_edate'],0,10));
    $result = date_diff($start,$end);
    $date_array_career['y'] +=  $result->format('%y');
    $date_array_career['m'] +=  $result->format('%m');
    if($date_array_career['m']>12){
        $date_array_career['y'] += floor($date_array_career['m']/12);
        $date_array_career['m'] = floor($date_array_career['m']%12);
    }
    $date_array_career['d'] += $result->format('%d');
    if($date_array_career['d']>30){
        $date_array_career['m'] += floor($date_array_career['d']/30);
        $date_array_career['d'] = ceil($date_array_career['d']%30);
    }
}

//총근속
$date = date('Y-m-d', (strtotime('- 1 month', strtotime($data['mc_regdate']))));
$datetime1 = new DateTime($date);
$datetime2 = new DateTime(date('Y-m-d'));
$regdate_interval = $datetime1->diff($datetime2);

//현 직급년차
$date = date('Y-m-d', (strtotime('- 1 month', strtotime($data['mc_bepromoted_date']))));
$datetime3 = new DateTime($date);
$datetime4 = new DateTime(date('Y-m-d'));
$position_interval = $datetime3->diff($datetime4);

//현 부서근속
$date = date('Y-m-d', (strtotime('- 1 month', strtotime($data['mc_affiliate_date']))));
$datetime5 = new DateTime($date);
$datetime6 = new DateTime(date('Y-m-d'));
$affiliate_interval = $datetime5->diff($datetime6);

//현 법인근속
$date = date('Y-m-d', (strtotime('- 1 month', strtotime($data['mc_regdate']))));
$datetime7 = new DateTime($date);
$datetime8 = new DateTime(date('Y-m-d'));
$mc_regdate_interval = $datetime6->diff($datetime7);
// echo('<pre>');print_r($mc_regdate_interval);echo('</pre>');
//부서 / 직급 쿼리
$query = "
select * from tbl_ess_group teg join tbl_position tp 
where teg.tg_seq =? and tp.tp_seq=?";
$ps = pdo_query($db,$query,array($data['mm_group'],$data['mm_position']));
$data_buseo = $ps ->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($data_buseo);echo('</pre>');
?>

<div id="wrap" class="depth03" >
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="full-frame my-profile-card"><!-- full-frame : 풀프레임 컬럼 레이아웃 --> 
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>My Profile</h2>
		<ul class="lnb">
            <li><a href="/ess/tree" >조직도</a></li>
			<li><a href="/ess/timeline"  class="active">나의 타임라인</a></li>
			<li><a href="/ess/" >인사기록카드</a></li>
			<li><a href="/ess/change"  >정보 변경 신청 내역</a></li>
			
			<!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		<h2 class="content-title">나의 타임라인</h2>
		<!-- 타임라인 -->
		<div class="column-wrap my-time-line">
			<div class="column porfile">
				<div class="item-info">
					<div class="thumb porfile-mask box-rd">
						<img src="<?=$data['mm_profile']?>" width="130" height="150" alt="프로필 사진">
					</div>
					<ul class="data-info">
						<li class="big">
							<span class="user"><?=$enc->decrypt($data['mm_name'])?></span>
							<span class="genter">
                                <?
                                    if($data['mm_gender']=='M'){echo '남';}else{echo '여';};
                                ?>
                            </span>
							<span class="age">만 <?=getManNai($data)?>세</span>
						</li>
						<li><i class="ico person"></i>사번 : <?=$data['mc_code']?></li>
                        <li><i class="ico location"></i>소속 : <?=implode('/ ',get_group_list_v2($db,$data['mmseq']))?></li>
                        <li><i class="ico location"></i>직위 : <?=get_position_title_type($db,$data['mc_position2'],2)?></li>
                        <li><i class="ico location"></i>직군 : <?=get_position_title_type($db,$data['mc_position3'],3)?></li>
                        <li><i class="ico location" style="width: 20px; height:20px;"></i>입사일 : <?=substr($data['mc_regdate'],0,10)?></li>
						<!-- <li><i class="ico calendar" style="width: 20px; height:20px;"></i><?=substr($data['mm_birth'],0,10)?></li> -->
					</ul>
				</div>
				<div class="summary">
					<!-- <ul class="summary-list column-wrap">
						<li>
							<p class="title">부서 및 직급</p>
							<p class="data"><?=get_orgmanage_title($db,$data['mc_group'])?> <br>(<?=get_position_title($db,$data['mc_position'])?>)</p>
						</li>
						<li>
							<p class="title">직군·직무</p>
							<p class="data"><span><?=$data['mc_job']?> / <?=$data['mc_job2']?></span></p>
						</li>
						<li>
							<p class="title">입사일자</p>
							<p class="data"><span><?=substr($data['mc_regdate'],0,10)?></span></p>
						</li>
						<li>
							<p class="title">생년월일</p>
							<p class="data"><span><?=substr($data['mm_birth'],0,10)?></span></p>
						</li>
					</ul> -->
                    <div class="career-box box-rd our">
                        <p>총 근속 <span><?=$regdate_interval->format('%y')?>년 <?=$regdate_interval->format('%m')?>개월</span></p>
                        <p class="detail"><span>현 회사 <?=$mc_regdate_interval->format('%y')?>년 <?=$mc_regdate_interval->format('%m')?>개월</span><span>현 직급 <?=$position_interval->format('%y')?>년 <?=$position_interval->format('%m')?>개월</span></p>
                        <p class="period"><?=substr($data['mc_regdate'],0,10)?> - <?=date('Y')?>-<?=date('m')?>-<?=date('d')?></p>
                    </div>
                    <div class="career-box box-rd other">
                        <p>사외경력 <span><?=$date_array_career['y']?>년 <?=$date_array_career['m']?>개월</span></p>
                        <p class="period"><?=substr($date_start,0,10)?> - <?=substr($date_end,0,10)?></p>
                    </div>
                </div>
			</div>
				
			<div class="column history">
				<!-- <div class="banner box-rd">
					<img src="../../@resource/images/my_profile/bnr_timeline.png" alt="">
				</div> -->
				<h3 class="col-title" style="line-height: 28px;">나의 이력사항</h3>
				<ol class="history-list">
                    <li>
                        <p class="box-center"><i class="ico cake"></i></p>
                        <p>출생 <span class="date"><?=substr($data['mm_birth'],0,10)?></span></p>
                        <p class="link"><a href="/ess/?active=family">가족사항 보기</a></p>
                    </li>
                    <li>
                        <p class="box-center our"><i class="ico join white"></i></p>
                        <p><?=implode('/ ',get_group_list_v2($db,$data['mmseq']))?> 소속 <span class="date"><?=substr($data['mc_regdate'],0,7)?> ~ <?=date('Y-m')?></span></p>
                    </li>
                    <?foreach ($career_list as $val){?>
                        <?if($val['info']==1){?>
                            <li>
                                <p class="box-center our"><i class="ico join white"></i></p>
                                <p><?=$enc->decrypt($val['name'])?> <span class="date"><?=substr($val['sdate'],0,7)?> ~ <?=substr($val['edate'],0,7)?></span></p>
                            </li>
                        <?}else{?>
                            <li>
                                <p class="box-center"><i class="ico bachelor"></i></p>
                                <!-- <p><?=$enc->decrypt($val['name'])?> <?=$degree_level[$val['level']]?>  <span class="date"><?=substr($val['sdate'],0,7)?> ~ <?=substr($val['edate'],0,7)?></span></p> -->
                                <p><?=$val['name']?> <span class="date"><?=substr($val['sdate'],0,7)?> ~ <?=substr($val['edate'],0,7)?></span></p>
                                <!-- <p class="detail"><?=$data['name']?></p> -->
                            </li>
                        <?}?>
                    <?}?>
<!--					<li>-->
<!--						<p class="box-center"><i class="ico bachelor"></i></p>-->
<!--						<p>고등학교 졸업 <span class="date">1975.03.28</span></p>-->
<!--						<p class="detail">한국 공업 고등학교</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center"><i class="ico bachelor"></i></p>-->
<!--						<p>대학교 졸업 <span class="date">1998.02</span></p>-->
<!--						<p class="detail">영산대학교</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center"><i class="ico join"></i></p>-->
<!--						<p>입사<span class="date">1998.02</span></p>-->
<!--						<p class="detail">대웅제약</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center"><i class="ico leave"></i></p>-->
<!--						<p>퇴사<span class="date">2008.02</span></p>-->
<!--						<p class="detail">대웅제약</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center our"><i class="ico join white"></i></p>-->
<!--						<p>입사<span class="date">2008.02</span></p>-->
<!--						<p class="detail">HLB / 과장</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center our"><i class="ico change white"></i></p>-->
<!--						<p>인사발령<span class="date">2010.02</span></p>-->
<!--						<p class="detail">보직임명 : A회사 남양구 공장 / 공장장</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center our"><i class="ico pen white"></i></p>-->
<!--						<p>교육<span class="date">2012.02</span></p>-->
<!--						<p class="detail">교육 / 어학 자격증 취득 : TOEIC</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center our"><i class="ico leave white"></i></p>-->
<!--						<p>퇴사<span class="date">2008.02</span></p>-->
<!--						<p class="detail">HLB / 과장</p>-->
<!--					</li>-->
<!--					<li>-->
<!--						<p class="box-center our"><i class="ico join white"></i></p>-->
<!--						<p>재입사<span class="date">2008.02</span></p>-->
<!--						<p class="detail">HLB / 과장</p>-->
<!--					</li>-->
				</ol>
			</div>
				
			<div class="column career">
                <!-- <div class="career-box box-rd other">
					<p>사외경력 <span><?=$date_array_career['y']?>년 <?=$date_array_career['m']?>개월</span></p>
					<p class="period"><?=substr($date_start,0,10)?> - <?=substr($date_end,0,10)?></p>
				</div>
				<div class="career-box box-rd our">
					<p>총 근속 <span><?=$regdate_interval->format('%y')?>년 <?=$regdate_interval->format('%m')?>개월</span></p>
                    <p class="detail"><span>현 회사 <?=$mc_regdate_interval->format('%y')?>년 <?=$mc_regdate_interval->format('%m')?>개월</span><span>현 직급 <?=$position_interval->format('%y')?>년 <?=$position_interval->format('%m')?>개월</span></p>
					<p class="period"><?=substr($data['mc_regdate'],0,10)?> - <?=date('Y')?>-<?=date('m')?>-<?=date('d')?></p>
				</div> -->
				
				<div class="certificate-wrap">
					<h3>자격증 <span><?=sizeof($certificate_list)?>개</span></h3>
					<!-- <select class="select">
						<option value="">최신순</option>
					</select> -->
					<ul class="data-list">
                        <?foreach($certificate_list as $key =>$value){?>
						<li>
							<i class="ico certificate"></i>
							<span class="text"><?=$value['mct_cert_name']?></span>
							<span class="date"><?=substr($value['mct_date'],0,10)?></span>
						</li>
                        <?}?>
					</ul>
                </div>
                <div class="certificate-wrap" style="margin-top: 70px;">
					<h3>프로젝트 <span><?=sizeof($project_list)?>개</span></h3>
					<!-- <select class="select">
						<option value="">최신순</option>
					</select> -->
					<ul class="data-list">
                        <?foreach($project_list as $key =>$value){?>
						<li>
							<i class="ico certificate project"></i>
							<span class="text"><?=$enc->decrypt($value['mpd_name'])?></span>
							<span class="date"><?=$result_array[$value['mpd_result']]?></span>
						</li>
                        <?}?>
					</ul>
				</div>
			</div>
		</div>
		<!-- //타임라인 -->
		
	</div>
</div>

<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(0)').addClass('active');
$('.depth02:eq(0)').find('li:eq(1)').addClass('active');
</script>