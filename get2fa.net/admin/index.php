<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: ' . $domain . '/admin/login.php');
}

include "../components/header.php";
$title = "Dashboard";

// count all users, course, lesson
$sql = "SELECT COUNT(*) as total FROM users";

// count all users
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetch(PDO::FETCH_ASSOC);

// count all courses
$sql = "SELECT COUNT(*) as total FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetch(PDO::FETCH_ASSOC);

// count all lessons
$sql = "SELECT COUNT(*) as total FROM lessions";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lessions = $stmt->fetch(PDO::FETCH_ASSOC);




?>

<body>

    <div id="layout-wrapper">

        <?php include "./components/top-menu.php" ?>

        <?php include "./components/navbar.php" ?>

        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">

                    <?php include "./components/title.php" ?>

                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary rounded-2 fs-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden ms-3">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                                Tổng khóa học
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0">
                                                    <span class="counter-value" data-target="<?= $courses["total"] ?>"><?= $courses["total"] ?></span>
                                                </h4>

                                            </div>
                                            <a class=" text-truncate mb-0" href="<?= $domain . "/admin/course.php" ?>">
                                                Đi đến trang quản lý khóa học
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>
                        <!-- end col -->

                        <div class="col-xl-4">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning rounded-2 fs-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award">
                                                    <circle cx="12" cy="8" r="7"></circle>
                                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88">
                                                    </polyline>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">
                                                Tổng bài học
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0">
                                                    <span class="counter-value" data-target="<?= $lessions["total"] ?>"><?= $lessions["total"] ?></span>
                                                </h4>

                                            </div>
                                            <a class=" text-truncate mb-0" href="<?= $domain . "/admin/lession.php" ?>">
                                                Đi đến trang quản lý bài học
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>
                        <!-- end col -->

                        <div class="col-xl-4">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info rounded-2 fs-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden ms-3">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                                Tổng tài khoản
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0">
                                                    <span class="counter-value" data-target="<?= $users["total"] ?>"><?= $users["total"] ?></span>
                                                </h4>

                                            </div>
                                            <a class=" text-truncate mb-0" href="<?= $domain . "/admin/user.php" ?>">
                                                Đi đến trang quản lý tài khoản
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>
                        <!-- end col -->
                    </div>


                </div>

            </div>


        </div>


        <?php include "../components/scripts.php" ?>
        <?php include "../components/footer.php" ?>

    </div>



</body>