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
@$gName = $_REQUEST['gName'];
@$gManName = $_REQUEST['gManName'];


$title="수정";
if(empty($seq)){
	$title="등록";
}else{

	if(!is_numeric($seq)){
		page_move('/manage/',"잘못된 접근입니다.");
		exit;
	}
	$query="SELECT COUNT(gseq) as cnt, A.*  FROM 
				 tbl_god as A";
	$query.=" WHERE gseq=?  and gIsDel='FALSE' ";
	$ps = pdo_query($db, $query, array($seq));
	$data = $ps->fetch(PDO::FETCH_ASSOC);
	
	if($data['cnt']<1){
			page_move('/manage/god.php',"삭제되었거나 존재하지 않는 광고주 입니다.");
		exit;
	}
}


$subquery="&gName=".urlencode($gName)."&gManName=".urlencode($gManName);


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
					<h2>   <img src="/manage/@resource/jesus.png" alt="" style="width:25px;margin-top:-5px;"> 주님 관리</h2>
					
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

									<h2 class="panel-title"><?=$data['gName']?> <?=$title?></h2>
							</div>
							<div class="panel-body">
								<form name="quizForm" id="quizForm" method="post"  enctype="multipart/form-data" action="/manage/proc/godProc.php">
								<input type="hidden" name="page" value="<?=$page?>">
								<input type="hidden" name="gName" value="<?=$gName?>">
								<input type="hidden" name="gManName" value="<?=$gManName?>">
								<input type="hidden" name="gseq" id="gseq" value="<?=$data['gseq']?>">
								<table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
										
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">회사명</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="gName2" name="gName2" value="<?=$data['gName']?>" class="form-control"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">담당자명</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="gManName2" name="gManName2"  value="<?=$data['gManName']?>" class="form-control"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">연락처</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="gTel" name="gTel" value="<?=$data['gTel']?>" class="form-control"> 
											</td>
										</tr>
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">이메일</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="gMail" name="gMail" value="<?=$data['gMail']?>" class="form-control"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">사업자 번호</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" id="gNumber"  name="gNumber" value="<?=$data['gNumber']?>" class="form-control"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">등록일</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<?=$data['gRegdate']?>
											</td>
										</tr>
								</table>
								<table  class="table table-bordered   table-hover mb-none" style="table-layout:fixed;margin-top:10px;">
									<tr>
										<th class="col-sm-1" style="text-align: center !important;">사업자 등록증</th>
										<td class="col-sm-8"  style="text-align: left !important;">
											
											<?if(!empty($data['gFile1'])){?>
												<p style="padding:10px;"><i class="fa fas fa-download"> <a href="/data/god/<?=$data['gFile1']?>" target="_blank"><?=$data['gFile1']?></a></i></p>
											<?}?>
											<p style="padding:10px;">
												<input type="file" name="file1" id="file1" class="form-control" style="width:300px;"> <br/>
												<i class="fa fas fa-warning"></i> 사업자등록증 파일은 <?=$upload_max_filesize?> 이하의  jpg.png,pdf,zip 파일만 가능합니다.
											</p>
										</td>
									</tr>
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

$('.btn-go-list').click(function(e){
	e.preventDefault();
	location.href="/manage/god.php?page="+page+$subquery ;
});

$('#btn-save').click(function(e){
	e.preventDefault();
	var gName2 = $.trim($('#gName2').val());
	var gManName2 = $.trim($('#gManName2').val());
	
	if(gName2==""){
		alert("회사명과 담당자명은 필수 입력 사항입니다.");
		return;
	}
	if(gManName2==""){
		alert("회사명과 담당자명은 필수 입력 사항입니다.");
		return;
	}
	
	$('#quizForm').submit();
	
	
});

</script>