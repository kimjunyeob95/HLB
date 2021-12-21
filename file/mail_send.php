<?
date_default_timezone_set('Asia/Seoul');
ini_set("zlib.output_compression", "On");
ini_set("display_errors", 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

$nameFrom = "에이치엘비";
$mailFrom = "wjdwngh1733@naver.com";
$nameTo = "에이치엘비";
$mailTo = "test@naver.com";
$content = "<strong>테스트입니다.</strong>";
$subject = "신규 사원로그인 정보";


$charset = "UTF-8";
$nameFrom = "=?$charset?B?" . base64_encode($nameFrom) . "?=";
$nameTo = "=?$charset?B?" . base64_encode($nameTo) . "?=";
$subject = "=?$charset?B?" . base64_encode($subject) . "?=";
$header = "Content-Type: text/html; charset=utf-8\r\n";
$header.= "MIME-Version: 1.0\r\n";
$header.= "Return-Path: <" . $mailFrom . ">\r\n";
$header.= "From: " . $nameFrom . " <" . $mailFrom . ">\r\n";
$header.= "Reply-To: <" . $mailFrom . ">\r\n";



$result = mail($mailTo, $subject, $content, $header, $mailFrom);

?>