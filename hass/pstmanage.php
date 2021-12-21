<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$list = get_position_list($db,1);
$list2 = get_position_list($db,2);
$list3 = get_position_list($db,3);
$list4 = get_position_list($db,4);
$list5 = get_position_list($db,5);
//$list2 = get_position2_list($db);
?>
<style>
    .button-area.large{text-align: left;}
    .main-input.common-div{margin-top: 14px;}
    .main-input.common-div:first-child{margin-top: 0px;}
    .tab-cont{margin-top:15px;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

    <!-- CONTENT -->
    <div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
        <?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

        <!-- 내용 -->
        <div id="content" class="content-primary">
            <div class="tab-wrap">
				<ul class="tab">
					<li class="active" data-title="직책"><a href="#tab-pstmanage">직책관리</a></li>
                    <li data-title="직위"><a href="#tab-pstmanage2">직위관리</a></li>
                    <li data-title="직군"><a href="#tab-pstmanage3">직군관리</a></li>
                    <li data-title="고용부분"><a href="#tab-pstmanage4">고용구분</a></li>
                    <li data-title="사원유형"><a href="#tab-pstmanage5">사원유형</a></li>
				</ul>
			</div>

            <div id="tab-pstmanage" class="section-wrap tab-cont">
                <h2 class="content-title">직책 관리</h2>
                <div class="add-wrap">
                    <?foreach ($list as $val){?>
                    <div class="main-input common-div">
                        <input type="text" title="직책" class="input-text" style="max-width:20%;" data-id="<?=$val['tp_seq']?>" name="position1" value="<?=$val['tp_title']?>" placeholder="직책" />
                        <button class="btn type01 small main-input-remove">삭제</button>
                    </div>
                    <?}?>
                </div>
                <div class="button-area large hide">
                    <button data-type="1"  class="btn type01 medium  main-input-add">추가</button>
                    <button id="btn-save" data-type="1" name="btn_save" type="button" class="btn type12 medium">저장</button>
                </div>
            </div>

            <div id="tab-pstmanage2" class="section-wrap tab-cont">
                <h2 class="content-title">직위 관리</h2>
                <div class="add-wrap">
                    <?foreach ($list2 as $val){?>
                    <div class="main-input common-div">
                        <input type="text" title="직위" class="input-text" style="max-width:20%;" data-id="<?=$val['tp_seq']?>" name="position2" value="<?=$val['tp_title']?>" placeholder="직위" />
                        <button class="btn type01 small main-input-remove">삭제</button>
                    </div>
                    <?}?>
                </div>
                <div class="button-area large hide">
                    <button data-type="2" class="btn type01  medium main-input-add">추가</button>
                    <button id="btn-save2" data-type="2" name="btn_save" type="button" class="btn type12 medium">저장</button>
                </div>
            </div>

            <div id="tab-pstmanage3" class="section-wrap tab-cont">
                <h2 class="content-title">직군 관리</h2>
                <div class="add-wrap">
                    <?foreach ($list3 as $val){?>
                    <div class="main-input common-div">
                        <input type="text" title="직군" class="input-text" style="max-width:20%;" data-id="<?=$val['tp_seq']?>" name="position3" value="<?=$val['tp_title']?>" placeholder="직군" />
                        <button class="btn type01 small main-input-remove">삭제</button>
                    </div>
                    <?}?>
                </div>
                <div class="button-area large hide">
                    <button data-type="3" class="btn type01 medium main-input-add">추가</button>
                    <button id="btn-save3" type="button" data-type="3" name="btn_save" class="btn type12 medium">저장</button>
                </div>
            </div>

            <div id="tab-pstmanage4" class="section-wrap tab-cont">
                <h2 class="content-title">고용구분 관리</h2>
                <div class="add-wrap">
                    <?foreach ($list4 as $val){?>
                    <div class="main-input common-div">
                        <input type="text" title="고용구분" class="input-text" style="max-width:20%;" data-id="<?=$val['tp_seq']?>" name="position4" value="<?=$val['tp_title']?>" placeholder="직책" />
                        <button class="btn type01 small main-input-remove">삭제</button>
                    </div>
                    <?}?>
                </div>
                <div class="button-area large hide">
                    <button data-type="4" class="btn type01 medium main-input-add">추가</button>
                    <button id="btn-save4" type="button" data-type="4" name="btn_save" class="btn type12 medium">저장</button>
                </div>
            </div>

            <div id="tab-pstmanage5" class="section-wrap tab-cont">
                <h2 class="content-title">사원유형 관리</h2>
                <div class="add-wrap">
                    <?foreach ($list5 as $val){?>
                    <div class="main-input common-div">
                        <input type="text" title="사원유형" class="input-text" style="max-width:20%;" data-id="<?=$val['tp_seq']?>" name="position5" value="<?=$val['tp_title']?>" placeholder="직책" />
                        <button class="btn type01 small main-input-remove">삭제</button>
                    </div>
                    <?}?>
                </div>
                <div class="button-area large hide">
                    <button data-type="5" class="btn type01 medium main-input-add">추가</button>
                    <button id="btn-save5" type="button" data-type="5" name="btn_save" class="btn type12 medium">저장</button>
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
            type = $(this).data('type');
            e.preventDefault();
            var $text = $('.tab li.active').data('title');
            var $index;
            $('.tab li').each(function(i,e){
                if($(e).hasClass('active')){return $index=i};
            });
            var $add_text = `
                    <div class="main-input common-div">
                        <input type="text" title="`+$text+`" class="input-text" style="max-width:20%;" name="position`+type+`" value="" placeholder="입력해주세요." />
                        <button class="btn type01 small add-input-remove">삭제</button>
                    </div>`;
            $('.add-wrap').eq($index).append($add_text);
        });
        $(document).on('click','.main-input-remove', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
        $(document).on('click','.add-input-remove', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
        $('[name="btn_save"]').click(function(){
            type = $(this).data('type');

            var check = true;
            var data = [];
            var obj = $('[name=position'+type+']');
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
                url : '/@proc/hass/position_proc.php',
                data : {'data':data,'type':type},
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
