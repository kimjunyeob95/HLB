<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$mmseq = $_SESSION['mmseq'];
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$title = '가족사항';
$teb_seq = 2;
$step = get_member_step($db,$mmseq);
// 가족사항
$query = "select * from member_family_data where mf_mmseq = {$mmseq}";
$ps = pdo_query($db,$query,array());
$family_list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($family_list,$data);
}

// 회원정보
$query = "select * from ess_member_base where mmseq = {$mmseq}";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);
?>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<style>
    div.uploader{width: 586px !important;}
    div.uploader span.filename{width: 450px !important;}
    tr.hide{display: none;}
</style>
<!-- WRAP -->
<div id="wrap" class="depth-main newcomer">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/new_head.php'; ?>
<!-- CONTENT -->
<div id="container" class="newcomer-info">
	<div id="content" class="content-primary">
		<!-- 가족정보 -->
		<div class="personal-info">
            <? include $_SERVER['DOCUMENT_ROOT'].'/new/info_link.php'; ?>
            <form id="info_form2">
			<div class="section">
				<h3 class="section-title">가족사항</h3>
				<div class="btn-aside">
					<a class="btn type01 small add-table-family" href="#">추가</a>
					<!-- <a class="btn type01 small" href="#">수정</a> -->
					<a class="btn type01 small remove-table-family" href="#">삭제</a>
				</div>
				<div class="table-wrap family">
                    <?if(empty($family_list)){?>
                        <table class="data-table table-family-list">
                            <caption>가족사항 입력표</caption>
                            <colgroup>
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 10%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">성명</th>
                                    <th scope="col">관계</th>
                                    <th scope="col">생년월일</th>
                                    <th scope="col">인적공제 여부</th>
                                    <th scope="col">동거 여부</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input class="input-text" type="text"title="성명"  name="mf_name[]" value=""></td>
                                    <td>
                                        <select name="mf_relationship[]">
                                            <option value="1">부</option>
                                            <option value="2">모</option>
                                            <option value="3">형제</option>
                                            <option value="4">자매</option>
                                            <option value="5">조모</option>
                                            <option value="6">조부</option>
                                            <option value="7">외조모</option>
                                            <option value="8">외조부</option>
                                            <option value="9">배우자</option>
                                            <option value="10">자녀</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="input-text input-datepicker" title="생년월일"  name="mf_birth[]" readonly value=""></td>
                                    <td>
                                        <select  name="mf_allowance[]" >
                                            <option value='T'>대상</option>
                                            <option value='F'>비대상</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select  name="mf_together[]">
                                            <option value='T'>동거</option>
                                            <option value='F'>비동거</option>
                                        </select>
                                    </td>
                                </tr>         
                            </tbody>
                        </table>
                    <?}else{?>
                        <table class="data-table table-family-list">
                            <caption>가족사항 입력표</caption>
                            <colgroup>
                                <col style="width: 10%" />
                                <col style="width: 1%" />
                                <col style="width: 10%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">성명</th>
                                    <th scope="col">관계</th>
                                    <th scope="col">생년월일</th>
                                    <th scope="col">인적공제 여부</th>
                                    <th scope="col">동거 여부</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?foreach ($family_list as $val){?>
                                <tr>
                                    <td><input type="text" class="input-text" name="mf_name[]" title="성명"  value="<?=$enc->decrypt($val['mf_name'])?>"></td>
                                    <td>
                                        <select name="mf_relationship[]">
                                            <option value="1" <?if($val['mf_relationship']=='1'){?>selected<?}?>>부</option>
                                            <option value="2" <?if($val['mf_relationship']=='2'){?>selected<?}?>>모</option>
                                            <option value="3" <?if($val['mf_relationship']=='3'){?>selected<?}?>>형제</option>
                                            <option value="4" <?if($val['mf_relationship']=='4'){?>selected<?}?>>자매</option>
                                            <option value="5" <?if($val['mf_relationship']=='5'){?>selected<?}?>>조모</option>
                                            <option value="6" <?if($val['mf_relationship']=='6'){?>selected<?}?>>조부</option>
                                            <option value="7" <?if($val['mf_relationship']=='7'){?>selected<?}?>>외조모</option>
                                            <option value="8" <?if($val['mf_relationship']=='8'){?>selected<?}?>>외조부</option>
                                            <option value="9" <?if($val['mf_relationship']=='9'){?>selected<?}?>>배우자</option>
                                            <option value="10" <?if($val['mf_relationship']=='10'){?>selected<?}?>>자녀</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="input-text input-datepicker" title="생년월일"  name="mf_birth[]" readonly value="<?=$enc->decrypt($val['mf_birth'])?>"></td>
                                    <td>
                                        <select  name="mf_allowance[]" >
                                            <option value='T' <?if($val['mf_allowance']=='T' || empty($val['mf_allowance'])){?>selected<?}?>>대상</option>
                                            <option value='F' <?if($val['mf_allowance']=='F'){?>selected<?}?>>비대상</option>
                                        </select>
                                    </td>
                                    <td colspan=3>
                                        <select  name="mf_together[]">
                                            <option value='T' <?if($val['mf_together']=='T' || empty($val['mf_together'])){?>selected<?}?>>동거</option>
                                            <option value='F' <?if($val['mf_together']=='F'){?>selected<?}?>>비동거</option>
                                        </select>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    <?}?>
				</div>
			</div>
			<div class="section">
				<h3 class="section-title">증빙서류 첨부</h3>
				<!-- <div class="btn-aside">
					<a class="btn type01 small add-table-file" href="#">추가</a>
					<a class="btn type01 small remove-table-file" href="#">삭제</a>
				</div> -->
				<div class="table-wrap">
					<table class="data-table table-file-list">
						<caption>가족사항 입력표</caption>
						<colgroup>
                            <col style="width: 5%" />
							<col style="width: 14%" />
						<thead>
							<tr>
                                <th scope="col">구분</th>
								<th scope="col">파일명</th>
							</tr>
						</thead>
						<tbody>
							<tr>
                                <td>주민등록등본</td>
								<td class="left"><input class="input_file" type="file" name="file1"></td>
							</tr>
                            <tr>
                                <td>가족관계증명서</td>
								<td class="left"><input class="input_file" type="file" name="file2"></td>
							</tr>
                            <tr>
                                <td>기타</td>
								<td class="left"><input class="input_file" type="file" name="file3"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
            </form>
			<ul class="data-list">
				<li>- 가족사항은 입사 후 당사에서 제공하는 급여/복리후생지원(가족수당, 진료비 지원, 연말정산 등)을 위한 기초자료입니다. <br>
 				 반드시 필요한 정보이므로 첨부하는 본인 기준 가족관계증명서와 일치하는 정보만 정확히 작성하여 주시길 바랍니다. (가족관계증명서 본인 기준 外 가족은 입사 후에 개별 등록 바랍니다.)</li>
				<li>- 인적공제 여부는 하단의 [인적공제 대상 등록 기준 안내]를 참고하여 작성해주시기 바랍니다. <br>
  				동거 및 인적공제 여부는 연말정산 기초자료로서 현재 기준으로 작성하여 주시고 차후 변경 시에는 그룹 인사시스템을 통해 수정이 가능합니다.</li>
				<li>- 가족관계 증명서 첨부는 [온라인 민원24(<a href="www.minwon.go.kr" target="_blank">www.minwon.go.kr</a>) > 가족관계 증명서 검색 > 출력] 에서 확인하실 수 있습니다.</li>
			</ul>
			<div class="notice-box">
				<h4>[인적공제 대상 등록 기준 안내]</h4>
				<p> - 인적공제 대상자는 생계를 같이하는 가족으로 연간소득합계액이 100만원 이하이며 장애자를 제외한 만 20세 이하 만 60세 이상 나이요건을 갖춘 사람에 한합니다.</p>
			</div>
			<div class="btn-area">
<!--				<a class="btn type02 large" href="./recruitStep01.php"><span class="ico prev"></span>이전</a>-->
<!--				<a class="btn type02 large" id="btn_save">임시저장</a>-->
<!--				<a class="btn type03 large" href="./recruitStep03.php">다음<span class="ico next"></span></a>-->
                <a class="btn type03 large" id="btn_save">다음</a>
			</div>
		</div>
		<!-- // 가족정보 -->
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<!-- // WRAP -->
<script>
    $('.add-table-file').click(function(){
        let tr_length = $('.table-file-list tr').length;
        let file_html = 
        `<tr>
            <td>
                <select name="mm_file_type_`+tr_length+`">
                    <option value="1">가족관계증명서</option>
                    <option value="2">주민등록등본</option>
                </select>
            </td>
            <td class="left"><input class="input_file" type="file" name="file`+tr_length+`"></td>
        </tr>`;
        if(tr_length==4){
            alert('증빙서류는 3개까지 등록가능합니다.');
            return false;
        }else{
            $('.table-file-list tbody').append(file_html);
            $('select, input[type=radio], input[type=checkbox], input[type=file]').uniform({
                fileDefaultHtml: '',
                fileButtonHtml: '파일첨부'
            });
        }
    });
    $('.remove-table-file').click(function(){
        let tr_length = $('.table-file-list tr').length;
        $('.table-file-list tbody tr:last-child').remove();
    });
    $('#mm_post, #mm_address').click(function(){
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분입니다.
                // 예제를 참고하여 다양한 활용법을 확인해 보세요.
                var zonecode = data.zonecode;
                var address = data.roadAddress;
                console.log(data);
                $('#mm_post').val(zonecode);
                $('#mm_address').val(address);
                $('#mm_address_detail').focus();
            }
        }).open();
    });

    $('.input-datepicker').datepicker();

    $('.add-table-family').click(function(){
        var text = `<tr>
                    <td><input class="input-text" type="text"title="성명"  name="mf_name[]" value=""></td>
                    <td>
                        <select name="mf_relationship[]">
                            <option value="1">부</option>
                            <option value="2">모</option>
                            <option value="3">형제</option>
                            <option value="4">자매</option>
                            <option value="5">조모</option>
                            <option value="6">조부</option>
                            <option value="7">외조모</option>
                            <option value="8">외조부</option>
                            <option value="9">배우자</option>
                            <option value="10">자녀</option>
                        </select>
                    </td>
                    <td><input type="text" class="input-text input-datepicker" title="생년월일"  name="mf_birth[]" readonly value=""></td>
                    <td>
                        <select  name="mf_allowance[]" >
                            <option value='T'>대상</option>
                            <option value='F'>비대상</option>
                        </select>
                    </td>
                    <td>
                        <select  name="mf_together[]">
                            <option value='T'>동거</option>
                            <option value='F'>비동거</option>
                        </select>
                    </td>
                </tr>`;
        $('.table-family-list tbody').append(text);
        $(document).find('.input-datepicker').removeAttr('id').removeClass('hasDatepicker').datepicker();

        /* 동적생성시 form 재설정 함수 */
    $('select, input[type=radio], input[type=checkbox], input[type=file]').uniform({
		fileDefaultHtml: '',
		fileButtonHtml: '파일첨부'
    });
    // select
	$('.select-label').click(function () {
		$(this).parents('.data-down').toggleClass('active');
	});
	$('.select-list li').click(function () {
		$(this).parents('.select-list').siblings('.select-label').text($(this).text());
		$(this).parents('.data-down').removeClass('active');
	});
    });

    $('.remove-table-family').click(function(e){
        e.preventDefault();
        var table_count = $('.table-family-list tbody tr').length;
        if(table_count == 1){return;}else{
            $('.table-family-list tbody tr:last-child').remove();
        }
        
    });


    $('#btn_save').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#info_form2').find('.input-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');

            if(val==""){
                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }

        });

        var form = $('#info_form2')[0];
        var data = new FormData(form);
        if(validate){
            //if(confirm("가족사항 임시저장을 하시겠습니까?")){
                hlb_fn_file_ajaxTransmit("/@proc/new/new_profileProc_family.php", data);
            //}
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='new_profileProc_family'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                //alert(result.msg);
                new_step_info(3);
                //location.reload();
                //location.href="/";
            }
        }
    }
</script>