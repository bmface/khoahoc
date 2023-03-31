<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="../imgs/logo.png" alt="" height="40">
            </span>
            <span class="logo-lg">
                <img src="../imgs/logo.png" alt="" height="40"><span class="ml-2 fs-1 text-white" style=" font-weight: 900; vertical-align: middle; ">EMO</span>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="../imgs/logo.png" alt="" height="40">
            </span>
            <span class="logo-lg">
                <img src="../imgs/logo.png" alt="" height="40"><span class="ml-2 fs-1 text-white" style=" font-weight: 900; vertical-align: middle; ">EMO</span>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar" data-simplebar="init" class="h-100">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"
                        style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <div class="container-fluid">
                                <div id="two-column-menu"></div>
                                <ul class="navbar-nav" id="navbar-nav" data-simplebar="init">
                                    <div class="simplebar-wrapper" style="margin: 0px;">
                                        <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer"></div>
                                        </div>
                                        <div class="simplebar-mask">
                                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                    aria-label="scrollable content"
                                                    style="height: auto; overflow: hidden;">
                                                    <div class="simplebar-content" style="padding: 0px;">
                                                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link " href="index.php">
                                                                <i class="mdi mdi-speedometer"></i>
                                                                <span data-key="t-dashboards">Thống Kê</span>
                                                            </a>

                                                        </li>

                                                        <li class="menu-title"><span data-key="t-menu">Quản Trị</span></li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link <?= addActiveClass("user.php", "/admin/user/")   ?>"
                                                                href="<?= $domain . "/admin/user.php" ?>">
                                                                <i class="mdi mdi-account"></i>
                                                                <span data-key="t-widgets">Tài khoản</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link  <?= addActiveClass("devices.php", "/admin/devices/")   ?>"
                                                                href="<?= $domain . "/admin/devices.php" ?>">
                                                                <i class="mdi mdi-security"></i>
                                                                <span data-key="t-widgets">Device</span>
                                                            </a>
                                                        </li>

                                                        <li class="menu-title"><span data-key="t-menu">Nội Dung</span>
                                                        </li>
                                                        <li class="nav-item ">
                                                            <a class="nav-link menu-link 
                                                            <?= addActiveClass("course.php", "/admin/course/")   ?>"
                                                                href="<?= $domain . "/admin/course.php" ?>">
                                                                <i class="mdi mdi-bookmark-box-multiple-outline"></i>
                                                                <span data-key="t-widgets">Khóa học</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link <?= addActiveClass("lession.php", "/admin/lession/")   ?>"
                                                                href="<?= $domain . "/admin/lession.php" ?>">
                                                                <i class="mdi mdi-book-open-page-variant-outline
"></i>
                                                                <span data-key="t-widgets">Bài học</span>
                                                            </a>
                                                        </li>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: 249px; height: 1235px;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                    </div>
                                </ul>
                            </div>
                            <!-- Sidebar -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 1235px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                style="height: 709px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
        </div>
    </div>
</div>
<style>
.navbar-menu .navbar-nav .nav-link {
    display: flex;
    flex-direction: row;
    color: #fff!important;
    font-weight: 500;
    font-size: 16px;
    margin: 5px 15px;
    padding: 0.75rem 10px!important;
    border-radius: 16px;
}
.navbar-menu .navbar-nav .nav-link:hover {
    background: #e8ebed;
    color: #1a1a1a!important;
}
</style>