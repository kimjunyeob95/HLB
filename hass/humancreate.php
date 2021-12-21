<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$coseq = $_SESSION['mInfo']['mc_coseq'];
$query = "select * from tbl_coperation where co_is_del ='FALSE' and co_seq <> {$coseq} ";
$ps = pdo_query($db,$query,array());
$coperation_list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($coperation_list,$data);
}

?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<style>
    #wrap.depth03 #content {min-height: 400px;}
    .answer{padding: 15px;}
    .data-table{border-top: 1px solid #d1d1d1;}
    .table-wrap form{display: none;}
    .table-wrap form.show{display: block;}
    .button-area{text-align:center; max-width:60%; display: none;}
    .button-area.show{display: block;}
    tr.hide {display: none;}
    .insert.search-div {position: relative;}
    td .result {position: absolute; z-index: 99; width: 50%; height: fit-content; border: 1px solid #d1d1d1;border-radius: 8px; overflow-y: scroll;}
    td .result.hide{display: none;}
    td .result ul{background-color: whitesmoke; padding: 8px;}
    td .result li{padding-bottom: 10px; cursor: pointer;}
</style>
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
    <h2 class="content-title">인사 발령 생성</h2>
		<div class="section-wrap">
            <!-- 기본 사항 -->
            <div id="tab-information"class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1; max-width:60%;">
                        <div class="answer">
                            <div class="radio-wrap">
                                <input checked type="radio" value="1" id="radio1" name="inp_type" class="radio-input"><label class="label" for="radio1">전출</label>
                                <input type="radio" id="radio2" value="2" name="inp_type" class="radio-input"><label class="label" for="radio2">겸직</label>
                                <input type="radio" id="radio3" value="3" name="inp_type" class="radio-input"><label class="label" for="radio3">퇴사</label>
                            </div>
                        </div>
                        
                    <!-- 발령 form -->
                    <form id="" class="member_form show">
                        <table class="data-table left">
                            <caption>발령정보</caption>
                            <colgroup>
                                <col style="width: 140px" />
                                <col style="width: *" />
                                <col style="width: 140px" />
                                <col style="width: *" />
                                <col style="width: 140px" />
                                <col style="width: *" />
                            </colgroup>
                            <tbody>
                                <tr class="insert">
                                    <th scope="col">발령명</th>
                                    <td class="insert"><input type="text" id="ta_title" class="input-text"></td>
                                </tr>
                                <tr>
                                    <th scope="col" id="ta_regdate" >전출일자</th>
                                    <td class="insert"><input type="text" id="tg_activity_date" class="input-text input-datepicker" readonly></td>
                                </tr>
                                <tr>
                                    <th scope="col">사원명 검색</th>
                                    <td>
                                        <div class="insert search-div">
                                            <div class="input-search">
                                                <input type="text" id="input-search1" name="">
                                                <a class="btn" href="#" id="btn_search"><img alt="검색" src="/@resource/images/common/search02.png"></a>
                                            </div>
                                            <div class="result search1-result hide">
                                                <ul id="search_list">
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="insert">
                                    <th scope="col" id="coperation">발령 회사명</th>
                                    <td>
                                        <select class="select" name="appointment" id="appointment">
                                            <?foreach ($coperation_list as $val){?>
                                                <option value="<?=$val['co_seq']?>">
                                                    <?=$val['co_name']?>
                                                    <?if(!empty($val['co_subname'])){?><?=$val['co_subname']?><?}?>
                                                </option>
                                            <?}?>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="button-area show large">
                    <button type="button" class="btn type01 large" id="id_submit">전출<span class="ico check01"></span></button>
                </div>
            </div>
            <!-- 기본 사항 -->
		</div>
		
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    var mmseq;
    $('#aside-menu .tree-wrap>li:eq(0)').addClass('active');
    var dateToday = new Date(); 
    $('.input-datepicker').datepicker('setStartDate',dateToday);
    
    $('.radio-input').change(function(e){
        if($(this).val()==1){
            $('tr.insert').removeClass('hide');
            $('#ta_regdate').text('전출일자');
            $('#coperation').text('전출 회사명');
            $('#id_submit').html('전출<span class="ico check01"></span>');
        }else if($(this).val()==2){
            $('tr.insert').removeClass('hide');
            $('#ta_regdate').text('겸직일자');
            $('#coperation').text('겸직 회사명');
            $('#id_submit').html('겸직<span class="ico check01"></span>');
        }else if($(this).val()==3){
            $('#ta_regdate').text('퇴사일자');
            $('tr.insert').addClass('hide');
            $('#id_submit').html('퇴사<span class="ico check01"></span>');
        }
    });

    //발령 구성원 검색
    $('#input-search1').keypress(function(e){
        if(e.keyCode==13){
            $('#search_list').html('');
            search = $('#input-search1').val();
            $.ajax({
                url : '/@proc/hass/search_get_member.php',
                data : {'search':search},
                type : 'post',
                dataType : 'json',
                success : function(data){
                    if(data[0] == '사원명을 정확하게 입력해주세요.'){
                        html = "<li>사원명을 정확하게 입력해주세요.</li>";
                        return $('#search_list').html(html);
                    }
                    for(i=0;i<data.length;i++){
                        html = "<li data-mmseq = '"+data[i].mmseq+"'>"+data[i].mm_name+"("+data[i].mc_code+") ["+data[i].group+"] ["+data[i].position+"]</li>";
                        $('#search_list').append(html);
                        if($('#search_list li').length>5){
                            $('.search1-result').css('height','400%');
                        };
                    }
                    
                    
                }
            });
            $('.search1-result').removeClass('hide');
        }
    });

    $('#btn_search').click(function(){
        $('#search_list').html('');
        search = $('#input-search1').val();
         $.ajax({
            url : '/@proc/hass/search_get_member.php',
            data : {'search':search},
            type : 'post',
            dataType : 'json',
            success : function(data){
                if(data[0] == '사원명을 정확하게 입력해주세요.'){
                    html = "<li>사원명을 정확하게 입력해주세요.</li>";
                    return $('#search_list').html(html);
                }
                for(i=0;i<data.length;i++){
                    html = "<li data-mmseq = '"+data[i].mmseq+"'>"+data[i].mm_name+"("+data[i].mc_code+") ["+data[i].group+"] ["+data[i].position+"]</li>";
                    $('#search_list').append(html);
                    if($('#search_list li').length>5){
                        $('.search1-result').css('height','400%');
                    };
                }
                
                
            }
        });
        $('.search1-result').removeClass('hide');
    });
    $(document).on('click','.search1-result ul li',function(){
        $('.search1-result').addClass('hide');
        mmseq = $(this).data('mmseq');
        $('#input-search1').val($(this).text());
    });

    $('#id_submit').click(function () {
        if(mmseq=='' || mmseq==null){
            alert('사원을 선택해주세요.');
            return false;
        }
        ta_title = $('#ta_title').val();
        co_seq =  $('#appointment').val();
        datetime =  $('#tg_activity_date').val();
        type = $('[name="inp_type"]:checked').val();
        if(confirm('정말로 진행하시겠습니까?')) {
            $.ajax({
                url: '/@proc/hass/appointment_proc.php',
                data: {'co_seq': co_seq, 'mmseq': mmseq, 'type': type, 'ta_title': ta_title,'datetime':datetime},
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.code == 'TRUE') {
                        alert(data.msg);
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    })
</script>
