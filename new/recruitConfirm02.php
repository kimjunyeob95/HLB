<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
if($_REQUEST['agree1']!=1 || $_REQUEST['agree2']!=1){
    echo '<script>location.href="/new/recruitConfirm01"</script>';
}
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");

?>
<!-- WRAP -->
<div id="wrap" class="depth-main newcomer">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/new_head.php'; ?>
<!-- CONTENT -->
    <form id="agree_step2_from">
        <input type="hidden" value="<?=$_REQUEST['agree1']?>" name="agree1">
        <input type="hidden" value="<?=$_REQUEST['agree2']?>" name="agree2">
    </form>
<div id="container" class="item-guidance">
	<div id="content" class="content-primary">
        <h2 class="content-title">입사관련 안내사항</h2>
		<strong class="sub-title">2017년 상반기 공채 입사자 입문교육 안내</strong>
        <!-- 채용 컨텐츠 영역 -->
        <div class="guidance-detail">
            <h3 class="section-title">입문교육</h3>
            <h4>1. 입문교육 입교일 : 2017. 00. 00</h4>
            <h4>2.  교육내용 및 기간 (총 5주)</h4>
            <ul class="data-list">
                <li>- 1~2주 : 그룹 공통 입문교육 (11박 12일, 2주차 금요일 귀가)</li>
                <li>- 3~5주 :  입문교육 (월~금 교육, 매주 금요일 귀가)</li>
            </ul>
            <h4>3.  교육장소 : 마북/파주/천안연수원, 사외교육장 등</h4>
            <p>※ 입문교육 중에는 규정된 경조사 外 외출/외박이 불가하므로 개인일정 정리 후 입교바랍니다.</p>
            <h4>4.  집합안내</h4>
            <ul class="data-list">
                <li>- 일시 : 상기 입교일 오전 06:30까지</li>
                <li>- 장소 : 3호선 압구정역 6번출구 백화점 옆 야외 공영주차장 ※ 개별입교 불가</li>
            </ul>
            <h4>5.  준비물(입사구비서류)</h4>
            <ul class="data-list">
                <li>- 신분증 사본 2부 (주민등록증, 운전면허증, 여권 중 택1)</li>
                <li>- 졸업(예정)증명서 1부</li>
                <li>- (본인기준) 가족관계증명서 1부 (단, 자녀가 있을 경우 "자녀기본증명서" 1부 추가 구비)
                    <span>※ 주민센터 방문/무인발급기 이용/인터넷 발급 신청(대법원 전자가족관계등록 시스템, 민원 24시)</span>
                    <span>※ 주민등록등본 대체 불가</span>
                </li>
                <li>- 병적증명서 1부(병역의무를 이행한 대상자 限)
                    <span>※ 연수원 입소 첫 날 모든 입사구비서류 제출 必</span>
                </li>
            </ul>
        </div>
        <!-- // 채용 컨텐츠 영역 -->
        <div class="btn-area">
            <a class="btn type04 large agree-btn"><span class="ico write02"></span>인사정보 작성하러 가기</a>
        </div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<!-- // WRAP -->
<script>
    var data = $('#agree_step2_from').serialize();
    $('.agree-btn').click(function(e){
        e.preventDefault();
        location.href = './recruitStep01?'+data;
    });
</script>
