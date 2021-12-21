<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php';



$query="select * from tbl_config WHERE cType='G' order by cOrder asc";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($list,$data);
}

$query="select * from tbl_config WHERE cType='S' order by cOrder asc";
$ps = pdo_query($db,$query,array());
$slist = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($slist,$data);
}



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
				<h2>앱 설정</h2>
			
				<div class="right-wrapper pull-right">
					<ol class="breadcrumbs">
						<li>
							<a href="/manage/main.php">
								<i class="fa fa-home"></i>
							</a>
						</li>
						
						<li><span>앱 설정 관리</span></li>
					</ol>
				</div>
			</header>
			<div class="row">
				<section class="panel panel-featured col-md-12 " style="border:none;">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
						</div>

						<h2 class="panel-title">앱 설정</h2>
					</header>
					<div class="panel-body  col-md-12 " style="display: block;">
						<table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
							<?for($i=0;$i<sizeof($list);$i++){?>
							<tr>
									<th class="col-sm-2" style="text-align: center !important;"><?=$list[$i]['cTitle']?></th>
									<td class="col-sm-10"  style="text-align:left !important;">	
										<input type="number" class="itext settingValue" seq="<?=$list[$i]['cseq']?>" style="max-width:200px;"   name="cseq1" value="<?=$list[$i]['cValue']?>">
										<button class="btn setSaveBtn" seq="<?=$list[$i]['cseq']?>"> <i class="fas fa fa-save"></i> 저장하기</button>
										<?if($list[$i]['cseq']==5 || $list[$i]['cseq']==6  ){?> <i class="fa fa-warning"></i> 앱 버전 코드 1.0.0 = 100 <?}?>
										<?if($list[$i]['cseq']==7 || $list[$i]['cseq']==8  ){?> <i class="fa fa-warning"></i> 0: 수동업데이트  /  1: 강제업데이트 <?}?>
									</td>
							</tr>
							<?}?>
						</table>
					</div>
				</section>
			</div>

				<div class="row">
				<section class="panel panel-featured col-md-12 " style="border:none;">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
						</div>

						<h2 class="panel-title">환경 설정</h2>
					</header>
					<div class="panel-body  col-md-12 " style="display: block;">
						<table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
							<?for($i=0;$i<sizeof($slist);$i++){?>
							<tr>
									<th class="col-sm-2" style="text-align: center !important;"><?=$slist[$i]['cTitle']?></th>
									<td class="col-sm-10"  style="text-align:left !important;">	
										<input type="text" class="form-control settingValue2"  readonly seq="<?=$slist[$i]['cseq']?>" style="max-width:500px;"   name="cseq1" value="<?=$slist[$i]['cValue']?>">
										<button class="btn setSaveBtn2" seq="<?=$slist[$i]['cseq']?>"> <i class="fas fa fa-save"></i> 저장하기</button>
									</td>
							</tr>
							<?}?>
						</table>
					</div>
				</section>
			</div>

			
		</section>
	</div>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>
	$('.setSaveBtn').click(function(){
		var eq = $('.setSaveBtn').index(this);
		var seq = $(this).attr('seq');
		var val = $('.settingValue:eq('+eq+')').val();
		if(val==""){
			alert("저장하실 값을 입력해 주세요.");
			return;
		}
		if(val<0){
			alert("0보다 큰 숫자를 입력해주세요.");
			return;
		}

		if(confirm("설정값을 저장하시겠습니까?\n적용 즉시 반영됩니다.")){
			$.ajax({
				url : "/manage/proc/setSetting.php",
				dataType :"json",
				data : {
					seq : seq,
					val : val
				},
				success : function(result){
					if(result.code=='FALSE'){
						alert("잘못된 접근입니다.");
						return;
					}else{
						alert("설정값이 저장 되었습니다.");
						return;
					}
				}
			})
		}


	});


	$('.setSaveBtn2').click(function(){
		var eq = $('.setSaveBtn2').index(this);
		var seq = $(this).attr('seq');
		var val = $('.settingValue2:eq('+eq+')').val();
		if(val==""){
			alert("저장하실 값을 입력해 주세요.");
			return;
		}
	
		if(confirm("설정값을 저장하시겠습니까?\n적용 즉시 반영됩니다.")){
			$.ajax({
				url : "/manage/proc/setSetting.php",
				dataType :"json",
				data : {
					seq : seq,
					val : val
				},
				success : function(result){
					if(result.code=='FALSE'){
						alert("잘못된 접근입니다.");
						return;
					}else{
						alert("설정값이 저장 되었습니다.");
						return;
					}
				}
			})
		}


	});




</script>