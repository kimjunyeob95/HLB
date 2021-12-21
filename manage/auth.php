<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : 
	설     명  : 
	작성일 : 
*****************************************/
$where=" WHERE co_is_del='FALSE' ";

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq  ";
$query.=$where;
$query.=" ORDER BY co_seq asc ";
$ps2 = pdo_query($db, $query, array());
$coperationList = array();
while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
	array_push($coperationList, $data2);
}

$enc = new encryption();
// $number = "1234";
// $number = $enc->encrypt($number);
// echo('<pre>');print_r($number);echo('</pre>');exit;
@$page = $_REQUEST['page'];				//페이지
@$mm_id = $_REQUEST['mm_id'];
@$mm_name = $_REQUEST['mm_name'];

$rows = 10;
$where = " 1=1 ";
if(empty($page)){
	$page=1;
}
if(!empty($mm_id)){
	$where .= " AND mm_id='{$mm_id}' ";
}
if(!empty($mm_name)){
	$where .= " AND mm_name='{$mm_name}' ";
}

// echo('<pre>');print_r($_REQUEST);echo('</pre>');
$query="SELECT COUNT(*) as cnt FROM tbl_manager_member WHERE ".$where;
// echo('<pre>');print_r($query);echo('</pre>');exit;
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

$query="SELECT * FROM tbl_manager_member WHERE ".$where;
$query.=" ORDER BY mm_seq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

$subquery="&mm_id=".$mm_id."&page=".$page;
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
                <h2>관리자 관리</h2>
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
                        <form class="form-horizontal" id="form_proccess" action="<?= $_SERVER['PHP_SELF']?>" method="post">
                            <div class="form-group col-md-12"> 
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">아이디</label>
                                <div class="col-md-2">
                                    <div class="input-daterange input-group col-md-12"  >
                                        <input type="text" class="form-control mb-md" name="mm_id" value="<?=$mm_id?>">
                                    </div>
                                </div>
                                <div class="col-md-3" >
                                    <button tabindex="-1" class="btn btn-primary" type="submit"> 검색하기 <i class="fa fa-search"></i> </button>
                                    <button class="btn " id="btn-re-research">검색 초기화 <i class="fa fa-refresh"></i></button>
                                </div>                    
                            </div>
                        </form>
                    </div>
                </section>
            </div>
            <div class="row ">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <div class="panel-heading">
                        검색 결과 :  <span style="color:#0d1cfc;font-weight:bold;"><?=number_format($total_rows)?></span> 건
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
                                <thead>
                                <tr>
                                    <th class="" style="text-align: center !important;width:40px;">No.</th>
                                    <th class="col-sm-1" style="text-align: center !important;">ID</th>
                                    <th class="col-sm-1" style="text-align: center !important;">PWD</th>
                                    <th class="col-sm-1" style="text-align: center !important;">관리자명</th>
                                    <th class="col-sm-1" style="text-align: center !important;">최종로그인</th>
                                    <th class="col-sm-1" style="text-align: center !important;">등록일시</th>
                                    <th class="col-sm-1" style="text-align: center !important;">최종로그인 IP</th>
                                </thead>
                                <tbody>
                                <? if(isset($list) && count($list) > 0) { 
                                        for($i=0;$i<sizeof($list);$i++){
                                ?>
                                    <tr class="view-member-qq" onClick="detail_page(<?=$list[$i]['mm_seq']?>);">
                                        <td class="center"><?=$numbering?></td>
                                        <td class="center" ><?=$list[$i]['mm_id']?></td>
                                        <td class="center">
                                            <?
                                                $pw=mb_strlen($enc->decrypt($list[$i]['mm_pwd']));
                                                for($i2=0;$i2<$pw;$i2++){
                                                    echo '*';
                                                };
                                            ?>
                                        </td>
                                        <td class="center"><?=$list[$i]['mm_name']?></td>
                                        <td class="center"><?=$list[$i]['mm_last_login']?></td>
                                        <td class="center"><?=$list[$i]['mm_regdate']?></td>
                                        <td class="center"><?=$list[$i]['mm_last_ip']?></td>
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
                        <div class="col-sm-12 col-md-12" style="margin-top: 30px;">
                            <div class="col-md-12">
                                <a href="/manage/authDetail.php"><button class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> 관리자 등록</button></a>
                            </div>
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

<script>
var $subquery = "<?=$subquery?>";

function detail_page(mseq){
    location.href="/manage/authDetail.php?"+$subquery+"&mmseq="+mseq;
}

$('#btn-re-research').click(function(e){
	e.preventDefault();
	location.href="/manage/auth.php";
});

jQuery.viewImage({
  'target': '.onHonverImg'
});

$('.view-member-qq').click(function(e){
	e.preventDefault();
}).css('cursor','pointer');

</script>