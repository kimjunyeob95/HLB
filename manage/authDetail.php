<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?
/*****************************************
	파 일 명 : superadminDetail.php
	설     명  : 슈퍼어드민 생성
    작성일 : 2021-01-21
*****************************************/
$enc = new encryption();

@$mm_name = $_REQUEST['mm_name'];
@$page = $_REQUEST['page'];
@$mmseq = $_REQUEST['mmseq'];

if(empty($page)){
	$page=1;
}
$where = " 1=1 ";
$title = "등록";
if(!empty($mmseq)){
    $title="수정";
    $where .= " AND mm_seq={$mmseq} ";
    $query="SELECT * FROM tbl_manager_member WHERE ".$where;
    $ps = pdo_query($db, $query, array());
    $data = $ps->fetch(PDO::FETCH_ASSOC);
}else{
    $mmseq = 'false';
}


//echo('<pre>');print_r($query);echo('</pre>');
// echo $_SERVER["REMOTE_ADDR"];exit;

$subquery="mm_name=".urlencode($mm_name)."&page=".$page;
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
label{vertical-align:sub;}
.fa-warning{color:#ff9900;}

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
                <h2><i class="fas fa fa-external-link-alt"></i> 권한 관리 - 관리자 <?=$title?></h2>
                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="/manage/main.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>권한 관리 - 관리자 <?=$title?></span></li>
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
                        <h2 class="panel-title">관리자 <?=$title?></h2>
                    </div>
                    <div class="panel-body">
                        <form id="superForm">
                            <input type="hidden" name="mmseq" value="<?=$mmseq?>">
                            <table class="table table-bordered table-hover mb-none" style="table-layout:fixed ">
                                <tr>
                                    <th class="col-sm-1" style="text-align: center !important;">관리자 ID</th>
                                    <td class="col-sm-2"  style="text-align: center !important;" >
                                        <input type="text" title="관리자아이디" name="mm_id" value="<?=$data['mm_id']?>" class="form-control input-text"> 
                                    </td>
                                    <th class="col-sm-1" style="text-align: center !important;">관리자명</th>
                                    <td class="col-sm-2"  style="text-align: center !important;">
                                        <input type="text" title="관리자명" name="mm_name"  value="<?=$data['mm_name']?>" class="form-control input-text"> 
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-sm-1" style="text-align: center !important;">비밀번호</th>
                                    <td class="col-sm-2"  style="text-align: center !important;">
                                        <input type="password" placeholder="비밀번호는 영문+숫자를 혼합하여 8자리 ~ 20자리 이내로 입력해주세요." title="비밀번호" name="mm_pwd" value="<?=$enc->decrypt($data['mm_pwd'])?>" class="form-control password01 input-text"> 
                                    </td>
                                    <th class="col-sm-1" style="text-align: center !important;">비밀번호 확인</th>
                                    <td class="col-sm-2"  style="text-align: center !important;">
                                        <input type="password" placeholder="비밀번호는 영문+숫자를 혼합하여 8자리 ~ 20자리 이내로 입력해주세요." title="비밀번호 확인" value="<?=$enc->decrypt($data['mm_pwd'])?>" class="form-control password02 input-text"> 
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



$('.btn-go-list').click(function(e){
	e.preventDefault();
	location.href="/manage/auth.php?"+$subquery ;
});

$('#btn-save').click(function(e){
	e.preventDefault();
    let validate=true;
    $('#superForm').find('.input-text').each(function(e){
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
        if($('.password01').val() != $('.password02').val()){
            alert('비밀번호가 서로 다릅니다.');
            $('.password02').focus();
            return false;
        }else{
            let pw1 = $('.password01').val();
            let num = pw1.search(/[0-9]/g);
            let eng = pw1.search(/[a-z]/ig);
            if(pw1.length < 8 || pw1.length > 20){
                alert("비밀번호는 영문+숫자를 혼합하여 8자리 ~ 20자리 이내로 입력해주세요.");
                $('.password01').focus();
                return false;
            }else if(pw1.search(/\s/) != -1){
                alert("비밀번호는 공백 없이 입력해주세요.");
                $('.password01').focus();
                return false;
            }else if(num < 0 && eng < 0){
                alert("영문,숫자를 혼합하여 입력해주세요.");
                $('.password01').focus();
                return false;
            }
            $.ajax({
                url: '/manage/proc/authProc.php',
                type: 'post',
                data: {
                    "mm_seq" : <?=$mmseq?>,
                    "mm_id" : $('input[name="mm_id"]').val(),
                    "mm_name" : $('input[name="mm_name"]').val(),
                    "mm_pwd" : $('input[name="mm_pwd"]').val(),
                    "type": "<?=$title?>"
                },
                dataType: 'json',
                success: function(response) {
                    if(response.code=='False'){
                        alert(response.msg);
                        return false;
                    }else{
                        alert(response.msg);
                        location.href="/manage/auth.php?"+$subquery ;
                    }
                    
                }
            });
        }
    }
       

});

</script>