<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?

@$nseq = $_REQUEST['nseq'];	// 공지사항 번호

$title = "등록";
$procType = "I";
if(!empty($nseq)){
	$where.= " where nseq =".$nseq;
	$query="SELECT * FROM tbl_notice 
				 ".$where;
	$ps = pdo_query($db,$query,array());


	$data = $ps->fetch(PDO::FETCH_ASSOC);

	if(empty($data)){
		page_move("/manage/","잘못된 접근입니다.");
		exit;
	}

	$title = "수정";
	$procType = "U";
}


 include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/editor_include.php';

?> 

<section class="body">
	<!-- start: header -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_header.php'; ?>
	<!-- end: header -->

	<div class="inner-wrapper">
		<!-- start: sidebar -->
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_gnb_menu.php'; ?>
		<!-- end: sidebar -->

		
		<!-- start: content-body -->
		<section role="main" class="content-body">
			<header class="page-header">
				<h2>공지 관리</h2>
			
				<div class="right-wrapper pull-right">
					<ol class="breadcrumbs">
						<li>
							<a href="index.html">
								<i class="fa fa-home"></i>
							</a>
						</li>
						<li><span>공지 관리</span></li>
					</ol>
				</div>
			</header>

			<!-- start: page -->
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">공지 사항 <?=@$title?></h2>
				</header>
				<div class="panel-body">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
								<section class="panel">
									<div class="panel-body">

										<form class="form-horizontal" action ="/manage/proc/noticeProc.php" enctype="multipart/form-data"  method="post" id ="frm" >
											<input type="hidden" name="nseq" value="<?=$data['nseq']?>" />
											<input type="hidden" name="procType" id="procType" value="<?=@$procType?>"/>
											<div class="form-group">
												<label class="col-sm-1 control-label">제목</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="nTitle" name="nTitle" value="<?=$data['nTitle']?>" placeholder="제목을 입력해 주세요" maxlength="199">
												</div>						
											</div>
											
											<div class="form-group">
											<label class="col-sm-1 control-label">내용</label>
												<div class="col-sm-6">
													<textarea class="form-control" id="nContent" name="nContent" style="height:250px;"><?=$data['nContent']?></textarea>
												</div>
											</div>
											
											<div class="form-group">
											<label class="col-sm-1 control-label">작성일시</label>
												<div class="col-sm-2">

													<div class="input-daterange input-group "  data-plugin-datepicker="" data-date-format="yyyy-mm-dd" style="max-width:500px;">
														<span class="input-group-addon" style="border-width:1px;">
															<i class="fa fa-calendar"></i>
														</span>
														<input type="text" readonly="" class="form-control" name="nRegdate"   value="<?=substr($data['nRegdate'],0,10)?>" id="nRegdate" placeholder="작성일자" value="">
														
												</div>
											</div>
										
											<div class="col-md-12" style="text-align:center; margin-top: 1vw;">
												<button tabindex="-1" class="btn btn-primary" type="button"   id="btn_reg" ><i class="fa fa-save"></i> 저장</button>
												<?if(!empty($nseq)){?>
													<button tabindex="-1" class="btn btn-danger" type="button" id="btn_del" ><i class="fa fa-trash-o"></i> 삭제</button>
												<?}?>
												<button tabindex="-1" class="btn btn-default" type="button"  onclick="location.href='/manage/notice.php'"><i class="fa fa-list"></i>목록으로</button>
											</div>
										</form>

									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
		</section>
		<!-- end: content-body -->
	
	</div>

</section>
<script>


  (function () {
      new FroalaEditor("#nContent",{
		'key' : 'aLF3c1C10D6A4E3E2C2C-7tdvkiD-11ldB-7j1A11lE-13D1yahB3D3C10A6C3B4F6F3G3C3==',
		heightMin: 300,
		imageUploadURL: '/manage/proc/imageUpload.php', // 업로드 처리 end point
		imageUploadParam: 'file', // 파일 파라메터명
		imageUploadMethod: 'POST',
		imageAllowedTypes: ['jpeg', 'jpg', 'png'],
		imageMaxSize: 20 * 1024 * 1024 // 최대 이미지 사이즈 : 2메가
	  })
    })()


$(document).ready(function() {
	
	// $("#btn_del").hide();
	
});

$('#btn_reg').click(function() {
	var nTitle = $("#nTitle").val();
	if(nTitle == null || nTitle == ""){

		alert("공지사항 제목을 입력해주세요.");
		return false;
		
	}

	$('#frm').submit();
});

$('#btn_del').click(function() {
	if(confirm("삭제하시겠습니까?")){
		$("#procType").val("D");
		$('#frm').submit();
	}

	return false;
});

</script>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>