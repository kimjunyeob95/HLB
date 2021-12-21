<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}
$rows = 10;
@$coseq = $_SESSION['mInfo']['mc_coseq'];
if(empty($page)){
    $page=1;
}
if(!empty($search)){
    $addquery = " and tn_title like '%{$search}%' ";
}
$query = "select count(*) as cnt from tbl_noti_page where tn_is_del = 'F' {$addquery} and tn_coseq = {$coseq} order by tn_regdate desc";
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


$query = "select * from tbl_noti_page where tn_is_del = 'F' {$addquery} and tn_coseq = {$coseq} order by tn_topshow desc,tn_regdate desc limit {$from} , {$rows} ";
$ps = pdo_query($db,$query,array());
$list = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data_gnb);
}
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
        <h2 class="content-title">공지사항 관리</h2>
        <form method="post" action="<?= $_SERVER['PHP_SELF']?>" >
        <select name="cboArea" id="cboArea">
            <option>제목</option>
        </select>
        <div class="input-search" id="" style="display: inline-block; width: 300px;">
            <input type="text" type="number" value="<?=$search?>" name="search" style="width: 298px; border:0;"/>
            <button type="submit" class="btn"  style="width: 20px;">
                <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
            </button>
        </div>
        <button type="submit" class="btn type01 small">조회</button>
        </form>
		<div class="section-wrap" style="margin-top:30px;">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>휴가 신청 내역</caption>
                    <colgroup>
                        <col style="width: 2%" />
                        <col style="width: 20%" />
                        <col style="width: 5%" />
                        <col style="width: 2%" />
                        <col style="width: 5%" />
                        <col style="width: 5%" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">제목</th>
                        <th scope="col">작성일시</th>
                        <th scope="col">조회수</th>
                        <th scope="col">노출여부</th>
                        <th scope="col">게시판 상단고정</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?foreach ($list as $val){?>
                        <tr>
                            <td onclick="detail_page(<?=$page?>,<?=$val['tn_seq']?>);"><?=$numbering--?></td>
                            <td class="center" onclick="detail_page(<?=$page?>,<?=$val['tn_seq']?>);"><?=$val['tn_title']?></td>
                            <td onclick="detail_page(<?=$page?>,<?=$val['tn_seq']?>);"><?=substr($val['tn_regdate'],0,16)?></td>
                            <td onclick="detail_page(<?=$page?>,<?=$val['tn_seq']?>);"><?=$val['tn_views']?></td>
                            <td>
                                <select class="select" name="tn_status">
                                    <option value="T" <?if($val['tn_status']=='T' || empty($val['tn_status'])){?>selected<?}?>>게시</option>
                                    <option value="F" <?if($val['tn_status']=='F'){?>selected<?}?>>비게시</option>
                                    <option value="C">삭제</option>
                                </select>
                                <button type="button" class="btn type10 small btn-setStatus" data-tnseq=<?=$val['tn_seq']?>>저장</button>
                            </td>
                            <td>
                                <select class="select" name="tn_topshow">
                                    <option value="T" <?if($val['tn_topshow']=='T' || empty($val['tn_topshow'])){?>selected<?}?>>상단고정</option>
                                    <option value="F" <?if($val['tn_topshow']=='F'){?>selected<?}?>>상단비고정</option>
                                </select>
                                <button type="button" class="btn type10 small btn-setStatusTop" data-tnseq=<?=$val['tn_seq']?>>저장</button>
                            </td>
                        </tr>
                        <?}?>
<!--                            <td class="ing">비게시</td>-->
                    </tbody>
                </table>
                <div>
                    <div class="button-area large" style="text-align:right;">
                         <button type="button" data-btn="작성" onclick="location.href='/hass/corpornoticeDetail'" class="btn type01 large btn-footer">등록<span class="ico save"></span></button>
                    </div>
                </div>
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
    $('#aside-menu .tree-wrap>li:eq(5)').addClass('active');
    function detail_page(page,seq){
        location.href='./corpornoticeDetail?page='+page+'&seq='+seq;
    }

    $('.btn-setStatus').click(function(){
        let tnseq = $(this).data('tnseq');
        let tn_status = $(this).prev().find('select[name="tn_status"]').val();
        let data = {
            tn_seq : tnseq,
            tn_status : tn_status
        }
        hlb_fn_ajaxTransmit_v2("/@proc/hass/notice_state.php", data);
    });
    $('.btn-setStatusTop').click(function(){
        $this_val = $(this).prev().find('select[name="tn_topshow"]').val();
        $this_title = '';
        if($this_val == 'T'){
            $this_title = '고정';
        }else{
            $this_title = '비고정';
        }
        if(confirm('해당 게시글을 상단 '+$this_title+'하시겠습니까?')){
            let tnseq = $(this).data('tnseq');
            let data = {
                tn_status : 'tn_topshow',
                tn_topshow : $(this).prev().find('select[name="tn_topshow"]').val(),
                tn_seq : tnseq,
            }
            hlb_fn_ajaxTransmit_v2("/@proc/hass/notice_state.php", data);
        }
    });
    function fn_callBack_v2(calback_id, result, textStatus){
        if(calback_id=='notice_state'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.reload();
            }
        }
    }
</script>

