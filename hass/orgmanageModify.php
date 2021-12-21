<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");

?>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">조직 수정</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <!-- <h3 class="section-title">공지사항</h3> -->
            <div class="table-wrap">
                <table class="data-table left">
                    <caption>신입 사원 승인 내역</caption>
                    <colgroup>
                        <col style="width: 20%" />
                        <col style="width: *" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="col">부서명</th>
                            <td><input type="text" class="input-text" value="개발팀"></td>
                        </tr>
                        <tr>
                            <th scope="col">인원</th>
                            <td>전무 3명, 상무 5명, 이사 6명, 이사대우 8명</td>
                        </tr>
                    </tbody>
                </table>
                <div class="button-area large">
                    <button data-btn="목록" class="btn type01 large data-btn">목록<span class="ico apply"></span></button>
                    <button data-btn="저장" class="btn type01 large data-btn">저장<span class="ico save"></span></button>
                    <button data-btn="추가" class="btn type01 large data-btn">직책 추가<span class="ico plus"></span></button>
                </div>
            </div>
            <!-- //공지사항 -->
		</div>
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(4)').addClass('active');
    $('.data-btn').click(function(){
        var $this_btn = $(this).data('btn');
        var text_add = `<tr>
                            <th scope="col">직책</th>
                            <td><input type="text" class="input-text" value=""></td>
                        </tr>`;
        if($this_btn=='목록'){
            location.href='./orgmanage';
        }else if($this_btn=='저장'){
            alert('저장 버튼누름');
        }else if($this_btn=='추가'){
            $('.data-table tbody').append(text_add);
        }
    });
</script>
