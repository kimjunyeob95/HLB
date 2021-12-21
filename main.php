<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/main_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

//상단 노출 갯수
$query = "select count(*) as cnt from tbl_noti_page where tn_is_del = 'F' and tn_status = 'T' and tn_topshow = 'T' and tn_coseq = {$coseq}";
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$count_rows = $data['cnt'];
if($count_rows>0){
    $count_rows = 10-$count_rows;    
}else{
    $count_rows = 10;
}

//상단 노출 게시판
$query = "select * from tbl_noti_page where tn_is_del = 'F' and tn_status = 'T' and tn_topshow = 'T' and tn_coseq = {$coseq} order by tn_regdate desc";
$ps = pdo_query($db,$query,array());
$list_toplist = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list_toplist, $data);
}

//공지사항
$query="select * from tbl_noti_page where tn_is_del = 'F' and tn_status = 'T' and tn_topshow = 'F' and tn_coseq = {$coseq} order by tn_regdate desc limit {$count_rows}";
$ps = pdo_query($db,$query,array());
$noticeList = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($noticeList,$data);
}


$data =  get_member_info($db,$mmseq); //기본정보 
$birth_list = get_birth_list($db);
$position_list = get_position_list($db,1);
$group_list = get_group_list($db);
$project_list = get_project_list($db,$mmseq); // 프로젝트
$project_list_v2 = get_project_list_v2($db,$mmseq); // 프로젝트
$member_birth_check = get_member_birth($db,$mmseq);


// echo('<pre>');print_r($_SESSION);echo('</pre>');
//휴가
$query="SELECT * FROM tbl_commute where tc_mmseq = {$mmseq} and tc_coseq = {$coseq} order by tc_regdate desc";
$ps = pdo_query($db,$query,array());
$list_holiday = array();
while($data_holiday = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list_holiday, $data_holiday);
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
$mc_regdate_interval = $datetime7->diff($datetime8);

//부서 / 직위 쿼리
$query = "
select * from tbl_ess_group teg join tbl_position tp 
where teg.tg_seq =? and tp.tp_seq=?";
$ps = pdo_query($db,$query,array($data['mm_group'],$data['mm_position']));
$data_buseo = $ps ->fetch(PDO::FETCH_ASSOC);

//현 법인근속
$date2 = date('Y-m-d', (strtotime(date('Y').'-'.substr($data['mm_birth'],5,5))));
$datetime10 = new DateTime($date2);
$datetime11 = new DateTime(date('Y-m-d'));
$mc_regdate_interval2 = $datetime10->diff($datetime11);


?>
<style>
    #circle_chart_1 > .text {position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); text-align: center;}
    #circle_chart_1 > .text > strong {display: block; text-align: center; color: #c41230; font-size: 46px;}
    #circle_chart_1 > .text > span {position: relative; top: -6px; color: #a3a3a3;}

    .ess-visual .bx-viewport{height: 330px !important;}
    .ess-visual .bx-controls{display: none;}
</style>
<!-- WRAP -->
<div id="wrap" class="depth-main">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->

<!-- CONTENT -->
<div id="container" class="ess-main">
	<div id="content" >
		<div class="ess-visual">
			<ul class="ess-slider">
				<li>
					<div class="info-text">
						<strong>HLB Group HR System</strong>
						<p>임직원 개개인의 성장을 응원합니다.</p>
					</div>
					<div class="thumb"><img src="/@resource/images/@thumb/main.png" alt="메인이미지"></div>
				</li>
				<!-- <li>
					<div class="info-text">
						<strong>HLB Group<br>HR System Open</strong>
						<p>에이치엘비그룹 인사시스템입니다.<br>임직원 개개인의 성장을 응원합니다.</p>
					</div>
					<div class="thumb"><img src="/@resource/images/@thumb/main.png" alt="메인이미지"></div>
				</li> -->
			</ul>
		</div>
		<!-- profile-wrap -->
		<div class="profile-wrap">
			<div class="section">
				<!-- 프로필 -->
				<div class="profile" style="position: relative; right: 40px;">
					<div class="item-info">
						<div class="thumb"><img src="<?=$data['mm_profile']?>" alt="프로필 사진"></div>
						<div class="info">
							<div class="number"><?=$data['mc_code']?></div>
							<div class="user"><span><?=$enc->decrypt($data['mm_name'])?></span></div>
							<div class="team"><?=implode('/ ',get_group_list_v2($db,$data['mmseq']))?> (<?=get_position_title($db,$data['mc_position'])?>)</div>
							<!-- <div class="sub-team">채용파트(2575425754)</div> -->
                        </div>
                        <?if($member_birth_check){?>
                            <div class="today-event">
                                <span class="ico event"></span>
                                <?=str_replace('-','.',substr($data['mm_birth'],5,5))?> 생일 축하 합니다.
                            </div> <!-- // 170104 추가 -->
                        <?}?>
					</div>
					<div class="cont-footer">

						<a class="btn medium type02" href="/ess/tree">팀조직도 조회<span class="ico search"></span></a>
						<a class="btn medium type02" href="/ess/timeline">나의 타임라인<span class="ico search"></span></a>
					</div>
				</div>
				<!-- //프로필 -->
				<!-- 총 근속 -->
			<div class="mydate">
					<div class="cont-header">
						<span class="title">총 근속</span>
						<span class="all-date"><?=$regdate_interval->format('%y')?>년 <?=$regdate_interval->format('%m')?>개월</span>
					</div>
					<ul class="mydate-list">
						<li>
							<div class="title">현 직급년차</div>
                            <div><span class="date">
                            <?if(!empty($data['mc_bepromoted_date'])){?>
							    <div><span class="date"><?=$position_interval->format('%y')?>년 <?=$position_interval->format('%m')?>개월</span></div>
                            <?}else{?>
                                -
                            <?}?>
                                </span>
                            </div>
                        </li>
                        <li>
							<div class="title">현 법인근속</div>
                            <?if(!empty($data['mc_regdate'])){?>
							    <div><span class="date"><?=$mc_regdate_interval->format('%y')?>년 <?=$mc_regdate_interval->format('%m')?>개월</span></div>
                            <?}else{?>
                                -
                            <?}?>
						</li>
						<li>
							<div class="title">현 부서근속</div>
							<div><span class="date">
                                <?if(!empty($data['mc_affiliate_date'])){?>
                                    <div><span class="date"><?=$affiliate_interval->format('%y')?>년 <?=$affiliate_interval->format('%m')?>개월</span></div>
                                <?}else{?>
                                    -
                                <?}?>
                                </span>
                            </div>
						</li>
						
					</ul>
				</div>
				<!-- //총 근속 -->
				<div class="career" style="margin-left:-2px;">
					<div class="cont-header">
						<span class="title">프로젝트</span>
						<span class="lavel"><?=sizeof($project_list)?>건 </span>
					</div>
					<ul class="career-list">
                        <?foreach ($project_list_v2 as $key =>$val){?>
                            <li>
                                <div class="title"><?=$enc->decrypt($val['mpd_name'])?></div>
                                <div class="text" style="border-bottom:none;color:#000033;">
                                    <?if($val['mpd_result']=='1'){?>진행중<?}?>
                                    <?if($val['mpd_result']=='2'){?>완료<?}?>
                                    <?if($val['mpd_result']=='3'){?>보류<?}?>
                                    <?if($val['mpd_result']=='4'){?>취소<?}?>
                                </div>
						    </li>
                        <?}?>
					</ul>
					<p class="info-text"> <a href="/ess/?active=project"> 상세 보기 &gt; </a></p>
				</div>
				<!-- //총 근속 -->
			</div>
		</div>
		<!--//  profile-wrap -->
		<div class="refresh-wrap">
			<div class="section"  style="height: 100%;">
				<div class="refresh">
					<h3 class="subject">Refresh <a href="/ess/holiday" class="btn-more">더보기</a></h3>
					<div class="article">
						<p>업무와 휴식의 적절한 <br>균형을 유지하세요.<span>멋진 하루를 선물하세요!</span></p>
						<a class="btn large type02" href="/ess/holiday">휴가 신청하기<span class="ico date"></span></a>
					</div>
                    <div class="chart" style="position:absolute;margin-left:325px;">
                        <div id="circle_chart_1" style="width: 173px; height: 173px;">
                        </div>
						<!-- <img src="../../@resource/images/ess_main/thumb_chart.gif" alt="차트"> -->
					</div>
				
					<ul class="date-list">
						<li>
							<div class="title">전체일수</div>
							<div><span class="date"><?=$data['mc_commute_all']?>일</span></div>
						</li>
						<li>
							<div class="title">사용일수</div>
							<div><span class="date em weighty"><?=$data['mc_commute_use']?>일</span></div>
						</li>
						<li>
							<div class="title">잔여일수</div>
							<div><span class="date"><?=$data['mc_commute_remain']?>일</span></div>
						</li>
					</ul>
				</div>

				<div class="career modify" style="margin-left:-2px;">
					<div class="cont-header">
						<span class="title">휴가 신청</span>
						<span class="lavel"><?=sizeof($list_holiday)?>건 </span>
					</div>
					<ul class="career-list">
						<li>
							<div class="title">신청일</div>
							<div class="text"  style="border-bottom:none;color:#000033;"><?=substr($list_holiday[0]['tc_regdate'],0,10)?></div>
						</li>
						<li>
							<div class="title">휴가</div>
							<div class="text" style="border-bottom:none;color:#000033;"><?=substr($list_holiday[0]['tc_date'],0,10)?> <?if(!empty($vacation[$list_holiday[0]['tc_div']])){?>(<?=$vacation[$list_holiday[0]['tc_div']]?>)<?}?></div>
						</li>
						<li>
							<div class="title">승인상태</div>
							<div class="text" style="border-bottom:none;color:#000033;"><?=$status2[$list_holiday[0]['tc_confirm1_state']]?></div>
						</li>
					</ul>
					<p class="info-text"> <a href="/ess/holiday"> 상세 보기 &gt; </a></p>
				</div>
				<!-- 201201 주석 처리 정수민
				<div class="hr-info" style="height:255px;">
					<dl class="hr-list">
						<dt class="active">HR System</dt>
						<dd>
							<p>새로운 HLB HR 시스템 <br>사용법에 대해<br>알려드릴께요.</p>
							<a class="btn large type05" href="#">자세히 보기<span class="ico check"></span></a>
						</dd>
						<dt>One Click HR</dt>
						<dd>
							 180228 텍스트 수정 <p>어려움과 고충, 함께 나누면 답이 보입니다. <br> 인사담당자와 1:1로 상담하실 수 있습니다.</p>
							<p>휴가신청, 나의 정보 수정요청<br> 인사담당자에게 <br> 1:1로 신청 하실 수 있습니다.<br></p><!-- 20180416 텍스트 수정
                            <a class="btn large type05" href="#">자세히 보기<span class="ico check"></span></a>
						</dd>
					</dl>
				</div> -->
			</div>
		</div>
		<div class="section" >

			<!-- <div class="hr-notice">
				<h3 class="subject">HR 공지사항<a href="#" class="btn-more">더보기</a></h3>
				<div class="cont">
					<p class="title"><a href="#">2016년 건강·장기요양보험료<br>정산 안내 ...</a></p>
					<p class="text"><a href="#">직장가입자의 보험료를 전년도 소득을 기준으로 우선<br>부과한 후, 다음해 3월에 당해 연도 확정소득을 신고...</a></p>
				</div>
				<div class="btn-control">
					<a class="prev" href="#">이전</a>
					<a class="next" href="#">다음</a>
				</div>
			</div>-->
			<!-- // hr-notice -->
			<!-- 팀원 기념일 180227 추가 -->
			<div class="team-anniversary">
				<h3 class="subject">기념일</h3>
				<p class="notice"> 소식에 관심을 표현해주세요.</p>
				<!-- <p class="by-team">(일반/연구/영업직 : 당일~ 2주 이내, 기술/생산/정비직 : 당일)</p> // 180308 삭제 -->
				<!-- SLIDE -->
					<ul class="birthday-slider">
                        <?foreach ($birth_list as $val){?>
                            <li>
                                <div class="thumb"><img src="<?=$val['mm_profile']?>"></div>
                                <?if($val['mm_birth']==date('m-d')){?>
                                    <div class="btn type03 small"><span class="ico birthday"></span>TODAY</div>
                                <?}?>
                                <div class="name"><?=$enc->decrypt($val['mm_name'])?></div>
                                <div class="position"><?=get_position_title($db,$val['mc_position'])?></div>
                                <div class="date"><?=str_replace('-','/',$val['mm_birth'])?> 생일</div>
                            </li>
                        <?}?>
					</ul>
					<!-- // SLIDE -->
					<!-- // 기념일이 있을 때 -->
					<!-- 기념일 없을 때 -->
					<!-- <div class="no-anniversary"><p>해당 팀원이 없습니다.</p></div> -->
					<!-- // 기념일 없을 때 -->
			</div>
			<!-- // 팀원 기념일 180227 추가 -->
			<!-- appoint -->
			<div class="appoint">
				<h3 class="subject">공지사항<a href="/ess/notice" class="btn-more">더보기</a></h3>
				<div class="tab-appoint">
					
					<div id="hlb" class="tab-cont active">
						<ul class="data-list">
                            <?if(sizeof($list_toplist)>0){
							for($i=0;$i<sizeof($list_toplist);$i++){?>
							<li onclick="move_detail(<?=$list_toplist[$i]['tn_seq']?>,'&1=1')" style="cursor: pointer">
								<span class="text"><?=$list_toplist[$i]['tn_title']?></span>
								<span class="team"></span>
								<span class="date"><?=dateTextFormat($list_toplist[$i]['tn_regdate'],0)?></span>
							</li>
							<?}
							}?>
							<?if(sizeof($noticeList)>0){
							for($i=0;$i<sizeof($noticeList);$i++){?>
							<li onclick="move_detail(<?=$noticeList[$i]['tn_seq']?>,'&1=1')" style="cursor: pointer">
								<span class="text"><?=$noticeList[$i]['tn_title']?></span>
								<span class="team"></span>
								<span class="date"><?=dateTextFormat($noticeList[$i]['tn_regdate'],0)?></span>
							</li>
							<?}
							}?>
						</ul>
					</div>
				
				</div>


			</div>
			<!-- //appoint -->
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script type="text/javascript">
$(document).ready(function () {
    let remain_holyday = (<?=$data['mc_commute_remain']?>/<?=$data['mc_commute_all']?>)*100;
    let total_holyday = 100-remain_holyday;
    console.log(remain_holyday);
    console.log(total_holyday);
    google.charts.load('current', {'packages' : ['corechart', 'line', 'bar']});
    google.charts.setOnLoadCallback(function(){
        drawCircleChart('circle_chart_1', {
            value_color : 'darkgray', // 값유지
            msg : '<strong><?=$data['mc_commute_remain']?></strong><span>잔여 일수</span>', // 가운데 택스트
            msg_style : null, // 널유지
            rowval :
            {
                '0' : {value : total_holyday, color : '#f6f6f6'}, //전체 일수
                '1' : {value : remain_holyday, color : '#c41230'}, //남은 일수
                //   '2' : {value : 5, color : '#d0d0d0'},
            },
            pieHole : 0.7 // 굵기
        });
    });

	slider = $('.ess-slider').bxSlider( {
		minSlides: 2,
		controls: false,
		auto: true,
		speed: 600,
		infiniteLoop: true,
		mode : 'fade'
		//preventDefaultSwipeY: false
	});

	$('.bx-pager-item').on('click', function() {
		// slider.destroySlider();
	});

	$('.birthday-slider').bxSlider( {
		maxSlides: 2,
		slideWidth: 100,
		slideMargin: 20,
		pager: true,
		controls: false,
		auto: false,
		speed: 600,
		infiniteLoop: false,
		touchEnabled: true
    });

});
</script>


