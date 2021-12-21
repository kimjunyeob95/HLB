<?php 
session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/admin_auth_check.php';

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");

@$page = $_REQUEST['page'];
@$type = $_REQUEST['type'];
@$coseq = $_REQUEST['coseq'];
@$co_name = $_REQUEST['co_name'];
@$have_logo = $_REQUEST['have_logo'];
@$co_subname = $_REQUEST['co_subname'];
@$co_address = $_REQUEST['co_address'];
@$code_value = $_REQUEST['code_value'];
@$co_color = $_REQUEST['co_color'];

// echo('<pre>');print_r($_FILES);echo('</pre>');
// echo('<pre>');print_r($_REQUEST);echo('</pre>');exit;
$result['code']='FALSE';
$result['msg']='잘못된 접근입니다.';

if(empty($type)){
	echo json_encode($result);
	exit;
}

/*		파일 업로드	**/
$save_path = $_SERVER['DOCUMENT_ROOT'].'/data/logo';
@$file_content1 = $_FILES['file1']['name'];
$c_file1="";

$enc = new encryption();

if($type=="등록"){
    if(!empty($file_content1)){
        $tmp_file_check = file_check_fn($_FILES['file1']);
        for($i=0;$i<sizeof($tmp_file_check);$i++){
    
            if($tmp_file_check[$i]['result']=='FALSE'){
                page_move("/manage/coperation.php?page=".$page,'업로드 할 수 없는 형식의 파일입니다.');
                exit;
            }
        }	
    }
    if(empty($file_content1)==FALSE){
        list($_ori_filename, $ext) = explode('.', $_FILES['file1']['name']);
        $ext = pathinfo( $_FILES['file1']['name'], PATHINFO_EXTENSION);
        $filename =  $co_name.'_logo_'.date('YmdHis');
        $ff = $_FILES['file1']['tmp_name'];
        move_uploaded_file($ff, $save_path.'/'.$filename.".".$ext);
        $c_file1 = $filename.".".$ext;
    }
	// 법인 신규 등록
	$query="INSERT tbl_coperation SET
					co_type=?,
					co_name=?,
					co_subname=?,
					co_address=?,
					co_logo=?,
					co_color=?";
    $ps = pdo_query($db,$query,array($code_value,$co_name,$co_subname,$co_address,$c_file1,$co_color));

    /** hass 사원 등록 **/

    //법인 seq 구하기
    $query="SELECT co_seq FROM tbl_coperation WHERE
            co_type=?
            and co_name=?
            and co_subname=?
            and co_address=?
            and co_logo=?
            and co_color=?";
    $ps = pdo_query($db,$query,array($code_value,$co_name,$co_subname,$co_address,$c_file1,$co_color));
    $mm_coseq = $ps->fetch(PDO::FETCH_ASSOC);
    $mm_coseq = $mm_coseq['co_seq'];

    $query="INSERT tbl_ess_group SET
            tg_coseq=?,
            tg_parent_seq=?,
            tg_title=?,
            tg_regDate=now()";
    $ps = pdo_query($db,$query,array($mm_coseq,0,$co_name));

    //슈퍼어드민 업데이트
    $query="SELECT * FROM ess_member_base A JOIN ess_member_code B ON A.mmseq=B.mc_mmseq WHERE
            mm_is_del = ? AND mm_super_admin = ? GROUP BY mmseq ";
    $ps = pdo_query($db,$query,array('FALSE','T'));
    $superList = array();
    while($data = $ps->fetch(PDO::FETCH_ASSOC)){
        array_push($superList, $data);
    }

    foreach($superList as $index => $val){
        $query = "INSERT INTO ess_member_code SET
                mc_mmseq={$val['mmseq']}, mc_coseq={$mm_coseq}, mc_code={$val['mc_code']}, mc_regdate=now(), mc_main='T', mc_hass='T' ";
        $ps = pdo_query($db,$query,array());
    }

    page_move("/manage/coperation.php?page=".$page,'법인이 등록되었습니다.');
}else if($type=="수정"){
    // 법인 수정
    //이미지 새로등록
    if(!empty($file_content1) && !empty($have_logo)){
        $tmp_file_check = file_check_fn($_FILES['file1']);
        for($i=0;$i<sizeof($tmp_file_check);$i++){
    
            if($tmp_file_check[$i]['result']=='FALSE'){
                page_move("/manage/coperation.php?page=".$page,'업로드 할 수 없는 형식의 파일입니다.');
                exit;
            }
        }	
        if(empty($file_content1)==FALSE){
            list($_ori_filename, $ext) = explode('.', $_FILES['file1']['name']);
            $ext = pathinfo( $_FILES['file1']['name'], PATHINFO_EXTENSION);
            $filename =  $co_name.'_logo_'.date('YmdHis');
            $ff = $_FILES['file1']['tmp_name'];
            move_uploaded_file($ff, $save_path.'/'.$filename.".".$ext);
            $c_file1 = $filename.".".$ext;
        }
        $query="UPDATE tbl_coperation SET
            co_type=?,
            co_name=?,
            co_subname=?,
            co_address=?,
            co_logo=?,
            co_color=? WHERE co_seq=?";
            // echo('<pre>');print_r($co_type);echo('</pre>');exit;
        $ps = pdo_query($db,$query,array($code_value,$co_name,$co_subname,$co_address,$c_file1,$co_color,$coseq));
        
        //기존 이미지 삭제
        unlink($_SERVER['DOCUMENT_ROOT'].'/data/logo/'.$have_logo);

        page_move("/manage/coperation.php?page=".$page,'법인이 수정되었습니다.');
    }else{
        //기존이미지 유지
        $query="UPDATE tbl_coperation SET
            co_type=?,
            co_name=?,
            co_subname=?,
            co_address=?,
            co_color=? WHERE co_seq=?";
        $ps = pdo_query($db,$query,array($code_value,$co_name,$co_subname,$co_address,$co_color,$coseq));
        page_move("/manage/coperation.php?page=".$page,'법인이 수정되었습니다.');
    }
	
}else if($type=="삭제"){
    $query="UPDATE tbl_coperation SET
    co_is_del='TRUE' WHERE co_seq=?";
    $ps = pdo_query($db,$query,array($coseq));
    page_move("/manage/coperation.php?page=".$page,'법인이 삭제되었습니다.');
}






?>