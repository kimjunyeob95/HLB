<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
?>
<?
    $query = "SELECT * FROM tbl_ess_group WHERE tg_coseq = 1";
    $ps = pdo_query($db, $query, array());
    $list = array();
    while($data = $ps->fetch(PDO::FETCH_ASSOC)){
        array_push($list, $data);
    }
    // echo('<pre>');print_r($list);echo('</pre>');
?>
<style>
    
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
            
            <div class="section-wrap">
                <div class="add-wrap">
                    <ul>
                        <li>
                            <div class="insert">
                                <label class="label"><?=$list[0]['tg_title']?></label>
                                <button class="btn type01 small">관리</button>
                                <button class="btn type01 small del">X</button>
                                <label class="label">총 인원 : <?=sizeof($list)?>명</label>
                                <button class="btn type01 small btn-toggle minus">접기</button>
                            </div>
                            <ul>
                                <li>
                                    <div class="insert">
                                        <input type="text" class="input-text" name="" value="2depth">
                                        <button class="btn type01 small">관리</button>
                                        <button class="btn type01 small del">X</button>
                                        <button class="btn type01 small btn-toggle minus">접기</button>
                                    </div>
                                    <ul>
                                        <li>
                                            <div class="insert">
                                                <input type="text" class="input-text" name="" value="3depth">
                                                <button class="btn type01 small">관리</button>
                                                <button class="btn type01 small del">X</button>
                                                <button class="btn type01 small btn-toggle minus">접기</button>
                                            </div>
                                            <ul>
                                                <li>
                                                    <div class="insert">
                                                        <input type="text" class="input-text" name="" value="4depth">
                                                        <button class="btn type01 small btn-add-inputs">관리</button>
                                                        <button class="btn type01 small del">X</button>
                                                        <button class="btn type01 small btn-toggle minus">접기</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="insert">
                                        <input type="text" class="input-text" name="" value="2depth">
                                        <button class="btn type01 small btn-add-inputs">관리</button>
                                        <button class="btn type01 small del">X</button>
                                        <button class="btn type01 small btn-toggle minus">접기</button>
                                    </div>
                                    <ul>
                                        <li>
                                            <div class="insert">
                                                <input type="text" class="input-text" name="" value="3depth">
                                                <button class="btn type01 small">관리</button>
                                                <button class="btn type01 small del">X</button>
                                                <button class="btn type01 small btn-toggle minus">접기</button>
                                            </div>
                                            <ul>
                                                <li>
                                                    <div class="insert">
                                                        <input type="text" class="input-text" name="" value="4depth">
                                                        <button class="btn type01 small btn-add-inputs">관리</button>
                                                        <button class="btn type01 small del">X</button>
                                                        <button class="btn type01 small btn-toggle minus">접기</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="insert">
                                        <input type="text" class="input-text" name="" value="2depth">
                                        <button class="btn type01 small btn-add-inputs">관리</button>
                                        <button class="btn type01 small del">X</button>
                                        <button class="btn type01 small btn-toggle minus">접기</button>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
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

        $('.btn-toggle').click(function(){
            if($(this).parent().next().length<1) return;
            if($(this).hasClass('minus')){
                $(this).removeClass('minus');    
            }else{
                $(this).addClass('minus');
            }
            $(this).parent().next().toggle();
        });
    </script>
