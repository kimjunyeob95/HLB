<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
@$mc_group = $_SESSION['mInfo']['mc_group'];
@$mmseq = $_SESSION['mmseq'];
$list_count = array();
$date_count = array();
if(empty($mc_group)){
    $mc_group = 0;
}

//내 소속 갯수 조회
$query = "select * from tbl_relation_group where trg_mmseq = {$mmseq}";
$ps = pdo_query($db,$query,array());
$group_count = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($group_count,$data);
};

$list = array();
$year = date('Y');

//내 소속인 인원들 배열
foreach($group_count as $index => $val){
    $query = "select *, case when mmseq ={$mmseq} then 1 else 2 end as type from  tbl_commute tc 
        join ess_member_base emb on tc.tc_mmseq = emb.mmseq 
        join tbl_relation_group trg on emb.mmseq = trg.trg_mmseq
        join ess_member_code emc on emb.mmseq = emc.mc_mmseq
        where tc_coseq = {$coseq} and trg_group={$val['trg_group']} and tc_confirm1_state = 'Y' and mc_coseq = {$coseq}  ";
    $ps = pdo_query($db,$query,array());
    while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
        array_push($list,$data);
    }
}

//기존 모든 법인 쿼리
// $query = "select *, case when mmseq ={$mmseq} then 1 else 2 end as type from  tbl_commute tc 
//         join ess_member_base emb on tc.tc_mmseq = emb.mmseq 
//         join ess_member_code emc on emb.mmseq = emc.mc_mmseq
//         where tc_coseq = {$coseq} and tc_confirm1_state = 'Y' and mc_coseq = {$coseq}  ";
// $list = array();
// $ps = pdo_query($db,$query,array());
// while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
//     array_push($list,$data);
// }

$query = "select LEFT(tc_sdate,10) as sdate,LEFT(tc_edate,10) as edate, tc_div from tbl_commute tc 
        join ess_member_base emb on tc.tc_mmseq = emb.mmseq 
        join ess_member_code emc on emb.mmseq = emc.mc_mmseq
        where tc_coseq = {$coseq} and tc_confirm1_state = 'Y' and mmseq = {$mmseq} and mc_coseq = {$coseq} and (MID(tc_sdate,1,4) = '{$year}' or MID(tc_edate,1,4) = '{$year}')
";
$ps = pdo_query($db,$query,array());
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    $date_array = dateGap($data['sdate'],$data['edate'],$data['tc_div']);
    $list_count = array_merge($list_count,$date_array);
}

function dateGap($sdate,$edate,$type){

    $sdate = str_replace("-","",$sdate);

    $edate = str_replace("-","",$edate);

    $date = array();
    for($i=$sdate;$i<=$edate;$i++){

        $year = substr($i,0,4);

        $month = substr($i,4,2);

        $day = substr($i,6,2);
        if(checkdate($month,$day,$year)){
            array_push($date,array($year."-".$month."-".$day,$type));
        }

    }

    return $date;

}
foreach ($list_count as $index => $val){
    if($val[1]==2 || $val[1]==3){
        $count = 0.5;
    }else{
        $count  = 1;
    }

    if(substr($val[0],0,4)!=date('Y') || date('w',strtotime($val[0])) == 6 || date('w',strtotime($val[0])) == 0){
        continue;
    }
    if(substr($val[0],5,2) == '01'){
        $date_count[0] += $count;
    }else if(substr($val[0],5,2) == '02'){
        $date_count[1] += $count;
    }else if(substr($val[0],5,2) == '03'){
        $date_count[2] += $count;
    }else if(substr($val[0],5,2) == '04'){
        $date_count[3] += $count;
    }else if(substr($val[0],5,2) == '05'){
        $date_count[4] += $count;
    }else if(substr($val[0],5,2) == '06'){
        $date_count[5] += $count;
    }else if(substr($val[0],5,2) == '07'){
        $date_count[6] += $count;
    }else if(substr($val[0],5,2) == '08'){
        $date_count[7] += $count;
    }else if(substr($val[0],5,2) == '09'){
        $date_count[8] += $count;
    }else if(substr($val[0],5,2) == '10'){
        $date_count[9] += $count;
    }else if(substr($val[0],5,2) == '11'){
        $date_count[10] += $count;
    }else if(substr($val[0],5,2) == '12'){
        $date_count[11] += $count;
    }
    // $date_count[substr($val,5,2)] += 1;
}
//echo('<pre>');print_r($date_count);echo('</pre>');
//반차 제외하고 날짜+1처리
for($i=0;$i<sizeof($list);$i++){
    if($list[$i]['tc_div'] != 2 || $list[$i]['tc_div'] != 3 || ($list[$i]['tc_sdate'] != $list[$i]['tc_edate']) ){
        $list[$i]['tc_edate'] = strtotime($list[$i]['tc_edate']);
        $list[$i]['tc_edate'] = date("Y-m-d",strtotime("+1 day",$list[$i]['tc_edate']));
    }
}

//자기 자신 tc_num 시퀀스 삭제 후 배열 재정렬
$copy = $list;
$use_tc_num = array();
for( $i=0; $i<count($list); $i++ ) {
    if ( in_array( $list[$i]['tc_num'], $use_tc_num ) ) {
        unset($copy[$i]);
    }
    else {
        $use_tc_num[] = $list[$i]['tc_num'];
    }
}
$list = $copy;
$list = array_values($list);
?>

<link href='/ess/fullcalendar/lib/main.css?t=20201019' rel='stylesheet' />
<script src='/ess/fullcalendar/lib/main.js?t=20201019'></script>
<style>
    .fc-license-message{display: none !important;}
</style>
<div id="wrap" class="depth-main diligence-wrap">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<div id="container" class="diligence">
	<div class="content-header" style="margin-bottom: 45px;">
		<p>근태관리는<br>임직원으로서 가장 기본이되는 책임이자 의무입니다.</p>
		<!-- <a class="btn large type03" href="./holyguide.php">근태 가이드 <span class="ico arrow"></span></a> -->
	</div>
	<div id="content" class="content-primary">
		<!-- calender-wrap -->
		<div class="calender-wrap">
			<!-- calender -->
			<div class="calender" id="calendar">
				<!-- calender-header -->
				
		
			</div>
			<!-- //calender -->
			
		</div>
		<!-- // calender-wrap -->
		<!-- refresh-wrap -->

		<!--// refresh-wrap -->
		<div class="refresh-state" style="padding-bottom:50px;">
			<!-- <h4><span class="ico calendar"></span><?=date('Y')?>년 연월차현황</h4>
			<div class="table-wrap">
				<table class="data-table">
					<caption><?=date('Y')?>년 연월차현황 테이블</caption>
					<colgroup>
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
						<col style="width: 8.3%" />
					</colgroup>
					<thead>
						<tr>
							<th scope="col">1월</th>
							<th scope="col">2월</th>
							<th scope="col">3월</th>
							<th scope="col">4월</th>
							<th scope="col">5월</th>
							<th scope="col">6월</th>
							<th scope="col">7월</th>
							<th scope="col">8월</th>
							<th scope="col">9월</th>
							<th scope="col">10월</th>
							<th scope="col">11월</th>
							<th scope="col">12월</th>
						</tr>
					</thead>
					<tbody>
						<tr>
                            <?for ($i=0;$i<12;$i++){?>
                                <td>
                                    <?if(!empty($date_count[$i])){
                                        echo $date_count[$i].'일';
                                    }else{
                                        echo '-';
                                    }?>
                                </td>
                            <?}?>
						</tr>
					</tbody>
				</table>
			</div> -->
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(1)').addClass('active');
//$('.depth02:eq(0)').find('li:eq(2)').addClass('active');

var date_list = new Array();
var date_list_php = new Array();

<?for($i=0;$i<sizeof($list);$i++){?>
    date_list_php.push(<?=json_encode($list[$i])?>);
    date_list.push({"id":'<?=$i+1?>',"start":"<?=substr($list[$i]['tc_sdate'],0,10)?>","end":"<?=substr($list[$i]['tc_edate'],0,10)?>","resourceId":<?=$list[$i]['type']?> ,"title":"<?=$enc->decrypt($list[$i]['mm_name'])?> (<?=$vacation[$list[$i]['tc_div']]?>)"}),
    date_list_php.push(<?=json_encode($list[$i])?>);
        console.log(date_list_php);
<?}?>

 document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
	  locale : "ko",
      editable: false,
      selectable: false,
	  headerToolbar: {
        left: 'today',
        center: 'title',
        right: 'prev,next'
      },
      businessHours: true,
      dayMaxEvents: true,
	  dateClick: function(info) {
		var dateStr = info.dateStr;
		//console.log(dataStr)
	  },
	eventClick: function(info) {
		var evtDate = new Date( info.event.start);
		var eDate = evtDate.getFullYear()+"-"+(evtDate.getMonth()+1)+"-"+evtDate.getDate();
		//대상자 알아내기
		 //var uName = info.event.title;
	 },
	resourceGroupField: 'building',
        resources: [
            { id: '0', building: '460 Bryant', title: 'Auditorium B', eventColor: '#fab14c' },
            { id: '1', building: '460 Bryant', title: 'Auditorium C', eventColor: '#ef3a70' },
            { id: '2', building: '460 Bryant', title: 'Auditorium E' , eventColor: '#4a86c0'  },
            { id: '3', building: '460 Bryant', title: 'Auditorium F', eventColor: '#20bdbe' },
            { id: '4', building: '564 Pacific', title: 'Auditorium G', eventColor: '#8856a4'  }
        ],
        events : date_list
	});
   calendar.render();
});

setTimeout(function(){
	$('.fc-license-message').remove();
},500)

</script>