<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : godDetail.php
	설     명  : 광고주 관리
	 작성일 : 2020-08-07
*****************************************/
$upload_max_filesize = ini_get('upload_max_filesize'); //php 파일 업로드 크기

@$page = $_REQUEST['page'];				//페이지
@$coseq = $_REQUEST['coseq'];

$title="수정";
if(empty($coseq)){
	$title="등록";
}else{
	if(!is_numeric($coseq)){
		page_move('/manage/',"잘못된 접근입니다.");
		exit;
	}
	$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq WHERE A.co_seq=? AND A.co_is_del='FALSE'";
	$ps = pdo_query($db, $query, array($coseq));
	$data = $ps->fetch(PDO::FETCH_ASSOC);
	// echo('<pre>');print_r($data);echo('</pre>');
	if($data<1){
			page_move('/manage/coperation.php',"삭제되었거나 존재하지 않는 법인 입니다.");
		exit;
	}
}


// $subquery="&bmTitle=".urlencode($bmTitle)."&bmType=".$bmType;


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
input[type=checkbox]{
	vertical-align: middle;
    width: 20px;
    height: 20px;
    margin-left: 5px;
}
label{vertical-align:sub;}
.fa-warning{color:#ff9900;}
.agree-wrap{padding: 0 10px;}
.agreement h3{font-size: 1.5rem}
.agree-wrap .text-wrap {padding: 10px; height: 200px; overflow-y: scroll; background-color:#f5f5f5}
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
						<h2><i class="fas fa fa-external-link-alt"></i> 법인 관리</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="/manage/main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								
								<li><span>법인 관리</span></li>
							</ol>
						</div>
					</header>
			<!-- start: page -->
			<div class="row ">
				<section class="panel col-sm-12">
							<div class="panel-heading">
										<h2 class="panel-title"></h2>
											<div class="panel-actions">
										<button class="btn btn-xs btn-go-list btn-primary"><i class="fas fa fa-list"></i> 목록으로</button>
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>

									</div>

									<h2 class="panel-title">법인 <?=$title?></h2>
							</div>
							<div class="panel-body">
                                <form id="coperForm" method="post" enctype="multipart/form-data" action="/manage/proc/coperProc.php?type=<?=$title?>&page=<?=$page?>">
                                    <input type="hidden" name="coseq" value="<?=$coseq?>">
                                    <input type="hidden" name="have_logo" value="<?=$data['co_logo']?>">
								    <table class="table table-bordered table-hover mb-none" style="table-layout:fixed ">
										<tr>
											<th class="col-sm-1" style="text-align: center !important;">법인명</th>
											<td class="col-sm-2"  style="text-align: center !important;" >
												<input type="text" title="법인명" id="co_name" name="co_name"  value="<?=$data['co_name']?>" class="form-control input-text"> 
                                            </td>
                                            <th class="col-sm-1" style="text-align: center !important;">법인 서브명</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" title="법인 서브명" id="co_subname" name="co_subname"  value="<?=$data['co_subname']?>" class="form-control"> 
											</td>
                                        </tr>
                                        <tr>
											<th class="col-sm-1" style="text-align: center !important;">법인 주소</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<input type="text" title="법인 주소" id="co_address" name="co_address"  value="<?=$data['co_address']?>" class="form-control input-text"> 
											</td>
											<th class="col-sm-1" style="text-align: center !important;">회사 종류</th>
											<td class="col-sm-2"  style="text-align: center !important;">
												<select name="code_value" class="form-control">
													<option value="1"  <?if($data['code_value']=="상장회사"){?>selected<?}?>>상장회사</option>
                                                    <option value="2"  <?if($data['code_value']=="핵심기업"){?>selected<?}?>>핵심기업</option>
                                                    <option value="3"  <?if($data['code_value']=="벤쳐기업"){?>selected<?}?>>벤쳐기업</option>
												</select>
											</td>
                                        </tr>
											<th class="col-sm-1" style="text-align: center !important;">법인 색상</th>
											<td class="col-sm-2" colspan=3  style="text-align: center !important;">
												<input type="text"  title="법인 색상" name="co_color" readonly maxlength="7" value="<?=$data['co_color']?>" class="form-control input-text colorPicker" style="max-width: 20%;"> 
                                            </td>
                                        </tr>
								    </table>
								<table  class="table table-bordered   table-hover mb-none" style="table-layout:fixed;margin-top:10px;">
									<tr>
										<th class="col-sm-1" style="text-align: center !important;">법인 로고 이미지</th>
										<td class="col-sm-8"  style="text-align: left !important;">
											
											<?if(!empty($data['co_logo'])){?>
												<p style="padding:10px;">
													<img src="/data/logo/<?=$data['co_logo']?>" alt="" style="width:360px;cursor:pointer" class="onHonverImg" >
												</p>
											<?}?>
											<p style="padding:10px;">
												<input type="file" name="file1" id="file1" class="form-control" style="width:300px;"> <br/>
												<i class="fa fas fa-warning"></i> 배너 이미지는 <?=$upload_max_filesize?> 이하의  jpg.png 파일만 가능합니다.<br/><br/>
												<!-- <i class="fa fas fa-warning"></i> 플랫폼 메인 배경 이미지 권장 사이즈 1920px * 700px<br>
												<i class="fa fas fa-warning"></i> CENTER 메인 배경 이미지 권장 사이즈 1920px * 700px<br>
												<i class="fa fas fa-warning"></i> EXPERT 메인 권장 사이즈 648px * 454px<br> -->
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

jQuery.viewImage({
  'target': '.onHonverImg'
});

$('.colorPicker').colorpicker({
      format: "hex"
  });


$('.btn-go-list').click(function(e){
	e.preventDefault();
	location.href="/manage/coperation.php?page="+page+$subquery ;
});

$('#btn-save').click(function(e){
	e.preventDefault();
    let validate=true;
    $('#coperForm').find('.input-text').each(function(e){
        var val = $.trim($(this).val());
        var txt = $(this).attr('title');

        if(val==""){
            $(this).focus();
            alert(  reulReturner(txt) + " 입력해 주세요");
            validate = false;
            return false;
        }
    });

    if(validate){
        if($('#file1').val()=='' && $('.onHonverImg').length==0){
            $('#file1').focus();
            alert('법인 로고 이미지를 등록해 주세요.');
            return false;
        }
        $('#coperForm').submit();
    }
});

</script>