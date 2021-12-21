<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/encryption.php';


$rows = 10;
$page = $_REQUEST['page'];
if(empty($page)){
    $page=1;
}

$where=" WHERE co_is_del='FALSE' ";

$query = "SELECT count(*) as cnt FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq  ";
$query.=$where;
$query.=" ORDER BY co_seq asc ";
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

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq  ";
$query.=$where;
$query.=" ORDER BY co_seq asc limit {$from} , {$rows} ";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq  ";
$query.=$where;
$query.=" ORDER BY co_seq asc";
// echo('<pre>');print_r($query);echo('</pre>');
$ps = pdo_query($db, $query, array());
$list_total = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list_total, $data);
}

$numbering2 = sizeof($list_total);

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
						<h2>법인 관리</h2>
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
					</div>
				</header>
				
		<!-- start: page -->
		<div class="row">
				<section class="panel">
							<div class="panel-heading">
								검색 결과 :  <span style="color:#0d1cfc;font-weight:bold;"><?=number_format($numbering2)?></span> 건
								<button class="btn btn-primary btn-add-new" style="float:right;" onClick="detail_page();"><i class="fas fa fa-plus"></i> 신규 법인 등록</button>
								<div>
									
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered   table-hover mb-none" style="table-layout:fixed ">
										<thead>
										<tr>
											<th class="" style="text-align: center !important;width:50px;">No.</th>
											<th class="col-sm-3" style="text-align: center !important;">회사명</th>
											<th class="col-sm-5" style="text-align: center !important;">주소</th>
											<th class="col-sm-3" style="text-align: center !important;">로고</th>
											<th class="col-sm-2" style="text-align: center !important;">관리</th>
                                            <th class="col-sm-2" style="text-align: center !important;">처리</th>
										</thead>
										<tbody>
										<? if(isset($list) && count($list) > 0) { 
											foreach ($list as $val){
                                        ?>
											<tr>
                                                <td class="center"><?=$numbering--?></td>
                                                <td class="center">
                                                    <?=$val['co_name']?>
                                                    <?if($val['co_subname']!=""){?>
                                                        ( <?=$val['co_subname']?> )
                                                    <?}?>
                                                </td>
                                                <td class="center">
                                                        <?=$val['co_address']?>
                                                </td>
                                            
                                                <td class="center">
                                                    <img src="/data/logo/<?=$val['co_logo']?>" alt=""  style="height:30px;">
                                                </td>
                                                <td class="center">
                                                    <button class="btn btn-xs btn-default" onClick="detail_page(<?=$val['co_seq']?>);"><i class="fa fa-edit"></i> 수정하기</button>
                                                    <button class="btn btn-xs btn-danger" onClick="delete_proc(<?=$val['co_seq']?>);"><i class="fa fa-trash-o"></i> 삭제하기</button>
                                                </td>
                                                <td class="center">
                                                    <select name="co_status">
                                                        <option value="T" <?if($val['co_status']=="T"){?>selected<?}?>>노출</option>
                                                        <option value="F" <?if($val['co_status']=="F"){?>selected<?}?>>비노출</option>
                                                    </select>
                                                    <button data-coname="<?=$val['co_name']?>" data-cosubname="<?=$val['co_subname']?>" data-seq=<?=$val['co_seq']?> class="btn btn-xd btn-default btn-setStatus"><i class="fa fa-edit"></i>저장</button>
                                                </td>
											</tr>
											<?
												}
											}else{?>
											<tr>
												<td colspan="7" class="center hidden-xs">검색된 데이터가 없습니다. <br/>검색 조건을 다시 확인하시고 검색해 주세요</td>
											</tr>
											<?}?>
										</tbody>
								</table>
								<div class="col-sm-12 col-md-12">
									<div class="dataTables_paginate paging_bs_normal center" id="datatable-default_paginate22">
									<?=get_page_html_for_admin($page, $total_rows, $rows,10,$subquery)?>
									</div>
								</div>
								<!-- <button class="btn btn-primary btn-add-new" style="float:right;"><i class="fas fa fa-plus"></i> 신규 법인 등록</button> -->
							</div>
			</section>
		</div>

</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>
<script>
    $('.btn-setStatus').click(function(){
        let co_status = $(this).prev().val();
        let seq = $(this).data('seq');
        let coname = $(this).data('coname');
        let cosubname = $(this).data('cosubname');
        let co_title = "";
        if(cosubname != ""){
            co_title += coname+" ("+cosubname+")" ;
        }else{
            co_title += coname;
        }
        let title = '노출';
        if(co_status == 'F'){
            title = '비노출';
        }
        if(confirm(co_title+' 법인을 '+title+'처리 하시겠습니까?')){
            $.ajax({
                url : "/manage/proc/coperStatusProc.php",
                data : { 
                    'co_seq' : seq,
                    'co_status' : co_status,
                    'title' : title
                },
                dataType :"json",
                method : "post",
                success : function(result){
                    if(result.code=='FALSE'){
                        return alert(result.msg);
                    }else{
                        alert(result.msg);
                        location.reload();
                    }
                }
            });
        }
        
        
    });

	function detail_page(coseq){
        if(coseq){
            location.href='./coperationDetail.php?coseq='+coseq+'&page='+<?=$page?>;
        }else{
            location.href='./coperationDetail.php?page='+<?=$page?>;
        }
        
    }
    function delete_proc(coseq){
        if(confirm('정말 해당 법인을 삭제하시겠습니까?')){
            location.href="/manage/proc/coperProc.php?coseq="+coseq+"&type=삭제&page=<?=$page?>";
        }else return false;
        
    }
</script>