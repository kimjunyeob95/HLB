<?
header("Content-Type: text/html; charset=UTF-8");
ini_set('log_errors', 'On');


// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/encryption.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/mailUtil.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");

session_cache_expire(60);
ini_set('session.cookie_lifetime', 0) ;
ini_set('session.gc_maxlifetime', 6000);
ini_set("session.cache_expire",60);
session_start();

$enc = new encryption();

?>
