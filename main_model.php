<?
@$coseq = $_SESSION['mInfo']['mc_coseq'];
$addquery='';
//공지사항
$query="select * from tbl_noti_page where tn_is_del = 'F' and tn_status = 'T' and tn_topshow = 'F' {$addquery} and tn_coseq = {$coseq} order by tn_regdate desc limit 8";
$ps = pdo_query($db,$query,array());
$noticeList = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($noticeList,$data);
}


?>