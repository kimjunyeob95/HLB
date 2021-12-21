<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : godDetail.php
	설     명  : 광고주 관리
	 작성일 : 2020-08-07
*****************************************/
$upload_max_filesize = ini_get('upload_max_filesize'); //php 파일 업로드 크기

@$page = $_REQUEST['page'];				//페이지
@$seq = $_REQUEST['seq'];
@$bmTitle = $_REQUEST['bmTitle'];
@$bmType = $_REQUEST['bmType'];





$title="수정";
if(empty($seq)){
	$title="등록";
}else{

	if(!is_numeric($seq)){
		page_move('/manage/',"잘못된 접근입니다.");
		exit;
	}
	$query="SELECT COUNT(bmseq) as cnt, A.*  FROM 
				 tbl_banner_main as A";
	$query.=" WHERE bmseq=?  and bmIsDel='FALSE' ";
	$ps = pdo_query($db, $query, array($seq));
	$data = $ps->fetch(PDO::FETCH_ASSOC);
	
	if($data['cnt']<1){
			page_move('/manage/banner.php',"삭제되었거나 존재하지 않는 배너 입니다.");
		exit;
	}
}


$subquery="&bmTitle=".urlencode($bmTitle)."&bmType=".$bmType;


?>


<style>
th{background:#eee;}
.itext{
	font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
	height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
	border-radius: 4px;
}

input{
border: 1px solid #E5E7E9;
    border-radius: 6px;
    height: 40px;
    padding: 2px;
    outline: none;
}

input[type=radio]{
	vertical-align: middle;
}
label{vertical-align:sub;}
.fa-warning{color:#ff9900;}

</style>
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
								
								<li><span>회원 관리</span></li>
							</ol>
						</div>
					</header>
			<!-- start: page -->
			<div class="row ">
				<section class="panel col-sm-10">
							<div class="panel-heading">
										<h2 class="panel-title"></h2>
											<div class="panel-actions">
										<button class="btn btn-xs btn-go-list btn-primary"><i class="fas fa fa-list"></i> 목록으로</button>
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>

									</div>

									<h2 class="panel-title"><?=$title?></h2>
							</div>
							<div class="panel-body">
								<form name="quizForm" id="quizForm" method="post"  enctype="multipart/form-data" action="/manage/proc/bannerProc.php">
								<input type="hidden" name="page" value="<?=$page?>">
								<input type="hidden" name="bmType" value="<?=$bmType?>">
								<input type="hidden" name="bmTitle" value="<?=$bmTitle?>">
								<input type="hidden" name="bseq" id="bmseq" value="<?=$data['bmseq']?>">
								<table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
										
										<tr>
										
											<th class="col-sm-1" style="text-align: center !important;">배너명</th>
											<td class="col-sm-11"  style="text-align: center !important;" colspan="3">
												<input type="text" id="bmTitle2" name="bmTitle2"  value="<?=$data['bmTitle2']?>" class="form-control"> 
											</td>
										<tr>
											<tr>
										
											<th class="col-sm-1" style="text-align: center !important;">서브 타이틀</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="bmSubTitle" name="bmSubTitle"  value="<?=$data['bmSubTitle']?>" class="form-control"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">타겟</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<select name="bmTarget" class="form-control">
													<option value="S"  <?if($data['bmTarget']=="S"){?>selected<?}?>>현재 창</option>
													<option value="B"  <?if($data['bmTarget']=="B"){?>selected<?}?>>새로운 창</option>
												</select>
											</td>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">링크</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="bLink" name="bLink" value="<?=$data['bLink']?>" class="form-control"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">버튼 TEXT</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="bmBtnText" name="bmBtnText" value="<?=$data['bmBtnText']?>" class="form-control"> 
											</td>
										</tr>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">배너 위치</th>
											<td>
												<select name="bmType" id="bmType" class="form-control">
													<option value="">선택하세요</option>
														<option value="PM"  <?=selected($data['bmType'],"PM")?>  >ESS 메인</option>
														<option value="CM"  <?=selected($data['bmType'],"CM")?>  >ESS 중앙</option>
												</select>

											</td>
										
										</tr>
								</table>
								<table  class="table table-bordered   table-hover mb-none" style="table-layout:fixed;margin-top:10px;">
									<tr>
										<th class="col-sm-1" style="text-align: center !important;">배너 이미지</th>
										<td class="col-sm-8"  style="text-align: left !important;">
											
											<?if(!empty($data['bmImage'])){?>
												<p style="padding:10px;">
													<img src="/data/banner/<?=$data['bmImage']?>" alt="" style="width:360px;cursor:pointer" class="onHonverImg" >
												</p>
											<?}?>
											<p style="padding:10px;">
												<input type="file" name="file1" id="file1" class="form-control" style="width:300px;"> <br/>
												<i class="fa fas fa-warning"></i> 배너 이미지는 <?=$upload_max_filesize?> 이하의  jpg.png 파일만 가능합니다.<br/><br/>
												<i class="fa fas fa-warning"></i> 플랫폼 메인 배경 이미지 권장 사이즈 1920px * 700px<br>
												<i class="fa fas fa-warning"></i> CENTER 메인 배경 이미지 권장 사이즈 1920px * 700px<br>
												<i class="fa fas fa-warning"></i> EXPERT 메인 권장 사이즈 648px * 454px<br>
											</p>
										</td>
									</tr>
									<!--
									<tr>
										<th class="col-sm-1" style="text-align: center !important;">배너 텍스트</th>
										<td class="col-sm-8"  style="text-align: left !important;">
											
											<?if(!empty($data['bmText'])){?>
												<p style="padding:10px;">
													<img src="/data/banner/<?=$data['bmText']?>" alt="" style="width:360px;cursor:pointer" class="onHonverImg" >
												</p>
											<?}?>
											<p style="padding:10px;">
												<input type="file" name="file1" id="file1" class="form-control" style="width:300px;"> <br/>
												<i class="fa fas fa-warning"></i> 이미지 권장 사이즈 590px * 215px<br>
											</p>
										</td>
									</tr>
									-->
								</table>
								</form>
							<div class="col-sm-12 col-md-12">
								<div class="center" style="margin-top:20px;">
									<button class="btn btn-primary" id="btn-save"><i class="fas fa fa-save"></i> <?=$title?> 하기</button>
									<button class="btn btn-go-list "><i class="fas fa fa-list"></i> 목록으로 </button>
								</div>
							</div>
			</section>
		</div>

	</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>
var page = "<?=$page?>";
var $subquery = "<?=$subquery?>";

jQuery.viewImage({
  'target': '.onHonverImg'
});



$('.btn-go-list').click(function(e){
	e.preventDefault();
	location.href="/manage/banner.php?page="+page+$subquery ;
});

$('#btn-save').click(function(e){
	e.preventDefault();


	var bmTitle2 = $.trim($('#bmTitle2').val());
	var bmType = $('#bmType').val();

	
	if(bmTitle2==""){
		alert("배너명은 필수 입력 사항입니다.");
		return;
	}

	if(bmType==""){
		alert("배너 타입을 선택해 주세요.");
		return;
	}



	
	$('#quizForm').submit();

	
});

</script>