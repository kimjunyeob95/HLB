<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
// echo('<pre>');print_r($_SESSION);echo('</pre>');exit;
?>
<style>
    div.uploader{width: 486px;}
    div.uploader span.filename{width: 350px;}
    div.upload_text{margin-top:20px; height: 500px; overflow: hidden; overflow-x: scroll; overflow-y: scroll;}
    div.upload_text.hide{display:none;}

    #excelResult tr td{width: 3%;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">급여 등록</h2>
		<div class="section-wrap">
            <!-- 급여 등록 -->
            <div id="tab-information" class="tab-cont" >
                <div class="table-wrap" style="margin-top:20px;border-top:1px solid #d1d1d1; max-width:80%;">
                    <form id="salary_form" action="/@proc/hass/new_excelProc.php" method="post" enctype="multipart/form-data">
                        <table class="data-table left">
                            <caption>급여 등록</caption>
                            <colgroup>
                                <col style="width: 140px" />
                                <col style="width: *" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">급여등록 날짜</th>
                                    <td>
                                        <input type="number" title="급여 년도" class="input-text" style="max-width:10%;" name="hu_salaryDate_year" value="<?=date('Y')?>"><span style="vertical-align: middle;">&nbsp;년&nbsp;</span>
                                        <select name="hu_salaryDate_month" >
                                            <?
                                                $today_month=date('m');
                                                for($i=1;$i<13;$i++){
                                                    if($i==$today_month){
                                                        echo '<option selected value='.$i.'>'.$i.'</option>';
                                                    }else{
                                                        echo '<option value='.$i.'>'.$i.'</option>';
                                                    }
                                                    
                                                }
                                            ?>
                                        </select><span style="vertical-align: middle;">&nbsp;월</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">급여 보여줄<br>날짜 / 시간</th>
                                    <td>
                                        <input type="text" title="급여 보여줄 날짜" class="input-text input-datepicker" style="max-width:15%;" readonly name="hu_showDate" value="">
                                        <select name="hu_showDate_hour">
                                            <?
                                                for($i=8;$i<21;$i++){
                                                    if($i>9){
                                                        echo '<option value='.$i.'>'.$i.'</option>';
                                                    }else{
                                                        echo '<option value=0'.$i.'>0'.$i.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <span style="vertical-align: middle;">&nbsp;시</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">급여 엑셀</th>
                                    <td><input type="file" id="file05" class="input-text" name="file05" title='엑셀'></td>
                                </tr>
                                <tr>
                                    <th scope="col">급여 엑셀양식</th>
                                    <td><a href="/data/excelData/급여 엑셀 양식.xlsx" target="_blank"><button type="button" data-type="다운로드" class="btn type10 medium">다운로드</button></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>

                <div id="grid" class="table-wrap upload_text hide"></div>
                <div style="text-align:center;">
                    <div class="button-area large">
                        <button type="button" id="btn-salary" class="btn type01 large">급여 등록<span class="ico check01"></span></button>
                    </div>
                </div>
            </div>
            <!-- 급여 등록 -->
		</div>
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('.input-datepicker').datepicker();

    var test1 = null, test2 = null;
    function gridExcelToWeb(file, target){
        var reader = new FileReader();

        reader.onload = function (evt) {
            if (evt.target.readyState == FileReader.DONE) {
                var data = evt.target.result;  //해당 데이터, 웹 서버에서 ajax같은거로 가져온 blob 형태의 데이터를 넣어주어도 동작 한다.
                data = new Uint8Array(data);
                var workbook = XLSX.read(data, { type: 'array' });
                var sheetName = '';
                workbook.SheetNames.forEach( function(data, idx){   //시트 여러개라면 이 안에서 반복문을 통해 돌리면 된다.
                    if(idx == 0){
                        sheetName = data;
                    }
                });
                test1 = workbook;

                var toHtml = XLSX.utils.sheet_to_html(workbook.Sheets[sheetName], { header: '' });

                target.html(toHtml);
                target.find('table').attr({class:'table table-bordered',id:'excelResult'});  //id나 class같은거를 줄 수 있다.\
                target.find('table').css('width','2000px');
                test2 = toHtml;
                var excel_count = $('#excelResult').find('tr').length;
                $('#excelResult').find('tr').each(function(idx){
                    if(idx <=1 || idx==excel_count-1){ 
                        $(this).css({'background-color':'#969da5a3'});
                        $(this).children().css({'color':'#000'});
                    }
                });
            }
        };
        reader.readAsArrayBuffer(file);
    }    



    $('#aside-menu .tree-wrap>li:eq(2)').addClass('active');

    $('#file05').change(function(){
        if(this.files[0].size >  20 * 1024 * 1024){
        alert("업로드 할 수 없는 형식의 파일입니다.\n20Mb 이하의 파일만 업로드 가능합니다.");
        $('#file05').val('');
        return;
        }

        var fileName = document.getElementById("file05").value;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="xls" || extFile=="xlsx"){
            const selectedFile = $(this)[0].files[0];
            gridExcelToWeb(selectedFile,  $('#grid'));
            $('.upload_text').removeClass('hide');
            return;
        }else{
        alert("업로드 할 수 없는 형식의 파일입니다.\nxls,xlsx 확장자의 엑셀 파일만 업로드 가능합니다.");
            $('#file05').val('');
        return;
        }
        
    });

    $('#btn-salary').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#salary_form').find('.input-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');
            if(val==""){
                alert(txt+"을(를) 등록 하세요");
                validate = false;
                $(this).focus();
                return false;
            }
        });

        if(validate){
            if(confirm("정말 급여를 등록 하시겠습니까?")){
                var ajax_data = {
                    'hu_salaryDate_year' : $('input[name="hu_salaryDate_year"]').val(),
                    'hu_salaryDate_month' : $('select[name="hu_salaryDate_month"]').val(),
                    'search':'search'
                };
                $.ajax({
                    url : '/@proc/hass/salaryExcelchkProc.php',
                    data : ajax_data,
                    type : 'post',
                    dataType : 'json',
                    success : function(data){
                        if(data.code=='true'){
                            if(confirm("해당 날짜에 등록되어있는 급여가 있습니다, 교체하시겠습니까?")){
                                $('#salary_form').submit();
                            }else{
                                alert(data.msg);
                                return false;
                            }
                        }else{
                            $('#salary_form').submit();
                        }
                    }
                });
            }
        }
    })
</script>

