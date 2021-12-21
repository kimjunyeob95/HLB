<?
    $prev_url = $_SERVER["HTTP_REFERER"];
    $current_url = $_SERVER["PHP_SELF"];
    // echo('<pre>');print_r($_SESSION['mInfo']['salary_auth']);echo('</pre>');
    if( ( strpos($prev_url,"submain4") !== false || strpos($prev_url,"salary") !== false) && strpos($current_url,"salary") == false && strpos($current_url,"submain4") == false) {
        //이전 주소가 급여페이지면서 현재 주소가 급여페이지가 아니면 세션값 삭제
        unset($_SESSION['mInfo']['salary_auth']);
    }else if( (strpos($prev_url,"submain4") !== false || strpos($prev_url,"salary") !== false) && (strpos($current_url,"submain4") !== false || strpos($current_url,"salary") !== false) ){
        //이전 주소가 급여페이지면서 현재 주소가 급여페이지면 세션값 유지
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?=$_SESSION['mInfo']['co_name']?><?if(!empty($_SESSION['mInfo']['co_subname'])){echo $_SESSION['mInfo']['co_subname'];}?></title>
<link rel="stylesheet" type="text/css" href="/@resource/css/style.css">
<link rel="stylesheet" type="text/css" href="/@resource/css/plugin/jquery.bxslider.css">
<link rel="stylesheet" type="text/css" href="/manage/editor/css/froala_style.css">
<link rel="stylesheet" href="/manage/assets/vendor/morris.js/morris.css" />

<script type="text/javascript" src="/@resource/js/jquery-1.11.3.min.js?t=2020110913"></script>
<script type="text/javascript" src="/@resource/js/jquery.uniform.bundled.js"></script>
<script type="text/javascript" src="/@resource/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="/@resource/js/common.js?t=2020110913"></script>
<script type="text/javascript" src="/@resource/js/sonic.js"></script>
<script type="text/javascript" src="/@resource/js/excel.min.js"></script>
<script type="text/javascript" src="/@resource/js/jquery.form.js"></script>

<script type="text/javascript" src="/@resource/js/ckeditorfolder/ckeditor/ckeditor.js"> </script>
<script type="text/javascript" src="/@resource/js/ajax.common.js"></script>

<script type="text/javascript" src="/manage/assets/vendor/morris.js/morris.js"></script>
<script type="text/javascript" src="/manage/assets/vendor/raphael/raphael.js"></script>

<script type="text/javascript" src="/include/fuc.js"></script>
<script src="/manage/assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/manage/@resource/plugin/datepicker/datepicker.js?t=20201109"></script>
<script type="text/javascript" src="/@resource/js/circles.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" href="/manage/@resource/plugin/datepicker/datepicker.css" />

<style>
.datepicker-top-left, .datepicker-top-right{
	    border-top-color: #ef8440;
}
<?
include $_SERVER['DOCUMENT_ROOT'].'/include/var.php';
?>

</style>
</head>
<body>
<a href="#container" class="skip-navi">본문 바로가기</a>