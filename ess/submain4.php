<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

// if(empty($_SESSION['mInfo']['salary_auth']) && $_SESSION['mInfo']['mm_super_admin']=='F'){
//     page_move("/ess/salary_auth");
// }
@$auth_js = '
    var user_pw = prompt("주민번호 뒷자리를 입력하세요.");
    if(user_pw == ""){
        location.reload();
    }else if(user_pw === null){
        location.href="/";
    }else{
        $.ajax({
            url: "/@proc/ess/chkSerialNum.php",
            type: "post",
            data: {
                mm_serial_no : user_pw
            },
            dataType: "json",
            success: function(response){
                if(response.code == "FALSE"){
                    alert(response.msg);
                    location.reload();
                }else{
                    $("#wrap").removeClass("hide");
                }
            },
            fail: function(error) {
                location.reload();
            }
        });
    }
';
if(empty($_SESSION['mInfo']['salary_auth'])){
    javascript($auth_js);
}

@$mmseq = $_SESSION['mmseq'];
@$mm_code = $_SESSION['mInfo']['mc_code'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
// echo('<pre>');print_r($_SESSION);echo('</pre>');
//보여줄 급여 쿼리
$query="select * from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False' and hu_showDate <= DATE_FORMAT(NOW(), '%Y-%m-%d %H') and hu_hss_seq={$mc_coseq}
order by hu_showDate desc limit 1 ) hu
on hu.hu_salaryDate_month = hue.hue_salaryDate_month and
hu.hu_hss_code = hue.hue_hss_code where
hue.hue_del = 'False' and
hu.hu_salaryDate_year = hue.hue_salaryDate_year and
hue.hue_ess_code ={$mm_code} and hue.hue_hss_seq={$mc_coseq}";
// echo('<pre>');print_r($query);echo('</pre>');
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);

//이번 년도 급여 쿼리
$query="select distinct hue.hue_salaryDate_month,hue.* from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False' and hu_salaryDate_year=?) hu
on hu.hu_salaryDate_year = hue.hue_salaryDate_year and
hu.hu_hss_code = hue.hue_hss_code where
hue.hue_del = 'False' and
hue.hue_ess_code =? and hue.hue_hss_seq=? order by hue.hue_salaryDate_month;";
$ps = pdo_query($db,$query,array(date('Y'),$mm_code,$mc_coseq));
$list = array();
while($data_salry = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data_salry);
}

// echo('<pre>');print_r($_SESSION);echo('</pre>');
$total_price; //총 누적액
$total_pay; //총 지급액
$total_deduct; //총 공제액
foreach ($list as $val){
    $total_price+=$val['total_pay']; //총 누적액
    $total_pay+=$val['pay12']; // 총 지금액
    $total_deduct+=$val['deduct12']; //총 공제액
}
?>

<?if(empty($_SESSION['mInfo']['salary_auth'])){?>
    <div id="wrap" class="depth-main diligence-wrap hide">
<?}else{?>
    <div id="wrap" class="depth-main diligence-wrap">
<?}?>
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>


<!-- CONTENT -->
<div id="container" class="reward-main">
	<div class="content-visual"><img src="../../@resource/images/reward/sub_main_visual_01_reward_test.png" alt="보상화면"></div>
	<div id="content" class="content-primary" style="width:1160px;">
		<!-- right-section -->
		<div class="right-section img">
            <!-- <img src="../../@resource/images/reward/reward_sub_bg.png" alt="보상화면"> -->
			<h2 class="title">오늘의 작은<br>노력과 열정이<br>내일을 <br>만듭니다.</h2>
			<p class="summary">이 세상에 위대한 사람은 없다.<br>단지 평범한 사람들이 일어나 맞서는<br>위대한 도전이 있을 뿐이다.<span>- 윌리엄 프레데릭 홀시</span></p>
			<!-- <div class="quick-menu">
				<ul>
					<li><a href="#">진료비 신청</a></li>
					<li><a href="#">예상퇴직금 조회 및<br> 퇴직금 중도정산 신청</a></li>
				</ul>
				<p><a href="#">예상퇴직금 조회 및<br> 퇴직금 중도정산 신청</a></p>
			</div> -->
		</div>
		<!-- // right-section -->
		<!-- reward-section -->
		<div class="reward-section">
			<!-- 171124 추가 -->
			<!-- <input type="button" id="btn_before" /> -->
			<h3 class="subject">
                <?=$data['hu_salaryDate_year']?>년 <?=$data['hu_salaryDate_month']?>월 보상
            </h3>
            <div class="mydate-list">
                <ul>
                    <li style="padding:0px; border:none;">
                        <span class="date">※ <?=$data['hu_showDate']?> 기준</span>
                    </li>
                </ul>
            </div>
			<!-- <input type="button" id="btn_after" /> -->
			<!-- // 171124 추가 -->
			<div class="reward-all">
				<span class="amount"><strong><?=number_format($data['total_pay'])?></strong>원</span>
				<span class="sum">(차인지급액 <?=number_format($data['total_pay'])?>)</span>
				<a href="/ess/salary" class="btn type03 large">당월 지급내역 상세조회<span class="ico check"></span></a>
			</div>
			<ul class="mydate-list">
				<li>
					<div class="title">지급(계)</div>
					<div class="sum">
						<span class="amount"><strong><?=number_format($data['pay12'])?></strong>원</span>
						<!-- <span class="sum">(1,600,000원)</span> -->
						<!-- <span class="date">※ <?=$data['hu_showDate']?> 기준</span> -->
					</div>
				</li>
				<li>
					<div class="title">공제(계)</div>
					<div class="sum">
						<span class="amount"><strong><?=number_format($data['deduct12'])?></strong>원</span>
						<!-- <span class="sum">(1,600,000원)</span> -->
						<!-- <span class="date">※ <?=$data['hu_showDate']?> 기준</span> -->
					</div>
				</li>
			</ul>
			<div class="button-area right">
				<a class="btn type02 large" href="/ess/salarySearch">월별 지급내역 조회<span class="ico check01"></span></a>
			</div>
		</div>
		<!-- // reward-section -->
		<!-- reward-detalle -->
		<div class="reward-detalle">
			<!-- cont-detalle 180311 삭제 -->
			<!-- <div class="cont-detalle">
				<h3 class="subject"><a href="#">복리후생 비용<span class="btn-more">더보기</span></a></h3>
				<ul class="data-list">
					<li>
						<span class="date">2017.02.22</span>
						<span class="text">유아교육비</span>
						<strong class="sum">150,000원</strong>
					</li>
					<li>
						<span class="date">2017.02.22</span>
						<span class="text">진료비</span>
						<strong class="sum">150,000원</strong>
					</li>
					<li>
						<span class="date">2017.02.22</span>
						<span class="text">경조비</span>
						<strong class="sum">300,000원</strong>
					</li>
				</ul>
			</div> -->
			<!-- // cont-detalle 180311 삭제 -->
			<!-- cont-detalle -->
			<div class="cont-detalle">
				<h3 class="subject"><a href="/ess/salarySearch"><?=substr(date('Y'),2,4)?>년 보상내역<span class="btn-more">더보기</span></a></h3>
				<ul class="data-list">
					<li>
						<span class="label">지급(계)</span>
						<strong class="sum"><?=number_format($total_pay)?>원</strong>
					</li>
					<li>
						<span class="label">공제(계)</span>
						<strong class="sum"><?=number_format($total_deduct)?>원</strong>
					</li>
					<!-- <li>
						<span class="label">성과급</span>
						<strong class="sum">-</strong>
					</li> -->
					<!-- 180311 삭제 -->
					<!-- <li>
						<span class="label">복리후생 및 기타</span>
						<strong class="sum">470,000원</strong>
					</li> -->
					<!-- // 180311 삭제 -->
					<li>
						<span class="label"><span class="ico sum02 type01"></span>총계</span>
						<strong class="sum"><?=number_format($total_price)?>원</strong>
					</li>
				</ul>
				<!-- 180311 추가 -->
				<p>※ 전년도/연말정산 소급분은 반영되지 않으며, <br>
     			급여명세서 내역과 동일합니다.</p>
     			<!-- // 180311 추가 -->
			</div>
			<!-- // cont-detalle -->
		</div>
		<!-- // reward-detalle -->
	</div>
</div>
<!-- // CONTENT -->


<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(2)').addClass('active');
//$('.depth02:eq(0)').find('li:eq(2)').addClass('active');
</script>