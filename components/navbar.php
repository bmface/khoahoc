<div id="navbar-menu" class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="./assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="./assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="./assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="./assets/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
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
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
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
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                                    <div class="simplebar-content" style="padding: 0px;">
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link" href="/dashboard.php">
                                                                <i class="fa-solid fa-circle-question" style="color:#1473e6;font-size: 45px;"></i>
                                                                <span data-key="t-dashboards">Đặt câu hỏi</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link" href="/dashboard.php">
                                                                <i class="fa-solid fa-house"></i>
                                                                <span data-key="t-dashboards">Trang chủ</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link" href="/loi-khuyen.php">
                                                                <i class="fa-solid fa-book-open"></i>
                                                                <span data-key="t-dashboards">Lời khuyên</span>
                                                            </a>
                                                        </li>
                                                        <!--<li class="nav-item">
                                                            <a class="nav-link menu-link" href="/learn.php">
                                                                <i class="fa-solid fa-lightbulb"></i>
                                                                <span data-key="t-dashboards">Học</span>
                                                            </a>
                                                        </li>-->
                                                        <!--<li class="nav-item">
                                                            <a class="nav-link menu-link" href="/faq.php">
                                                                <i class="fa-solid fa-square-pen"></i>
                                                                <span data-key="t-dashboards">Faq</span>
                                                            </a>
                                                        </li>-->
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link" href="/support.php">



                                                                <i class="fa-solid fa-headset"></i>
                                                                <span data-key="t-dashboards">Hỗ trợ</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link" href="/tickets.php">

                                                                <span class=" position-relative p-0 avatar-xs ">
                                                                    <span class="">
                                                                        <i class="fa-solid fa-ticket"></i>
                                                                    </span>

                                                                    <?php

                                                                    $sql = "SELECT * FROM tickets WHERE status = 'Support trả lời' AND user_id = '" . $_SESSION["user_id"] . "'";
                                                                    $smts = $conn->prepare($sql);
                                                                    $smts->execute();
                                                                    $count = $smts->rowCount();
                                                                    ?>

                                                                    <?php
                                                                    // if $count > 0
                                                                    if ($count > 0) {
                                                                    ?>

                                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                                            <?php echo $count; ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                </span>

                                                                <span data-key="t-dashboards">Ticket</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link menu-link" href="https://emomedia.vn/">
                                                                <i class="fa-solid fa-at"></i>
                                                                <span data-key="t-dashboards">Nhà cung cấp</span>
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
            <div class="simplebar-scrollbar" style="height: 709px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
        </div>
    </div>
</div>