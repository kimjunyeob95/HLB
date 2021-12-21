<?
    include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
?>
<?
$enc = new encryption();
$where_query = array();

@$page = $_REQUEST['page'];
@$mc_coseq_page = $_REQUEST['mc_coseq_page'];
if(empty($page)){
	$page=1;
}
if(!empty($mc_coseq_page)){
    $where_query['where']['mc_coseq'] = $mc_coseq_page;
}
if(empty($_REQUEST['mm_status'])){
    $_REQUEST['mm_status'] = 'Y';
}

$where_query['where']['keyword'] = $_REQUEST['keyword'];
@$where_query['where']['mm_status'] = $_REQUEST['mm_status'];

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq WHERE co_is_del='FALSE' ORDER BY co_seq asc ";
$ps2 = pdo_query($db, $query, array());
$coperationList = array();
while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
	array_push($coperationList, $data2);
}


$total_cnt = get_member_join_co_list($db,$where_query,'cnt');

@$rows = $_REQUEST['rows'];
if(empty($_REQUEST['rows'])){
    $rows = 10;
}
// 페이징
if(empty($page)){
    $page=1;
}
$total_rows = $total_cnt;
if ($total_rows > 0) {
    $total_page = ceil($total_rows / $rows);
} else {
    $total_page = 1;
}

$from = ($page - 1) * $rows;
$numbering = $total_rows - $from;
// 페이징 끝
$member_list = get_member_join_co_list($db,$where_query,'',$from,$rows);
$member_list_all = get_member_join_co_list($db,$where_query,'all');

$subquery= '&mc_coseq_page='.$mc_coseq_page.'&keyword='.$_REQUEST['keyword'].'&mm_status='.$_REQUEST['mm_status'].'&rows='.$_REQUEST['rows'];
?>
<style>
.center-block.center img { height: 70px; margin: 0 auto; margin-bottom: 10px;}
.center-block.center .title { width: 100%; margin: 0 auto; font-size: 18px; height: 40px; line-height: 40px; text-align: center; padding: 0 20px;}
.center-block.center .title span{float: right;}
.center-block.center .sub-title{width: 100%; margin: 0 auto; margin-top:4px; font-size: 16px; height: 30px; line-height: 30px; text-align: center; padding: 0 20px; background-color:#141819; color: #fff}

.modal {top: 5%;}
.modal-dialog{width: 1280px;}
.modal-body{height: 500px;overflow-y: scroll;}
.ajax-table{margin-left: 3.87%;}

.go-detail{cursor: pointer;}
.show-rows {width: 8%; position: absolute; right: 270px; top: 15px;}
.modal-header{text-align: center;}
</style>
<section class="body">

	<!-- start: header -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_header.php'; ?>
	<!-- end: header -->

	<div class="inner-wrapper">
		<!-- start: sidebar -->
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_gnb_menu.php'; ?>
		<!-- end: sidebar -->

		<section role="main" class="content-body" style="z-index:9999;">
            <header class="page-header">
                <h2>인재 검색</h2>
                <div class="panel-actions">
                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                </div>
            </header>
            <!-- start: page -->
            <div class="row">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title">법인 선택</h2>
                    </header>
                    <div class="panel-body  col-md-12 " style="display: block;">
                        <form class="form-horizontal" id="form_proccess" action="<?= $_SERVER['PHP_SELF']?>" method="post">
                            <input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">
                            <div class="form-group col-md-12">
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">법인</label>
                                <div class="col-md-2">
                                    <div class="input-daterange input-group col-md-3"  >
                                        <select class="form-control mb-md" name="mc_coseq_page">
                                            <option value="">전체</option>
                                            <?for($i=0;$i<sizeof($coperationList);$i++){?>
                                                <option value="<?=$coperationList[$i]['co_seq']?>" <?if($mc_coseq_page==$coperationList[$i]['co_seq']){?>selected<?}?>><?=$coperationList[$i]['co_name']?> <?if($coperationList[$i]['co_subname']!=""){?> ( <?=$coperationList[$i]['co_subname']?> )<?}?></option>
                                            <?}?>
                                        </select>
                                    </div>
                                </div>
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">재직 여부</label>
                                <div class="col-md-2">
                                    <div class="input-daterange input-group col-md-3"  >
                                        <select class="form-control mb-md" name="mm_status">
                                            <option value="Y" <?if($_REQUEST['mm_status']=='Y'){?>selected<?}?>>재직</option>
                                            <option value="D" <?if($_REQUEST['mm_status']=='D'){?>selected<?}?>>퇴직</option>
                                            <option value="all" <?if($_REQUEST['mm_status']=='all'){?>selected<?}?>>전체</option>
                                        </select>
                                    </div>
                                </div>
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">목록 갯수</label>
                                <div class="col-md-2">
                                    <div class="input-daterange input-group col-md-3"  >
                                        <select class="form-control mb-md" name="rows">
                                            <option value="10" <?if($_REQUEST['rows']=='10'){?>selected<?}?>>10개</option>
                                            <option value="20" <?if($_REQUEST['rows']=='20'){?>selected<?}?>>20개</option>
                                            <option value="30" <?if($_REQUEST['rows']=='30'){?>selected<?}?>>30개</option>
                                            <option value="50" <?if($_REQUEST['rows']=='50'){?>selected<?}?>>50개</option>
                                            <option value="100" <?if($_REQUEST['rows']=='100'){?>selected<?}?>>100개</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <div class="row">
                <section class="panel panel-featured col-md-12 " style="border:none;">
                    <header class="panel-heading" >
                        <div class="panel-actions">
                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        </div>
                        <h2 class="panel-title">인재 검색 <small>( 소속,직위,직책,직무,직군,사번,성명,학교명,전공,키워드,자격증 검색 )</small></h2>
                    </header>
                    <div class="panel-body  col-md-12 " style="display: block;">
                        <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF']?>" method="post">
                            <select class="form-control mb-md" name="mc_coseq_page" style="display: none;">
                                <option value="">전체</option>
                                <?for($i=0;$i<sizeof($coperationList);$i++){?>
                                    <option value="<?=$coperationList[$i]['co_seq']?>" <?if($mc_coseq_page==$coperationList[$i]['co_seq']){?>selected<?}?>><?=$coperationList[$i]['co_name']?> <?if($coperationList[$i]['co_subname']!=""){?> ( <?=$coperationList[$i]['co_subname']?> )<?}?></option>
                                <?}?>
                            </select>
                            <div class="form-group col-md-12">
                                <label class="col-md-1 control-label" style="line-height: 30px; width:auto !important;">키워드</label>
                                <div class="col-md-4">
                                    <div class="input-daterange input-group col-md-12"  >
                                        <input type="text" class="form-control mb-md" name="keyword" value="<?=$_REQUEST['keyword']?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-2" >
                                    <button tabindex="-1" class="btn btn-primary" type="submit"> 검색하기 <i class="fa fa-search"></i> </button>
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
                        <button id="btn-human" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary" style="position: absolute; right: 160px; bottom: 10px;"><i class="fas fa fa-search"></i>인물비교</button>
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
                            <thead>
                                <tr>
                                    <th class="" style="text-align: center !important;width:80px;">선택</th>
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
                                <? if(isset($member_list) && count($member_list) > 0) { 
                                        for($i=0;$i<sizeof($member_list);$i++){
                                            ?>
                                    <tr class="view-member-qq">
                                        <td class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input class="chk-mmseq" type="checkbox" data-mcseq=<?=$member_list[$i]['mc_coseq']?> value="<?=$member_list[$i]['mmseq']?>" style="width: 18px; height: 18px;">
                                                </label>
                                            </div>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);">
                                            <?for($i2=0;$i2<sizeof($coperationList);$i2++){?>
                                                <?if($member_list[$i]['mc_coseq']==$coperationList[$i2]['co_seq']){?><?=$coperationList[$i2]['co_name']?> <?if($coperationList[$i2]['co_subname']!=""){?> ( <?=$coperationList[$i2]['co_subname']?> )<?}?><?}?>
                                            <?}?>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);"><p class="view-member-qq"><?=$member_list[$i]['mc_code']?></p></td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);"><?=$enc->decrypt($member_list[$i]['mm_name'])?></td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);"><?=$gender[$member_list[$i]['mm_gender']]?></td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);"><?=implode('<br> ',get_group_list_admin($db,$member_list[$i]['mmseq'],$member_list[$i]['mc_coseq']))?></td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);">
                                            <?=get_position_title_type_admin($db,$member_list[$i]['mc_position2'],2,$member_list[$i]['mc_coseq'])?>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);">
                                            <?=get_position_title_admin($db,$member_list[$i]['mc_position'],$member_list[$i]['mc_coseq'])?>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);">
                                            <p class="view-member-qq" ><?=substr($member_list[$i]['mm_birth'],0,10)?></p>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);">
                                            <?=substr($member_list[$i]['mc_regdate'],0,10)?>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);">
                                            <?=$member_state[$member_list[$i]['mm_status']]?>
                                        </td>
                                        <td class="center go-detail" onClick="detail_page(<?=$member_list[$i]['mc_coseq']?>,<?=$member_list[$i]['mmseq']?>);"><?=$member_list[$i]['mm_last_login']?></td>
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

            <!-- 모달 영역 -->
            <div class="modal fade" id="myModal" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">인물 비교 결과</h4>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row ajax-wrap">
                                    <div class="col-md-1 center">
                                        <table class="table table-bordered table-striped  table-hover mb-none" style="table-layout:fixed ">
                                            <tbody>
                                                <tr colspan=2>
                                                    <th colspan=2 style="text-align: center !important; height: 150px; width: 150px;">인물비교</th>
                                                </tr>
                                                <tr>
                                                    <th rowspan=10 style="text-align: center !important;">기본정보</th>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">성명(사번)</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">나이</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">법인</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">최종학력</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 80px;">소속</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">현 근속</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">직위</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 60px;">직책</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 100px;">자격증</th>
                                                    </tr>
                                                </tr>
                                                <tr colspan=2>
                                                    <th colspan=2 style="text-align: center !important; height: 50px;">입사일자<br>(그룹/자사)</th>
                                                </tr>
                                                <tr colspan=2>
                                                    <th colspan=2 style="text-align: center !important; height: 50px;">직무</th>
                                                </tr>
                                                <tr colspan=2>
                                                    <th colspan=2 style="text-align: center !important; height: 50px;">직군</th>
                                                </tr>
                                                <tr>
                                                    <th rowspan=4 style="text-align: center !important;">인사평가</th>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 31px;">평가년도</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 31px;">1차 등급</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center !important; height: 31px;">2차 등급</th>
                                                    </tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 모달 영역 -->

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
    </div>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>
<script>
    var $subquery = "<?=$subquery?>";
    var page = "<?=$page?>";

    function tableToExcel(id) {
        var data_type = 'data:application/vnd.ms-excel;charset=utf-8';
        var table_html = encodeURIComponent(document.getElementById(id).outerHTML);
    
        var a = document.createElement('a');
        a.href = data_type + ',%EF%BB%BF' + table_html;
        a.target = '_blank';
        a.download = 'employee_excel'+'.xls';
        a.click();
    }
    
	$('select[name="mc_coseq_page"]').change(function(){
		$('#form_proccess').submit();
	});

    $('select[name="rows"]').change(function(){
		$('#form_proccess').submit();
	});

    $('select[name="mm_status"]').change(function(){
		$('#form_proccess').submit();
	});

    function detail_page(mc_coseq,mseq){
        location.href="/manage/humanDetail.php?mccoseq="+mc_coseq+"&mmseq="+mseq+"&page="+page+$subquery;
    }

    $('#btn-human').click(function(e){
        e.preventDefault();
        let $chk_count=0;
        let $mmseqArray=[];
        let $mcseqArray=[];
        $('.chk-mmseq').each(function(i,e){
            if($(e).is(":checked")){
                $chk_count += 1;
                $mmseqArray.push($(e).val());
                $mcseqArray.push($(e).data('mcseq'));
            }
        });
        if($chk_count < 2){
            alert('최소 두 명이상 선택해주세요.');
            return false;
        }else{
            $('.ajax-table').remove();
            $mmseqArray.forEach(function(e,i){
                $.ajax({
                    url : "/manage/proc/compareProc.php",
                    data : { 
                        'mc_coseq' : $mcseqArray[i],
                        'mmseq' : e,
                    },
                    dataType :"html",
                    method : "post",
                    success : function(result){
                        $('.ajax-wrap').append(result);
                    }
                });
            });
            
            $('#myModal').modal({
                backdrop: false,
                show : false,
            });
        }
    });
</script>