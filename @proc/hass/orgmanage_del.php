<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';

@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
$id = $_REQUEST['id'];

$query = "delete from tbl_ess_group where tg_seq in 
            (SELECT tg_seq FROM
                (SELECT tg_seq,tg_title,tg_parent_seq,tg_coseq,
                        CASE WHEN tg_seq = {$id} THEN @idlist := CONCAT(tg_seq)
                             WHEN FIND_IN_SET(tg_parent_seq,@idlist) THEN @idlist := CONCAT(@idlist,',',tg_seq)
                        END as checkId
                 FROM tbl_ess_group
                 ORDER BY tg_seq ASC) as T
            WHERE checkId IS NOT NULL and tg_coseq = {$mc_coseq})";
pdo_query($db,$query,array());

// 삭제 하위조직에 포함된 구성원도 초기화 시켜줘야함
$result['code']='TRUE';
$result['msg']= ' 처리되었습니다.';
echo json_encode($result);
?>
