<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <!-- Show khi mở giao diện light-->
                    <a href="/dashboard.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="/imgs/logo.png" alt="EMO" height="20"> 
                        </span>
                        <span class="logo-lg">
                            <img src="/imgs/logo.png" alt="EMO" height="40">
                            <span class="logo-title ml-2">
                            <?php
                            include "components/title.php";
                            ?>
                            </span>
                        </span>
                    </a>


                    <!-- Show khi mở giao diện dark-->
                    <a href="/dashboard.php" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="/imgs/logo-white.png" alt="EMO" height="20">
                        </span>
                        <span class="logo-lg">
                            <img src="/imgs/logo-white.png" alt="EMO" height="40">
                            <span class="logo-title ml-2">
                            <?php
                            include "components/title.php";
                            ?>
                            </span>
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
                    <span class="hamburger-icon d-none">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <i class="fa-solid fa-bars fs-1"></i>
                </button>


            </div>

            <div class="d-flex align-items-center">


                <div class="ms-1 header-item d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
                        <i class="bx bx-moon fs-22"></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item">
                    
                    
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="/imgs/logo-user.jpg" alt="Logo User">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?= $_SESSION['username'] ?? "" ?></span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header your-code">Mã code của bạn là: <?= $_SESSION['username'] ?? "" ?></h6>

                        <a class="dropdown-item" href="logout.php"><i class="mdi mdi-logout fs-16 align-middle me-1"></i>
                            <span class="align-middle" data-key="t-logout">Đăng xuất</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>