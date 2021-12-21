<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

$query = "SELECT * FROM tbl_ess_group WHERE tg_coseq = 1";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}
function Tree_Array($atr, $id='id', $parent_id='parent_id', $children='children')
{
    $nlist = array(
        array( $children => array() )
    );
    $raw = array(&$nlist[0]);
    if(isset($atr))
    {
        foreach($atr as $q => $w)
        {
            $raw[$w[$parent_id]][$children][$w[$id]] = $w;
            $raw[$w[$id]] = &$raw[$w[$parent_id]][$children][$w[$id]];
        }
    }

    return $nlist[0][$children];
}

function has_children($rows,$id) {
    foreach ($rows as $row) {
        if ($row['tg_parent_seq'] == $id)
            return true;
    }
    return false;
}

function build_menu($rows,$parent=0,$i=1)
{
    //echo $i;
    $result='';
        foreach ($rows as $row) {
            if ($row['tg_parent_seq'] == $parent) {
                $result .= "<div class='add-inputs common-div'>";
                $result .= '<input type="text" title="하위" class="input-text" style="max-width:20%;" name="" value="" placeholder="'.$row['tg_title'].'">
                <button class="btn type01 small btn-add-inputs">추가</button>
                <button class="btn type01 small btn-remove-inputs">삭제</button>';
                if (has_children($rows, $row['tg_seq']))
                    $result .= build_menu($rows, $row['tg_seq'],++$i);

                $result .= "</div>";
            }
        }
    return $result;
}
?>

<style>
    .button-area.large{text-align: left;}
    .button-area.large.hide{display:none;}
    .add-inputs{margin-top:15px; margin-left:40px;}
    .add-inputs .add-inputs-more{margin-top:15px; margin-left:40px;}
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
            <!--        <select name="cboArea" id="cboArea">-->
            <!--            <option>부서명</option>-->
            <!--        </select>-->
            <!--        <div class="input-search"  style="display: inline-block; width: 300px;">-->
            <!--            <input type="text" type="number" value="" name="search" style="width: 298px; border:0;"/>-->
            <!--            <button type="submit" class="btn"  style="width: 20px;">-->
            <!--                <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">-->
            <!--            </button>-->
            <!--        </div>-->
            <!--        <button type="submit" class="btn type01 small">조회</button>-->

            <div class="section-wrap">
                <h3 class="section-title">조직 추가</h3>
                <div class="add-wrap">
                    <?=build_menu($list);?>
                </div>
                <div class="button-area large hide">
                    <button id="btn-save" type="button" class="btn type12 medium">저장</button>
                </div>
            </div>
            <!--		<div class="section-wrap" style="margin-top: 15px;">-->
            <!--             공지사항 -->
            <!--             <h3 class="section-title">공지사항</h3> -->
            <!--            <div class="table-wrap">-->
            <!--                <table class="data-table" id="tb_insaHeader">-->
            <!--                    <caption>신입 사원 승인 내역</caption>-->
            <!--                    <colgroup>-->
            <!--                        <col style="width: 20px" />-->
            <!--                        <col style="width: 80px" />-->
            <!--                        <col style="width: 80px" />-->
            <!--                        <col style="width: 80px" />-->
            <!--                    </colgroup>-->
            <!--                    <thead>-->
            <!--                    <tr>-->
            <!--                        <th scope="col">no</th>-->
            <!--                        <th scope="col">부서명</th>-->
            <!--                        <th scope="col">인원</th>-->
            <!--                        <th scope="col">관리</th>-->
            <!--                    </tr>-->
            <!--                    </thead>-->
            <!--                    <tbody>-->
            <!--                        <tr>-->
            <!--                            <td>1</td>-->
            <!--                            <td class="center">개발팀</td>-->
            <!--                            <td class="center">사장 1명</td>-->
            <!--                            <td>-->
            <!--                                <div class="btn-area" onclick="move_detail_page();">-->
            <!--                                    <a class="btn type01 medium">수정</a>-->
            <!--                                </div>-->
            <!--                            </td>-->
            <!--                        </tr>-->
            <!--                        <tr>-->
            <!--                            <td>2</td>-->
            <!--                            <td class="center">상용사업본부</td>-->
            <!--                            <td class="center">전무 3명, 상무 5명, 이사 6명, 이사대우 8명</td>-->
            <!--                            <td>-->
            <!--                                <div class="btn-area" onclick="move_detail_page();">-->
            <!--                                    <a class="btn type01 medium">수정</a>-->
            <!--                                </div>-->
            <!--                            </td>-->
            <!--                        </tr>-->
            <!--                    </tbody>-->
            <!--                </table>-->
            <!--                <div class="pagination">-->
            <!--                    <a href="javascript:void(0);" class="first">처음</a>-->
            <!--                    <a href="javascript:void(0);" class="prev">이전</a>-->
            <!--                    <span class="page">-->
            <!--                        <strong title="현위치">1</strong>-->
            <!--                        <a href="#">2</a>-->
            <!--                        <a href="#">3</a>-->
            <!--                        <a href="#">4</a>-->
            <!--                    </span>-->
            <!--                    <a href="javascript:void(0);" class="next">다음</a>-->
            <!--                    <a href="javascript:void(0);" class="last">끝</a>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            //공지사항 -->
            <!--		</div>-->
        </div>
        <!-- // 내용 -->
    </div>
    <!-- // CONTENT -->
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
    <!-- // WRAP -->
    <script>
        $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');

        $('#main-input-add').click(function(e){
            e.preventDefault();
            var $add_text = `
                <div class="add-inputs">
                    <input type="text" title="하위" class="input-text" style="max-width:20%;" name="" value="" placeholder="하위" />
                    <button class="btn type01 small btn-add-inputs">추가</button>
                    <button class="btn type01 small btn-remove-inputs">삭제</button>
                </div>`;
            $('.add-wrap').append($add_text);
            if($('.button-area.large').hasClass('hide')){
                $('.button-area.large').removeClass('hide');
            }
        });

        $('#main-input-remove').click(function(e){
            e.preventDefault();
            var input_count=$('.add-wrap').find('.add-inputs').length;
            if(input_count==1){
                if(!$('.button-area.large').hasClass('hide')){
                    $('.button-area.large').addClass('hide');
                }
            }else if(input_count<1) return;
            $('.section-wrap').find('.add-inputs:last-child').remove();

        });

        $(document).on('click','.btn-add-inputs',function(e){
            e.preventDefault();
            var $add_text = `
                <div class="add-inputs-more">
                    <input type="text" title="하위" class="input-text" style="max-width:20%;" name="" value="" placeholder="하위" />
                    <button class="btn type01 small btn-add-inputs">추가</button>
                    <button class="btn type01 small btn-remove-inputs">삭제</button>
                </div>`;
            $(this).parent().append($add_text);
        });
        $(document).on('click','.btn-remove-inputs',function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
    </script>
