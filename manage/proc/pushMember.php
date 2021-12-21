<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}

include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");


@$pTitle = $_REQUEST['pTitle'];
@$pMsg = $_REQUEST['pMsg'];
@$seq = $_REQUEST['seq'];

if($pTitle =="" || $pMsg=="" || $seq==""){
	exit;
}


$url = "https://fcm.googleapis.com/fcm/send";
$serverKey="AAAAkdMX1uA:APA91bFK71zkGFTrsXWd4MMy0GvfEqDHBnI91HdVZImKzDHhFJvg02CitqQ352xVC-huUtM1NmIV9TAEvGebxvH23U2mHWlPsmC7hH_8nRUk_aiHPUEvEKudt0nQial6ljLQZOEu5onL";
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key='. $serverKey;

$query="select deviceToken from tbl_device as A join tbl_member as B on A.deviceMseq=B.mseq
where B.mIsDelete='FALSE' AND B.mPush='TRUE'  and  deviceMseq= ?";
$ps = pdo_query($db,$query,array($seq));

//$pMsg=nl2br($pMsg);

while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	$token = $data['deviceToken'];
	$notification = array('title' =>$pTitle , 'body' => $pMsg, 'sound' => 'default', 'badge' => '1');
	$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
	$json = json_encode($arrayToSend);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	//Send the request
	$response = curl_exec($ch);
	//Close request
	if ($response === FALSE) {
	die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);
}





?>