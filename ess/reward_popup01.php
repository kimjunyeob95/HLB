<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/common_color.php';
?>
<!--  Body window-popup-->
<div class="window-popup">
	<h2 class="popup-title">연봉계약안내</h2>
	<div class="popup-wrap">
		<p class="notice large">지난 한해동안 회사 발전을 위한 귀하의 노력에<br>
		진심으로 감사드리며, 아래와 같이 귀하의 2017년도<br>
		연봉산정 기준 및 계약 내용을 알려드립니다.</p>
		<div class="cont-box">
			<p>귀하의 2017년도 연봉은 기 숙지하시는 바와 같이 <br>2016년도 성과평가 결과를 기준으로 등급별(S~D등급) 차등<br> 책정되었으며, 책정된 연봉은 <em class="em weighty">"2017.01.01 ~ 2017.12.31" 까지<br> 1년간 적용됩니다.</em></p>
			<p class="result">※ 2016년도 성과평가 결과 : "<span id="span_ZcapgrdTxt"></span>"</p>
		</div>
		<p class="cont">아래의 “확인” 버튼을 클릭하시면 세부화면으로  이동하여 2017년도 <br>연봉의 세부내역을 확인할 수 있습니다.<br><br>
			<em class="em weighty">※ 연봉 조회 기간 : 2017. 01. 13 ~ 22 (10일간)</em>
		</p>
		<div class="button-area large">
			<input type="button" id="btn_print" class="btn small type01" onclick="js_selectYsalWntiPop();" value="확인">
		</div>
		<a class="popup-close" href="javascript:void(0);">팝업닫기</a>
	</div>
</div>
<!-- // Body window-popup-->
</body>
</html>

<script>
    $('.popup-close').click(function(e){
        e.preventDefault();
        window.close();
    })
</script>