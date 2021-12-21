<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mmseq = $_SESSION['mmseq'];
$title = '개인정보';
$teb_seq = 1;
$step = get_member_step($db,$mmseq);
$member_info = get_member_info($db,$mmseq);
if(($_REQUEST['agree1']!=1 || $_REQUEST['agree2']!=1 || $_REQUEST['agree3']!=1) && empty($member_info['mm_save_step'])){
    echo '<script>location.href="/new/recruitConfirm01.php"</script>';
}else{
    $_REQUEST['agree1'] = 1;
    $_REQUEST['agree2'] = 1;
    $_REQUEST['agree3'] = 1;
}

?>
<style>
    .emergency{display: inline-block; width: 34%;}
    .emergency.tel{display: inline-block; width: 50%;}
    .emergency span{vertical-align: sub;}
    .emergency .input-text{max-width: 80%;}
    .emergency.tel .input-text{max-width: 74%;}
</style>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<!-- WRAP -->
<div id="wrap" class="depth-main newcomer">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/new_head.php'; ?>
<!-- CONTENT -->
<div id="container" class="newcomer-info">
    <form id="info_form1" enctype="multipart/form-data" method="post">
        <input type="hidden" value="<?=$_REQUEST['agree1']?>" name="mm_agree1">
        <input type="hidden" value="<?=$_REQUEST['agree2']?>" name="mm_agree2">
        <input type="hidden" value="<?=$_REQUEST['agree3']?>" name="mm_agree3">
	    <div id="content" class="content-primary">
		<!-- 개인정보 -->
		<div class="personal-info">
            <? include $_SERVER['DOCUMENT_ROOT'].'/new/info_link.php'; ?>
			<!-- 180108 추가 -->
			<div class="alert-text">
				<strong>* 표시는 필수 입력항목입니다.</strong>
				<!-- <p><span class="ico info02"></span>표시에 마우스를 올리면 정보를 보실 수 있습니다.</p> -->
			</div>
			<!-- // 180108 추가 -->
			<div class="table-wrap">
				<table class="data-table left">
					<caption>개인정보 입력사항표</caption>
					<colgroup>
						<col style="width: 12%" />
						<col style="width: 7%" />
						<col style="width: 14%" />
						<col style="width: 7%" />
						<col style="width: 14%" />
						<col style="width: *" />
					</colgroup>
					<tbody>
                        <tr>
							<th scope="row">사원번호</th>
							<td colspan="4"><div><input type="text" class="input-text"  disabled value="<?=$member_info['mc_code']?>"></div></td>
							<td><div class="notice">*사원번호는 로그인시 필요하니 기억해 주세요</div></td>
						</tr>
						<tr>
							<th scope="row">성명(한글)</th>
							<td colspan="4"><div><input type="text" class="input-text"  disabled title="성명 한글" value="<?=$enc->decrypt($member_info['mm_name'])?>"></div></td>
							<td><div class="notice">*성명은 인사기록카드의 기초자료로 사용되오니 정확히 기재해 주세요</div></td>
						</tr>
						<tr>
							<th scope="row">성명(영문)</th>
							<td colspan="4"><div><input type="text" class="input-text" name="mm_en_name"  title="성명 영문" value="<?=$member_info['mm_en_name']?>"></div></td>
							<td><div class="notice">*여권과 동일하게(띄어쓰기, 대소문자 구분 포함) 입력해 주시기 바랍니다
								<!-- 말풍선 -->
								<!-- <div class="balloon-word">
									<span class="ico-info"></span>
									<div class="text-box">
										<p>입사 후 명함제작, 인사기록카드 <br>
										및 해외 출장 신청을 위한 기초자료로<br>
										사용됩니다. <br><br>

										여권이 없는 경우에는 입력하신<br>
										영문 성명을 추후 여권을 발급하실 때<br>
										동일하게 입력하시기 바랍니다.</p>
									</div>
								</div> -->
								<!-- // 말풍선 -->
							</div></td>
						</tr>
                        <tr>
							<th scope="row">비밀번호</th>
							<td colspan="4"><div><input type="password" class="input-text"  title="비밀번호" name="mm_password" value="<?=$enc->decrypt($member_info['mm_password'])?>"></div></td>
							<td><div class="notice">*비밀번호는 영문+숫자를 혼합하여 8~20자리 이내로 입력해주세요.</div></td>
						</tr>
                        <tr>
                            <th scope="row">비밀번호 확인</th>
                            <td colspan="4"><div><input type="password" class="input-text" title="비밀번호 확인" name="mm_re_password" value="<?=$enc->decrypt($member_info['mm_password'])?>"></div></td>
                            <td><div class="notice"></div></td>
                        </tr>
						<tr class="attach">
							<th scope="row">사진</th>
							<td colspan="4">
								<div>
									<div class="thumb"><img id ='profile_image'src="<?=$member_info['mm_profile']?>"></div>
									<!-- <a class="btn type01 small file">파일첨부</a> -->
                                    <input type="file" accept="image/*" name="mm_profile" id="mm_profile"/>
								</div>
							</td>
							<td>
								<ul class="data-list">
									<li>*사원증 제작, 인사기록표 등에 활용되오니 단정한 차림의 사진을 업로드해주세요</li>
									<li>*사진은 여권용 사이즈로 촬영하여 jpg, jpeg 파일 형태로 업로드해주시길 바랍니다</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th scope="row">생일(실제)</th>
							<td colspan="4">
								<div>
                                    <input type="text" title="생일(실제)" class="input-text input-datepicker" name="mm_birth" style="max-width:50%;"  readonly value="<?=substr($member_info['mm_birth'],0,10)?>">
								</div>
							</td>
							<td><div></div></td>
						</tr>
						<tr>
							<th scope="row">주민등록번호</th>
							<td colspan="4"><div><input class="input-text" type="number" maxlength="13" oninput="maxLengthCheck(this)" title="주민등록번호" placeholder="-없이 입력" name="mm_serial_no" value="<?=$enc->decrypt($member_info['mm_serial_no'])?>"></div></td>
							<td>
                                <div class="notice">*숫자만 입력해주세요.</div>
                            </td>
						</tr>
                        <tr>
							<th scope="row">최종학력</th>
							<td colspan="4"><div>
                                    <select name="mm_education">
                                        <option value='1' <?if($member_info['mm_education']==1){?>selected<?}?>>고졸</option>
                                        <option value='2' <?if($member_info['mm_education']==2){?>selected<?}?>>전문학사</option>
                                        <option value='3' <?if($member_info['mm_education']==3){?>selected<?}?>>학사</option>
                                        <option value='4' <?if($member_info['mm_education']==4){?>selected<?}?>>석사</option>
                                        <option value='5' <?if($member_info['mm_education']==5){?>selected<?}?>>박사</option>
                                    </select>
                                </div></td>
							<td><div></div></td>
						</tr>
						<tr>
							<th scope="row">국적</th>
							<td colspan="4">
								<div><?=getNationTag_v2($member_info['mm_country'],'국적')?></div>
							</td>
							<td><div></div></td>
                        </tr>
                        <tr>
							<th scope="row">거주 국가</th>
							<td colspan="4">
								<div><?=getNationTag_v3($member_info['mm_from'],'거주 국가')?></div>
							</td>
							<td><div></div></td>
                        </tr>
                        <tr>
							<th scope="row">주소</th>
							<td colspan="3"><input type="text" id="mm_address"  name="mm_address" title="주소" class="input-text" value="<?=$enc->decrypt($member_info['mm_address'])?>"></td>
                            <th scope="row">우편번호</th>
							<td colspan="3"><input type="text" id="mm_post" name="mm_post" title="우편번호" class="input-text" value="<?=$member_info['mm_post']?>"></td>
                        </tr>
                        <tr>
							<th scope="row">상세주소</th>
							<td colspan="5"><input type="text" name="mm_address_detail" title="상세주소" class="input-text" value="<?=$enc->decrypt($member_info['mm_address_detail'])?>" ></td>
						</tr>
						<tr>
							<th scope="row">연락처</th>
							<td colspan="4"><div><input type="number" class="input-text" title="연락처" name="mm_cell_phone" value="<?=$enc->decrypt($member_info['mm_cell_phone'])?>"></div></td>
							<td>
                                <div class="notice">*연락처는 입사 후 명함제작 및 인사기록카드의 기초자료로 사용됩니다
                                    <!-- 말풍선 -->
                                    <!-- <div class="balloon-word">
                                        <span class="ico-info"></span>
                                        <div class="text-box">
                                            <p>입사 후 휴대폰 번호를 <br>
                                            변경하신다면, 새로운 번호를<br>
                                            재등록 하시기 바랍니다.</p>
                                        </div>
                                    </div> -->
                                    <!-- // 말풍선 -->
                                </div>
                                <div class="notice">*숫자만 입력해주세요.</div>
                            </td>
						</tr>
                        <!-- <tr>
							<th scope="row">전화 번호</th>
							<td colspan="4"><div><input type="number" class="input-text" title="전화 번호" name="mm_cell_phone" value="<?=$enc->decrypt($member_info['mm_cell_phone'])?>"></div></td>
							<td><div class="notice"></div></td>
                        </tr> -->
                        <tr>
							<th scope="col">성별</th>
							<td colspan="5"><?=getGenderTag_v2($member_info['mm_gender'])?></td>
                        </tr>
                        <tr>
							<th scope="row">이메일 주소</th>
							<td colspan="4"><div><input type="text" class="input-text"  disabled value="<?=$enc->decrypt($member_info['mm_email'])?>"></div></td>
							<td><div class="notice"></div></td>
                        </tr>
						<tr>
							<th scope="row">비상 연락처</th>
							<td colspan="4">
                                <div class="emergency"><span>관계</span> <input type="text" class="input-text" name="mm_prepare_relation" value="<?=$member_info['mm_prepare_relation']?>" title="비상 연락처 관계"></div>
                                <div class="emergency tel"><span>연락처</span> <input placeholder="-없이 입력" type="number" class="input-text" value="<?=$enc->decrypt($member_info['mm_prepare_phone'])?>" name="mm_prepare_phone" title="비상 연락처 휴대폰"></div>
							</td>
                            <td>
                                <div class="notice">*숫자만 입력해주세요.</div>
                            </td>
						</tr>
					</tbody>
				</table>
                <h3 class="section-title">병력(선택사항)</h3>
                    <table class="data-table table-form-army left" style="border-top:1px solid #d1d1d1;">
                        <caption>병력</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                            <col style="width: 1%" />
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">병역구분</th>
                                <th scope="col">입대일</th>
                                <th scope="col">제대일</th>
                                <th scope="col">군별</th>
                                <th scope="col">계급</th>
                                <th scope="col">병과</th>
                                <th scope="col">사유(면제 및 기타)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="insert" class="center">
                                    <select name="mm_arm_type">
                                        <?foreach($arm_type as $key => $val){?>
                                            <option value='<?=$key?>' <?if($key==$member_info['mm_arm_type']){?>selected<?}?>><?=$val?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td class="insert"><input title = "입대일" value="<?=substr($member_info['mm_arm_sdate'],0,10)?>" type="text" class="input-text input-datepicker" name="mm_arm_sdate" readonly></td>
                                <td class="insert"><input title = "제대일" value="<?=substr($member_info['mm_arm_edate'],0,10)?>" type="text" class="input-text input-datepicker" name="mm_arm_edate" readonly></td>
                                <td scope="insert" class="center">
                                    <select name="mm_arm_group" class="arm_select">
                                        <?foreach($arm_group as $key => $val){?>
                                            <option value='<?=$key?>' <?if($key==$member_info['mm_arm_group']){?>selected<?}?>><?=$val?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td scope="insert" class="center">
                                    <select name="mm_arm_class" class="arm_select">
                                        <?foreach($arm_class as $key => $val){?>
                                            <option value='<?=$key?>' <?if($key==$member_info['mm_arm_class']){?>selected<?}?>><?=$val?></option>
                                        <?}?>
                                    </select>
                                </td>
                                <td scope="insert">
                                    <input title = "병과" value="<?=$member_info['mm_arm_discharge']?>" type="text" class="input-text" name="mm_arm_discharge">
                                </td>
                                <td scope="insert"><input title = "사유(면제 및 기타)" value="<?=$member_info['mm_arm_reason']?>" type="text" class="input-text" name="mm_arm_reason"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="section-wrap">
                <h3 class="section-title">장애정보 / 국가보훈정보</h3>
                <table class="data-table left" style="border-top:1px solid #d1d1d1;">
                    <caption>장애정보 / 국가보훈정보</caption>
                    <colgroup>
                        <col style="width: 1%" />
                        <col style="width: 1%" />
                        <col style="width: 1%" />
                        <col style="width: 1%" />
                        <col style="width: 1%" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">장애여부</th>
                            <th scope="col">장애구분</th>
                            <th scope="col">장애등급</th>
                            <th scope="col">보훈여부</th>
                            <th scope="col">보훈구분</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td scope="insert" class='center'>
                            <select  name="mm_disorder_1" >
                                <?foreach($disorder_type_1 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_disorder_1']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class="center">
                            <select  name="mm_disorder_2" >
                                <?foreach($disorder_type_2 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_disorder_2']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class='center' >
                            <select  name="mm_disorder_3" >
                                <?foreach($disorder_type_3 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_disorder_3']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class='center'>
                            <select  name="mm_nation_1" >
                                <?foreach($nation_type_1 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_nation_1']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                        <td scope="insert" class="center">
                            <select  name="mm_nation_2" >
                                <?foreach($nation_type_2 as $key => $val){?>
                                    <option value='<?=$key?>' <?if($key==$member_info['mm_nation_2']){?>selected<?}?>><?=$val?></option>
                                <?}?>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>                          

			</div>
			<div class="btn-area">
				<!-- <a class="btn type02 large"><span class="ico prev"></span>이전</a> -->
<!--				<a class="btn type02 large" id="btn_save">임시저장</a>-->
<!--				<a class="btn type03 large" href="./recruitStep02.php">다음<span class="ico next"></span></a>-->
                <a class="btn type03 large" id="btn_save">다음</a>
			</div>
		</div>
		<!-- // 개인정보 -->
	</div>
    </form>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<!-- // WRAP -->
<script>
    function maxLengthCheck(object){
        if (object.value.length > object.maxLength){
            object.value = object.value.slice(0, object.maxLength);
        }    
    };
    
    $('select[name="mm_arm_type"]').change(function(){
        if($(this).val()==1){
            $('.table-form-army').find('.input-text').each(function(e){
                $(this).val('');
                $(this).attr('disabled',true);
            });
            $('.table-form-army').find('.arm_select').each(function(e){
                $(this).siblings('span').text('없음');
                $(this).val(0);
                $(this).attr('disabled',true);
            });
        }else{
            $('.table-form-army').find('.input-text').each(function(e){
                $(this).attr('disabled',false);
            });
            $('.table-form-army').find('.arm_select').each(function(e){
                $(this).attr('disabled',false);
            });
        }
    });
    $(function (){
        if($('select[name="mm_arm_type"]').val()==1){
            $('.table-form-army').find('.input-text').each(function(e){
                $(this).val('');
                $(this).attr('disabled',true);
            });
            $('.table-form-army').find('.arm_select').each(function(e){
                $(this).val(0);
                $(this).prev().text('없음');
                $(this).attr('disabled',true);
            });
        }
        $('.ico-info').mouseover(function() {
            $(this).siblings('.text-box').addClass('active');
        }).mouseout(function() {
            $(this).siblings('.text-box').removeClass('active');
        });
    });

    $('#mm_profile').change(function(e){
        readURL(this);
        
    })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var fileName = $("#mm_profile").val();
                fileName = fileName.slice(fileName.indexOf(".") + 1).toLowerCase();
                if(fileName != "jpg" && fileName != "png" &&  fileName != "gif" &&  fileName != "bmp" &&  fileName != "jpeg"){
                    alert("이미지 파일은 (jpg, png, gif, bmp, jpeg) 형식만 등록 가능합니다.");
                    $("#mm_profile").val("");
                    $(".filename").text("");
                    return false;
                }else{
                    $('#profile_image').attr('src',e.target.result);  
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#mm_post, #mm_address').click(function(){
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분입니다.
                // 예제를 참고하여 다양한 활용법을 확인해 보세요.
                var zonecode = data.zonecode;
                var address = data.roadAddress;
                $('#mm_post').val(zonecode);
                $('#mm_address').val(address);
                $('#mm_address_detail').focus();
            }
        }).open();
    });
    $('#btn_save').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#info_form1').find('.input-text').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');

            if(val=="" && (txt!='입대일' && txt!='제대일' && txt!='병과' && txt!='사유(면제 및 기타)')){
                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }

        });
        if(!validate){
            return false;
        }else{
            if($('input[name="mm_password"]').val() != $('input[name="mm_re_password"]').val()){
                alert('비밀번호를 확인해주세요.');
                $('input[name="mm_password"]').focus();
                return false;
            };
            let pw1 = $('input[name="mm_password"]').val();
            let num = pw1.search(/[0-9]/g);
            let eng = pw1.search(/[a-z]/ig);
            if(pw1.length < 8 || pw1.length > 20){
                alert("비밀번호는 8자리 ~ 20자리 이내로 입력해주세요.");
                $('input[name="mm_password"]').focus();
                return false;
            }else if(pw1.search(/\s/) != -1){
                alert("비밀번호는 공백 없이 입력해주세요.");
                $('input[name="mm_password"]').focus();
                return false;
            }else if(num < 0 && eng < 0){
                alert("영문,숫자를 혼합하여 입력해주세요.");
                $('input[name="mm_password"]').focus();
                return false;
            }
        }
        var form = $('#info_form1')[0];
        var data = new FormData(form);
        if(validate){
            // if(confirm("개인정보 임시저장을 하시겠습니까?")){
            //     hlb_fn_file_ajaxTransmit("/@proc/new/new_profileProc.php", data);
            // }
            hlb_fn_file_ajaxTransmit("/@proc/new/new_profileProc.php", data);
        }
    })

    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='new_profileProc'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                //alert(result.msg);
                new_step_info(2);
                //location.reload();
                //location.href="/";
            }
        }
    }

    var file = document.querySelector('#mm_profile');
    file.onchange = function () {
        var fileList = file.files ;

        // 읽기
        var reader = new FileReader();
        reader.readAsDataURL(fileList [0]);

        //로드 한 후
        reader.onload = function  () {
            document.querySelector('#profile_image').src =  reader.result;

        };
    };

    $('.input-datepicker').datepicker();
</script>