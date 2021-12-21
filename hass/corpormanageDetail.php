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
    $query = "select * from tbl_hr_notice where hrnseq = {$seq}";
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
        <h2 class="content-title">HR안내 상세관리</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <form id ="frm" >
                <input type="hidden" value="<?=$seq?>" name="hrnseq">
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
                            <td class="left"><input type="text" class="input-text" name="hrnTitle" value="<?=$data['hrnTitle']?>"></td>
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
                                    <select class="select" name="hrnStatus">
                                        <option value="T" <?if($data['hrnStatus']=='T' || empty($data['hrnStatus'])){?>selected<?}?>>게시</option>
                                        <option value="F" <?if($data['hrnStatus']=='F'){?>selected<?}?>>비게시</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">내용</th>
                            <td>
                                <textarea style="display: none;" name="hrnContent"><?=$data['hrnContent']?></textarea>
                                <textarea class="form-control" id="nContent"><?=$data['hrnContent']?></textarea>
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
                    <button type="button" data-btn="목록" onclick="location.href='/hass/corpormanage.php?page=<?=$page?>'" class="btn type01 large">목록<span class="ico apply"></span></button>
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
        $('textarea[name="hrnContent"]').text(CKEDITOR.instances.nContent.getData());
        var form = $('#frm')[0];
        var data = new FormData(form);
        hlb_fn_file_ajaxTransmit("/@proc/hass/hr_notice_proc.php", data);
    });

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='hr_notice_proc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                if(result.type=='I'){
                    alert(result.msg);
                    location.href = "/hass/corpormanage.php";
                }else{
                    alert(result.msg);
                    location.reload();
                }
            }
        }
    }

    // setTimeout(() => {
    //     $('.fr-element p').attr('contenteditable','false');    
    // }, 100);
        
</script>

