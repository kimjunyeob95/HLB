<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : member.php
	설     명  : 회원 관리
	 작성일 : 2020-08-07
*****************************************/

@$page = $_REQUEST['page'];				//페이지
@$mType = $_REQUEST['mType'];
@$mNick = $_REQUEST['mNick'];
@$dType = $_REQUEST['dType'];



$query="SELECT * FROM tbl_member ";
$ps = pdo_query($db,$query,array());
$favoritList = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($favoritList,$data);
}


$rows = 30;
if(empty($page)){
	$page=1;
}

$where=" WHERE mIsDelete='FALSE' ";


if(!empty($mNick)){
	$where.=" and  instr( mNick, '".$mNick."') ";
}

if(!empty($mType)){
	$where.=" and mType='".$mType."' ";
}

if(!empty($dType)){
	$where.=" and deviceOs='".$dType."' ";
}


$query="SELECT COUNT(deviceSeq) as cnt FROM  tbl_device as A join tbl_member as B on A.deviceMseq = B.mseq 
				 ".$where;
$ps = pdo_query($db,$query,array());

$data = $ps->fetch(PDO::FETCH_ASSOC);
$total_rows = $data['cnt'];
if ($total_rows > 0) {
	$total_page = ceil($total_rows / $rows);
} else {
	$total_page = 1;
}

$from = ($page - 1) * $rows;
$numbering = $total_rows - $from;


$query="SELECT * FROM 
				 tbl_device as A join tbl_member as B on A.deviceMseq = B.mseq  ";
$query.=$where;
$query.=" ORDER BY mseq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

//Param set
$subquery="&mType=".urlencode($mType)."&mNick=".$mNick."&dType=".$dType;




?>


<section class="body">

	<!-- start: header -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_header.php'; ?>
	<!-- end: header -->

	<div class="inner-wrapper">
		<!-- start: sidebar -->
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_gnb_menu.php'; ?>
		<!-- end: sidebar -->

		<section role="main" class="content-body">
					<header class="page-header">
						<h2> <i class="fas fa fa-mobile-alt"></i> 기기 관리</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="/manage/main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								
								<li><span>기기 관리</span></li>
							</ol>
						</div>
					</header>
		<!-- start: page -->
		<div class="row ">
			
				<section class="panel panel-featured col-md-12 " style="border:none;">
				<header class="panel-heading" >
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
					</div>

					<h2 class="panel-title">검색</h2>
				</header>
				<div class="panel-body  col-md-12 " style="display: block;">
						<form class="form-horizontal" id="form_proccess" action="<?= $_SERVER['PHP_SELF']?>" method="get">

					

						<div class="form-group col-md-12">
							<label class="col-md-1 control-label" style="line-height: 30px;">회원타입</label>
							<div class="col-md-1">
								<div class="input-daterange input-group col-md-2"  >
									<select class="form-control mb-md" name="mType">
										<option value="">전체</option>
										<option value="K" <?=selected($mType, "K")?> >카카오</option>
										<option value="F"  <?=selected($mType, "F")?> >페이스북</option>
										<option value="N"  <?=selected($mType, "N")?> >네이버</option>
										<option value="G"  <?=selected($mType, "G")?> >구글</option>
										<option value="A"  <?=selected($mType, "A")?> >애플</option>
									</select>
								</div>
							</div>

							<label class="col-md-1 control-label" style="line-height: 30px;">OS</label>
							<div class="col-md-1">
								<div class="input-daterange input-group col-md-2"  >
									<select class="form-control mb-md" name="dType">
										<option value="">전체</option>
										<option value="A" <?=selected($dType, "A")?> >안드로이드</option>
										<option value="I"  <?=selected($dType, "I")?> >iOS</option>
									</select>
								</div>
							</div>
							
							<label class="col-md-1 control-label " style="line-height: 30px;">닉네임</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-12"  >
									<input type="text"  class="form-control mb-md" name="mNick" value="<?=$mNick?>">
								</div>
							</div>
						
							


							<div class="col-md-3" >
							<button tabindex="-1" class="btn btn-primary" type="submit"> 검색하기 <i class="fa fa-search"></i> </button>
							<button class="btn " id="btn-re-research">검색 초기화 <i class="fa fa-refresh"></i></button>
							
						</div>
					
						</div>
					</form>
				</div>
			</section>

		</div>
		<div class="row">
				<section class="panel">
							<div class="panel-heading">
								검색 결과 :  <span style="color:#0d1cfc;font-weight:bold;"><?=number_format($total_rows)?></span> 건

								<div>
									
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
										<thead>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">No.</th>
											
											<th class="col-sm-2" style="text-align: center !important;">OS</th>
											<th class="col-sm-2" style="text-align: center !important;">deviceId</th>
											<th class="col-sm-2" style="text-align: center !important;">기기 등록일시</th>
											
											<th class="col-sm-1" style="text-align: center !important;">회원타입</th>
											<th class="col-sm-4" style="text-align: center !important;">ID</th>
											<th class="col-sm-2" style="text-align: center !important;">EMAIL</th>
											<th class="col-sm-1" style="text-align: center !important;">프로필</th>
											<th class="col-sm-2" style="text-align: center !important;">닉네임</th>
											<th class="col-sm-1" style="text-align: center !important;">성별</th>
											
										</thead>
										<tbody>
										<? if(isset($list) && count($list) > 0) { 
												for($i=0;$i<sizeof($list);$i++){
													?>
											<tr>
											<td class="center"><?=$numbering?></td>

											<td class="center">
												<?if($list[$i]['deviceOs']=="A"){?> <i class="fa fas fa-android"></i> 안드로이드<?}?>
												<?if($list[$i]['deviceOs']=="I"){?> <i class="fa fas fa-apple"></i> iOs<?}?>
											</td>
											<td class="center">
												<?=$list[$i]['deviceId']?>
											</td>
											<td class="center"><?=$list[$i]['deviceRegdate']?></td>
											

											<td class="center">
													<? if($list[$i]['mType']=="K"){?>카카오<?}?> 
													<? if($list[$i]['mType']=="F"){?> 페이스북<?}?> 
													<? if($list[$i]['mType']=="N"){?>네이버<?}?> 
													<? if($list[$i]['mType']=="A"){?>애플<?}?> 
													<? if($list[$i]['mType']=="G"){?>구글<?}?> 
											</td>
											<td class="center"><?=$list[$i]['mId']?></td>
											<td class="center" ><p class="view-member-qq" seq="<?=$list[$i]['mseq']?>"><?=$list[$i]['mEmail']?></p></td>
											<td  class="center">
												<?if(!empty($list[$i]['mProfile'])){?>
												<img src="/data/profile<?=$list[$i]['mProfile']?>" alt="" style="width:35px;height:35px;border-radius:30px;cursor:pointer;" onerror="$(this).remove();" class="onHonverImg">
												<?}else{?>
												<img src="/data/defualt_icon.png" alt="" style="width:35px;height:35px;border-radius:30px;">
												<?}?>
											</td>
											<td class="center">
												<p class="view-member-qq" seq="<?=$list[$i]['mseq']?>" ><?=$list[$i]['mNick']?></p>
											</td>
											<td class="center">
												<?if($list[$i]['mGender']=="M"){?>남성<?}?>
												<?if($list[$i]['mGender']=="F"){?>여성<?}?>
												<?if($list[$i]['mGender']=="N"){?>-<?}?>
											</td>

										
											
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
								<div class="col-sm-12 col-md-12">
									<div class="dataTables_paginate paging_bs_normal center" id="datatable-default_paginate22">
									<?=get_page_html_for_admin($page, $total_rows, $rows,10,$subquery)?>
									</div>
								</div>
							</div>
			</section>
		</div>

	</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>
var $subquery = "<?=$subquery?>";
var page = "<?=$page?>";

$('#btn-re-research').click(function(e){
	e.preventDefault();
	location.href="/manage/device.php";
});

jQuery.viewImage({
  'target': '.onHonverImg'
});


</script>