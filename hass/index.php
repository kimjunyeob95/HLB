<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/info_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$page = $_REQUEST['page'];
$where_query = array();
//$where_query['where']['mm_status'] = 'Y'; // 정규직만
$where_query['where']['name_code'] = $_REQUEST['search'];
$where_query['where']['keyword'] = $_REQUEST['keyword'];
$where_query['where']['mc_coseq'] =  $_SESSION['mInfo']['mc_coseq'];
$paging_subquery= '&search='.$_REQUEST['search'].'&keyword='.$_REQUEST['keyword'];
// echo $_REQUEST['search'];


$total_cnt = get_member_join_co_list($db,$where_query,'cnt');
// 페이징
$rows = 5;
if(empty($page)){
    $page=1;
}
$total_rows = $total_cnt;
if ($total_rows > 0) {
    $total_page = ceil($total_rows / $rows);
} else {
    $total_page = 1;
}

$from = ($page - 1) * $rows;
$numbering = $total_rows - $from;
// 페이징 끝
$member_list = get_member_join_co_list($db,$where_query,'',$from,$rows);

@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
@$yearCurrent_request = $_REQUEST['yearCurrent'];

$query = "select * from tbl_appointment ta join ess_member_base emb on ta.ta_mmseq = emb.mmseq where 
            ta_prev_coseq = {$mc_coseq} limit 0,5";
$ps = pdo_query($db,$query,array());
$list_human = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_human,$data);
}


//재직자만
$where = " emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and emb.mm_status='Y' and emc.mc_coseq={$mc_coseq}";
//퇴사자만
$where2 = " emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and emb.mm_status='D' and emc.mc_coseq={$mc_coseq}";
//재직자 or 퇴사자
$where3 = " emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and ( emb.mm_status='D' or emb.mm_status='Y') and emc.mc_coseq={$mc_coseq}";

//재직자 총인원
$query="SELECT * FROM ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where;
$query.=" ORDER BY mmseq DESC";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}
$position_list2 = get_position_list_admin($db,2,$mc_coseq); //직위
/*
소속 데이터
*/
$list_sosog = get_group_list_all_v3($db,$mc_coseq);
/*
월별 인원현황
*/
//조회가능 년도
$query="SELECT distinct left(emc.mc_regdate,4) as mc_regdate FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." 
order by mc_regdate ";
$ps = pdo_query($db, $query, array());
$array_yearYes = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($array_yearYes, $data);
}
if(empty($yearCurrent_request)){
    $yearCurrent_request=date('Y');
}
if(count($array_yearYes)==1){
    $yearCurrent_request=$array_yearYes[0]['mc_regdate'];
}

//해당년도 월별배열 총인원
$query="SELECT emc.mc_regdate FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where3." 
and LEFT(emc.mc_regdate,4) <= '{$yearCurrent_request}' order by mc_regdate";
$ps = pdo_query($db, $query, array());
$data_total_yearMorris = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($data_total_yearMorris, $data);
}
// echo('<pre>');print_r($data_total_yearMorris);echo('</pre>');
//해당년도 월별배열 입사
$query="SELECT emc.mc_regdate FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." 
and LEFT(emc.mc_regdate,4) = '{$yearCurrent_request}' ";
$ps = pdo_query($db, $query, array());
$data_total_yearMorris_man = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($data_total_yearMorris_man, $data);
}

//해당년도 월별배열 퇴사
$query="SELECT emb.mm_retirement_date FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where2." 
and LEFT(emb.mm_retirement_date,4) = '{$yearCurrent_request}' ";
$ps = pdo_query($db, $query, array());
$data_total_yearMorris_woman = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($data_total_yearMorris_woman, $data);
}
$date_count=array('0'=>0,'1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0);
foreach ($data_total_yearMorris as $index => $val){
    if(substr($val['mc_regdate'],5,2) == '01'){
        $date_count[0] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '02'){
        $date_count[1] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '03'){
        $date_count[2] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '04'){
        $date_count[3] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '05'){
        $date_count[4] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '06'){
        $date_count[5] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '07'){
        $date_count[6] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '08'){
        $date_count[7] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '09'){
        $date_count[8] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '10'){
        $date_count[9] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '11'){
        $date_count[10] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '12'){
        $date_count[11] += 1;
    }
}
$date_count_in=array('0'=>0,'1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0);
foreach ($data_total_yearMorris_man as $index => $val){
    if(substr($val['mc_regdate'],5,2) == '01'){
        $date_count_in[0] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '02'){
        $date_count_in[1] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '03'){
        $date_count_in[2] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '04'){
        $date_count_in[3] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '05'){
        $date_count_in[4] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '06'){
        $date_count_in[5] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '07'){
        $date_count_in[6] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '08'){
        $date_count_in[7] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '09'){
        $date_count_in[8] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '10'){
        $date_count_in[9] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '11'){
        $date_count_in[10] += 1;
    }else if(substr($val['mc_regdate'],5,2) == '12'){
        $date_count_in[11] += 1;
    }
}
$date_count_out=array('0'=>0,'1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0);
foreach ($data_total_yearMorris_woman as $index => $val){
    if(substr($val['mm_retirement_date'],5,2) == '01'){
        $date_count_out[0] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '02'){
        $date_count_out[1] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '03'){
        $date_count_out[2] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '04'){
        $date_count_out[3] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '05'){
        $date_count_out[4] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '06'){
        $date_count_out[5] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '07'){
        $date_count_out[6] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '08'){
        $date_count_out[7] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '09'){
        $date_count_out[8] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '10'){
        $date_count_out[9] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '11'){
        $date_count_out[10] += 1;
    }else if(substr($val['mm_retirement_date'],5,2) == '12'){
        $date_count_out[11] += 1;
    }
}
// echo('<pre>');print_r($date_count);echo('</pre>');
//총인원(이번년도)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where3." 
and DATE_FORMAT(NOW(),'%Y') = LEFT(emc.mc_regdate,4) ";
// echo('<pre>');print_r($query);echo('</pre>');
$ps = pdo_query($db, $query, array());
$data_total_yearCurrent = $ps->fetch(PDO::FETCH_ASSOC);

//총인원(전년)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where3." 
and left(DATE_SUB(  curdate(),  INTERVAL 1 year  ) ,4) = LEFT(emc.mc_regdate,4) ";
$ps = pdo_query($db, $query, array());
$data_total_yearPrev = $ps->fetch(PDO::FETCH_ASSOC);

//총인원(전월)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where3." 
and left(DATE_SUB(  curdate(),  INTERVAL 1 month  ) ,7) = LEFT(emc.mc_regdate,7) ";
$ps = pdo_query($db, $query, array());
$data_total_prev = $ps->fetch(PDO::FETCH_ASSOC);

//입사자(이번년도)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." 
and DATE_FORMAT(NOW(),'%Y') = LEFT(emc.mc_regdate,4) ";
$ps = pdo_query($db, $query, array());
$data_enter_yearCurrent = $ps->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($data_enter_yearCurrent);echo('</pre>');
//입사자(전년)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." 
and left(DATE_SUB(  curdate(),  INTERVAL 1 year  ) ,4) = LEFT(emc.mc_regdate,4) ";
$ps = pdo_query($db, $query, array());
$data_enter_yearPrev = $ps->fetch(PDO::FETCH_ASSOC);
// echo('<pre>');print_r($data_enter_yearPrev);echo('</pre>');
//입사자(전월)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." 
and left(DATE_SUB(  curdate(),  INTERVAL 1 month  ) ,7) = LEFT(emc.mc_regdate,7) ";
$ps = pdo_query($db, $query, array());
$data_enter = $ps->fetch(PDO::FETCH_ASSOC);

//퇴사자(이번년도)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where2." 
and DATE_FORMAT(NOW(),'%Y') = LEFT(emb.mm_retirement_date,4) ";
$ps = pdo_query($db, $query, array());
$data_leave_yearCurrent = $ps->fetch(PDO::FETCH_ASSOC);

//퇴사자(전년)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where2." 
and left(DATE_SUB(  curdate(),  INTERVAL 1 year  ) ,4) = LEFT(emb.mm_retirement_date,4) ";
$ps = pdo_query($db, $query, array());
$data_leave_yearPrev = $ps->fetch(PDO::FETCH_ASSOC);

//퇴사자(전월)
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where2." 
and left(DATE_SUB(  curdate(),  INTERVAL 1 month  ) ,7) = LEFT(emb.mm_retirement_date,7) ";
$ps = pdo_query($db, $query, array());
$data_Leave = $ps->fetch(PDO::FETCH_ASSOC);

//남성
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_gender = 'M' ";
$ps = pdo_query($db, $query, array());
$data_man = $ps->fetch(PDO::FETCH_ASSOC);

//여성
$query="SELECT COUNT(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_gender = 'F' ";
$ps = pdo_query($db, $query, array());
$data_woman = $ps->fetch(PDO::FETCH_ASSOC);

//고졸
$query="SELECT count(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_education = 1 ";
$ps = pdo_query($db, $query, array());
$data_level1 = $ps->fetch(PDO::FETCH_ASSOC);

//전문학사
$query="SELECT count(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_education = 2 ";
$ps = pdo_query($db, $query, array());
$data_level2 = $ps->fetch(PDO::FETCH_ASSOC);

//학사
$query="SELECT count(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_education = 3 ";
$ps = pdo_query($db, $query, array());
$data_level3 = $ps->fetch(PDO::FETCH_ASSOC);

//석사
$query="SELECT count(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_education = 4 ";
$ps = pdo_query($db, $query, array());
$data_level4 = $ps->fetch(PDO::FETCH_ASSOC);

//박사
$query="SELECT count(*) as cnt FROM ess_member_base emb 
join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." and emb.mm_education = 5 ";
$ps = pdo_query($db, $query, array());
$data_level5 = $ps->fetch(PDO::FETCH_ASSOC);
?>
<style>
.bg-darkgrey {background: #484343; color: #FFF;}
.bg-red {background: #f54325; color: #FFF; }
.section .table-wrap.wrap01{display: flex; justify-content: space-between; border-top: none;}
.section .table-wrap.wrap02,
.section .table-wrap.wrap03{border-top: none;}
.center-block.center {width: 100%; padding: 20px; text-align: center;}
.center-block.center img { height: 70px; margin: 0 auto; margin-bottom: 10px;}
.center-block.center .title { margin: 0 auto; font-size: 17px; height: 40px; line-height: 40px; text-align: center; padding: 0 22px;}
.center-block.center .title span{float: right;}
.center-block.center .sub-title{ margin: 0 auto; margin-top:4px; font-size: 15px; height: 35px; line-height: 35px; text-align: center; padding: 0 24px; background-color:#ececec; color: #555555}
.center-block.center .title > span.textLeft,
.center-block.center .sub-title > span.textLeft{float:left}
.center-block.center .title > span.textRight,
.center-block.center .sub-title > span.textRight{float:right;font-weight: 600;}
.center-block.center .title > span,
.center-block.center .sub-title > span{letter-spacing: -0.5px;}
.glyphicon-chevron-up:before{font-size:1.35rem}
#uniform-yearCurrent{margin-top: -4px;}
</style>

<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
		<div class="welcome-intro">
			<strong><?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?>님</strong>
			<p>인사 시스템에 오신것을 환영합니다.</p>
		</div>
        <!-- 년도 조회 -->
        <form method="post" id="form_proccess" action="<?= $_SERVER['PHP_SELF']?>" style="margin-top: 20px;">
            <input type="hidden" value="<?=$_REQUEST['keyword']?>" name='keyword'/>

            <!-- 인사통계 영역 -->
            <div class="section">
                <h3 class="section-title">인사통계</h3>
                <div class="table-wrap wrap01">
                    <div class="center-block center">
                        <img src="../@resource/images/manage/users.png" alt="" class="img-responsive">
                        <div class="bg-darkgrey title">
                            <span class="textLeft">총 인원</span>
                            <span class="textRight"><?=count($list)?>명</span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전년</span>
                            <span class="textRight">
                            <?
                                //증감식
                                $yearCurrent = $data_total_yearCurrent['cnt'];
                                $yearPrev = $data_total_yearPrev['cnt'];
                                if($yearCurrent>$yearPrev){
                                    $result=round((($yearCurrent-$yearPrev)/$yearCurrent*100),1);
                                    if($result==0 || is_nan($result)){
                                        echo "0%";
                                    }else{
                                        echo $result.'% ↑';
                                    }
                                }else{
                                    $result=round((($yearPrev-$yearCurrent)/$yearPrev*100),1);
                                    if($result ==0 || is_nan($result)){
                                        echo "0%";
                                    }else{
                                        echo $result.'% ↓';
                                    }
                                }  
                            ?>
                            </span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전월</span>
                            <span class="textRight"><?=$data_total_prev['cnt']?>명</span>
                        </div>
                    </div>
                    <div class="center-block center">
                        <img src="../@resource/images/manage/insert.png" alt="" class="img-responsive">
                        <div class="bg-red title">
                            입사율
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전년</span>
                            <span class="textRight">
                            <?
                                //증감식
                                $yearCurrent = $data_enter_yearCurrent['cnt'];
                                $yearPrev = $data_enter_yearPrev['cnt'];
                                if($yearCurrent>$yearPrev){
                                    $result=round((($yearCurrent-$yearPrev)/$yearCurrent*100),1);
                                    if($result==0 || is_nan($result)){
                                        echo "0%";
                                    }else{
                                        echo $result.'% ↑';
                                    }
                                }else{
                                    $result=round((($yearPrev-$yearCurrent)/$yearPrev*100),1);
                                    if($result ==0 || is_nan($result)){
                                        echo "0%";
                                    }else{
                                        echo $result.'% ↓';
                                    }
                                }  
                            ?>
                            </span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전월</span>
                            <span class="textRight"><?=$data_enter['cnt']?>명</span>
                        </div>
                    </div>
                    <div class="center-block center">   
                        <img src="../@resource/images/manage/out.png" alt="" class="img-responsive">
                        <div class="bg-red title">
                            퇴사율
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전년</span>
                            <span class="textRight">
                            <?
                                //증감식
                                $yearCurrent = $data_leave_yearCurrent['cnt'];
                                $yearPrev = $data_leave_yearPrev['cnt'];
                                if($yearCurrent>$yearPrev){
                                    $result=round((($yearCurrent-$yearPrev)/$yearCurrent*100),1);
                                    if($result==0 || is_nan($result)){
                                        echo "0%";
                                    }else{
                                        echo $result.'% ↑';
                                    }
                                }else{
                                    $result=round((($yearPrev-$yearCurrent)/$yearPrev*100),1);
                                    if($result ==0 || is_nan($result)){
                                        echo "0%";
                                    }else{
                                        echo $result.'% ↓';
                                    }
                                }  
                            ?>
                            </span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전월</span>
                            <span class="textRight"><?=$data_Leave['cnt']?>명</span>
                        </div>
                    </div>
                    <div class="center-block center">
                        <img src="../@resource/images/manage/manwoman.png" alt="" class="img-responsive">
                        <div class="bg-darkgrey title">
                            성비
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">남성</span>
                            <span class="textRight"><? echo $data_man['cnt'].'명 ('.floor(($data_man['cnt']/count($list))*100).'%)'; ?></span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">여성</span>
                            <span class="textRight"><? echo $data_woman['cnt'].'명 ('.floor(($data_woman['cnt']/count($list))*100).'%)';?></span>
                        </div>
                    </div>
                    <div class="center-block center">
                        <img src="../@resource/images/manage/inwon.png" alt="" class="img-responsive">
                        <div class="bg-darkgrey title">
                            인원현황
                        </div>
                        <? foreach($position_list2 as $index => $val) {
                            $where = " emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and emb.mm_status='Y' and emc.mc_coseq={$mc_coseq} and emc.mc_position2 = {$val['tp_seq']} ";
                            $query="SELECT count(*) as cnt FROM ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where;
                            $ps = pdo_query($db,$query,array());
                            $data = $ps ->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="sub-title center-block">
                                <span class="textLeft"><?=$val['tp_title']?></span>
                                <span class="textRight"><?=$data['cnt']?>명 (<?=floor(($data['cnt']/count($list))*100)?>%)</span>
                            </div>
                        <?}?>
                    </div>
                    <div class="center-block center">
                        <img src="../@resource/images/manage/education.png" alt="" class="img-responsive">
                        <div class="bg-darkgrey title">
                            학력분포
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">고졸</span>
                            <span class="textRight"><?=$data_level1['cnt']?>명 (<?=floor(($data_level1['cnt']/count($list))*100)?>%)</span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">전문학사</span>
                            <span class="textRight"><?=$data_level2['cnt']?>명 (<?=floor(($data_level2['cnt']/count($list))*100)?>%)</span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">학사</span>
                            <span class="textRight"><?=$data_level3['cnt']?>명 (<?=floor(($data_level3['cnt']/count($list))*100)?>%)</span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">석사</span>
                            <span class="textRight"><?=$data_level4['cnt']?>명 (<?=floor(($data_level4['cnt']/count($list))*100)?>%)</span>
                        </div>
                        <div class="sub-title center-block">
                            <span class="textLeft">박사</span>
                            <span class="textRight"><?=$data_level5['cnt']?>명 (<?=floor(($data_level5['cnt']/count($list))*100)?>%)</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 인사통계 영역 -->

            <!-- 월별인원추이 영역 -->
            <div class="section" style="margin-top:0px;">
                <div class="section-wrap">
                    <h3 class="section-title" style="display: inline-block;">
                        <select name="yearCurrent" id="yearCurrent">
                            <?for($i=0;$i<sizeof($array_yearYes);$i++){
                            ?>
                                <option value="<?=$array_yearYes[$i]['mc_regdate']?>" 
                                    <?if($yearCurrent_request==$array_yearYes[$i]['mc_regdate']){?>selected<?}?>>
                                    <?=$array_yearYes[$i]['mc_regdate']?>
                                </option>
                            <?}?>
                        </select>
                        년 월별 인원추이
                    </h3>
                    
                </div>
                <div class="table-wrap wrap02">
                    <div id="myfirstchart" style="height: 250px;"></div>
                </div>
            </div>
            <!-- 월별인원추이 영역 -->

            <!-- 남녀인원현황 영역 -->
            <div class="section" style="margin-top:0px;">
                <h3 class="section-title">남여 인원현황</h3>
                <div class="table-wrap wrap03">
                    <div id="bar-chart" style="height: 250px;"></div>
                </div>
            </div>
            <!-- 남녀인원현황 영역 -->
        </form>
		<!-- //년도 조회 -->

        <!-- 키워드 조회 -->
        <form method="post" action="<?= $_SERVER['PHP_SELF']?>" style="margin-top: 20px;">
            <input type="hidden" value="<?=$yearCurrent_request?>" name='yearCurrent'/>
            <div class="section">
                <fieldset class="fieldset large">
                    <legend>키워드조회폼</legend>
                    <div class="field">
                        <h4><span class="ico write" style="margin-left:0"></span> 키워드조회</h4>
                        <div class="insert first">
                            <label for="keyword" class="label">키워드</label>
                            <input type="text" id="keyword" value="<?=$_REQUEST['keyword']?>" name='keyword' class="input-text" style="width: 140px" />
                        </div>
                        <div class="btn-area">
                            <button class="btn small type01" style="margin-left: 6px;">검색하기 <span class="ico search"></span></button>
                        </div>
                        <div class="insert first">
                            <label for="keyword" class="label">(소속,직위,직책,직무,직군,사번,성명,학교명,전공,키워드,자격증 검색가능)</label>
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>
		<!-- //키워드 조회 -->

		<!-- 검색결과 -->
        
            <div class="section">
                <h3 class="section-title">검색결과</h3>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>검색결과 표</caption>
                        <colgroup>
                            <col style="width: 11%">
                            <col style="width: 12%">
                            <col style="width: 13%">
                            <col style="width: 10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">사번</th>
                                <th scope="col">성명</th>
                                <th scope="col">소속</th>
                                <th scope="col">직급</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?if(!empty($member_list)){?>
                            <?foreach ($member_list as $val){?>
                            <tr style="cursor:pointer;" onclick="go_detail('<?=$val['mmseq']?>')">
                                <td><?=$val['mc_code']?></td>
                                <td><?=$enc->decrypt($val['mm_name'])?></td>
                                <td><?=implode('<br> ',get_group_list_v2($db,$val['mmseq']))?></td>
                                <td><?=get_position_title($db,$val['mc_position'])?></td>
                            </tr>
                            <?}?>
                        <?}else{?>
                            <tr>
                                <td colspan="4">검색 결과가 없습니다.</td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
        
		<!-- //검색결과 -->
		<div class="section">
			<h3 class="section-title"><a href="/hass/humanPostlist">인사발령 <span class="btn-more">더보기</span></a></h3>
			<div class="table-wrap">
				<table class="data-table">
					<caption>검색결과 표</caption>
					<colgroup>
						<col style="width: *">
						<col style="width: 12%">
						<col style="width: 20%">
						<col style="width: 10.5%">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">발령제목</th>
							<th scope="col">담당자</th>
							<th scope="col">발령법인</th>
							<th scope="col">게시일자</th>
						</tr>
					</thead>
					<tbody>
                        <?foreach ($list_human as $val){?>
						<tr onclick="location.href='humanPostdetail?seq=<?= $val['ta_mmseq']?>&type=<?= $val['ta_type']?>'" style="cursor:pointer;">
							<td class="left"><?=$val['ta_title']?></td>
							<td><?=$enc->decrypt(get_member_info($db,$val['ta_admin_seq'])['mm_name'])?></td>
							<td class="center" data-seq=<?=$val['ta_next_coseq']?>>
                                <?=title_coperation($db,$val['ta_next_coseq'])?>
                                <?if(!empty(title_coperation_subtitle($db,$val['ta_next_coseq']))){
                                    echo "(".title_coperation_subtitle($db,$val['ta_next_coseq']).")";
                                }?>
                            </td>
							<td class="date"><?=substr($val['ta_apply_date'],0,10)?></td>
						</tr>
                        <?}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- // 내용 -->
</div>
    <script>
        //구성원 뷰페이지
        function go_detail(seq){
            location.href="/hass/orginfomanageDetail?seq="+seq;
        }

        $('select[name="yearCurrent"]').change(function(){
            $('#form_proccess').submit();
        });

        //월별 인원현황
        Morris.Bar({
            element: 'myfirstchart',
            data: [
                <? 
                $count = 1;
                $current_date = date('Y-m');
                $where = " emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and emb.mm_status='Y' and emc.mc_coseq={$mc_coseq}";
                for($i=0;$i<12;$i++){
                    $month = "0".$count;
                    if($i>8){
                        $month = $count;
                    }
                ?>
                { month: '<?=$i+1?>월', total: 
                <?
                    if(date('Y-m')<$yearCurrent_request."-".$month){
                        echo 0;
                    }else{
                        $query="SELECT emc.mc_regdate FROM ess_member_base emb 
                        join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE ".$where." 
                        and LEFT(emc.mc_regdate,7) <= '{$yearCurrent_request}-{$month}' order by mc_regdate";
                        $ps = pdo_query($db, $query, array());
                        $data_total_yearMorris = array();
                        while($data = $ps->fetch(PDO::FETCH_ASSOC)){
                            array_push($data_total_yearMorris, $data);
                        }
                    
                    echo count($data_total_yearMorris);
                    }
                    
                $count++;
                ?>, man: <?=$date_count_in[$i]?>, woman: <?=$date_count_out[$i]?>},
                <?}?>
            ],
            xkey: 'month',
            parseTime: false,
            ykeys: ['total','man','woman'],
            labels: ['총 인원','입사','퇴사'],
            barColors: ['#4da74d','#337ab7','#d9534f'],
            hideHover: 'auto',
            resize: true,
        });

        //소속별 남녀인원현황
    Morris.Bar({
        element: 'bar-chart',
        data: [
            <? foreach($list_sosog as $index => $val) {
                if($val['tg_parent_seq']==0){continue;}
                $man_count=0;
                $woman_count=0;
                $where = " and emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and emb.mm_status='Y' ";

                $query = "select * from ess_member_code emc
                join ess_member_base emb on emc.mc_mmseq = emb.mmseq 
                join tbl_relation_group trg on emb.mmseq = trg.trg_mmseq 
                where trg.trg_group = {$val['tg_seq']} and trg_coseq = {$mc_coseq} and mc_coseq = {$mc_coseq} ".$where;
                $ps = pdo_query($db, $query, array());
                $member_list = array();
                while($data1 = $ps->fetch(PDO::FETCH_ASSOC)){
                    array_push($member_list, $data1);
                }
                foreach ($member_list as $val2){
                    if($val2['mm_gender']=='M'){
                        $man_count += 1;
                    }else{
                        $woman_count += 1;
                    }
                }
            ?>
                { name: '<?=$val['tg_title']?>', man: <?=$man_count?>, woman: <?=$woman_count?>},
            <?}?>
        ],
        xkey: 'name',
        parseTime: false,
        ykeys: ['man','woman'],
        labels: ['남성','여성'],
        barColors: ['#337ab7','#d9534f'],
        resize: true,
        xLabelFormat: function(x) {return x.src.name.toString();}
        
    });
    </script>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>

