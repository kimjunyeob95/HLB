

<header class="header" style="z-index: 999999;">
    <div class="logo-container">
        <a href="/manage/main.php" class="logo"  style="text-decoration: none;margin-top:20px;">
			<img src="/data/logo/1.png" alt="" style="width:77px;">
        </a>
        <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right" style="margin-top: 12px;">
       <!--  <ul class="notifications" style="margin: 5px 0.5vw 0 0; " id="move">
            <li>
                <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                    <span class="badge"><?=$data_header['cnt']?></span>
                </a>
        
                
            </li>
        </ul> -->
        <div id="userbox" class="userbox" style="margin: 0.5vw 17px 0 0;">
            <a href="#" data-toggle="dropdown">
<!--                <figure class="profile-picture">
                    <img src="assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
                </figure> -->
                <div class="profile-info" data-lock-name="John Doe" data-lock-email="www@hanbom.com">
                    <span class="name"><u style="color:#000"><?=$_SESSION['admin_name']?>님</u> 환영합니다.</span>
                    <span class="role"><?=$_SESSION['admin_info']['id']?></span>
                </div>

                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                <ul class="list-unstyled">
                    <li class="divider"></li>
					<!--
                    <li>
                        <a role="menuitem" tabindex="-1" href="/manage/pw_change.php">비밀번호변경</a>
                    </li>
					-->
                    <li>
                        <a role="menuitem" tabindex="-1" href="/manage/proc/logout.php">로그아웃</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end: search & user box -->
</header>

