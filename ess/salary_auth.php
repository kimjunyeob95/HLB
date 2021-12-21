<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/main_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
// echo('<pre>');print_r($mm_serial_no);echo('</pre>');
/**
 * 개인정보 view
 **/
// echo('<pre>');print_r($_SESSION);echo('</pre>');
?>
<style>
    .section h3{padding-bottom:30px;}
    .ess-main .section h3 { margin-top: 87px; }
    .salary-text-area {text-align: center; margin-top: 20px;}
</style>
<!-- WRAP -->
<div id="wrap" class="depth-main">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->

<!-- CONTENT -->
<div id="container" class="ess-main">
	<div id="content" >
        <div class="section">
            <h3>임직원 확인</h3>
            <div class="table-wrap">
                <table class="data-table left" id="form-change-pw">
                    <caption></caption>
                    <colgroup>
                        <col style="width: 20%">
                        <col style="width: *">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>주민번호 뒷자리</th>
                            <td><input type="password" maxlength=7 title="주민번호뒷자리" class="input-text" name="mm_serial_no" value=""></td>
                        </tr>
                    </tbody>
                </table>
                <div class="salary-text-area">
                    <p>※ 정보보호를 위해 해당정보를 입력하여 주십시오.</p>
                </div>
                <div class="button-area large">
                    <button type="button" id="btn-change-pw" class="btn type01 large">확인<span class="ico check01"></span></button>
                </div>
            </div>
	    </div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script type="text/javascript">
$(document).ready(function () {
    $('.header-wrap ').addClass('active');
    $('.depth01:eq(2)').addClass('active');

    $('input[name="mm_serial_no"]').keypress(function(e){
        let validate = true;
        if(e.keyCode==13){
            $('#form-change-pw').find('.input-text').each(function(e){
                var val = $.trim($(this).val());
                var txt = $(this).attr('title');

                if(val==""){
                    $(this).focus();
                    alert(  reulReturner(txt) + " 입력해 주세요");
                    validate = false;
                    return false;
                }

            });
            if(validate){
                var data = { 'mm_serial_no' : $('input[name="mm_serial_no"]').val() };
                hlb_fn_ajaxTransmit("/@proc/ess/chkSerialNum.php", data);
            }
        }
    });
    $('#btn-change-pw').keypress(function(e){
        e.preventDefault();
    });
	$('#btn-change-pw').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#form-change-pw').find('.input-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');

            if(val==""){
                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }

        });
        if(validate){
            var data = { 'mm_serial_no' : $('input[name="mm_serial_no"]').val() };
            hlb_fn_ajaxTransmit("/@proc/ess/chkSerialNum.php", data);
        }
        
    });
    
});
function fn_callBack(calback_id, result, textStatus){
    if(calback_id=='chkSerialNum'){
        if(result.code=='FALSE'){
            alert(result.msg);
            return;
        }else{
        alert('임직원 확인되었습니다.');
        location.href="/ess/submain4";
        }
    }
}
</script>

