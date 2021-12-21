<?
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';
	session_start();
	if(!empty($_SESSION['admin_info'])){
		page_move('/manage/main.php');
		exit;
	}

	

?>
<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>HLB HR SYSTEM | ADMIN</title>
		<meta name="keywords" content="" />
		<meta name="description" content="">
		<meta name="author" content="okler.net">

		<!-- 파비콘 -->
		<!--
		<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="assets/images/icon.png" type="image/x-icon" />
		-->

		<!-- 폰트 -->
		<link href='http://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" />

		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="https://www.google.com/recaptcha/api.js"async defer></script>
	</head>

		

	</head>
	<style>
		*{
			margin: 0 auto;
			padding: 0;
			font-family: 'NotoKrR', sans-serif; 
			font-size: 16px;
		}
		button{
			cursor: pointer;
		}
		.logo-container {
			padding: 20px;
		}
		.title {
			padding: 40px 0;
			font-size: 2.2rem;
			font-weight: 100;
			color: #888888;
		}
		section{
			width: 500px;
			height: 500px;
			text-align: center;
			margin-top: 4vw;
			display:table;
		}
		.main{
			display:table-cell;
    		vertical-align:middle;
		}
		input{
			font-size: 0.8rem;
			border: none;
			width: 22vw;
			border-bottom: 1.2px solid #000;
			padding: 20px 10px;
			text-align: left;
			color: #000;
			
		}
		input::placeholder{
			color: #b2b2b2 !important;
		}
		.section4 {
			margin-top: 1vw;
		}
		.section4_left{
			display: inline-block;
			text-align: center;
			width: 12vw;
		}
		.section4_right{
			display: inline-block;
		}
		.btn1 {
			font-size: 0.8rem;
			width: 5vw;
			height: 2.5vw;
			background-color: #fff;
			border: 1px solid #cfcccc;
			color: #000;
			text-align: center;
		}
		.btn2{
			font-size: 0.8rem;
			width: 5vw;
			height: 2.5vw;
			background-color: #fff;
			border: 1px solid #cfcccc;
			color: #000;
			text-align: center;
	
		}
		i {
			margin-right: 0.2vw;
		}
		.section6 {
			margin-top: 1.5vw;
		}
		.btn3 {
			font-size: 1.2rem;
			width: 23vw;
			padding: 10px;
			background-color: #f77b00;
			border: none;
			color: #fff;
			text-align: center;
		}
		p{
			margin-top: 1vw;
			font-size: 0.688rem;
			text-align: center;
			color: #000;
		}
		@media (max-width: 1450px){
			input {
				width: 32vw;
			}
			.btn1{
				width: 7vw;
				height: 4vw;
				font-size: 0.8rem;
			}
			.btn2{
				width: 7vw;
				height: 4vw;
				font-size: 0.8rem;
			}
			.section4_left {
				width: 18vw;
			}
			.btn3 {
				width: 34vw;
			}
			p {
				font-size: 0.788rem;
			}
		}
		@media (max-width: 1030px){
			.btn1 {
				font-size: 0.7rem;
			}
			.btn2 {
				font-size: 0.7rem;
			}
			.title {
    			font-size: 2.2rem;
			}
		}
		@media (max-width: 930px){
			section{
				margin-top: 10vw;
			}
			.title {
				padding: 20px 0;
				font-size: 2rem;
			}
			.main > div {
				margin-top: 1.2vw;
			}
			.section4_left {
				width: 17vw;
			}
			.btn1 {
				width: 8vw;
			}
			.btn2 {
				width: 8vw;
			}
		}
		.recaptcha-wrap{

		padding-right: 10px;
		padding: 20px 10px 20px;    text-align: center;
		}
		.recaptcha-wrap span{
			font-size: 12px; color: #df1e36;text-align:center;
		}
	</style>
	<body >

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
				
				</div>
			</header>
			<!-- end: header -->


		<section>
			<div class="main">
				<div class="section1">
					<p class="title" style="text-align: center;">
						<img src="/data/logo/1.png" alt="" style="width:180px;">

					</p>
					<h4>HLB HR SYSTEM ADMIN</h4>
				</div>
				<div class="section2" style="margin-top:50px;">
					<input type="id" name="id" id="inputId" placeholder="아이디를 입력해 주세요">
				</div>
				<div class="section3">
					<input type="password" name="password" id="inputPwd" placeholder="비밀번호를 입력해 주세요">
				</div>
				<div class="section6">
					<button class="btn3" id="btn_login" onclick="func_login()">로그인</button>
				</div>
				<p style="color:;">COPYRIGHT 2020 HLB ALL RIGHTS ARE RESERVED.<br>허가 없는 해당 사이트의 접근은 민,형사상 처벌을 받을 수 있습니다.</p>
			</div>
		</section>
	</body>


	<script type="text/javascript">

		function onSubmit(token){
		$('#validChk').val('Y');
	}
	
		const func_login = (function() {
			var chk = $('#validChk').val();
			var inputId = $.trim($('#inputId').val());
			var inputPwd = $.trim($('#inputPwd').val());
			var inputCaptcha = $.trim($('#inputCaptcha').val());
			if(inputId==""){
				alert("아이디를 입력해주세요");
				return;
			}
			if(inputPwd==""){
				alert("비밀번호를 입력해주세요");
				return;
			}
			
			$.ajax({
				url : "/manage/proc/adminLogin.php",
				data : {
					id : inputId,
					pwd : inputPwd,
					captcha : inputCaptcha
				},
				dataType : "json",
				method : 'POST',
				success : function(result){
					alert(result.msg);
					if(result.code=='TRUE'){
						location.href="/manage/main.php";
					}
				}
			})
		});

		$('#inputPwd').keypress(function(e){
			if(e.keyCode==13){
				func_login();
			}
		})
	</script>
	
	
	


</html>