<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';

@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

@$page = $_REQUEST['page'];
$rows = 5;
if(empty($page)){
    $page=1;
}


//mms 권한 리스트
$query ="select * from ess_member_base emb 
    join tbl_ess_group tes on emb.mmseq = tes.tg_mms_mmseq
    where mmseq = {$mmseq} and tg_mms_mmseq = {$mmseq}";
$ps = pdo_query($db,$query,array());
$mms_list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($mms_list, $data['tg_seq']);
}

$tg_list = array();
foreach ($mms_list as $key => $val){
    $query =" SELECT tg_seq FROM
                (SELECT tg_seq,tg_title,tg_parent_seq,tg_coseq,
                        CASE WHEN tg_seq = {$val} THEN @idlist := CONCAT(tg_seq)
                             WHEN FIND_IN_SET(tg_parent_seq,@idlist) THEN @idlist := CONCAT(@idlist,',',tg_seq)
                        END as checkId
                 FROM tbl_ess_group
                 ORDER BY tg_seq ASC) as T
            WHERE checkId IS NOT NULL and tg_coseq = {$coseq}";
    $ps = pdo_query($db,$query,array());
    while($data = $ps->fetch(PDO::FETCH_ASSOC)){
        array_push($tg_list, $data['tg_seq']);
    }
}
$tg_list = array_unique($tg_list);
$tg_list = implode(',',$tg_list);
$query = "select trg_mmseq from tbl_relation_group where trg_group in({$tg_list})";
$ps = pdo_query($db,$query,array());
$mmseq_list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($mmseq_list, $data['trg_mmseq']);
}
$mmseq_list = array_unique($mmseq_list);
$mmseq_list = implode(',',$mmseq_list);
$query = "select count(*) as cnt from tbl_commute tc 
        join ess_member_base emb on tc.tc_mmseq = emb.mmseq 
        join ess_member_code emc on emb.mmseq =  emc.mc_mmseq
        where tc_coseq = {$coseq} and mc_coseq = {$coseq} 
        and mmseq in ({$mmseq_list})";
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

$query="SELECT * FROM tbl_commute tc
        join ess_member_base emb on tc.tc_mmseq = emb.mmseq 
        join ess_member_code emc on emb.mmseq =  emc.mc_mmseq
        where tc_coseq = {$coseq}  and mc_coseq = {$coseq} 
        and mmseq in ({$mmseq_list})
        order by tc_regdate desc limit {$from} , {$rows} ";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
?>

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
                <li ><a href="/ess/holiday.php">휴가 신청</a></li>
                <!-- <li><a href="/ess/holyguide.php" >근태 가이드</a></li> -->
                <li><a href="/ess/mssholyday.php" class="active">휴가관리</a></li>
                <!-- <li><a href="/ess/organization.php"  >조직도</a></li> -->
            </ul>
        </div>
        <!-- //사이드 메뉴 -->
        <div id="content" class="content-primary">
            <h2 class="content-title">휴가관리</h2>

            <div class="section">
                <h3 class="section-title">휴가 신청 이력</h3>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>휴가 신청 이력 표</caption>
                        <colgroup>
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 10%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 10%" />
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">신청일자</th>
                            <th scope="col">휴가구분</th>
                            <th scope="col">사번</th>
                            <th scope="col">성명</th>
                            <th scope="col">직위</th>
                            <th scope="col">휴가 시작날짜</th>
                            <th scope="col">휴가 종료날짜</th>
                            <th scope="col">사용일수</th>
                            <th scope="col">잔여일수</th>
                            <th scope="col">처리</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($list as $val){?>
                            <tr>
                                <td><?=$numbering--?></td>
                                <td><?=substr($val['tc_regdate'],0,10)?></td>
                                <td><?=$vacation[$val['tc_div']]?></td>
                                <td><?=$val['mc_code']?></td>
                                <td><?=$enc->decrypt($val['mm_name'])?></td>
                                <td><?=get_position_title_type($db,$val['mc_position2'],2)?></td>
                                <td><?=substr($val['tc_sdate'],0,10)?></td>
                                <td><?=substr($val['tc_edate'],0,10)?></td>
                                <td><?=$val['tc_vacation_count']?>일</td>
                                <td><?=$val['mc_commute_remain']?></td>
                                <td>
                                    <div class="insert">
                                        <?if($val['tc_confirm1_state']=='A'){?>
                                            <select class="select"  name="select-salary">
                                                <option value="Y">승인</option>
                                                <option value="N">반려</option>
                                            </select>
                                            <button type="button" data-reason="<?=$val['tc_content']?>" data-holiday="<?=substr($val['tc_sdate'],0,10)?> ~ <?=substr($val['tc_edate'],0,10)?>" data-mmseq="<?=$val['mmseq']?>" data-tcnum="<?=$val['tc_num']?>" name="btn_save" class="btn type10 small">저장</button>
                                        <?}else{?>
                                            <?
                                            if($status2[$val['tc_confirm1_state']]=='처리중'){ echo'<span class="ing">처리중</span>';}
                                            else if($status2[$val['tc_confirm1_state']]=='승인'){ echo'<span class="complete">승인</span>';}
                                            else if($status2[$val['tc_confirm1_state']]=='반려'){ echo'<span class="companion">반려</span>';}
                                            ?>
                                        <?}?>

                                    </div>
                                </td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
        </div>

        <!-- 반려사유 팝업 -->
        <div class="new-layer-popup group-popup">
            <div class="layer-wrap">
                <div class="popup-wrap">
                    <button type="button" class="pop-close">닫기</button>
                    <h1>반려사유</h1>
                    <div class="section-wrap" style="padding-top: 28px;">
                        <div class="insert">
                            <label class="label">반려사유 :</label>
                            <input type="text" class="input-text" id="tc_return" name="tc_return" title="반려사유" value="" style="width: 80%;">
                        </div>
                    </div>
                    <div class="insert" style="text-align: center; padding-top: 28px;">
                        <button type="button" id="btn_chk_save" class="btn type01 large" >저장<span class="ico check01"></span></button>
                    </div>

                </div>
            </div>
        </div>
        <!-- // 반려사유 팝업 -->
    </div>
    <?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
    <script>
        var type = '';
        var user_mmseq = '';
        var tc_num = '';
        var holiday = '';
        $('#select_mail').change(function () {
            if($(this).val()!='직접입력'){
                $('[name="mail_footer"]').val($(this).val());
            }else{
                $('[name="mail_footer"]').val('');
                $('[name="mail_footer"]').focus();
            }
        })
        $('[name="btn_save"]').click(function () {
            type = $(this).siblings('.selector').find('[name="select-salary"] option:selected').val();
            user_mmseq = $(this).data('mmseq');
            tc_num = $(this).data('tcnum');
            holiday = $(this).data('holiday');
            reason = $(this).data('reason');
            if(type=='N'){
                $('.group-popup').addClass('active');
                $('body, html').css('height','100%');
                $('body, html').css('overflow','hidden');
                return false;
            }

            data = {'type':type , 'tc_num':tc_num ,'user_mmseq':user_mmseq,'reason':reason,'holidaydata':holiday};
            hlb_fn_ajaxTransmit("/@proc/ess/commute_save.php", data);
        });
        $('#btn_chk_save').click(function(){
            return_text = $('#tc_return').val();
            data = {'type':type , 'tc_num':tc_num ,'user_mmseq':user_mmseq,'tc_return':return_text,'holidaydata':holiday};
            hlb_fn_ajaxTransmit("/@proc/ess/commute_save.php", data);
        })
        // 팝업 닫기 201209 추가
        $('.new-layer-popup .pop-close').on('click', function() {
            $(this).parents('.new-layer-popup').removeClass('active');
            $('body, html').css('height','auto');
            $('body, html').css('overflow','visible');
        });

        $('#mm_birth, .input-datepicker').datepicker();
        $('.header-wrap ').addClass('active');
        $('.depth01:eq(1)').addClass('active');
        $('.depth02:eq(1)').find('li:eq(1)').addClass('active');


        function fn_callBack(calback_id, result, textStatus){
            if(calback_id=='commute_save'){
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