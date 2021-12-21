<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?

@$page = $_REQUEST['page'];				//페이지
@$bmTitle = $_REQUEST['bmTitle'];
@$bmType = $_REQUEST['bmType'];



$rows = 30;
if(empty($page)){
	$page=1;
}

$where=" WHERE bmIsDel='FALSE' ";

if(!empty($bmTitle)){
	$where.=" and  instr( bmTitle, '".$bmTitle."') ";
}



if(!empty($bmType)){
	$where.=" and  bmType='".$bmType."'";
}




$query="SELECT COUNT(A.bmseq) as cnt FROM tbl_banner_main as A 
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
				 tbl_banner_main  ";
$query.=$where;
$query.=" ORDER BY bmRegdate desc, bmseq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

//Param set
$subquery="&bmTitle=".$bmTitle."&bmType=".$bmType;





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
						<h2><i class="fas fa fa-external-link-alt"></i> 배너 관리</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="/manage/main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								
								<li><span>배너 관리</span></li>
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
							
						
								<label class="col-md-1 control-label " style="line-height: 30px;">배너위치</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-12"  >
									<select class="form-control mb-md" name="bmType">
										<option value="">전체</option>
										
										<option value="PM"  <?=selected($bmType,"PM")?>  >ESS 메인 상단</option>
										<option value="CM"  <?=selected($bmType,"CM")?>  >ESS 메인 중앙</option>
										
									</select>
								</div>
							</div>
							<label class="col-md-1 control-label " style="line-height: 30px;">배너 제목</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-12"  >
									<input type="text"  class="form-control mb-md" name="bmTitle" value="<?=$bmTitle?>">
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

								<button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 배너 등록</button>
								<div>
									
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
										<thead>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">No.</th>
											<th class="col-sm-2" style="text-align: center !important;">배너위치</th>
											<th class="col-sm-2" style="text-align: center !important;">배너</th>
											<th class="col-sm-3" style="text-align: center !important;">배너 이미지</th>
											<th class="col-sm-3" style="text-align: center !important;">URL</th>
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
												<?if($list[$i]['bType']=="1"){?>	메인배너<?}?>
												<?if($list[$i]['bType']=="2"){?>	동영상 광고 배너<?}?>
												<?if($list[$i]['bType']=="3"){?>	관심사 퀴즈 배너<?}?>
											</td>
											<td class="center">
												<?=$list[$i]['bTitle']?><br/><br/>
												<a href="<?=$list[$i]['bLink']?>" target="_blank">광고 링크 ></a>
											</td>
											<td class="center">
												<img src="/data/banner/<?=$list[$i]['bImage']?>" alt="" style="width:320px;cursor:pointer;" class="onHonverImg">
											</td>
											<td class="center">
												<?=substr($list[$i]['bSdate'],0,10)?> ~ <?=substr($list[$i]['bEdate'],0,10)?>

											</td>
											<td class="center">
												<button class="btn  btn-xs btn-modify-quiz " seq="<?=$list[$i]['bseq']?>" ><i class="fas fa fa-edit"></i> 수정하기</button>
												<button class="btn  btn-danger btn-xs btn-delete-quiz " seq="<?=$list[$i]['bseq']?>" ><i class="fas fa fa-trash-o"></i> 삭제하기</button>
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
									<button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 배너 등록</button>
								</div>
							</div>
			</section>
		</div>

	</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>


<script>
var $subquery = "<?=$subquery?>";
var page = "<?=$page?>";

jQuery.viewImage({
  'target': '.onHonverImg'
});


$('#btn-re-research').click(function(e){
	e.preventDefault();
	location.href="/manage/banner.php";
});


$('.btn-add-new').click(function(e){
	e.preventDefault();
	location.href="/manage/bannerDetail.php?page="+page+$subquery;
});

$('.btn-modify-quiz').click(function(e){
	e.preventDefault();
	var seq = $(this).attr('seq');
	location.href="/manage/bannerDetail.php?seq="+seq+"&page="+page+$subquery;
});


$('.btn-delete-quiz').click(function(e){
	e.preventDefault();
	var seq = $(this).attr('seq');
	if(confirm("정말로 해당 배너를 삭제하시겠습니까?")){
		$.ajax({
			url : "/manage/proc/bannerDelete.php",
			method : "post",
			dataType : "json",
			data :{
				seq : seq
			},success : function(result){
				if(result.code=='FALSE'){
					alert(result.msg);
					return;
				}else{
					alert("삭제되었습니다");
					location.reload();
				}
			}
		});
	}
	
});

</script>

