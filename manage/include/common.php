<?
header("Content-Type: text/html; charset=UTF-8");
ini_set('log_errors', 'On');
date_default_timezone_set('Asia/Seoul');

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';

include $_SERVER['DOCUMENT_ROOT'].'/lib/encryption.php';
include $_SERVER['DOCUMENT_ROOT'].'/manage/include/constants.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");
?>