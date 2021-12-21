<?
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/auth_check.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/var.php';
?>


<!doctype html>
<html class="fixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling webkit chrome win js no-mobile-device custom-scroll">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>HLB HR SYSTEM | 관리자</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="">
		<script src="https://kit.fontawesome.com/900457b11f.js" crossorigin="anonymous"></script>

		<!-- 파비콘 -->
		<!--
		<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="assets/images/icon.png" type="image/x-icon" />
		-->

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
        

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/owl.carousel/assets/owl.carousel.css" />
		<link rel="stylesheet" href="assets/vendor/owl.carousel/assets/owl.theme.default.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css?t=2020102612" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
		
				<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/morris.js/morris.css" />
		<link rel="stylesheet" href="assets/vendor/chartist/chartist.min.css" />
		
		
		

		<!-- 에디터 -->
		<!-- <link href="assets\vendor\summernote\summernote.css" rel="stylesheet">
		<script src="assets\vendor\summernote\summernote.js"></script> -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		
		<script type="text/javascript" src="/@resource/js/ckeditorfolder/ckeditor/ckeditor.js"> </script>
        <script src="/manage/js/view-image.js"></script>
        <script type="text/javascript" src="/@resource/js/ajax.common.js"></script>
        <script type="text/javascript" src="/include/fuc.js"></script>
		
		
        <script type="text/javascript" src="assets/vendor/morris.js/morris.js"></script>
        <script type="text/javascript" src="assets/vendor/raphael/raphael.js"></script>
        <script type="text/javascript" src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

		<!-- Sortable -->
		 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<style type="text/css">
			.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{padding:6px;}
			body{font-size:13px;}
		</style>	




	</head>
	<body>
	<script>
		// Maintain Scroll Position
		if (typeof localStorage !== 'undefined') {
			if (localStorage.getItem('sidebar-left-position') !== null) {
				var initialPosition = localStorage.getItem('sidebar-left-position'),
					sidebarLeft = document.querySelector('#sidebar-left .nano-content');
				
				sidebarLeft.scrollTop = initialPosition;
			}
		}
		function reulReturner(label) {
			var strGA = 44032; //가
			var strHI = 55203; //힣
			var lastStrCode = label.charCodeAt(label.length-1);
			var prop=true;
			var msg;

			/* 괄호 문자열 예외 처리 김준엽 201123 */
			if(label == '직장(학교명)') return mag = label+'을';

			if(lastStrCode < strGA || lastStrCode > strHI) {
				return false; //한글이 아님
			}

			if (( lastStrCode - strGA ) % 28 == 0) prop = false;
			if(prop) {
			msg = label+'을';
			}
			else {
			msg = label+'를';
			}

			return msg;
		}
	</script>


