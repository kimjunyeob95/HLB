<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/common_color.php';
?>
<style>
.menu-wrap .nav li.depth01{min-height:250px;}
</style>
<?
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mmseq = $_SESSION['mmseq'];
$query = "select * from ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq 
join tbl_coperation tc on tc.co_seq = emc.mc_coseq  where emb.mmseq = {$_SESSION['mmseq']} and tc.co_status='T' ";
$ps = pdo_query($db,$query,array());
$list_gnb = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)) {
    array_push($list_gnb, $data_gnb);
}
// echo('<pre>');print_r($_SESSION);echo('</pre>');
// $query = "select * from tbl_noti_page where tn_is_del = 'F' and tn_status = 'T' and tn_menushow = 'T' and tn_coseq = {$coseq} order by tn_regdate desc";
// $ps = pdo_query($db,$query,array());
// $list_gnb_menu = array();
// while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
//     array_push($list_gnb_menu,$data_gnb);
// }
// echo('<pre>');print_r($list_gnb_menu);echo('</pre>');
$query = "select * from tbl_hr_top_notice where hrTopState = 'T' and hrTopCoseq = {$coseq} and hrTopDel = 'F' limit 1";
$ps = pdo_query($db,$query,array());
$main_notice = $ps ->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($main_notice);echo('</pre>');exit;
$query ="select * from tbl_ess_group where tg_mms_mmseq = {$mmseq} and tg_coseq = {$coseq}";
$ps = pdo_query($db,$query,array());
$reader = $ps ->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($_SESSION['mInfo']['co_logo']);echo('</pre>');
?>
<div id="header">
		<!-- header-wrap -->
	<div class="header-wrap">
        <h1 style="background: url('/data/logo/<?=$_SESSION['mInfo']['co_logo']?>') 50% 50% no-repeat; background-size: contain;"><a href="/"><span class="blind">HLB</span></a></h1>
		<!-- personal-info -->
		<div class="personal-info">
            <?if(sizeof($list_gnb)==1){?>
            <div class="position"><span class="name"><?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?> </span>님 환영합니다.</div>
            <?}else{?>
            <div class="select-area" style="display: inline-block; float: left; padding-right: 26px;">
                <div class="insert">
                    <select class="select" id="change_coperation">
                        <option value="0" selected style="display: none;">
                            <?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?>님 환영합니다
                        </option> 
                        <?foreach ($list_gnb as $index => $val){?>
                            <option value="<?=$val['mc_seq']?>">
                                <?=$val['co_name']?>
                                <?if(!empty($val['co_subname'])){?> <?=$val['co_subname']?><?}?>
                            </option>
                        <?}?>
                    </select>
                </div>
            </div>
            <?}?>
            <div class="state changepw"><a href="/include/changepw.php"><span class="ico people"></span>비밀번호 변경</a></div>
			<div class="state logout"><a href="/@proc/logout.php">Logout</a></div>
		</div>
		<!-- // personal-info -->
		<!-- utill-menu -->
		<div class="utill-menu">
			<ul>
                <?if($_SESSION['mInfo']['mc_hass']=='T'){?>
                <li class="active"><a href="/">My HR</a></li>
                <li class=""><a href="../hass/index">인사 담당</a></li>
                <?}?>
			</ul>
		</div>
		<!-- // utill-menu -->
		<!-- gnb -->
		<div class="gnb">
			<ul class="nav">
				<li class="depth01"><a href="/ess/timeline">My profile</a>
					<ul class="depth02">
                        <li><a href="/ess/tree">조직도</a></li>
						<li><a href="/ess/timeline">나의 타임라인</a></li>
						<li><a href="/ess/">인사기록카드</a></li>
						<li><a href="/ess/change">정보 변경 신청 내역</a></li>
						
						<!-- <li><a href="/ess/organization.php">조직도</a></li> -->
					</ul>
				</li>
				<!-- <li class="depth01"><a href="/ess/tree.php">조직도</a>
                    <ul class="depth02">
						
					</ul>
                </li> -->
				<li class="depth01"><a href="/ess/submain3">근태</a>
					<ul class="depth02">
						<li><a href="/ess/holiday">휴가 신청</a></li>
                        <!-- <li><a href="/ess/holyguide.php">근태 가이드</a></li> -->
                        <?if(!empty($reader)){?>
                            <li><a href="/ess/mssholyday">휴가관리</a></li>
                        <?}?>
					</ul>
				</li>
				<li class="depth01"><a href="/ess/submain4">보상</a>
					<ul class="depth02">
						<li><a href="/ess/salary">급여명세서</a></li>
                        <li><a href="/ess/salarySearch">월별 지급내역</a></li>
						<!-- <li><a href="#" onclick="popup_click('reword01')">연봉계약서</a></li> -->
						<!-- <li><a href="#" onclick="popup_click('reword02')">퇴직금 중도인출안내</a></li> -->
					</ul>
				</li>
			
				<li class="depth01"><a href="/ess/notice">공지사항</a>
					<ul class="depth02">
                        <li><a href="/ess/notice">공지사항</a></li>
						<li><a href="/ess/hrnotice">HR 안내</a></li>
                        <!-- <li><a href="/ess/cornotice.php">법인별 주요사항</a></li> -->
                        <!-- <li><a href="/ess/retireguide.php">퇴직안내문</a></li> -->
                        <!-- <?for($i=0;$i<sizeof($list_gnb_menu);$i++){?>
                            <li><a href="/ess/noticeDetail.php?pageNum=<?=$i?>&seq=<?=$list_gnb_menu[$i]['tn_seq']?>"><?=$list_gnb_menu[$i]['tn_title']?></a></li>
                        <?}?> -->
					</ul>
				</li>
            </ul>
            
			<div class="btn-area">
				<a href="#" class="all-menu"><span class="blind">모든 메뉴</span></a>
			</div>
            <div class="select-area">
                <div class="insert">
                    <p><?=$main_notice['hrTopTitle']?></p>
                </div>
            </div>
            <!-- <div class="select-area">
                <div class="insert">
                    <select class="select" id="change_coperation">
                        <?foreach ($list_gnb as $val){?>
                            <option value="<?=$val['mc_seq']?>" <?if($val['mc_seq']==$_SESSION['mInfo']['mc_seq']){?>selected<?}?>>
                                <?=$val['co_name']?>
                                <?if(!empty($val['co_subname'])){?> <?=$val['co_subname']?><?}?>
                            </option>
                        <?}?>
                    </select>
                </div>
			</div> -->
		</div>
		<!-- // gnb -->
		<!-- 전체메뉴 -->
		<div class="menu-wrap">
			<div class="menu-inner">
				<a href="#" class="btn-close"><span class="blind">전체메뉴 닫기</span></a>
				<h2 class="title">전체메뉴</h2>
				<ul class="nav">
					<li class="depth01"><a href="#">My profile</a>
						<ul class="depth02">
                            <li><a href="/ess/tree">조직도</a></li>
							<li><a href="/ess/timeline">나의 타임라인</a></li>
							<li><a href="/ess/"">인사기록카드</a></li>
							<li><a href="/ess/change">정보 변경 신청 내역</a></li>
							
							<!-- <li><a href="/ess/organization.php">팀 조직도</a></li> -->
						</ul>
					</li>
					<!-- <li class="depth01"><a href="#">조직도</a>
                        <ul class="depth02">
                            <li><a href="/ess/tree.php">조직도</a></li>
                        </ul>
                    </li> -->
					<li class="depth01"><a href="#">근태</a>
						<ul class="depth02">
							<li><a href="/ess/holiday">휴가 신청</a></li>
							<!-- <li><a href="/ess/holyguide.php">근태 가이드</a></li> -->
                            <?if(!empty($reader)){?>
                                <li><a href="/ess/mssholyday">휴가관리</a></li>
                            <?}?>
							<!--
							<li><a href="#">모성보호</a>
								<ul class="depth03">
									<li><a href="#">임신 사실 확인</a></li>
									<li><a href="#">출산휴가 신청</a></li>
									<li><a href="#">육아휴직(단축근무) 신청</a></li>
								</ul>
							</li>
							-->
						</ul>
					</li>
					<li class="depth01"><a href="/ess/submain4">보상</a>
						<ul class="depth02">
							<li><a href="/ess/holiday">급여명세서</a></li>
                            <li><a href="/ess/salarySearch">월별 지급내역</a></li>
							<!-- <li><a href="#" onclick="popup_click('reword01')">연봉계약서</a></li> -->
							<!-- <li><a href="#" onclick="popup_click('reword02')">퇴직금 중도인출안내</a></li> -->
						</ul>
					</li>
				
					<li class="depth01"><a href="/ess/notice">공지사항</a>
						<ul class="depth02">
                            <li><a href="/ess/notice">공지사항</a></li>
							<li><a href="/ess/hrnotice">HR 안내</a></li>
                            <!-- <li><a href="/ess/cornotice.php">법인별 주요사항</a></li> -->
                            <!-- <li><a href="/ess/retireguide.php">퇴직안내문</a></li> -->
                            <!-- <?for($i=0;$i<sizeof($list_gnb_menu);$i++){?>
                                <li><a href="/ess/noticeDetail.php?pageNum=<?=$i?>&seq=<?=$list_gnb_menu[$i]['tn_seq']?>"><?=$list_gnb_menu[$i]['tn_title']?></a></li>
                            <?}?> -->
						</ul>
					</li>
					
					<!--
					<li class="depth01 menu05"><a href="#">보상</a>
						<ul class="depth02">
							<li><a href="#">급여명세서</a></li>
							<li><a href="#">연봉계약서</a></li>
							<li><a href="#">퇴직금 중도 인출</a></li>
						</ul>
					</li>
					-->

				</ul>
			</div>
			<div class="menu-dimmed"></div>
		</div>
		<!-- // 전체메뉴 -->
	</div>
	<!--// header-wrap -->
</div>

<script>
    function popup_click(url){
        var url;
        var name;
        var option = "width = 700, height = 700, top = 200, left = 500, location = no, menubar = no, toolbar = no, directories=no, status=no";
        
        if(url == 'reword01'){
            url = "/ess/reward_popup01.php";
            name = "연봉 계약서"
            window.open(url, name, option);
        }else if(url == "reword02"){
            url = "/ess/reward_popup02.php";
            name = "퇴직금 중도인출 신청 안내"
            window.open(url, name, option);
        }

    }
    $('#change_coperation').change(function(){
        mc_seq = $(this).val();
        var data = { 'mc_seq' : mc_seq };
        hlb_fn_ajaxTransmit_v2("/@proc/login_change.php", data);
    })

    function fn_callBack_v2(calback_id, result, textStatus){
        if(calback_id=='login_change'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                if(location.pathname.indexOf('page_view') != -1){
                    return location.href = '/main';
                }
                alert(result.msg);
                location.reload();
            }
        }
    }
</script>