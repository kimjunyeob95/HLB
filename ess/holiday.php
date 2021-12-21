<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$page = $_REQUEST['page'];
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

@$tc_sdate = $_REQUEST['tc_sdate'];
@$tc_edate = $_REQUEST['tc_edate'];

$where = '';

if(!empty($tc_sdate) && !empty($tc_edate)){
    $where .= " and tc_sdate >= '{$tc_sdate}' and tc_edate < '{$tc_edate}' + INTERVAL 1 DAY ";
}else if(!empty($tc_sdate) && empty($tc_edate)){
    $where .= " and tc_sdate >= '{$tc_sdate}' ";
}else if(empty($tc_sdate) && !empty($tc_edate)){
    $where .= " and tc_edate < '{$tc_edate}' + INTERVAL 1 DAY ";
}else if(empty($tc_sdate) && empty($tc_edate)){
    $where .= ' and substring(tc_sdate,1,4) >= '.date('Y');
}

$rows = 5;
if(empty($page)){
    $page=1;
}
$query = "select count(*) as cnt from tbl_commute where tc_mmseq = {$mmseq} and tc_coseq = {$coseq}".$where;
$ps = pdo_query($db,$query,array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$total_rows = $data['cnt'];
if ($total_rows > 0) {
    $total_page = ceil($total_rows / $rows);
} else {
    $total_page = 1;
}
$from = ($page - 1) * $rows;
$numbering = $total_rows - $from;

$query="SELECT * FROM tbl_commute where tc_mmseq = {$mmseq} and tc_coseq = {$coseq} ".$where." order by tc_regdate desc limit {$from} , {$rows} ";
// echo('<pre>');print_r($query);echo('</pre>');
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
$data_member =  get_member_info($db,$mmseq); //기본정보 

$query ="select * from tbl_ess_group where tg_mms_mmseq = {$mmseq} and tg_coseq = {$coseq}";
$ps = pdo_query($db,$query,array());
$reader = $ps ->fetch(PDO::FETCH_ASSOC);

$data =  get_member_info($db,$mmseq); //기본정보 

$paging_subquery="&tc_sdate=".$tc_sdate."&tc_edate=".$tc_edate;
?>
<style>
    #circle_chart_1 > .text {position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); text-align: center;}
    #circle_chart_1 > .text > strong {display: block; text-align: center; color: #c41230; font-size: 46px;}
    #circle_chart_1 > .text > span {position: relative; top: -6px; color: #a3a3a3;}

    .btn-datepicker{cursor: text;}
</style>
<div id="wrap" class="depth03">
    <!-- HEADER -->
    <?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
    <!-- // HEADER -->
    <!-- CONTENT -->
    <div id="container" class="pregnancy">
        <!-- 사이드 메뉴 -->
        <div id="aside-menu" class="side-menu">
            <h2>근태</h2>
            <ul class="lnb">
                <li ><a href="/ess/holiday" class="active">휴가 신청</a></li>
                <!-- <li><a href="/ess/holyguide.php" >근태 가이드</a></li> -->
                <?if(!empty($reader)){?>
                    <li><a href="/ess/mssholyday">휴가관리</a></li>
                <?}?>
                <!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
            </ul>
        </div>
        <!-- //사이드 메뉴 -->
        <div id="content" class="content-primary holiday">
            <h2 class="content-title">휴가 신청</h2>
            <div class="refresh-wrap">
			    <div class="section"  style="height: 100%;">
                    <div class="refresh">
                        <div class="chart">
                            <div id="circle_chart_1" style="width: 173px; height: 173px; position:relative;">
                            </div>
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
                </div>
            </div>
            <form id="commute_form">
                <!-- 출산휴가 신청 -->
                <div class="section-apply">
                    <h3 class="section-title">휴가 신청</h3>
                    <fieldset class="fieldset">
                        <legend>출산휴가 신청</legend>
                            <div class="field">
                                <div class="insert">
                                    <label for="select01" class="label" style="width: 65px">휴가구분</label>
                                    <select class="select" id="tc_div" name="tc_div" title="휴가구분">
                                        <option value="1">연차</option>
                                        <option value="2">오전반차</option>
                                        <option value="3">오후반차</option>
                                        <option value="4">하계휴가</option>
                                        <option value="5">경조휴가</option>
                                        <option value="6">대체휴가</option>
                                        <option value="7">보건휴가</option>
                                        <option value="8">포상휴가</option>
                                        <option value="9">출산휴가</option>
                                        <option value="10">기타</option>
                                    </select>
                                </div>
                                <div class="insert">
                                    <label for="leave01" class="label">휴가날짜</label>
                                    <div class="input-date">
                                        <input type="text" readonly name="tc_sdate" class="input-datepicker input-text" id="tc_sdate" title="휴가시작날짜">
                                        <button class="btn btn-datepicker" type="button"><img alt="달력" src="../../@resource/images/common/calendar.png"></button>
                                    </div>
                                    -
                                    <div class="input-date">
                                        <input type="text" readonly name="tc_edate" class="input-datepicker input-text"  id="tc_edate" title="휴가종료날짜">
                                        <button class="btn btn-datepicker" type="button"><img alt="달력" src="../../@resource/images/common/calendar.png"></button>
                                    </div>
                                    <span class="hypen">
                                </div>
                                <div class="insert" style="margin-left: 17px !important;">
                                    <label for="mc_commute_use" class="label">사용일수</label>
                                    <div class="input-date">
                                        <input type="number" name="mc_commute_use" class="input-text" id="mc_commute_use" title="휴가사용일수">
                                    </div>
                                </div>
                            </div>
                        <div class="field">
                            <div class="insert">
                                <label for="userEtc" class="label" style="width: 65px">사유</label>
                                <input type="text" class="input-text" title="사유" name="tc_content" id="userEtc" placeholder="사유를 입력하세요" style="width: 629px">
                            </div>
                        </div>                        
                        <div class="field">
                            <div class="insert">
                                <label for="userFile" class="label" style="width: 65px">증빙첨부</label>
                                <input type="file" class="" name ='tc_file' id="tc_file" title="파일첨부">
                                <p class="info-text">※ 증빙첨부는 경조휴가, 출산휴가 등에만 해당됩니다.</p>
                            </div>
                            
                        </div>
                    </fieldset>
                    <p class="info-text">※ 휴가 신청자는 팀장의 승인 후 반영 됩니다. <br/>&nbsp;&nbsp;&nbsp;&nbsp;입력하신 정보가 정확한지 다시 한번 확인 후 요청 버튼을 눌러 주세요.</p>
                    <div class="button-area large">
                        <button type="button" id="btn-set-education" class="btn type01 large">휴가 신청<span class="ico apply"></span></button>
                    </div>

                </div>
                <!-- // 출산휴가 신청 -->
            </form>
            <div class="section">
                <h3 class="section-title" style="display: inline-block;">휴가 신청 이력</h3>
                <form method="post" action="<?= $_SERVER['PHP_SELF']?>" style="display: inline-block; vertical-align: super;">
                    <div class="insert" style="display: inline-block; width: 300px; margin-left:15px;">
                        <input type="text" class="input-text input-datepicker" style="max-width: 45%" name="tc_sdate" readonly value=<?
                        if(empty($tc_sdate)){
                            echo ''.date('Y').'-01-01';
                        }else{
                            echo ''.$tc_sdate;
                        }?>>
                        -
                        <input type="text" class="input-text input-datepicker" style="max-width: 45%" name="tc_edate" readonly value=<?=$tc_edate?>>
                    </div>
                    <button type="submit" class="btn type01 small">조회</button>
                    <button id="btn_reset" type="button" class="btn type01 small">초기화</button>
                </form>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>휴가 신청 이력 표</caption>
                        <colgroup>
                            <col style="width: 3%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">신청일자</th>
                            <th scope="col">휴가구분</th>
                            <th scope="col">사유</th>
                            <th scope="col">반려사유</th>
                            <th scope="col">휴가 시작날짜</th>
                            <th scope="col">휴가 종료날짜</th>
                            <th scope="col">사용일수</th>
                            <th scope="col">잔여일수</th>
                            <th scope="col">결재상태</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?if(sizeof($list)<1){?>
                            <tr><td colspan="10">내역이 없습니다.</td></tr>
                        <?}else{?>
                            <?for($i=0;$i<sizeof($list);$i++){?>
                                <tr>
                                    <td><?=$numbering--?></td>
                                    <td><?=substr($list[$i]['tc_regdate'],0,10)?></td>
                                    <td><?=$vacation[$list[$i]['tc_div']]?></td>
                                    <td><?=$list[$i]['tc_content']?></td>
                                    <td><?=$list[$i]['tc_return']?></td>
                                    <td><?=substr($list[$i]['tc_sdate'],0,10)?></td>
                                    <td><?=substr($list[$i]['tc_edate'],0,10)?></td>
                                    <td><?=$list[$i]['tc_vacation_count']?>일</td>
                                    <td><?=$data_member['mc_commute_remain']?>일</td>
                                    <?
                                        if($status2[$list[$i]['tc_confirm1_state']]=='처리중'){ echo'<td class="ing">처리중</td>';}
                                        else if($status2[$list[$i]['tc_confirm1_state']]=='승인'){ echo'<td class="complete">승인</td>';}
                                        else if($status2[$list[$i]['tc_confirm1_state']]=='반려'){ echo'<td class="companion">반려</td>';}
                                    ?>
                                </tr>
                            <?}?>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
        </div>
    </div>
    <?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
    <script>
    $('#btn_reset').click(function(e){
        e.preventDefault();
        $('.input-datepicker').val('');

    });
        $(document).ready(function(){
            let remain_holyday = <?=$data['mc_commute_remain']?>/<?=$data['mc_commute_all']?>*100;
            let total_holyday = 100-remain_holyday;
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

            
        })
        $('#select_mail').change(function () {
            if($(this).val()!='직접입력'){
                $('[name="mail_footer"]').val($(this).val());
            }else{
                $('[name="mail_footer"]').val('');
                $('[name="mail_footer"]').focus();
            }
        })
        $('#mm_birth, .input-datepicker').datepicker();
        
        $('.header-wrap ').addClass('active');
        $('.depth01:eq(1)').addClass('active');
        $('.depth02:eq(1)').find('li:eq(0)').addClass('active');

        $('#btn-set-education').click(function(e){
            e.preventDefault();
            var validate = true;

            $('.section-apply').find('.input-text').each(function(e){
                var val = $.trim($(this).val());
                var txt = $(this).attr('title');
                if(val==""){
                    alert(  reulReturner(txt) + " 입력해 주세요");
                    validate = false;
                    return false;
                }
            });

            //var data = $('#holiday_form').serialize();
            var form = $('#commute_form')[0];
            var data = new FormData(form);
            if(validate){
                if(confirm("휴가 신청을 하시겠습니까?")){
                    hlb_fn_file_ajaxTransmit("/@proc/ess/commuteProc.php", data);
                }
            }
        })

        function fn_callBack(calback_id, result, textStatus){
            if(calback_id=='commuteProc'){
                if(result.code=='FALSE'){
                    alert(result.msg);
                    return;
                }else{
                    alert(result.msg);
                    location.reload();
                    //location.href="/";
                }
            }
        }
    </script>