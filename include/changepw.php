<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/main_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

@$mmseq = $_SESSION['mmseq'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
/**
 * 개인정보 view
 **/
$data =  get_member_info($db,$mmseq);
// echo('<pre>');print_r($_SESSION);echo('</pre>');
?>
<style>
    .section h3{padding-bottom:30px;}
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
            <h3>비밀번호 변경</h3>
            <div class="table-wrap">
                <form id="form-change-pw" action="/@proc/ess/changepwProc.php">
                    <table class="data-table left">
                        <caption></caption>
                        <colgroup>
                            <col style="width: 20%">
                            <col style="width: *">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>사번</th>
                                <td><input type="text" class="input-text" name="mc_code" disabled value="<?=$data['mc_code']?>"></td>
                            </tr>
                            <tr>
                                <th>성명</th>
                                <td><input type="text" class="input-text" name="mm_name" disabled value="<?=$enc->decrypt($data['mm_name'])?>"></td>
                            </tr>
                            <tr>
                                <th>기존 비밀번호</th>
                                <td><input type="password" title="기존 비밀번호" name="password_origin" class="input-text password_origin" placeholder=""></td>
                            </tr>
                            <tr>
                                <th>비밀번호</th>
                                <td><input type="password" title="비밀번호" class="input-text password1" placeholder="*비밀번호는 영문+숫자를 혼합하여 8~20자리 이내로 입력해주세요." name="mm_password"></td>
                            </tr>
                            <tr>
                                <th>비밀번호 확인</th>
                                <td><input type="password" title="비밀번호 확인" placeholder="*비밀번호는 영문+숫자를 혼합하여 8~20자리 이내로 입력해주세요." class="input-text password2"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <div class="button-area large">
                    <button type="submit" id="btn-change-pw" class="btn type01 large">비밀번호 변경<span class="ico check01"></span></button>
                </div>
            </div>
	    </div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script type="text/javascript">
$(document).ready(function () {
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
            var password_origin = $(".password_origin").val();
            var pw1 = $(".password1").val();
            var pw2 = $(".password2").val();
            var num = pw1.search(/[0-9]/g);
            var eng = pw1.search(/[a-z]/ig);


            if(pw1 != pw2){
                alert('비밀번호를 동일하게 입력해주세요.');
                return false;
            }

            if(pw1.length < 8 || pw1.length > 20){
                alert("비밀번호는 영문+숫자를 혼합하여 8자리 ~ 20자리 이내로 입력해주세요.");
                return false;
            }else if(pw1.search(/\s/) != -1){
                alert("비밀번호는 공백 없이 입력해주세요.");
                return false;
            }else if(num < 0 && eng < 0){
                alert("영문,숫자를 혼합하여 입력해주세요.");
                return false;
            }else {
                $('#form-change-pw').submit();
                return true;
            }
        }
    });
});

</script>

