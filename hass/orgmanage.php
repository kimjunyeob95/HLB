<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

$list = get_group_list_all($db);
// echo('<pre>');print_r($list);echo('</pre>');
$query = "select count(distinct trg_mmseq) as cnt from tbl_relation_group trg  
            left join ess_member_code emc on trg_mmseq = emc.mc_mmseq
            left join ess_member_base emb on trg_mmseq = emb.mmseq
            where trg_coseq = {$_SESSION['mInfo']['mc_coseq']} and mc_coseq = {$_SESSION['mInfo']['mc_coseq']} and mm_is_del='FALSE' and mm_super_admin='F' and mm_status='Y' ";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);
$group_cnt = $data['cnt'];
function has_children($rows,$id) {
    foreach ($rows as $row) {
        if ($row['tg_parent_seq'] == $id)
            return true;
    }
    return false;
}
// echo('<pre>');print_r($list);echo('</pre>');
function build_menu($rows,$parent=0)
{
    
    $result='';
    foreach ($rows as $row) {
        // echo('<pre>');print_r($row);echo('</pre>');
        if ($row['tg_parent_seq'] == $parent) {
              $result .=  '<ul>';
              $result .=  '<li>';
              $result .=  '<div class="insert">';
              if($row['tg_parent_seq']==0){
                  $result .= '<label class="label">'.$row['tg_title'].'</label>';
              }else{
                  $result .= '<input type="text" class="input-text" name="" value="'.$row['tg_title'].'">';
              }
              $result .= '<button class="btn type01 small btn_view" data-id="'.$row['tg_seq'].'">관리</button>';
              $result .= '<button class="btn type01 small del btn-remove-inputs" data-pid="'.$row['tg_parent_seq'].'" data-id="'.$row['tg_seq'].'">X</button>';
              if($row['tg_parent_seq']==0) {
                  $result .= '<label class="label">총 인원 : '.$GLOBALS['group_cnt'].'명</label>';
              }
              $result .= '<button class="btn type01 small btn-toggle minus">접기</button>';
              $result .= '</div>';
            if (has_children($rows, $row['tg_seq'])) {
                $result .= build_menu($rows, $row['tg_seq']);
            }
            $result .= "</li></ul>";
        }
    }
    
    return $result;
}
?>
<style>
    .button-area.large{text-align: left;}
    .button-area.large.hide{display:none;}
    .add-inputs{margin-top:15px; margin-left:40px;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

    <!-- CONTENT -->
    <div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
        <?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

        <!-- 내용 -->
        <div id="content" class="content-primary">
            <h2 class="content-title">조직 관리</h2>
            
            <div class="section-wrap" style="display: flex;">
                <div class="add-wrap">
                    <?=build_menu($list);?>
                </div>
                <div class="add-wrap" style="padding: 8px 0 8px 40px;">
                    <div class="insert">
                        <input type="text" class="input-text" name="" value="무소속">
                        <button class="btn type01 small btn_view_no" data-id="">관리</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // 내용 -->
    </div>
    <!-- // CONTENT -->
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
    <!-- // WRAP -->
    <script>
        $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');
        $(document).on('click','.btn-remove-inputs',function(e){
            e.preventDefault();
            pid = $(this).data('pid');
            id = $(this).data('id');
            title = $(this).siblings('input').val();
            if(pid==0){
                alert("최상위 조직은 삭제할수 없습니다.");
                return false;
            }
            if(confirm(title+'을 정말로 삭제하겠습니까?')){
                $.ajax({
                    url : "/@proc/hass/orgmanage_del.php",
                    type : 'post',
                    data : {'id':id},
                    dataType: 'json',
                    success:function(data){
                        if(data.code=='FALSE'){
                            alert(data.msg);
                            return;
                        }else
                            alert(data.msg);
                            location.reload();
                    }
                })
            }
        });
        $(document).on('click','.btn_view',function(){
            id = $(this).data('id');
            location.href='/hass/orgmanage_view?id='+id;
        });
        $(document).on('click','.btn_view_no',function(){
            location.href='/hass/orgmanage_no_view';
        });
        $('.btn-toggle').click(function(){
            if($(this).parent().next().length<1) return;
            if($(this).hasClass('minus')){
                $(this).removeClass('minus');
            }else{
                $(this).addClass('minus');
            }
            $(this).parent().siblings('ul').toggle();
        });
    </script>
