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
    $addquery = " and hrnTitle like '%{$search}%' ";
}
$query = "select count(*) as cnt from tbl_hr_notice where hrnDel = 'F' {$addquery} and hrnCoseq = {$coseq} order by hrnRegdate desc";
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


$query = "select * from tbl_hr_notice where hrnDel = 'F' {$addquery} and hrnCoseq = {$coseq} order by hrnRegdate desc limit {$from} , {$rows} ";
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
        <h2 class="content-title">HR안내 관리</h2>
        <form method="post" action="<?= $_SERVER['PHP_SELF']?>" >
        <select name="cboArea" id="cboArea">
            <option>제목</option>
        </select>
        <div class="input-search" id="" style="display: inline-block; width: 300px;">
            <input type="text" value="<?=$search?>" name="search" style="width: 298px; border:0;"/>
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
                    <caption>HR안내 내역</caption>
                    <colgroup>
                        <col style="width: 2%" />
                        <col style="width: 20%" />
                        <col style="width: 5%" />
                        <col style="width: 2%" />
                        <col style="width: 5%" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">no</th>
                        <th scope="col">제목</th>
                        <th scope="col">작성일시</th>
                        <th scope="col">조회수</th>
                        <th scope="col">노출여부</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?foreach ($list as $val){?>
                        <tr>
                            <td onclick="detail_page(<?=$page?>,<?=$val['hrnseq']?>);"><?=$numbering--?></td>
                            <td class="center" onclick="detail_page(<?=$page?>,<?=$val['hrnseq']?>);"><?=$val['hrnTitle']?></td>
                            <td onclick="detail_page(<?=$page?>,<?=$val['hrnseq']?>);"><?=substr($val['hrnRegdate'],0,16)?></td>
                            <td class="center" onclick="detail_page(<?=$page?>,<?=$val['hrnseq']?>);"><?=$val['hrnViews']?></td>
                            <td>
                                <select class="select" name="hrnStatus">
                                    <option value="T" <?if($val['hrnStatus']=='T' || empty($val['hrnStatus'])){?>selected<?}?>>게시</option>
                                    <option value="F" <?if($val['hrnStatus']=='F'){?>selected<?}?>>비게시</option>
                                    <option value="C">삭제</option>
                                </select>
                                <button type="button" class="btn type10 small btn-setStatus" data-hrnseq=<?=$val['hrnseq']?>>저장</button>
                            </td>
                        </tr>
                        <?}?>
                    </tbody>
                </table>
                <div>
                    <div class="button-area large" style="text-align:right;">
                         <button type="button" data-btn="작성" onclick="location.href='/hass/corpormanageDetail'" class="btn type01 large btn-footer">등록<span class="ico save"></span></button>
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
        location.href='./corpormanageDetail.php?page='+page+'&seq='+seq;
    }

    $('.btn-setStatus').click(function(){
        let hrnseq = $(this).data('hrnseq');
        let hrnStatus = $(this).prev().find('select[name="hrnStatus"]').val();
        let data = {
            hrnseq : hrnseq,
            hrnStatus : hrnStatus
        }
        hlb_fn_ajaxTransmit_v2("/@proc/hass/hr_notice_state.php", data);
    });
    function fn_callBack_v2(calback_id, result, textStatus){
        if(calback_id=='hr_notice_state'){
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

