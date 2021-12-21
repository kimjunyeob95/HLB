<?php 
    include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/model/position_model.php'; 
    include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
?>

<?
/*****************************************
	파 일 명 : mssmember.php
	설     명  : 회원 관리
	 작성일 : 2020-08-07
*****************************************/
$enc = new encryption();

@$page = $_REQUEST['page'];				//페이지
@$mc_coseq = $_REQUEST['mc_coseq'];
@$mm_name = $_REQUEST['mm_name'];

$rows = 10;
$where = " ";
if(empty($page)){
	$page=1;
}
if(!empty($mc_coseq)){
	$where .= " AND emc.mc_coseq={$mc_coseq} ";
}
if(!empty($mm_name)){
    $mm_name_enc=$enc->encrypt($mm_name);
	$where .= " AND emb.mm_name = '{$mm_name_enc}' ";
}

// echo('<pre>');print_r($_REQUEST);echo('</pre>');
$where .=" and emb.mm_is_del='FALSE' and emb.mm_super_admin='F' and emb.mm_status='Y' ";

$query="SELECT COUNT(mmseq) as cnt FROM ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE 
mmseq in(select distinct tg_mms_mmseq from tbl_ess_group where tg_mms_mmseq  <> 0) ".$where;
// $query="SELECT COUNT(mmseq) as cnt FROM ess_member_base as A join ess_member_code as B join tbl_ess_group as C
// on A.mmseq = B.mc_mmseq and A.mmseq=C.tg_mms_mmseq WHERE ".$where." group by B.mc_code";
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


$query="SELECT * FROM ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE 
mmseq in(select distinct tg_mms_mmseq from tbl_ess_group where tg_mms_mmseq  <> 0) ".$where;
$query.=" ORDER BY mmseq DESC limit ".$from .",".$rows;
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($list, $data);
}

$query="SELECT * FROM ess_member_base emb join ess_member_code emc on emb.mmseq = emc.mc_mmseq WHERE 
mmseq in(select distinct tg_mms_mmseq from tbl_ess_group where tg_mms_mmseq  <> 0) ".$where;
$query.=" ORDER BY mmseq DESC ";
$ps = pdo_query($db, $query, array());
$member_list_all = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
	array_push($member_list_all, $data);
}

// echo('<pre>');print_r($list);echo('</pre>');
//Param set
$subquery="&mc_coseq=".$mc_coseq."&mm_name=".urlencode($mm_name);


$where=" WHERE co_is_del='FALSE' ";

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq";
$query.=$where;
$query.=" ORDER BY A.co_seq asc ";
$ps2 = pdo_query($db, $query, array());
$coperationList = array();
while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
	array_push($coperationList, $data2);
}
// echo('<pre>');print_r($coperationList);echo('</pre>');
//만 나이 계산
function getManNai($data){
    $birth_year = substr((int)$data,0,4);
    $birth_month = substr((int)$data,5,2);
    $brith_day = substr((int)$data,8,2);

    $now_year = date("Y");
    $now_month = date("m");
    $now_day = date("d");

    if($birth_month < $now_month){
       $age = $now_year - $birth_year;
    }else if($birth_month == $now_month){
     if($brith_day <= $now_day)
      $age = $now_year - $birth_year;
     else
      $age = $now_year - $birth_year -1;
    }else{
       $age = $now_year - $birth_year-1;
    }
    return $age;
}
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
                <h2> <i class="fas fa fa-user-circle"></i> MSS 회원 </h2>
            
                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="/manage/main.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        
                        <li><span> MSS 회원 관리</span></li>
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
						<form class="form-horizontal" id="form_proccess" action="<?= $_SERVER['PHP_SELF']?>" method="post">

					

						<div class="form-group col-md-12">
							<label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">법인</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-3"  >
									<select class="form-control mb-md" name="mc_coseq">
										<option value="">전체</option>
										<?for($i=0;$i<sizeof($coperationList);$i++){?>
                                        <option value="<?=$coperationList[$i]['co_seq']?>" <?if($mc_coseq==$coperationList[$i]['co_seq']){?>selected<?}?>><?=$coperationList[$i]['co_name']?> <?if($coperationList[$i]['co_subname']!=""){?> ( <?=$coperationList[$i]['co_subname']?> )<?}?></option>
										<?}?>
									</select>
								</div>
							</div>
							
							<label class="col-md-1 control-label " style="line-height: 30px; width:auto !important;">사원명</label>
							<div class="col-md-2">
								<div class="input-daterange input-group col-md-12"  >
									<input type="text" class="form-control mb-md" name="mm_name" value="<?=$mm_name?>">
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
		<div class="row">
				<section class="panel">
							<div class="panel-heading">
								검색 결과 :  <span style="color:#0d1cfc;font-weight:bold;"><?=number_format($total_rows)?></span> 건
								<button id="btn-excel" type="button" class="btn" style="position: absolute; right: 20px; bottom: 10px;" onclick="tableToExcel('tb_insaHeader')"><i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;엑셀 다운로드</button>
							</div>
							<div class="panel-body">
								<table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
										<thead>
										<tr>
											<th class="" style="text-align: center !important;width:40px;">No.</th>
											<th class="col-sm-2" style="text-align: center !important;">법인</th>
                                            <th class="col-sm-2" style="text-align: center !important;">사번</th>
											<th class="col-sm-1" style="text-align: center !important;">성명</th>
                                            <th class="col-sm-1" style="text-align: center !important;">성별</th>
                                            <th class="col-sm-1" style="text-align: center !important;">소속</th>
											<th class="col-sm-1" style="text-align: center !important;">직위</th>
											<th class="col-sm-1" style="text-align: center !important;">직책</th>
											<th class="col-sm-1" style="text-align: center !important;">생년월일</th>
											<th class="col-sm-2" style="text-align: center !important;">입사일자</th>
											<th class="col-sm-1" style="text-align: center !important;">재직여부</th>
											<th class="col-sm-1" style="text-align: center !important;">최종로그인</th>
										</thead>
										<tbody>
										<? if(isset($list) && count($list) > 0) { 
												for($i=0;$i<sizeof($list);$i++){
													?>
											<tr class="view-member-qq" onClick="detail_page(<?=$list[$i]['mc_coseq']?>,<?=$list[$i]['mmseq']?>);">
                                                <td class="center"><?=$numbering?></td>
                                                <td class="center">
                                                    <?for($i2=0;$i2<sizeof($coperationList);$i2++){?>
                                                        <?if($list[$i]['mc_coseq']==$coperationList[$i2]['co_seq']){?><?=$coperationList[$i2]['co_name']?> <?if($coperationList[$i2]['co_subname']!=""){?> ( <?=$coperationList[$i2]['co_subname']?> )<?}?><?}?>
                                                    <?}?>
                                                </td>
                                                <td class="center" ><p class="view-member-qq"><?=$list[$i]['mc_code']?></p></td>
                                                <td class="center"><?=$enc->decrypt($list[$i]['mm_name'])?></td>
                                                <td class="center"><?=$gender[$list[$i]['mm_gender']]?></td>
                                                <td class="center"><?=implode('<br> ',get_group_list_admin($db,$list[$i]['mmseq'],$list[$i]['mc_coseq']))?></td>
                                                <td  class="center">
                                                    <?=get_position_title_type_admin($db,$list[$i]['mc_position2'],2,$list[$i]['mc_coseq'])?>
                                                </td>
                                                <td  class="center">
                                                    <?=get_position_title_admin($db,$list[$i]['mc_position'],$list[$i]['mc_coseq'])?>
                                                </td>
                                                <td class="center">
                                                    <p class="view-member-qq" ><?=substr($list[$i]['mm_birth'],0,10)?></p>
                                                </td>
                                                <td class="center">
                                                    <?=substr($list[$i]['mc_regdate'],0,10)?>
                                                </td>
                                                <td class="center">
                                                    <?=$member_state[$list[$i]['mm_status']]?>
                                                </td>
                                                <td class="center"><?=$list[$i]['mm_last_login']?></td>
											</tr>
											<?
												$numbering--;
												}
											}else{?>
											<tr>
												<td colspan="12" class="center hidden-xs">검색된 데이터가 없습니다. <br/>검색 조건을 다시 확인하시고 검색해 주세요</td>
											</tr>
											<?}?>
										</tbody>
								</table>
								<div class="col-sm-12 col-md-12">
									<div class="dataTables_paginate paging_bs_normal center" id="datatable-default_paginate22">
									<?=get_page_html_for_admin($page, $total_rows, $rows,10,$subquery)?>
									</div>
								</div>
							</div>
			</section>
		</div>

        <!-- 엑셀 영역 -->
        <table id="tb_insaHeader" class="table table-bordered table-striped  table-hover mb-none" style="display: none;">
            <thead>
                <tr>
                    <th class="col-sm-1" style="text-align: center !important;">no</th>
                    <th class="col-sm-2" style="text-align: center !important;">법인</th>
                    <th class="col-sm-1" style="text-align: center !important;">사번</th>
                    <th class="col-sm-1" style="text-align: center !important;">성명</th>
                    <th class="col-sm-1" style="text-align: center !important;">성별</th>
                    <th class="col-sm-1" style="text-align: center !important;">소속</th>
                    <th class="col-sm-1" style="text-align: center !important;">직위</th>
                    <th class="col-sm-1" style="text-align: center !important;">직책</th>
                    <th class="col-sm-1" style="text-align: center !important;">생년월일</th>
                    <th class="col-sm-1" style="text-align: center !important;">입사일자</th>
                    <th class="col-sm-1" style="text-align: center !important;">재직여부</th>
                    <th class="col-sm-1" style="text-align: center !important;">최종로그인</th>
                </tr>
            </thead>
            <tbody>
                <? foreach($member_list_all as $i => $val){ ?>
                    <tr class="view-member-qq">
                        <td class="center"><?=$i+1?></td>
                        <td class="center">
                            <?for($i2=0;$i2<sizeof($coperationList);$i2++){?>
                                <?if($val['mc_coseq']==$coperationList[$i2]['co_seq']){?><?=$coperationList[$i2]['co_name']?> <?if($coperationList[$i2]['co_subname']!=""){?> ( <?=$coperationList[$i2]['co_subname']?> )<?}?><?}?>
                            <?}?>
                        </td>
                        <td class="center"><p class="view-member-qq"><?=$val['mc_code']?></p></td>
                        <td class="center"><?=$enc->decrypt($val['mm_name'])?></td>
                        <td class="center"><?=$gender[$val['mm_gender']]?></td>
                        <td class="center"><?=implode('<br> ',get_group_list_admin($db,$val['mmseq'],$val['mc_coseq']))?></td>
                        <td class="center">
                            <?=get_position_title_type_admin($db,$val['mc_position2'],2,$val['mc_coseq'])?>
                        </td>
                        <td class="center">
                            <?=get_position_title_admin($db,$val['mc_position'],$val['mc_coseq'])?>
                        </td>
                        <td class="center">
                            <p class="view-member-qq" ><?=substr($val['mm_birth'],0,10)?></p>
                        </td>
                        <td class="center">
                            <?=substr($val['mc_regdate'],0,10)?>
                        </td>
                        <td class="center">
                            <?=$member_state[$val['mm_status']]?>
                        </td>
                        <td class="center"><?=$val['mm_last_login']?></td>
                    </tr>
                <?}?>
            </tbody>
        </table>
        <!-- 엑셀 영역 -->

	</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>

<script>
var $subquery = "<?=$subquery?>";
var page = "<?=$page?>";

function detail_page(mc_coseq,mseq){
    location.href="/manage/mssmemberDetail.php?mccoseq="+mc_coseq+"&mmseq="+mseq+"&page="+page;
}

function tableToExcel(id) {
    var data_type = 'data:application/vnd.ms-excel;charset=utf-8';
    var table_html = encodeURIComponent(document.getElementById(id).outerHTML);

    var a = document.createElement('a');
    a.href = data_type + ',%EF%BB%BF' + table_html;
    a.target = '_blank';
    a.download = 'employee_excel'+'.xls';
    a.click();
}

$('#btn-re-research').click(function(e){
	e.preventDefault();
	location.href="/manage/mssmember.php";
});

jQuery.viewImage({
  'target': '.onHonverImg'
});

$('.view-member-qq').click(function(e){
	e.preventDefault();
}).css('cursor','pointer');

</script>