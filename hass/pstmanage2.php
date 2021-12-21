<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$list = get_position_list($db,2);

?>
<style>
    .button-area.large{text-align: left;}
    .main-input.common-div{margin-top: 14px;}
    .main-input.common-div:first-child{margin-top: 0px;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

    <!-- CONTENT -->
    <div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
        <?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

        <!-- 내용 -->
        <div id="content" class="content-primary">
            <h2 class="content-title">직급 관리</h2>
            <div class="section-wrap">
                <div class="add-wrap">
                    <?foreach ($list as $val){?>
                    <div class="main-input common-div">
                        <input type="text" title="직급" class="input-text" style="max-width:20%;" data-id="<?=$val['tp2_seq']?>" name="position" value="<?=$val['tp2_title']?>" placeholder="직급" />
                        <button class="btn type01 small main-input-remove">삭제</button>
                    </div>
                    <?}?>
                </div>
                <div class="button-area large hide">
                    <button class="btn type01 medium main-input-add">추가</button>
                    <button id="btn-save" type="button" class="btn type12 medium">저장</button>
                </div>
            </div>
        </div>
        <!-- // 내용 -->
    </div>
    <!-- // CONTENT -->
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
    <!-- // WRAP -->
    <script>
        $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');
        $('.main-input-add').click(function(e){
            e.preventDefault();
            var $add_text = `
                    <div class="main-input common-div">
                        <input type="text" title="직급" class="input-text" style="max-width:20%;" name="position" value="" placeholder="직급" />
                        <button class="btn type01 small add-input-remove">삭제</button>
                    </div>`;
            $('.add-wrap').append($add_text);
        });
        $(document).on('click','.main-input-remove', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
        $(document).on('click','.add-input-remove', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
        $('#btn-save').click(function(){
            var check = true;
            var data = [];
            var obj = $('[name=position]');
            $(obj).each(function(i){
                id = $(this).data('id');
                if(id=='' || id ==undefined){
                    id = 0;
                }
                data[i] = Array($(this).val(),id);
                if($(this).val()==0 || $(this).val()==null){
                    alert('빈값을 모두 입력해주세요.');
                    $(this).focus();
                    check = false;
                    return false;
                }
            });
            if(!check){
                return false;
            }
            $.ajax({
                url : '/@proc/hass/position2_proc.php',
                data : {'data':data},
                type : 'post',
                dataType : 'json',
                success : function (data) {
                    if(data.code=='FALSE'){
                        alert(data.msg);
                        return;
                    }else
                        alert(data.msg);
                    location.reload();
                }
            })
        })
    </script>
