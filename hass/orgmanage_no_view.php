<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$coseq = $_SESSION['mInfo']['mc_coseq'];
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
/*
$query = "select * from tbl_ess_group where tg_parent_seq = {$_REQUEST['id']} order by tg_sort_date asc";
$ps = pdo_query($db, $query, array());
$list = array();
while($data = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($list, $data);
}

$query = "select * from tbl_ess_group where tg_seq = {$_REQUEST['id']}";
$ps = pdo_query($db, $query, array());
$data = $ps->fetch(PDO::FETCH_ASSOC);
$position_list = get_position_list($db,1);

$leader_info = get_member_info($db,$data['tg_mms_mmseq']);
*/
// 조직원 리스트
$where=" and mm_is_del='FALSE' and mm_super_admin='F' and mm_status='Y' ";
if(!empty($_REQUEST['search_category']) && !empty($_REQUEST['search']) && $_REQUEST['search_category']=='mc_code'){
    $where .= " and mc_code = {$_REQUEST['search']} ";
}
if(!empty($_REQUEST['search_category']) && !empty($_REQUEST['search']) && $_REQUEST['search_category']=='mm_name'){
    $where .= " and mm_name = '{$enc->encrypt($_REQUEST['search'])}' ";
}
if(!empty($_REQUEST['search_category']) && !empty($_REQUEST['search']) && $_REQUEST['search_category']=='mm_email'){
    $where .= " and mm_email = '{$enc->encrypt($_REQUEST['search'])}' ";
}
if(!empty($_REQUEST['search_category']) && !empty($_REQUEST['search']) && $_REQUEST['search_category']=='mm_phone'){
    $where .= " and mm_phone = '{$enc->encrypt($_REQUEST['search'])}' ";
}


$query = "select distinct emb.mmseq,emb.*,emc.*,trg.* from ess_member_base emb 
            left join  ess_member_code emc on emc.mc_mmseq = emb.mmseq 
            left join tbl_relation_group trg on emb.mmseq = trg.trg_mmseq 
            where trg.trg_group is null and mc_coseq = {$coseq} ".$where;
$ps = pdo_query($db, $query, array());
$member_list = array();
while($data1 = $ps->fetch(PDO::FETCH_ASSOC)){
    array_push($member_list, $data1);
}

?>
<style>

    .button-area.large{text-align: left;}
    .main-input.common-div{margin-top: 14px;}
    .main-input.common-div:first-child{margin-top: 0px;}

    .insert.search-div {position: relative;}
    .add-wrap ul li {position: relative;padding: 4px 0 4px 0px;}
    .search-div .result {position: absolute; z-index: 99; width: 14.5%; left: 5.5%; height: fit-content; border: 1px solid #d1d1d1;border-radius: 8px; overflow-y: scroll;}
    .search-div .result.hide{display: none;}
    .search-div .result ul{background-color: whitesmoke; padding: 8px;}
    .search-div .result li{padding-bottom: 10px; cursor: pointer;}

    .info-wrap tr td{cursor: pointer;}
    .tab-cont.hide{display: none;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
    <? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

    <!-- CONTENT -->
    <div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
        <?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

        <!-- 내용 -->
        <div id="content" class="content-primary">
            <h2 class="content-title">무소속 조직</h2>
            <div class="section-wrap">
                <div class="add-wrap">
                    <!-- <div class="insert">
                        <select name="tg_mms_mmseq" class="select" id="tg_mms_mmseq">
                            <?foreach ($position_list as $val){?>
                                <option value="<?=$val['tp_seq']?>" <?if($val['tp_seq']==$data['tg_mms_mmseq']){?>selected<?}?>><?=$val['tp_title']?></option>
                            <?}?>
                        </select>
                    </div> -->
<!--                    <div class="insert search-div">-->
<!--                        <label class="label" for="search">팀장 성명검색</label>-->
<!--                        <div class="input-search">-->
<!--                            <input type="search" id="input-search1" name="" value="--><?//if(!empty($enc->decrypt($leader_info['mm_name'])) && !empty($leader_info['mc_code'])){?><!----><?//=$enc->decrypt($leader_info['mm_name'])?><!--(--><?//=$leader_info['mc_code']?><!--)--><?//}?><!--" >-->
<!--                            <a class="btn" href="#" id="btn_search"><img alt="검색" src="../@resource/images/common/search02.png"></a>-->
<!--                        </div>-->
<!--                        <div class="result search1-result hide">-->
<!--                            <ul id="search_list">-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
                    <?if(sizeof($list) <1){?>
                        <!-- <div class="main-input common-div">
                            <input type="text" title="조직" name="category" class="input-text" style="max-width:20%;" value="" placeholder="조직" />
                            <button class="btn type01 small main-input-remove">삭제</button>
                        </div> -->
                    <?}else{?>
                        <?foreach ($list as $val){?>
                            <div class="main-input common-div">
                                <input type="text" title="조직" name="category" data-id="<?=$val['tg_seq']?>" class="input-text" style="max-width:20%;" value="<?=$val['tg_title']?>" placeholder="조직" />
                                <button class="btn type01 small main-input-remove">삭제</button>
                                <label class="label" style="margin-left: 10px; vertical-align: sub;">조직 정렬날짜 :</label>
                                <input type="text" class="input-text input-datepicker"  title="조직 정렬날짜" name="sort_date" style="max-width:40%;" value="<?=substr($val['tg_sort_date'],0,10)?>">
                            </div>
                        <?}?>
                    <?}?>
                </div>
                <div class="section-wrap" style="margin-top: 15px;">
                    <h3 class="section-title" style="display: inline-block;">조직명 사원목록</h3>
                    <form method="post" action="<?= $_SERVER['PHP_SELF']?>"  style="display: inline-block; vertical-align: super; margin-left:10px;">
                        <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                        <select name="search_category" id="cboArea">
                            <option value="mc_code" <?if($_REQUEST['search_category']=='mc_code'|| empty($_REQUEST['search_category'])){?>selected<?}?>>사번</option>
                            <option value="mm_name"  <?if($_REQUEST['search_category']=='mm_name'){?>selected<?}?>>성명</option>
                            <option value="mm_email" <?if($_REQUEST['search_category']=='mm_email'){?>selected<?}?>>E-Mail</option>
                            <option value="mm_phone" <?if($_REQUEST['search_category']=='mm_phone'){?>selected<?}?>>연락처</option>
                        </select>
                        <div class="input-search" id="" style="display: inline-block; width: 300px;">
                            <input type="text" type="number" value="<?=$_REQUEST['search']?>" name="search" style="width: 298px; border:0;"/>
                            <button type="submit" class="btn"  style="width: 20px;">
                                <img alt="검색" src="../../@resource/images/common/search02.png" onclick="">
                            </button>
                        </div>
                        <button type="submit" id="" class="btn type01 small">조회</button>
                    </form>
                    <!-- 공지사항 -->

                    <div class="table-wrap info-wrap" style="height: 300px; overflow-y: scroll;">
                        <table class="data-table" id="tb_insaHeader">
                            <caption>인사 발령 내역</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 3%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">no</th>
                                <th scope="col">사번</th>
                                <th scope="col">성명</th>
                                <th scope="col">성별</th>
                                <th scope="col">소속</th>
                                <th scope="col">직위</th>
                                <th scope="col">직책</th>
                                <th scope="col">나이</th>
                                <th scope="col">입사일자</th>
                                <th scope="col">재직여부</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?$member_count = sizeof($member_list);?>
                            <?foreach ($member_list as $val){?>
                                <tr class="get_info_list" data-seq = "<?=$val['mmseq']?>">
                                    <td><?=$member_count--?></td>
                                    <td><?=$val['mc_code']?></td>
                                    <td class="center"><?=$enc->decrypt($val['mm_name'])?></td>
                                    <td class="center"><?=$gender[$val['mm_gender']]?></td>
                                    <td>
                                        <?=implode('/ ',get_group_list_v2($db,$val['mmseq']))?>
                                    </td>
                                    <td><?=get_position_title_type($db,$val['mc_position2'],2)?></td>
                                    <td><?=get_position_title($db,$val['mc_position'])?></td>
                                    <td>만 <?=get_age(substr($val['mm_birth'],0,10))?>세</td>
                                    <td><?=substr($val['mc_regdate'],0,10)?></td>
                                    <td><?=$member_state[$val['mm_status']]?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                        <!-- <?=get_page_html_v2($page, $total_rows, $rows, 5,$paging_subquery)?> -->
                    </div>
                    <!-- //공지사항 -->

                    <div id="tab-information" class="tab-cont show-data">
                    </div>
                </div>
            </div>
            <!-- // 내용 -->
        </div>
        <!-- // CONTENT -->
        <? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
        <!-- // WRAP -->
        <script>
            $('.input-datepicker').datepicker();

            $('#tb_insaHeader tr').click(function(){
                $('.show-data').addClass('hide');
                $('.show-data').removeClass('hide');
            });

            $('.get_info_list').click(function(){
                seq = $(this).data('seq');
                $.ajax({
                    url : '/@proc/hass/get_info.php',
                    data : {seq:seq},
                    type : 'post',
                    dataType : 'html',
                    success : function(data){
                        $('#tab-information').html(data);
                    }
                });
            })


            var pid = '<?=$_REQUEST['id']?>';
            $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');
            $('.main-input-add').click(function(e){
                e.preventDefault();
                var $add_text = `
                    <div class="main-input common-div">
                        <input type="text" title="조직" name="category" class="input-text" style="max-width:20%;" value="" placeholder="조직" />
                        <button class="btn type01 small add-input-remove">삭제</button>
                        <label class="label" style="margin-left: 10px; vertical-align: sub;">조직 정렬날짜 :</label>
                        <input type="text" class="input-text input-datepicker"  title="조직 정렬날짜" name="sort_date" style="max-width:40%;" value="">
                    </div>`;
                $('.add-wrap').append($add_text);
                $('.input-datepicker').datepicker();
            });
            $(document).on('click','.main-input-remove', function(e){
                e.preventDefault();
                var input_count=$('.main-input').length;
                //if(input_count==1) return;
                $(this).parent().remove();
            });
            $(document).on('click','.add-input-remove', function(e){
                e.preventDefault();
                $(this).parent().remove();
            });

            $('#btn-save').click(function(){
                if(mmseq=='' || mmseq==null){
                    alert('팀장을 선택해주세요.');
                    return false;
                }
                var check = true;
                var data = [];
                var data_date = [];
                var obj = $('[name=category]');
                var obj2 = $('[name=sort_date]');
                $(obj).each(function(i){
                    id = $(this).data('id');
                    if(id=='' || id ==undefined){
                        id = 0;
                    }
                    data[i] = Array($(this).val(),id);
                    if($(this).val()==0 || $(this).val()==null){
                        alert('빈값을 모두 입력해주세요.');
                        $(this).focus();
                        check = false;
                        return false;
                    }
                });

                $(obj2).each(function(i){
                    data_date[i] = $(this).val();
                    if($(this).val()==0 || $(this).val()==null){
                        alert('빈값을 모두 입력해주세요.');
                        $(this).focus();
                        check = false;
                        return false;
                    }
                });

                if(!check){
                    return false;
                }
                //mms_mmseq = $('#tg_mms_mmseq option:selected').val();

                $.ajax({
                    url : "/@proc/hass/orgmanage_proc.php",
                    type : 'post',
                    data : {'data':data,'data_date':data_date,'pid':pid,'leader_mmseq':mmseq},
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
            });
        </script>
