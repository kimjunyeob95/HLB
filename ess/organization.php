<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
?>
<script type="text/javascript" src="/manage/@resource/jquery.orgchart.js"></script>
<link rel="stylesheet" href="/manage/@resource/jquery.orgchart.css" />
<style>
	div.orgChart{border:none;}
	div.orgChart div.node.level1 {
		background-color: #f0f0f0;
	}
	div.orgChart div.node.level1.special {
		background-color: white;
	}
	div.orgChart div.node.level2 {
		background-color: #fff;
	}
	div.orgChart div.node.level3 {
		background-color: #fff;
	}
	div.orgChart{
		background-color:#fff;
	}
	div.orgChart div.hasChildren{
		background-color: #fff;
	}
	div.orgChart div.node{border:none;}
	div.orgChart tr.lines td.right{border-left: 1px solid #ddd;}
	div.orgChart tr.lines td.top{border-top: 1px solid #ddd;}
	div.orgChart tr.lines td.line{width:5px;}
	div.orgChart tr.lines td.left{border-right:1px solid #ddd;}
	
	</style>
<div id="wrap" class="depth03">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>
<!-- // HEADER -->
<!-- CONTENT -->
<div id="container" class="my-profile-card">
	<!-- 사이드 메뉴 -->
	<div id="aside-menu" class="side-menu">
		<h2>My Profile</h2>
		<ul class="lnb">
			<li><a href="/ess/" >인사기록카드</a></li>
			<li><a href="/ess/timeline.php"  >나의 타임라인</a></li>
			<li><a href="/ess/organization.php" class="active" >조직도</a></li>
		</ul>
	</div>
	<!-- //사이드 메뉴 -->
	<div id="content" class="content-primary">
		  <ul id="organisation" style="display:none;">
                <li style="width:140px;height:180px;"><img src="/manage/@resource/ceo.png" alt="" style="width:130px;border-radius:10px;" title="대표이사"><em style="font-size:1.1rem;">CEO</em><br/><br/>
                    <ul >
                        <li  style="width:140px;height:200px;"><img src="/manage/@resource/bb.png" alt="" style="width:130px;border-radius:10px;" title="제작본부장"><br><p style="font-size:1.1rem;margin-top:5px;">제작본부 <em>(35) </em></p>
                            <ul>
                                <li  style="width:140px;height:290px;">기획팀<em>(10) </em></li>
                                <li   style="width:140px;height:290px;">디자인팀<em>(10) </em></li>
                                <li   style="width:140px;height:290px;">개발팀<em>(15) </em></li>
                            </ul>
                        </li>
                        <li  style="width:140px;height:200px;"><img src="/manage/@resource/thumb_130x170.jpg" alt="" style="width:130px;border-radius:10px;" title="제작본부장"><br><p style="font-size:1.1rem;margin-top:5px;">HR팀<em>(3) </em></p></li>
                        <li   style="width:140px;height:200px;"><img src="/manage/@resource/bb.png" alt="" style="width:130px;border-radius:10px;" title="제작본부장"><p style="font-size:1.1rem;"><p style="font-size:1.1rem;margin-top:5px;">경영지원실<em>(5) </em></p>
                            <ul>
                                <li   style="width:140px;height:290px;">Maurice Fischer
                                    <ul>
                                        <li   style="width:140px;height:290px;">Peter Browning</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
			 <div id="main">
            </div>

		
	</div>
</div>

<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<script>
$('.header-wrap ').addClass('active');
$('.depth01:eq(0)').addClass('active');
$('.depth02:eq(0)').find('li:eq(2)').addClass('active');

$(function() {
	$("#organisation").orgChart({container: $("#main"), stack: true, depth: 4});
});
</script>