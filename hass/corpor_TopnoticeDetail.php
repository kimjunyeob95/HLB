<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/editor_include.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");

foreach ($_REQUEST as $key => $value){
    ${$key} = $value;
}

$title = '등록';
if(!empty($seq)){
    $title = '수정';
    $query = "select * from tbl_hr_top_notice where hrTopseq = {$seq}";
    $ps = pdo_query($db,$query,array());
    $data = $ps ->fetch(PDO::FETCH_ASSOC);
}
?>

<style>
    table tbody tr{cursor: pointer;}
    /* .fr-toolbar.fr-desktop{display: none;} */
    #uniform-select-salary span{text-align: left;}
    .fr-wrapper {height: 500px; overflow-y: scroll;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">한줄공지 상세관리</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <form id ="frm" >
                <input type="hidden" value="<?=$seq?>" name="hrTopseq">
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>HR 안내</caption>
                    <colgroup>
                        <col style="width: 140px" />
                        <col style="width: *" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="col">제목</th>
                            <td class="left"><input type="text" class="input-text" name="hrTopTitle" value="<?=$data['hrTopTitle']?>"></td>
                        </tr>
                        <tr>
                            <th scope="col">노출여부</th>
                            <td class="left">
                                <div class="insert">
                                    <select class="select" id="select-salary" name="hrTopState">
                                        <option value="T" <?if($data['hrTopState']=='T' || empty($data['hrTopState'])){?>selected<?}?>>게시</option>
                                        <option value="F" <?if($data['hrTopState']=='F'){?>selected<?}?>>비게시</option>
                                    </select>
                                    <span style="vertical-align: sub;">(한줄공지 시 기존 게시글은 자동 비게시처리됩니다.)</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </form>
            <!-- //공지사항 -->
            <div style="text-align:center;margin-top:50px;">
                <div class="button-area large">
                    <button type="button" data-btn="목록" onclick="location.href='/hass/corpor_Topnotice?page=<?=$page?>'" class="btn type01 large">목록<span class="ico apply"></span></button>
                    <button type="button" data-btn="<?=$title?>" id="btn_save" class="btn type01 large btn-footer"><?=$title?><span class="ico save"></span></button>
                </div>
            </div>
		</div>
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('.input-datepicker').datepicker();
    $('#aside-menu .tree-wrap>li:eq(5)').addClass('active');

    $('#btn_save').click(function(){
        var form = $('#frm')[0];
        var data = new FormData(form);
        hlb_fn_file_ajaxTransmit("/@proc/hass/hr_top_notice_proc.php", data);
    });

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='hr_top_notice_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                if(result.type=='I'){
                    alert(result.msg);
                    location.href = "/hass/corpor_Topnotice.php";
                }else{
                    alert(result.msg);
                    location.reload();
                }
            }
        }
    }


</script>

