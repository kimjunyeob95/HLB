<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}


header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=utf-8");  
header( "Content-Disposition: attachment; filename = change_list_".date('Ymd').".xls" );   
header( "Content-Description: PHP4 Generated Data" );  


include $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/lib.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

@$page = $_REQUEST['page'];				//페이지
@$sdate = $_REQUEST['sdate'];				//페이지
@$edate = $_REQUEST['edate'];				//페이지
@$sGender=$_REQUEST['sGender'];				//검색어
@$sPhone=$_REQUEST['sPhone'];		//검색타입
@$sChange = $_REQUEST['sChange']; 



$where=" WHERE 1=1 ";

if(!empty($sPhone)){
	$where.= " and instr( mPhone,'".$sPhone."')  ";
}

if(!empty($sGender)){
	$where.=" and mGender='".$sGender."' ";
}
if(!empty($sChange)){
	if($sChange!="G"){
		$where.=" and umchange='".$sChange."' ";
	}else{
		$where.=" and ( umchange='".$sChange."' or  umchange='E') ";
	}
}


if(!empty($sdate)){
	$where.=" and  substring(umRegdate,1,10) >= '".$sdate."' ";
}

if(!empty($edate)){
	$where.=" and  substring(umRegdate,1,10) <= '".$edate."' ";
}


$query="SELECT * FROM 
				tbl_pont_use as A join tbl_member as B 
				on A.umseq= B.mseq ";
$query.=$where;
$query.=" ORDER BY umRegdate DESC ";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

$numbering= sizeof($list);

?>
<meta content="application/vnd.ms-excel; charset=UTF-8\" name="Content-type\">
<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
	<thead>
	<tr>
		<th style="background-color:#f0f0f0;">No.</th>
		<th style="background-color:#f0f0f0;">신청일자</th>
		<th style="background-color:#f0f0f0;">신청시각</th>
		<th style="background-color:#f0f0f0;">교환처</th>
		<th style="background-color:#f0f0f0;">교환타입</th>
		<th style="background-color:#f0f0f0;">쿠폰번호</th>
		<th style="background-color:#f0f0f0;">휴대폰번호</th>
		<th style="background-color:#f0f0f0;">이름</th>
		<th style="background-color:#f0f0f0;">성별</th>
		<th style="background-color:#f0f0f0;">지역</th>
	</tr>
</thead>
<tbody>
	<? if(isset($list) && count($list) > 0) { 
	for($i=0;$i<sizeof($list);$i++){
		$coupon_no_use  = $list[$i]['coupon_no_use'];
		$coupon_number="";
		if($coupon_no_use>0){
			$query="SELECT coupon_no FROM tbl_coupon_no WHERE seq=?";
			$ps2 = pdo_query($db,$query,array($coupon_no_use));
			$cData = $ps2->fetch(PDO::FETCH_ASSOC);
			$coupon_number = $cData['coupon_no'];
		}
	?>
	<tr>
		<td class="center"><?=$numbering?></td>
		<td class="center"><?=substr($list[$i]['umRegdate'],0,10)?></td>
		<td class="center"><?=substr($list[$i]['umRegdate'],11,8)?></td>
		<td class="center">
			<?if($list[$i]['umchange']=="C"){?>CU<?}?>
			<?if($list[$i]['umchange']=="G"){?>GS25<?}?>
			<?if($list[$i]['umchange']=="S"){?>세븐일레븐<?}?>
			<?if($list[$i]['umchange']=="E"){?>GS25<?}?>
		</td>
		<td class="center">
			<?if($list[$i]['umPoint']=="500"){?>
				포인트 
			<?}else{?>
				쿠폰
				<?if($list[$i]['umchange']=="E"){?>
						(교환처 미입력)
				<?}?>
			<?}?>
		</td>
		<td><?=$coupon_number?></td>
		<td class="center">
			<?=substr($list[$i]['mPhone'],0,3)?>-<?=substr($list[$i]['mPhone'],3,4)?>-<?=substr($list[$i]['mPhone'],7,4)?>
		</td>
		<td class="center"><?=$list[$i]['mName']?></td>
		<td class="center">
			<?if($list[$i]['mGender']=="M"){?>
				남
			<?}else{?>
				여
			<?}?>
		</td>
		<td class="center"><?=$list[$i]['mArea']?></td>
		
	</tr>
	<?
	$numbering--;
	}
	}else{?>
	<tr>
		<td colspan="10" class="center hidden-xs">검색된 데이터가 없습니다. <br/>검색 조건을 다시 확인하시고 검색해 주세요</td>
	</tr>
	<?}?>
</tbody>
</table>