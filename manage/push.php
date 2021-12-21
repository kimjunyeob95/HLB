<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : member.php
	설     명  : 회원 관리
	 작성일 : 2020-08-07
*****************************************/

@$page = $_REQUEST['page'];				//페이지
@$mType = $_REQUEST['mType'];
@$mNick = $_REQUEST['mNick'];
@$dType = $_REQUEST['dType'];



$query="SELECT * FROM tbl_member ";
$ps = pdo_query($db,$query,array());
$favoritList = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($favoritList,$data);
}


$rows = 30;
if(empty($page)){
	$page=1;
}

$where=" WHERE mIsDelete='FALSE' ";


if(!empty($mNick)){
	$where.=" and  instr( mNick, '".$mNick."') ";
}

if(!empty($mType)){
	$where.=" and mType='".$mType."' ";
}

if(!empty($dType)){
	$where.=" and deviceOs='".$dType."' ";
}


$query="SELECT COUNT(deviceSeq) as cnt FROM  tbl_device as A join tbl_member as B on A.deviceMseq = B.mseq 
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
				 tbl_device as A join tbl_member as B on A.deviceMseq = B.mseq  ";
$query.=$where;
$query.=" ORDER BY mseq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

//Param set
$subquery="&mType=".urlencode($mType)."&mNick=".$mNick."&dType=".$dType;




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
						<h2> <i class="fas fa fa-bell"></i> 전체 푸시 발송 </h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="/manage/main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								
								<li><span>기기 관리</span></li>
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

					<h2 class="panel-title">전체 푸시 발송</h2>
				</header>
				<div class="panel-body  col-md-12 " style="display: block;">
					<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
						<tr>
							<th  class="col-sm-1" style="text-align: center !important;">제목</th>
							<td  class="col-sm-11" style="text-align: center !important;">
								<input title="제목" class="form-control" maxlength="50" style="max-width:400px;" id="pTitle">
							</td>
						</tr>
						<tr>
							<th  class="col-sm-1" style="text-align: center !important;">내용</th>
							<td  class="col-sm-11">
								<textarea style="width:400px;height:100px;" id="pMsg"></textarea>
							</td>
						</tr>
						<tr>
						<th  style="text-align: center !important;">발송</th>
						<td>
							<button class="btn btn-primary btn-large" style="width:400px;" id="btn-send-push">푸시 발송하기 </button><br/>
							<i class="fas fa fa-warning"></i> 회원 푸시 설정이 ON 이며, 기기에서 앱 푸시 허용이 되어 있는 기기에만 발송 됩니다.

						</td>
						</tr>
						</table>

				</div>
			</section>

		</div>
	

	</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>

jQuery.viewImage({
  'target': '.onHonverImg'
});


$('#btn-send-push').click(function(e){
	e.preventDefault();
	$('#btn-send-push').hide();
	var pTitle = $('#pTitle').val();
	var pMsg = $('#pMsg').val();
	if(pTitle==""){
		alert("푸시 제목을 입력해 주세요");
		$('#btn-send-push').show();
		return;
	}
	if(pMsg==""){
		alert("푸시 내용을 입력해 주세요");
		$('#btn-send-push').show();
		return;
	}
	if(confirm("전체 회원에게 푸시 메세지를 보내시겠습니까?")){
		$.ajax({
			url : "/manage/proc/pushAll.php",
			aync : false,
			method : "post",
			data :  {
				pTitle : pTitle,
				pMsg : pMsg
			},
			success : function(){
				$('#btn-send-push').show();
				alert("전체 푸시가 발송 되었습니다");
				location.reload();
			}
		})
		
	}else{
		$('#btn-send-push').show();
	}

})

</script>