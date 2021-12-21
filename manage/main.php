<?
    include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';

@$mc_coseq = $_REQUEST['mc_coseq'];
@$yearCurrent_request = $_REQUEST['yearCurrent'];
$where=" WHERE co_is_del='FALSE' ";

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq  ";
$query.=$where;
$query.=" ORDER BY co_seq asc ";
$ps2 = pdo_query($db, $query, array());
$coperationList = array();
while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
	array_push($coperationList, $data2);
}
// echo('<pre>');print_r($_REQUEST);echo('</pre>');

if(empty($mc_coseq)){
    $mc_coseq=1;
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
.center-block.center img { height: 70px; margin: 0 auto; margin-bottom: 10px;}
.center-block.center .title { width: 100%; margin: 0 auto; font-size: 17px; height: 40px; line-height: 40px; text-align: center; padding: 0 22px;}
.center-block.center .title span{float: right;}
.center-block.center .sub-title{width: 100%; margin: 0 auto; margin-top:4px; font-size: 15px; height: 35px; line-height: 35px; text-align: center; padding: 0 24px; background-color:#f3f3f3; color: #545454}
.center-block.center .title > span.textLeft,
.center-block.center .sub-title > span.textLeft{float:left}
.center-block.center .title > span.textRight,
.center-block.center .sub-title > span.textRight{float:right;font-weight: 600;}
.center-block.center .title > span,
.center-block.center .sub-title > span{letter-spacing: -0.5px;}
.glyphicon-chevron-up:before{font-size:1.35rem}
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
                <h2>대시보드</h2>
                <div class="panel-actions">
                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                </div>
            </header>
            <!-- start: page -->
            <div class="row">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title">선택</h2>
                    </header>
                    <div class="panel-body  col-md-12 " style="display: block;">
                        <form class="form-horizontal" id="form_proccess" action="<?= $_SERVER['PHP_SELF']?>" method="post">
                            <div class="form-group col-md-12">
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">법인</label>
                                <div class="col-md-2">
                                    <div class="input-daterange input-group col-md-3"  >
                                        <select class="form-control mb-md" name="mc_coseq">
                                            
                                            <?for($i=0;$i<sizeof($coperationList);$i++){?>
                                            <option value="<?=$coperationList[$i]['co_seq']?>" <?if($mc_coseq==$coperationList[$i]['co_seq']){?>selected<?}?>><?=$coperationList[$i]['co_name']?> <?if($coperationList[$i]['co_subname']!=""){?> ( <?=$coperationList[$i]['co_subname']?> )<?}?></option>
                                            <?}?>
                                        </select>
                                    </div>
                                </div>
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">년도</label>
                                <div class="col-md-2">
                                    <div class="input-daterange input-group col-md-3"  >
                                        <select class="form-control mb-md" name="yearCurrent">
                                            <?for($i=0;$i<sizeof($array_yearYes);$i++){
                                            ?>
                                                <option value="<?=$array_yearYes[$i]['mc_regdate']?>" 
                                                    <?if($yearCurrent_request==$array_yearYes[$i]['mc_regdate']){?>selected<?}?>>
                                                    <?=$array_yearYes[$i]['mc_regdate']?>
                                                </option>
                                            <?}?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <div class="row" style="max-height: 500px; overflow-y: scroll;">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title">인사통계</h2>
                    </header>
                    <div class="panel-body  col-md-12 " style="display: block;background:#fff">
                        <div class="row">
                            <div class="col-md-2">
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
                                                    echo $result.'% <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>';
                                                }
                                            }else{
                                                $result=round((($yearPrev-$yearCurrent)/$yearPrev*100),1);
                                                if($result ==0 || is_nan($result)){
                                                    echo "0%";
                                                }else{
                                                    echo $result.'% <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>';
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
                            </div>
                            <div class="col-md-2">
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
                                                    echo $result.'% <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>';
                                                }
                                            }else{
                                                $result=round((($yearPrev-$yearCurrent)/$yearPrev*100),1);
                                                if($result ==0 || is_nan($result)){
                                                    echo "0%";
                                                }else{
                                                    echo $result.'% <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>';
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
                            </div>
                            <div class="col-md-2">
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
                                                    echo $result.'% <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>';
                                                }
                                            }else{
                                                $result=round((($yearPrev-$yearCurrent)/$yearPrev*100),1);
                                                if($result ==0 || is_nan($result)){
                                                    echo "0%";
                                                }else{
                                                    echo $result.'% <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>';
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
                            </div>
                            <div class="col-md-2">
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
                            </div>
                            <div class="col-md-2">
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
                            </div>
                            <div class="col-md-2">
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
                    </div>
                </section>
            </div>

            <div class="row">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title"><?=$yearCurrent_request?>년 월별 인원추이</h2>
                    </header>
                    <div class="panel-body  col-md-12 " style="display: block;">
                        <div class="row">
                            <div id="myfirstchart" style="height: 250px;"></div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="row">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title">남여 인원현황</h2>
                    </header>
                    <div class="panel-body  col-md-12 " style="display: block;">
                        <div class="row">
                            <div id="bar-chart"></div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </div>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>
<script>
	$('select[name="mc_coseq"]').change(function(){
		$('#form_proccess').submit();
    });
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
