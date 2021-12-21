<?
    include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php';

@$nseq = $_REQUEST['nseq'];	// 공지사항 번호

$title = "등록";
$procType = "I";
if(!empty($nseq)){
	$where.= " where nseq =".$nseq;
	$query="SELECT * FROM tbl_template_page 
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
				<h2> 페이지 관리</h2>
			
				<div class="right-wrapper pull-right">
					<ol class="breadcrumbs">
						<li>
							<a href="index.html">
								<i class="fa fa-home"></i>
							</a>
						</li>
						<li><span> 페이지 관리</span></li>
					</ol>
				</div>
			</header>

			<!-- start: page -->
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title"> 페이지 관리 <?=@$title?></h2>
				</header>
				<div class="panel-body">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
								<section class="panel">
									<div class="panel-body">

										<form class="form-horizontal" action ="/manage/proc/templateProc.php" enctype="multipart/form-data"  method="post" id ="frm" >
											<input type="hidden" name="nseq" value="<?=$data['nseq']?>" />
											<input type="hidden" name="procType" id="procType" value="<?=@$procType?>"/>
											<div class="form-group">
												<label class="col-sm-1 control-label">제목</label>
												<div class="col-sm-11">
													<input type="text" class="form-control" id="nTitle" name="nTitle" value="<?=$data['nTitle']?>" placeholder="제목을 입력해 주세요" maxlength="199">
												</div>						
											</div>
											
											<div class="form-group">
												<div class="col-sm-12">
                                                    <textarea style="display: none;" name="nContent"><?=$data['nContent']?></textarea>
                                                    <textarea class="form-control" id="nContent"><?=$data['nContent']?></textarea>
                                                    <script>
                                                        CKEDITOR.replace('nContent',{
                                                            height : '400px'
                                                        });

                                                    </script>
												</div>
											</div>
											
											<div class="form-group">
											<label class="col-sm-1 control-label">작성일시</label>
												<div class="col-sm-4">

													<div class="input-daterange input-group "  data-plugin-datepicker="" data-date-format="yyyy-mm-dd" style="max-width:500px;">
														<span class="input-group-addon" style="border-width:1px;">
															<i class="fa fa-calendar"></i>
														</span>
														<input type="text" readonly="" class="form-control" name="nRegdate"   value="<?=substr($data['nRegdate'],0,10)?>" id="nRegdate" placeholder="작성일자" value="">
														
												</div>
											</div>
										
											<div class="col-md-12" style="text-align:center; margin-top: 1vw;">
												<button tabindex="-1" class="btn btn-primary" type="button"   id="btn_reg" ><i class="fa fa-save"></i> 저장</button>
												<!--
												<?if(!empty($nseq)){?>
													<button tabindex="-1" class="btn btn-danger" type="button" id="btn_del" ><i class="fa fa-trash-o"></i> 삭제</button>
												<?}?>
												-->
												<button tabindex="-1" class="btn btn-default" type="button"  onclick="location.href='/manage/template.php'"><i class="fa fa-list"></i>목록으로</button>
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




$(document).ready(function() {
	
	// $("#btn_del").hide();
	
});

$('#btn_reg').click(function() {
	var nTitle = $("#nTitle").val();
	if(nTitle == null || nTitle == ""){

		alert(" 제목을 입력해주세요.");
		return false;
		
	}
    $('textarea[name="nContent"]').text(CKEDITOR.instances.nContent.getData());
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