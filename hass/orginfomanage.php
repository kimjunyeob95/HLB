<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$page = $_REQUEST['page'];
//$where_query['where']['mm_status'] = 'Y';
if(empty($_REQUEST['mm_status'])){
    $_REQUEST['mm_status']='Y';
}
// echo('<pre>');print_r($_SESSION);echo('</pre>');
$where_query['where']['mc_coseq'] =  $_SESSION['mInfo']['mc_coseq'];
$where_query['where'][$_REQUEST['category']] = $_REQUEST['search'];
$where_query['where']['mm_status'] = $_REQUEST['mm_status'];
$where_query['where']['mc_group'] =  $_REQUEST['mc_group'];
$where_query['where']['mc_position'] =  $_REQUEST['mc_position'];
$total_cnt  = get_member_list($db,$where_query,'cnt');
// 페이징
$rows = 10;
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
$list  = get_member_list($db,$where_query,'',$from,$rows);
$list_all  = get_member_list($db,$where_query,'');
$group_list = get_group_list($db);
$position_list = get_position_list($db,1);
// $text = "tislwlstnf1@naver.com";

// echo('<pre>');print_r($enc->encrypt($text));echo('</pre>');
?>
<style>
    table tbody tr{cursor: pointer;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">

<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <form method="post" action="<?= $_SERVER['PHP_SELF']?>" >
        <h2 class="content-title">임직원 관리</h2>
        <select name="mc_group" id="group">
            <option value="all">조직 전체</option>
            <?foreach ($group_list as $val){?>
            <option value="<?=$val['tg_seq']?>" <?if($_REQUEST['mc_group']==$val['tg_seq']){?>selected<?}?>><?=$val['tg_title']?></option>
            <?}?>
        </select>
        <select name="mc_position" id="position">
            <option value="all">직책 전체</option>
            <?foreach ($position_list as $val){?>
                <option value="<?=$val['tp_seq']?>" <?if($_REQUEST['mc_position']==$val['tp_seq']){?>selected<?}?>><?=$val['tp_title']?></option>
            <?}?>
        </select>
        <select name="category" id="cboArea">
            <option value="mm_name"  <?if($_REQUEST['category']=='mm_name'|| empty($_REQUEST['category'])){?>selected<?}?>>성명</option>
            <option value="mc_code" <?if($_REQUEST['category']=='mc_code'){?>selected<?}?>>사번</option>            
            <option value="mm_email" <?if($_REQUEST['category']=='mm_email'){?>selected<?}?>>E-Mail</option>
            <option value="mm_phone" <?if($_REQUEST['category']=='mm_phone'){?>selected<?}?>>연락처</option>
        </select>
        <select name="mm_status" id="">
            <option value="Y" <?if($_REQUEST['mm_status']=='Y'|| empty($_REQUEST['category'])){?>selected<?}?>>재직</option>
            <option value="D" <?if($_REQUEST['mm_status']=='D'){?>selected<?}?>>퇴직</option>
            <option value="all" <?if($_REQUEST['mm_status']=='all'){?>selected<?}?>>전체</option>
        </select>
        <div class="input-search" id="" style="display: inline-block; width: 300px;">
            <input type="text" type="number" value="<?=$_REQUEST['search']?>" name="search" style="width: 298px; border:0;"/>
            <button type="submit" class="btn"  style="width: 20px;">
                <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
            </button>
        </div>
        <button type="submit" id="" class="btn type01 small">조회</button>
        <button type="button" class="btn type10 small" onclick="tableToExcel('tb_insaHeader')">엑셀다운로드</button>
        </form>
		<div class="section-wrap" style="margin-top: 15px;">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table">
                    <caption>임직원 내역</caption>
                    <colgroup>
                        <col style="width: 3%" />
                        <col style="width: 1%" />
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
                        <th scope="col">no</th>
                        <th scope="col">사번</th>
                        <th scope="col">성명</th>
                        <th scope="col">성별</th>
                        <th scope="col">소속</th>
                        <th scope="col">직위</th>
                        <th scope="col">직책</th>
                        <th scope="col">생년월일</th>
                        <th scope="col">입사일자</th>
                        <th scope="col">재직여부</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?foreach ($list as $val){?>
                            <tr onclick="move_detail_page('<?=$val['mmseq']?>',<?=$page?>);">
                                <td><?=$numbering?></td>
                                <td><?=$val['mc_code']?></td>
                                <td class="center"><?=$enc->decrypt($val['mm_name'])?></td>
                                <td class="center"><?=$gender[$val['mm_gender']]?></td>
                                <td><?=implode('<br> ',get_group_list_v2($db,$val['mmseq']))?></td>
                                <td><?=get_position_title_type($db,$val['mc_position2'],2)?></td>
                                <td><?=get_position_title($db,$val['mc_position'])?></td>
                                <td><?=substr($val['mm_birth'],0,10)?></td>
                                <td><?=substr($val['mc_regdate'],0,10)?></td>
                                <td><?=$member_state[$val['mm_status']]?></td>
                                
                            </tr>
                        <?$numbering--;}?>
                    </tbody>
                </table>
                <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
            </div>
            <!-- //공지사항 -->
		</div>

        <!-- 엑셀 영역 -->
        <table class="data-table" id="tb_insaHeader" style="display: none;">
            <caption>임직원 내역</caption>
            <colgroup>
                <col style="width: 3%" />
                <col style="width: 1%" />
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
                <th scope="col">no</th>
                <th scope="col">사번</th>
                <th scope="col">성명</th>
                <th scope="col">성별</th>
                <th scope="col">소속</th>
                <th scope="col">직위</th>
                <th scope="col">직책</th>
                <th scope="col">생년월일</th>
                <th scope="col">입사일자</th>
                <th scope="col">재직여부</th>
            </tr>
            </thead>
            <tbody>
                <?
                $count_list = count($list_all);
                foreach ($list_all as $val){
                ?>
                    <tr onclick="move_detail_page('<?=$val['mmseq']?>',<?=$page?>);">
                        <td><?=$count_list?></td>
                        <td><?=$val['mc_code']?></td>
                        <td class="center"><?=$enc->decrypt($val['mm_name'])?></td>
                        <td class="center"><?=$gender[$val['mm_gender']]?></td>
                        <td><?=implode('<br> ',get_group_list_v2($db,$val['mmseq']))?></td>
                        <td><?=get_position_title_type($db,$val['mc_position2'],2)?></td>
                        <td><?=get_position_title($db,$val['mc_position'])?></td>
                        <td><?=substr($val['mm_birth'],0,10)?></td>
                        <td><?=substr($val['mc_regdate'],0,10)?></td>
                        <td><?=$member_state[$val['mm_status']]?></td>
                        
                    </tr>
                <?$count_list--;}?>
            </tbody>
        </table>
        <!-- //엑셀 영역 -->
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');

    function move_detail_page(seq,page){
        location.href='./orginfomanageDetail?seq='+seq+'&page='+page;
    }
    function tableToExcel(id) {
        var data_type = 'data:application/vnd.ms-excel;charset=utf-8';
        var table_html = encodeURIComponent(document.getElementById(id).outerHTML);
    
        var a = document.createElement('a');
        a.href = data_type + ',%EF%BB%BF' + table_html;
        a.target = '_blank';
        a.download = 'employee_excel'+'.xls';
        a.click();
    }
</script>

