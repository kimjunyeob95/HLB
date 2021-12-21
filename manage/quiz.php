<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : quiz.php
	설     명  : 퀴즈 관리
	 작성일 : 2020-08-07
*****************************************/

@$page = $_REQUEST['page'];				//페이지
@$qType = $_REQUEST['qType'];
@$qcType = $_REQUEST['qcType'];
@$qTitle = $_REQUEST['qTitle'];
@$sDate = $_REQUEST['sDate'];
@$eDate = $_REQUEST['eDate'];



$query="SELECT * FROM tbl_code WHERE cType=1 ";
$ps = pdo_query($db,$query,array());
$favoritList = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($favoritList,$data);
}


$rows = 30;
if(empty($page)){
	$page=1;
}

$where=" WHERE qDelete='FALSE' ";


if(!empty($qTitle)){
	$where.=" and  instr( qTitle, '".$qTitle."') ";
}

if(!empty($qType)){
	$where.=" and qType='".$qType."' ";
}

if(!empty($qcType)){
	$where.=" and qcType='".$qcType."' ";
}

if(!empty($sDate)){
	$where.=" and substring(qSdate,1,10) <='".$sDate."'";
}
if(!empty($eDate)){
	$where.=" and substring(qEdate,1,10) >='".$eDate."'";
}


$query="SELECT COUNT(A.qseq) as cnt FROM tbl_quiz_data as A join  tbl_code as B 
				ON A.qCtype = B.cseq
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
				 tbl_quiz_data as A join  tbl_code as B 
				ON A.qCtype = B.cseq  ";
$query.=$where;
$query.=" ORDER BY qseq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

//Param set
$subquery="&sDate=".urlencode($sDate)."&eDate=".urlencode($eDate)." &qType=".urlencode($qType)."&qcType=".$qcType."&qTitle=".$qTitle;



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
						<h2><i class="fas fa fa-question-circle"></i> 관심사 퀴즈 관리</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="/manage/main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								
								<li><span>관심사 퀴즈 관리</span></li>
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
								<label class="col-md-1 control-label" style="line-height: 30px;">출제 기간</label>
								
									<div class="input-daterange input-group "  data-plugin-datepicker="" data-date-format="yyyy-mm-dd" style="max-width:600px;">
										<span class="input-group-addon" style="border-width:1px;">
											<i class="fa fa-calendar"></i>
										</span>
										<input type="text" readonly="" class="form-control" name="sDate"   value="<?=$sDate?>" id="sDate" placeholder="시작일자" >
										<span class="input-group-addon"> ~ </span>
										<input type="text" readonly="" class="form-control" name="eDate"  value="<?=$eDate?>" id="eDate" placeholder="종료일자" >
								</div>
						</div>

						<div class="form-group col-md-12">
							<label class="col-md-1 control-label" style="line-height: 30px;">퀴즈타입</label>
							<div class="col-md-1">
								<div class="input-daterange input-group col-md-2"  >
									<select class="form-control mb-md" name="qType">
										<option value="">전체</option>
										<option value="C" <?=selected($qType, "C")?> >객관식</option>
										<option value="O"  <?=selected($qType, "O")?> >O/X</option>
										<option value="S"  <?=selected($qType, "S")?> >주관식</option>
									</select>
								</div>
							</div>
							<label class="col-md-1 control-label " style="line-height: 30px;">관심사</label>
							<div class="col-md-1">
								<div class="input-daterange input-group col-md-12"  >
									<select class="form-control mb-md" name="qcType">
										<option value="">전체</option>
										<?for($i=0;$i<sizeof($favoritList);$i++){?>
										<option value="<?=$favoritList[$i]['cseq']?>"  <?=selected($qcType,$favoritList[$i]['cseq'])?>  ><?=$favoritList[$i]['cData']?></option>
										<?}?>
									</select>
								</div>
							</div>
							<label class="col-md-1 control-label " style="line-height: 30px;">퀴즈 내용</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-12"  >
									<input type="text"  class="form-control mb-md" name="qTitle" value="<?=$qTitle?>">
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

								<button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 퀴즈 등록</button>
								<div>
									
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
										<thead>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">No.</th>
											<th class="col-sm-1" style="text-align: center !important;">퀴즈타입</th>
											<th class="col-sm-2" style="text-align: center !important;">관심사</th>
											<th class="col-sm-2" style="text-align: center !important;">출제기간</th>
											<th class="col-sm-5" style="text-align: center !important;">퀴즈</th>
											<th class="col-sm-4" style="text-align: center !important;">보기/정답</th>
											<th class="col-sm-2" style="text-align: center !important;"></th>
										</thead>
										<tbody>
										<? if(isset($list) && count($list) > 0) { 
												for($i=0;$i<sizeof($list);$i++){
													?>
												<tr>
											<td class="center"><?=$numbering?></td>
											<td class="center">
												<?if($list[$i]['qType']=="C"){?>객관식<?}?>
												<?if($list[$i]['qType']=="O"){?>O/X<?}?>
												<?if($list[$i]['qType']=="S"){?>주관식<?}?>
											</td>
											<td class="center"><?=$list[$i]['cData']?></td>
											<td class="center"><?=substr($list[$i]['qSdate'],0,10)?> ~ <?=substr($list[$i]['qEdate'],0,10)?></td>
											<td class="left"><?=$list[$i]['qTitle']?></td>
											<td class="center">
											<?if($list[$i]['qType']=="C"){?>
												<?if($list[$i]['qQanswerNum']=="1"){?><strong style="color:#0d1cfc;font-size:14px;"><?}?>
												1. <?=$list[$i]['qAnswer1']?>  	<?if($list[$i]['qQanswerNum']=="1"){?></strong><?}?>/ 
											
												
												<?if($list[$i]['qQanswerNum']=="2"){?><strong style="color:#0d1cfc;font-size:14px;"><?}?>
												2. <?=$list[$i]['qAnswer2']?>  <?if($list[$i]['qQanswerNum']=="2"){?></strong><?}?> / 
												
												<?if($list[$i]['qQanswerNum']=="3"){?><strong style="color:#0d1cfc;font-size:14px;"><?}?>
												3. <?=$list[$i]['qAnswer3']?>   <?if($list[$i]['qQanswerNum']=="3"){?></u></strong><?}?>/  

												<?if($list[$i]['qQanswerNum']=="4"){?><strong style="color:#0d1cfc;font-size:14px;"><?}?>
												4. <?=$list[$i]['qAnswer4']?> <?if($list[$i]['qQanswerNum']=="4"){?></strong><?}?>

											<?}?>

											<?if($list[$i]['qType']=="S"){?>
													<?=$list[$i]['qQanswer']?>
											<?}?>

											<?if($list[$i]['qType']=="O"){?>
												 <?=$list[$i]['aAnswerOx']?>
												<?if($list[$i]['aAnswerOx']=="X"){?> / <?=$list[$i]['qQanswer']?> <?}?>
											<?}?>

											</td>
											<td class="center">
												<button class="btn  btn-xs btn-modify-quiz " seq="<?=$list[$i]['qseq']?>" ><i class="fas fa fa-edit"></i> 수정하기</button>
												<button class="btn  btn-danger btn-xs btn-delete-quiz " seq="<?=$list[$i]['qseq']?>" ><i class="fas fa fa-trash-o"></i> 삭제하기</button>
											</td>
												
											</tr>
											<?
												$numbering--;
												}
											}else{?>
											<tr>
												<td colspan="6" class="center hidden-xs">검색된 데이터가 없습니다. <br/>검색 조건을 다시 확인하시고 검색해 주세요</td>
											</tr>
											<?}?>
										</tbody>
								</table>
								<div class="col-sm-12 col-md-12">
									<div class="dataTables_paginate paging_bs_normal center" id="datatable-default_paginate22">
									<?=get_page_html_for_admin($page, $total_rows, $rows,10,$subquery)?>
									</div>
									<button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 퀴즈 등록</button>
								</div>
							</div>
			</section>
		</div>

	</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>
<script>
var page ="<?=$page?>";
var subquery = "<?=$subquery?>";


$('#btn-re-research').click(function(e){
	e.preventDefault();
	location.href="/manage/quiz.php";
});


$('.btn-add-new').click(function(e){
	e.preventDefault();
	location.href="/manage/quizDetail.php?page="+page+subquery;
});

$('.btn-modify-quiz').click(function(e){
	e.preventDefault();
	var seq = $(this).attr('seq');
	location.href="/manage/quizDetail.php?seq="+seq+"&page="+page+subquery;
});

$('.btn-delete-quiz').click(function(e){
	e.preventDefault();
	var seq = $(this).attr('seq');
	if(confirm("정말로 해당 퀴즈를 삭제하시겠습니까?")){
		$.ajax({
			url : "/manage/proc/quizDelete.php",
			method : "post",
			dataType : "json",
			data :{
				qseq : seq
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
