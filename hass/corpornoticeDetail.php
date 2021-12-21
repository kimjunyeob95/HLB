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
$query = "select * from tbl_template_page where nDel = 'FALSE' order by nRegdate";
$ps = pdo_query($db,$query,array());
$list = array();
while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data_gnb);
}
if(!empty($seq)){
    $title = '수정';
    $query = "select * from tbl_noti_page where tn_seq = {$seq}";
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
        <h2 class="content-title">공지사항 상세관리</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <form id ="frm" >
                <input type="hidden" value="<?=$seq?>" name="tn_seq">
            <div class="table-wrap">
                <table class="data-table" id="tb_insaHeader">
                    <caption>휴가 신청 내역</caption>
                    <colgroup>
                        <col style="width: 140px" />
                        <col style="width: *" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="col">제목</th>
                            <td class="left"><input type="text" class="input-text" name="tn_title" value="<?=$data['tn_title']?>"></td>
                        </tr>
                        <tr>
                            <th scope="col">템플릿</th>
                            <td class="left">
                                <div class="insert">
                                    <select class="select" id="select-salary">
                                        <option value="0"> 선택안함 </option>
                                        <?foreach ($list as $val){?>
                                            <option value="<?=$val['nseq']?>"><?=$val['nTitle']?></option>
                                        <?}?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">노출여부</th>
                            <td class="left">
                                <div class="insert">
                                    <select class="select" name="tn_status">
                                        <option value="T" <?if($data['tn_status']=='T' || empty($data['tn_status'])){?>selected<?}?>>게시</option>
                                        <option value="F" <?if($data['tn_status']=='F'){?>selected<?}?>>비게시</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">게시판 상단고정</th>
                            <td class="left">
                                <div class="insert">
                                    <select class="select" name="tn_topshow">
                                        <option value="T" <?if($data['tn_topshow']=='T' || empty($data['tn_topshow'])){?>selected<?}?>>상단고정</option>
                                        <option value="F" <?if($data['tn_topshow']=='F'){?>selected<?}?>>상단비고정</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">내용</th>
                            <td>
                                <textarea style="display: none;" name="tn_content"><?=$data['tn_content']?></textarea>
                                <textarea class="form-control" id="nContent"><?=$data['tn_content']?></textarea>
                                <script>
                                    CKEDITOR.replace('nContent',{
                                        height : '400px'
                                    });

                                </script>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </form>
            <!-- //공지사항 -->
            <div style="text-align:center;margin-top:50px;">
                <div class="button-area large">
                    <button type="button" data-btn="목록" onclick="location.href='/hass/corpornotice.php?page=<?=$page?>'" class="btn type01 large">목록<span class="ico apply"></span></button>
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
    $('#select-salary').change(function(){
        data = {'nseq':$(this).val()};
        $.ajax({
            url : "/@proc/hass/get_template.php",
            data : data,
            dataType : "json",
            method : 'POST',
            success : function(result){
                CKEDITOR.instances.nContent.setData(result.data.nContent);
            }
        })
    });

    $('#btn_save').click(function(){
        $('textarea[name="tn_content"]').text(CKEDITOR.instances.nContent.getData());
        var form = $('#frm')[0];
        var data = new FormData(form);
        hlb_fn_file_ajaxTransmit("/@proc/hass/notice_proc.php", data);
    });

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='notice_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                if(result.type=='I'){
                    alert(result.msg);
                    location.href = "/hass/corpornotice.php";
                }else{
                    alert(result.msg);
                    location.reload();
                }
            }
        }
    }

</script>

