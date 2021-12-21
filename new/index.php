<?
/*****************************************
	파일경로 : /login.php
	파일설명 : 시스템 로그인 페이지
	
******************************************/
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
/*$enc = new encryption();*/
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_status='T' ";
$ps = pdo_query($db,$query,array());
$coperation_list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($coperation_list,$data);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>HLB</title>
<link rel="stylesheet" type="text/css" href="/@resource/css/style.css">
<script type="text/javascript" src="/@resource/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/@resource/js/jquery.uniform.bundled.js"></script>
<script type="text/javascript" src="/@resource/js/common.js"></script>
<script type="text/javascript" src="/@resource/js/ajax.common.js"></script>
<script type="text/javascript" src="/@resource/js/jquery.bxslider.min.js"></script>
<script src="/manage/assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="/include/fuc.js"></script>
<script src="/manage/@resource/plugin/datepicker/datepicker.js?t=20201109"></script>
<link rel="stylesheet" href="/manage/@resource/plugin/datepicker/datepicker.css" />
</head>
<body>
<a href="#container" class="skip-navi">본문 바로가기</a>
<!-- WRAP -->
<div id="wrap" class="depth-main login-wrap new">
<!-- HEADER -->
<div id="header">
		<!-- header-wrap -->
	<!-- <div class="header-wrap">
		<h1><a href="/"><span class="blind">HLB</span></a></h1>
	</div> -->
	<!--// header-wrap -->
</div>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="login new">
	<div id="content" class="content-primary">
		<div class="login-title new">
			<div>
                <h2> </h2>
				<p class="summary">에이치엘비그룹 가족이 되신 것을 환영합니다.<br>임직원을 위한 인사정보등록 사이트입니다.</p>
			</div>
		</div>
        <div class="form-wrap">
            <form id="loginForm">
                <fieldset class="fieldset">
                    <legend>로그인 입력화면</legend>
                    <div class="field">
                        <div class="insert seq">
                            <label class="label">법인</label>
                            <select class="select" name="coperation">
                                <?foreach ($coperation_list as $val){?>
                                <option value="<?=$val['co_seq']?>"><?=$val['co_name']?><?if(!empty($val['co_subname'])){?> <?=$val['co_subname']?><?}?></option>
                                <?}?>
                            </select>
                        </div>
                        <div class="insert">
                            <label for="userId"><img src="../../@resource/images/common/ico_pw.png" alt="사번"></label>
                            <input type="number" id="userCode" placeholder="사번을 입력하세요.">
                        </div>
                        <div class="insert">
                            <label for="userId"><img src="../../@resource/images/common/ico_user.png" alt="이름"></label>
                            <input type="text" id="userName" placeholder="성명 입력하세요.">
                        </div>
                    </div>
                    <input type="button"  id="btn-login-act" value="LOGIN" class="btn large type01" style="height: 158px;margin-top:0px;">
                </fieldset>
                <div class="data-list">
                    <!-- <ul>
                        <li>- 생년월일은 주민등록상 기재되어 있는 생년월일로 입력해주세요.</li>
                        <li>- E-Mail 주소는 지원 시 기재했던 E-Mail 주소로 입력해주세요.</li>
                    </ul> -->
                </div>
                <!--
                <div class="data-list">
                    <ul>
                        <li>- 비밀번호는 Autoway 암호와 동일합니다. </li>
                        <li>- 비밀번호 변경은 Autoway에서 해주십시요. </li>
                        <li>- 보안동의를 위해 반드시 팝업 항상허용을 해주십시요.</li>
                    </ul>
                </div>
                -->
            </form>
        </div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
    $('#userDate').datepicker();
var loginAction = function(){
    var coperation = $('select[name="coperation"]').val();
	var userName = $.trim($('#userName').val());
	var userCode = $.trim($('#userCode').val());
	if(userName==""){
		alert("이름을 입력해 주세요");
		$('#userName').focus();
		return;
	}
	if(userCode==""){
		alert("사번을 입력해 주세요");
		$('#userCode').focus();
		return;
	}
	var data = { 'userName' : userName ,  'userCode' : userCode, 'coperation' : coperation};
	hlb_fn_ajaxTransmit("/@proc/new_loginProc.php", data);
	
	
};

$('#loginForm').keypress(function(e){
	if(e.keyCode==13){
		loginAction();
	}
});

$('#btn-login-act').click(function(e){
	e.preventDefault();
	loginAction();
});
function fn_callBack(calback_id, result, textStatus){
	if(calback_id=='new_loginProc'){
		if(result.code=='FALSE'){
			alert(result.msg);
			return;
		}else{
		    if(result.step < 1) {
                alert(result.msg);
            }else {
                alert('작성 이력페이지로 이동합니다.');
            }
            new_step_info(result.step*1+1);
		}
	}
}



</script>
