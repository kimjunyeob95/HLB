<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?

@$page = $_REQUEST['page'];
@$searchTitle = $_REQUEST['searchTitle'];


$rows = 30;
if(empty($page)){
	$page=1;
}

$where=" WHERE 1=1 and nDel='FALSE'";

if(!empty($searchTitle)){
	$where.= " and instr( nTitle,'".$searchTitle."') ";
}


$query="SELECT COUNT(nseq ) as cnt FROM tbl_notice 
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
				 tbl_notice ";
$query.=$where;
$query.=" ORDER BY nseq desc limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

//Param set
$subquery="&searchTitle=".urlencode($searchTitle);


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
				<h2>공지 관리</h2>
			
				<div class="right-wrapper pull-right">
					<ol class="breadcrumbs">
						<li>
							<a href="/manage/main.php">
								<i class="fa fa-home"></i>
							</a>
						</li>
						
						<li><span>공지 관리</span></li>
					</ol>
				</div>
			</header>
			<!-- start: page -->
			<div class="row">
				<section class="panel panel-featured col-md-12 " style="border:none;">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
						</div>

						<h2 class="panel-title">검색</h2>
					</header>
					<div class="panel-body  col-md-12 " style="display: block;">
						<form class="form-horizontal" id="form_proccess" action="/manage/notice.php" method="get">

							<div class="form-group col-md-3">
								<label class="col-md-4 control-label" style="line-height: 20px;">제목</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="searchTitle" id="searchTitle" value="<?=$searchTitle?>">
								</div>
							</div>
							
							<div class="col-md-2" >
								<button tabindex="-1" class="btn btn-primary " type="submit"><i class="fa fa-search"></i>검색하기</button>
							</div>
						
						</form>
					</div>
				</section>
			</div>

			<div class="row">
				<section class="panel">
					<div class="panel-heading">
						검색 결과 :  <span style="color:#0d1cfc;font-weight:bold;"><?=number_format($total_rows)?></span> 건
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
								<thead>
								<tr>
									<th class="col-sm-1" style="text-align: center !important;">No.</th>
									<th class="col-sm-2" style="text-align: center !important;">제목</th>
									<th class="col-sm-1" style="text-align: center !important;">작성일시</th>
									<th class="col-sm-1" style="text-align: center !important;">처리</th>
								</tr>
							</thead>
							<tbody>
							<? if(isset($list) && count($list) > 0) { 
								for($i=0;$i<sizeof($list);$i++){?>
								<tr>
									<td class="center"><?=$numbering?></td>
									<td class="center" ><?=$list[$i]['nTitle']?></td>
									<td class="center" ><?=$list[$i]['nRegdate']?></td>
									<td class="center">
										<button class="btn btn-xs  btn-detail" seq="<?=$list[$i]['nseq']?>"><i class="fa fa-edit"></i> 수정하기</button>
									</td>
								</tr>
							<?
								$numbering--;
								}
							}else{?>
								<tr>
									<td colspan="4" class="center hidden-xs">검색된 데이터가 없습니다. <br/>검색 조건을 다시 확인하시고 검색해 주세요</td>
								</tr>
							<?}?>
							</tbody>
						</table>

						<div class="col-md-12" style="text-align:right; margin-top: 1vw;">
							<button tabindex="-1" class="btn btn-primary" type="button" onclick="location.href='/manage/notice_detail.php'" ><i class="fa fa-edit"></i>등록</button>
						</div>
						
						<div class="col-sm-12 col-md-12">
							<div class="dataTables_paginate paging_bs_normal center" id="datatable-default_paginate22">
							<?=get_page_html_for_admin($page, $total_rows, $rows,10,$subquery)?>
							</div>
						</div>
								
					</div>
				</section>
				
			</div>

		</section>
				

	</div>


</section>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script type="text/javascript">
	$('.btn-detail').click(function(e){
		e.preventDefault();
		var seq = $(this).attr('seq');
		location.href="/manage/notice_detail.php?nseq="+seq;
	});
</script>