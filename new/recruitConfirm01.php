<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");

?>
<!-- WRAP -->
<div id="wrap" class="depth-main newcomer">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/new_head.php'; ?>
<!-- CONTENT -->
<div id="container" class="newcomer-intro" style="width: 1620px !important;max-width: 1680px !important;">
	<div id="content" class="content-primary">
		<!-- 신규입사자 정보등록 사이트 -->
		<div class="intro-text">
			<h2 class="content-title">에이치엘비그룹 임직원 인사정보등록</h2>
			<span class="welcome">당신은 에이치엘비그룹의 소중한 구성원입니다.</span>
			<div class="img-wrap">
				<img src="/@resource/images/common/recruit_image.png" alt="이미지" style="height: 100%;">
			</div>
			<!-- <ul class="data-list">
				<li class="list01">
					<h3></h3>
					<p>실패를 두려워하지 않고 <br>새로운 영역을 개척하는 사람</p>
				</li>
				<li class="list02">
					<h3></h3>
					<p>끊임없는 학습과 열정으로<br>스스로 성장하는 사람</p>
				</li>
				<li class="list03">
					<h3></h3>
					<p>소통과 협업으로<br>시너지를 발휘하는 사람</p>
				</li>
			</ul> -->
		</div>
		<!-- //신규입사자 정보등록 사이트 -->

		<div class="agree-wrap">
			<!-- 개인정보 수집 및 이용에 관한 동의 -->
			<div class="agreement">
				<h3>
					<label for="agree01">개인정보 수집 및 이용에 관한 동의</label>
					<input type="checkbox" name="agree01" value="1" id="agree01">
				</h3>
				<div class="text-wrap">
                    본인은 에이치엘비그룹 임직원으로서 인적자원관리상 개인정보제공이 필요하다는 것을 이해하고 있으며,
                    이를 위해 “개인정보보호법” 등의 규정에 따라 아래의 개인정보를 수집·이용하는 것에 동의합니다. <br>

                    1.  개인정보항목<br>
                        - 성명, 현주소, 생년월일, 이메일, 연락처, 학력, 경력, 가족사항, 병역, 자격사항, 어학능력, 교육 및 외부활동,
                        논문저서, 기타 근무와 관련된 개인정보<br>
                    2. 수집·이용목적<br>
                        - 주요 근로조건의 결정, 그룹 인사이동(배치전환, 승진 등), 보상, 평가, 노무, 상벌 등의 인적자원관리<br>
                    3. 보유기간<br>
                        - 수집 이용 동의일로부터 퇴사 후 5년이 지난 해의 12월 말일까지<br>
                    4. 개인정보 수집 동의 거부의 권리 및 동의 거부에 따른 불이익<br>
                        - 회사는 보다 원활한 서비스 제공을 위하여 필수입력정보와 선택입력정보를 수집하고 있으며,
                        위 사항에 대하여 원하지 않는 경우 동의를 거부할 수 있습니다. <br>
                        다만, 수집하는 개인정보의 항목에서
                        필수정보에 대한 수집 및 이용에 대하여 동의하지 않는 경우는 서비스 제공에 어려움이 있을 수 있습니다.
				</div>
			</div>
			<!-- // 개인정보 수집 및 이용에 관한 동의 -->
			<!-- 개인정보 제3자 제공에 대한 동의 -->
			<div class="agreement">
				<h3>
					<label for="agree02">민감정보와 고유식별정보 수집</label>
					<input type="checkbox" name="agree02" value="1" id="agree02">
				</h3>
				<div class="text-wrap">
                    본인은 상기 개인정보에 대한 동의와 별도로 아래의 민감 정보와 고유식별정보를 수집·이용하는 것에 동의합니다. <br>
                    1. 민감정보항목<br>
                        - 국가보훈대상, 장애여부, 기타 인적자원관리에 필요한 민감정보<br>
                    2. 수집·이용목적<br>
                        - 우선채용대상자격 및 정부지원금(장려금 등) 주요 근로조건의 결정, 그룹 인사이동(배치전환, 승진 등), 노무 등의 인적자원관리<br>
                    3. 보유기간<br>
                        - 수집 이용 동의일로부터 퇴사 후 5년이 지난 해의 12월 말일까지<br>
                    4. 민감정보 수집 동의 거부의 권리 및 동의 거부에 따른 불이익<br>
                        - 회사는 보다 원활한 서비스 제공을 위하여 민감정보를 수집하고 있으며, 위 사항에 대하여 원하지 않는 경우 동의를 거부할 수 있습니다.<br>
                        다만, 수집하는 민감정보의 항목에서 필수정보에 대한 수집 및 이용에 대하여 동의하지 않는 경우는
                        서비스 제공에 어려움이 있을 수 있습니다.

				</div>
			</div>
			<!-- // 개인정보 제3자 제공에 대한 동의 -->

            <div class="agreement">
				<h3>
					<label for="agree03">개인정보 제3자 제공에 대한 동의</label>
					<input type="checkbox" name="agree03" value="1" id="agree03">
				</h3>
				<div class="text-wrap">
                    본인은 개인정보를 제3자에게 수집·이용하는 것에 동의합니다.<br>

                    1. 개인정보항목<br>
                    - 성명, 현주소, 생년월일, 이메일, 연락처, 학력, 경력, 가족사항, 병역, 자격사항, 어학능력, 교육 및 외부활동,
                        논문저서, 기타 근무와 관련된 개인정보<br>
                    2. 수집·이용목적<br>
                        - 임직원 정보 관리 및 경영사항 위탁<br>
                    3. 위탁 기관<br>
                        - 에이치엘비 ㈜<br>
                    4. 보유기간.<br>
                        - 수집 이용 동의일로부터 퇴사 후 5년이 지난 해의 12월 말일까지<br>
                    5. 개인정보의 제3자 제공 거부의 권리 및 거부에 따른 불이익<br>
                        - 회사는 보다 원활한 서비스 제공을 위하여 제3자에게 개인정보를 제공하고 있으며, 위 사항에 대하여 원하지 않는 경우
                        동의를 거부할 수 있습니다. 다만, 거부할 경우 서비스 제공에 어려움이 있을 수 있습니다.


				</div>
			</div>
		</div>

		<div class="btn-area">
			<a class="btn type02 large disagree-btn"><span class="ico not-agree"></span>비동의</a>
			<a class="btn type03 large agree-btn"><span class="ico agree"></span>동의</a>
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<!-- // WRAP -->
<script>
	$(document).ready(function(){
        $('.disagree-btn').click(function(e){
            e.preventDefault();
            alert('비동의시, 다음단계로의 진행이 불가능합니다.');
            return false;
        });
		$('.agree-btn').click(function(e){
			e.preventDefault();
			if(!$('#agree01').parent().hasClass('checked')){
				alert('개인정보 수집 및 이용에 관한 동의하세요.');
				return false;
			}
			if(!$('#agree02').parent().hasClass('checked')){
				alert('민감정보와 고유식별정보 수집에 동의하세요.');
				return false;
			}
            if(!$('#agree03').parent().hasClass('checked')){
                alert('개인정보 제3자 제공에 대한 동의하세요.');
                return false;
            }
			agree1 = $('#agree01').val();
            agree2 = $('#agree02').val();
            agree3 = $('#agree03').val();
        	location.href = './recruitStep01?agree1='+agree1+'&agree2='+agree2+'&agree3='+agree3;
    	});	
	});
    
</script>
