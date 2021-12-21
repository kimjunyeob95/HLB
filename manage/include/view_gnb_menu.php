<style type="text/css">
.nav-active{line-height:12px;}
ul.nav-main li i{font-size:1.4rem;}
ul.nav-main li span{font-size:1.2rem;}
</style>
<aside id="sidebar-left" class="sidebar-left">
				
    <div class="sidebar-header" >
        <div class="sidebar-title" style="color:#abb4be;">
        MENU
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
				
                <ul class="nav nav-main">
                    <li class="nav-active" >
                        <a href="/manage/main.php">
                          <i class="fa fa-list"></i>
                            <span >대시보드</span>
                        </a>                        
                    </li>
                    <li class="nav-active" >
                        <a href="/manage/human.php">
                          <i class="fa  fas fa-users"></i>
                            <span >인재 검색</span>
                        </a>                        
                    </li>
                    <li class="nav-active" >
                        <a href="/manage/coperation.php">
                          <i class="fa  fas fa-building"></i>
                            <span >법인 관리</span>
                        </a>                        
                    </li>
					<li style="background:#000;color:#fff;">
						<p style="padding-left:10px;font-size:12px;">조직관리</p>
					</li>
					<li class="nav-active">
                        <a href="/manage/member.php">
                           <i class="fas fa fa-users"></i>
                            <span >ESS 사원</span>
                        </a>                        
                    </li>
					<li class="nav-active">
                        <a href="/manage/mssmember.php">
                           <i class="fas fa fa-user-md"></i>
                            <span >MSS 회원</span>
                        </a>                        
                    </li>
					<li class="nav-active">
                        <a href="/manage/hassmember.php">
                           <i class="fas fa fa-heading"></i>
                            <span >HASS 회원</span>
                        </a>                        
                    </li>
					<li class="nav-parent nav-expanded">
                        <a href="#">
                           <i class="fas fa fa-lock"></i>
                            <span >권한 관리</span>
                        </a>            
                        <ul class="nav nav-children">
                            <li data-type="superadmin">
                                <a href="/manage/superadminList.php">superAdmin 계정</a>
                            </li>
                            <li data-type="manage">
                                <a href="/manage/auth.php">관리자 계정</a>
                            </li>
                        </ul>            
                    </li>
					<li class="nav-active">
                        <a href="/manage/tree.php">
                           <i class="fas fa fa-stream"></i>
                            <span >조직도 관리</span>
                        </a>                        
                    </li>
					<li style="background:#000;color:#fff;">
						<p style="padding-left:10px;font-size:12px;">게시판 </p>
					</li>
					<!-- <li class="nav-active">
                        <a href="/manage/notice.php">
                           <i class="fas fa fa-chalkboard"></i>
                            <span >공지사항 관리</span>
                        </a>                        
                    </li>
					<li class="nav-active">
                        <a href="/manage/banner.php">
                           <i class="fas fa fa-images"></i>
                            <span >배너 관리</span>
                        </a>                        
                    </li> -->
						<li class="nav-active">
                        <a href="/manage/template.php">
                           <i class="fas fa fa-images"></i>
                            <span >페이지</span>
                        </a>                        
                    </li>
				


		
				
				
				

				
                   
                        
                      <!--
                    <li>
                        <a href="mailbox-folder.html">
                            <span class="pull-right label label-primary">182</span>
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span>서비스 통계</span>
                        </a>                        
                    </li>
					-->
                  
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    
                    sidebarLeft.scrollTop = initialPosition;
                }
            }

        </script>
        
        </div>
    </div>


</aside>

