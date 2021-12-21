<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : god.php
	설     명  : 주님 관리
	 작성일 : 2020-08-07
*****************************************/

@$page = $_REQUEST['page'];				//페이지
@$gName = $_REQUEST['gName'];
@$gManName = $_REQUEST['gManName'];

$rows = 30;
if(empty($page)){
	$page=1;
}

$where=" WHERE gIsDel='FALSE' ";


if(!empty($gName)){
	$where.=" and  instr( gName, '".$gName."') ";
}

if(!empty($gManName)){
	$where.=" and gManName='".$gManName."' ";
}


$query="SELECT COUNT(gseq) as cnt FROM tbl_god 
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
				 tbl_god ";
$query.=$where;
$query.=" ORDER BY gseq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

//Param set
$subquery="&gName=".urlencode($gName)."&gManName=".urlencode($gManName);



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
						<h2>   <img src="/manage/@resource/jesus.png" alt="" style="width:25px;margin-top:-5px;"> 주님 관리</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="/manage/main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								
								<li><span>주님 관리</span></li>
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
							<label class="col-md-1 control-label" style="line-height: 30px;">회사명</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-3"  >
									<input type="text"  class="form-control mb-md" name="gName" value="<?=$gName?>">
								</div>
							</div>
							
							<label class="col-md-1 control-label " style="line-height: 30px;">담당자명</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-12"  >
									<input type="text"  class="form-control mb-md" name="gManName" value="<?=$gManName?>">
								</div>
							</div>
						
							


							<div class="col-md-3" >
							<button tabindex="-1" class="btn btn-primary" type="submit">검색하기 <i class="fa fa-search"></i> </button>
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
								<button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 광고주 등록</button>
								<div>
									
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
										<thead>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">No.</th>
											<th class="col-sm-1" style="text-align: center !important;">회사명</th>
											<th class="col-sm-1" style="text-align: center !important;">담당자명</th>
											<th class="col-sm-2" style="text-align: center !important;">연락처</th>
											<th class="col-sm-2" style="text-align: center !important;">이메일주소</th>
											<th class="col-sm-2" style="text-align: center !important;">사업자번호</th>
											<th class="col-sm-3" style="text-align: center !important;">등록일</th>
											<th class="col-sm-2" style="text-align: center !important;"></th>
										</thead>
										<tbody>
										<? if(isset($list) && count($list) > 0) { 
												for($i=0;$i<sizeof($list);$i++){
													?>
											<tr>
											<td class="center"><?=$numbering?></td>
											<td class="center">
												<?=$list[$i]['gName']?>
											</td>
											<td class="center">
													<?=$list[$i]['gManName']?>
											</td>
											<td class="center">
												<?=$list[$i]['gTel']?>
											</td>
											<td class="center">
												<?=$list[$i]['gMail']?>
											</td>
											<td class="center">
												<?if(!empty($list[$i]['gFile1'])){?> <a href="/data/god/<?=$list[$i]['gFile1']?>" target="_blank"> <?=$list[$i]['gNumber']?> <i class="fa fas fa-download"></i> </a><?}else{?>
														<?=$list[$i]['gNumber']?>
												<?}?>
											</td>
											<td class="center">
												<?=$list[$i]['gRegdate']?>
											</td>
											<td class="center">
												<button class="btn  btn-xs btn-modify-god " seq="<?=$list[$i]['gseq']?>" ><i class="fas fa fa-edit"></i> 수정하기</button>
												<button class="btn  btn-danger btn-xs btn-delete-god " seq="<?=$list[$i]['gseq']?>" ><i class="fas fa fa-trash-o"></i> 삭제하기</button>
											</td>
											</tr>
											<?
												$numbering--;
												}
											}else{?>
											<tr>
												<td colspan="7" class="center hidden-xs">검색된 데이터가 없습니다. <br/>검색 조건을 다시 확인하시고 검색해 주세요</td>
											</tr>
											<?}?>
										</tbody>
								</table>
								<div class="col-sm-12 col-md-12">
									<div class="dataTables_paginate paging_bs_normal center" id="datatable-default_paginate22">
									<?=get_page_html_for_admin($page, $total_rows, $rows,10,$subquery)?>
									</div>
								</div>
								<button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 광고주 등록</button>
							</div>
			</section>
		</div>

	</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>
var $subquery = "<?=$subquery?>";
var page = "<?=$page?>";


$('.btn-add-new').click(function(e){
	e.preventDefault();
	location.href="/manage/godDetail.php?page="+page+$subquery;
});


$('.btn-modify-god').click(function(e){
	e.preventDefault();
	var seq = $(this).attr('seq');
	location.href="/manage/godDetail.php?seq="+seq+"&page="+page+$subquery;
});

$('.btn-delete-god').click(function(e){
	e.preventDefault();
	var seq = $(this).attr('seq');
	if(confirm("정말로 해당 광고주를 삭제하시겠습니까?")){
		if(confirm("광고주를 삭제하게 되면 해당 광고도 모두 함께 삭제됩니다.\n그래도 삭제 하시겠습니까?")){
			$.ajax({
				url : "/manage/proc/godDelete.php",
				dataType : "json",
				method : "post",
				data :  {
					seq : seq
				},
				success  : function(result){
					if(result.code=='FALSE'){
						alert(result.msg);
						return;
					}else{
						alert("광고주 정보가 삭제되었습니다.");
						location.reload();
					}
				}
			})
		}
	}
});

</script>