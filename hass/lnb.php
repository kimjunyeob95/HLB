<style>
    .section-wrap tr td.active{color: #ef8440; font-size: 18px; font-weight: bold;}
    #wrap.depth03 .tree-menu .tree {display: none;}
    #wrap.depth03 .tree-menu .tree-wrap li.active .tree {display: block;}
    #wrap.depth03 .tree-menu .tree-wrap .tree a{background: none;}

    #wrap.depth03 #container.close #aside-menu {width: 0;}
    #wrap.depth03 #container.close:before {left: 0;}
    #container.close #aside-menu .menu-control {right: 10px;background-image: url('../@resource/images/common/ico_arrow_05.png'); border-radius: 5px;}
    #container.close #aside-menu .tree-wrap {display: none;}
</style>
<!-- 사이드 트리 메뉴 -->
	<div id="aside-menu" class="tree-menu">
		<h2 class="blind">인사담당 메뉴</h2>
		<button type="button" class="menu-control"><span class="blind">닫기</span></button> <!-- 180124 버튼 추가 -->
		<ul class="tree-wrap">
			<li><a href="#">인사기록</a>
				<ul class="tree" >
					<li>
						<a href="./humanmodify">정보 수정 신청내역</a>
					</li>
					<li>
						<a href="./humancreate">인사 발령</a>
					</li>
                    <li>
						<a href="./humanlist">인사 발령 내역</a>
					</li>
                    <!-- <li>
						<a href="./humanPostlist.php">인사 발령 보낸 내역</a>
					</li> -->
				</ul>
			</li>
			<li><a href="#">신규 입사자</a>
				<ul class="tree" >
					<li>
						<a href="./employecreate">신규 사번 생성</a>
					</li>
					<li>
						<a href="./employeaccept">신입 사원 승인</a>
					</li>
                    <li>
						<a href="./employeNewExcel">신규 입사자 등록</a>
					</li>
<!--					<li>-->
<!--						<a href="./employecreateList.php">사원 생성 내역</a>-->
<!--					</li>-->
				</ul>
			</li>
			<li><a href="#">급여</a>
				<ul class="tree" >
					<li>
						<a href="./salarychecklist">급여 내역 확인</a>
					</li>
					<li>
						<a href="./salaryregister">급여 등록</a>
					</li>
					
				</ul>
			</li>
			<li><a href="#">근태</a>
			<ul class="tree">
					<li>
						<a href="./holidaymanage">휴가 신청 관리</a>
					</li>
                    <!-- <li>
						<a href="./holidayguide.php">근태 가이드 관리</a>
					</li> -->
				</ul>
			</li>
			<!-- 플러스 active시 마이너스로 변경 -->
			<li><a href="#">조직정보</a>
				<ul class="tree">
					<li>
						<a href="./orginfomanage">임직원 관리</a>
					</li>
					<li>
						<a href="./orgmanage">조직 관리</a>
					</li>
					<li>
						<a href="./pstmanage">직책 관리</a>
                    </li>
                    <!-- <li>
						<a href="./pstmanage2.php">직급 관리</a>
					</li> -->
				</ul>
            </li>
            <li><a href="#">공지 관리</a>
				<ul class="tree">
                    <!-- <li>
						<a href="./corpornotice.php">법인 공지사항 관리</a>
					</li> -->
					<li>
						<a href="./corpornotice">공지사항</a>
					</li>
                    <li>
						<a href="./corpormanage">HR 안내</a>
					</li>
                    <li>
						<a href="./corpor_Topnotice">한줄공지</a>
					</li>
				</ul>
            </li>
            <!-- <li><a href="#">임직원 등록</a>
				<ul class="tree">
					<li>
						<a href="./humanExcel.php">임직원 등록</a>
					</li>
				</ul>
			</li> -->
		</ul>
	</div>
	<!-- //사이드 메뉴 -->

<script>
//nav 선택
$('#aside-menu .tree-wrap li a').click(function(e){
    if($(this).parent().hasClass('active')) return $(this).parent().removeClass('active');
    // $('#aside-menu .tree-wrap li').removeClass('active');
    $(this).parent().addClass('active');
});

//lnb toggle
$('.menu-control').click(function(){
    if($('#container').hasClass('close')){
        $('#container').removeClass('close');
    }else {
        $('#container').addClass('close');
    }
});
$(document).ready(function(){
    var winsize = $(window).width();
    if(winsize < 1400){
        $('#container').addClass('close');
    }
})
$(window).resize(function(){
    var winsize = $(window).width();
    if(winsize < 1400){
        $('#container').addClass('close');
    }
});
</script>