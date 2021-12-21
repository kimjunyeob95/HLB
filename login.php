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
</head>
<body>
<a href="#container" class="skip-navi">본문 바로가기</a>
<!-- WRAP -->
<div id="wrap" class="depth-main login-wrap">
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
<div id="container" class="login">
	<div id="content" class="content-primary">
		<div class="login-title">
			<div style="width: 700px;">
				<!-- <h2 style="color: #000; padding-top:0px; font-size: 40px;">Login</h2> -->
                <img src="/@resource/images/@thumb/HLB Group.png" alt="로그인 이미지" style="width: 60%;">
				<p class="summary" style="color: #000; padding-top: 30px; border-top: 6px solid #ececec;">에이치엘비그룹 임직원을 위한 인사시스템입니다.</p>
			</div>
		</div>
		<form id="loginForm">
			<fieldset class="fieldset">
				<legend>로그인 입력화면</legend>
				<div class="field">
                    <div class="insert seq">
                        <label class="label">법인</label>
                        <select class="select" name="coperation">
                            <option value="">법인을 선택하세요.</option>
                            <?foreach ($coperation_list as $val){?>
                            <option value="<?=$val['co_seq']?>"><?=$val['co_name']?><?if(!empty($val['co_subname'])){?> <?=$val['co_subname']?><?}?></option>
                            <?}?>
                        </select>
                    </div>
					<div class="insert id">
						<label for="userId"><img src="/@resource/images/common/ico_user.png" alt="로그인"></label>
						<input type="text" id="userId" name="userId" placeholder="사원번호를 입력하세요">
					</div>
					<div class="insert pw">
						<label for="userPw"><img src="/@resource/images/common/ico_pw.png" alt="비밀번호"></label>
						<input type="password" id="userPw"  name="userPw" placeholder="비밀번호를 입력하세요">
					</div>
				</div>
				<input type="button"  id="btn-login-act" value="LOGIN" class="btn large type01">
			</fieldset>
			
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
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>

var loginAction = function(){
	var userId = $.trim($('#userId').val());
	var userPw = $.trim($('#userPw').val());
	var coseq = $('[name="coperation"]').val();
    if(coseq==""||coseq==undefined){
		alert("법인을 선택해 주세요");
		return;
    }
	if(userId==""){
		alert("사원 번호를 입력해 주세요");
		$('#userId').focus();
		return;
	}
	if(userPw==""){
		alert("비밀번호를 입력해 주세요");
		$('#userPw').focus();
		return;
	}

	var data = { 'coseq': coseq, 'userId' : userId ,  'userPw' : userPw };
	hlb_fn_ajaxTransmit("/@proc/loginProc.php", data); 
};

$('#userPw').keypress(function(e){
	if(e.keyCode==13){
		loginAction();
	}
});

$('#btn-login-act').click(function(e){
	e.preventDefault();
	loginAction();
});


function fn_callBack(calback_id, result, textStatus){
	if(calback_id=='loginProc'){
		if(result.code=='FALSE'){
			alert(result.msg);
			return;
		}else{
                alert(result.msg);
                location.href="/";
		}
	}
}



</script>
