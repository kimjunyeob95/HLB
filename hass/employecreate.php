<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<style>
    .data-table{border-top: 1px solid #d1d1d1;}
</style>
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
    <h2 class="content-title">신규 사번 생성</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-information"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px; max-width:60%;">
                    <!-- 신규입사자 form -->
                    <form id="new_member_form" class= member_form">
                        <table class="data-table left">
                            <caption>신규입사자 정보</caption>
                            <colgroup>
                                <col style="width: 140px" />
                                <col style="width: *" />
                                <col style="width: 140px" />
                                <col style="width: *" />
                                <col style="width: 140px" />
                                <col style="width: *" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">성명</th>
                                    <td class="insert"><input type="text"  class="input-text" name="mm_name" title ='성명' ></td>
                                </tr>
                                <tr>
                                    <th scope="col">E-Mail</th>
                                    <td class="insert "><input type="text" class="input-text" name="mm_email" title = 'E-Mail'></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <!-- 신규입사자 form -->
                </div>
                <div class="button-area large" style="text-align: center; max-width:60%;">
                    <button type="button" id="btn-set-my-info" class="btn type01 large">신규입사자 사번 생성<span class="ico check01"></span></button>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
		
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(1)').addClass('active');
    $('.input-datepicker').datepicker();
    $('#btn-set-my-info').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#new_member_form').find('.input-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');
            if(val==""){
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }
        });
        if(!validate){
            return false;
        }else{
            if(chk_email($('input[name="mm_email"]').val())==false){
                alert('올바르지 않은 이메일 형식입니다.');
                return false;
            };
        }
        var data = $('#new_member_form').serialize();
        if(validate){
            if(confirm("신규 사번을 생성하시겠습니까?")){
                hlb_fn_ajaxTransmit("/@proc/hass/employecreateProc.php", data);
            }
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='employecreateProc'){
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
