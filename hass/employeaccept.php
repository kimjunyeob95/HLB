<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$page = $_REQUEST['page'];
$where_query = array();
$where_query['orderby']['mm_status'] = 'desc';
$where_query['where']['new'] = 'Y'; //신입사원 체크
$where_query['where']['mm_status'] = $_REQUEST['mm_status'];
$where_query['where']['mm_coseq'] =  $_SESSION['mInfo']['mm_coseq'];
$where_query['where'][$_REQUEST['category']] = $_REQUEST['search'];
$paging_subquery= '&mm_status='.$_REQUEST['mm_status'].'&search='.$_REQUEST['search']."&category=".$_REQUEST['category'];

$total_cnt = get_member_list($db,$where_query,'cnt');
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
$member_list = get_member_list($db,$where_query,'',$from,$rows);

// echo('<pre>');print_r($member_list);echo('</pre>');exit;
?>
<style>
    table tbody tr{cursor: pointer;}
    table tbody tr td img{height: 100px;}
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
        <h2 class="content-title">신입 사원 승인 내역</h2>
        <select name="mm_status" id="cboArea">
            <option value="all" <?if($_REQUEST['mm_status']=='all'|| empty($_REQUEST['mm_status'])){?>selected<?}?>>전체</option>
            <option value="N" <?if($_REQUEST['mm_status']=='N'){?>selected<?}?> >반려</option>
            <option value="A" <?if($_REQUEST['mm_status']=='A'){?>selected<?}?> >신청완료</option>
            <option value="S" <?if($_REQUEST['mm_status']=='S'){?>selected<?}?> >미입력</option>
        </select>
        <select name="category" id="cboArea">
            <option value="mm_name"  <?if($_REQUEST['category']=='mm_name' || empty($_REQUEST['category'])){?>selected<?}?>>성명</option>
            <option value="mc_code" <?if($_REQUEST['category']=='mc_code'){?>selected<?}?>>사번</option>
            <option value="mm_email" <?if($_REQUEST['category']=='mm_email'){?>selected<?}?>>E-Mail</option>
            <option value="mm_phone" <?if($_REQUEST['category']=='mm_phone'){?>selected<?}?>>연락처</option>
        </select>
        <div class="input-search" id="" style="display: inline-block; width: 300px;">
            <input type="text" type="number" value="<?=$_REQUEST['search']?>" name="search" style="width: 298px; border:0;"/>
            <button type="submit" class="btn"  style="width: 20px;">
                <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
            </button>
        </div>
        <button type="submit" id="" class="btn type01 small">조회</button>
        </form>
		<div class="section-wrap" style="margin-top: 30px;">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>신입 사원 승인 내역</caption>
                    <colgroup>
                        <col style="width: 20px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                        <col style="width: 80px" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">사번</th>
                        <th scope="col">성명</th>
                        <th scope="col">E-Mail</th>
                        <th scope="col">생년월일</th>
                        <th scope="col">신청일시</th>
                        <th scope="col">승인일시</th>
                        <th scope="col">상태</th>
                        <th scope="col">관리</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?foreach ($member_list as $val){?>
                            <?if($val['mm_status']=='S'){?>
                                <tr>
                            <?}else{?>
                                <tr>
                            <?}?>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');"><?=$numbering?></td>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');" class="center"><?=$val['mc_code']?></td>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');" class="center"><?=$enc->decrypt($val['mm_name'])?></td>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');"><?=$enc->decrypt($val['mm_email'])?></td>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');"><?=substr($val['mm_birth'],0,10)?></td>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');"><?=substr($val['mm_applydate'],0,10)?></td>
                                <td onclick="move_member_detail(<?=$val['mmseq']?>,'<?=$paging_subquery."&page=".$page;?>');"><?=substr($val['mm_confirm_date'],0,10)?></td>
                                <?
                                    if($val['mm_status']=='Y'){
                                        echo '<td class="complete">처리완료<br>(승인)</td>';
                                    }else if($val['mm_status']=='N'){
                                        echo '<td class="complete">처리완료<br>(반려)</td>';
                                    }else if($val['mm_status']=='A'){
                                        echo '<td class="ing">처리미완료<br>(신청완료)</td>';
                                    }else if($val['mm_status']=='S'){
                                        echo '<td class="ing">처리미완료<br>(미입력)</td>';
                                    }
                                ?>
                                <td>
                                <?
                                    if($val['mm_status']=='S'){
                                        echo '<button type="button" data-seq='.$val['mmseq'].' class="btn type10 medium btn-delete">삭제</button>';
                                    }else{
                                        echo '-';
                                    }
                                ?>
                                    
                                </td>
                                <!--
                                <td>
                                    <?if($val['mm_status']=='Y' || $val['mm_status']=='N'){?>
                                        처리완료
                                    <?}else{?>
                                        처리미완료
                                    <?}?>
                                    <br>
                                    (
                                    <?if($val['mm_status']=='Y'){?>
                                        승인
                                    <?}else if($val['mm_status']=='N'){?>
                                        반려
                                    <?}else if($val['mm_status']=='A'){?>
                                        신청완료
                                    <?}?>
                                    )
                                </td>
                                    -->
                            </tr>
                        <?$numbering--;}?>
                    </tbody>
                </table>
                <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?>
            </div>
            <!-- //공지사항 -->
		</div>
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(1)').addClass('active');

    $('.btn-delete').click(function(e){
        e.preventDefault();
        
        if(confirm("해당 신입 사원을 삭제하시겠습니까?")){
            var data = {
                mmseq:  $(this).data('seq'),
                type: "remove_user"
            };
            $.ajax({
                url : '/@proc/hass/member_infoProc.php',
                data : data,
                type : 'post',
                dataType : 'json',
                success : function(data){
                    if(data.code=='TRUE'){
                        alert('해당 신입 사원이 삭제되었습니다.');
                        location.reload();
                    }else{
                        alert(data.msg);
                    }
                }
            });
        }
    })
</script>

