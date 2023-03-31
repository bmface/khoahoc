<?php


// check url if has view-source
if (strpos($_SERVER['REQUEST_URI'], 'view-source') !== false) {
    header('location: login.php');
}

include "config.php";

checkUserLogged();

checkRememberCookie();

updateTimeDevice();

// check if not exist get course_id
if (!isset($_GET['id'])) {
    header('location: dashboard.php');
}

// get current user
$sql = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user not exist
if (!$user) {
    header('location: login.php');
}

$course_id = $_GET['id'];

// sql injection
$course_id = addslashes($course_id);

// check user have course
$sql = "SELECT * FROM user_courses WHERE user_id = " . $user['id'] . " AND course_id = " . $_GET['id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user_course = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user not have course
if (!$user_course) {
    header('location: dashboard.php');
}



// get course with lessions
$sql = "select c.name as course_name , l.* from courses c join lessions l on l.course_id = c.id where c.id = " . $course_id . " order by l.position asc";

$stmt = $conn->prepare($sql);
$stmt->execute();
$lessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// check if course not have lession
if (!$lessions) {
    header('location: dashboard.php');
}


// check if empty or not exist lession id or lession id not in lessions
if (!isset($_GET["lession_id"]) || empty($_GET["lession_id"]) || !in_array($_GET["lession_id"], array_column($lessions, "id"))) {
    header('location: course.php?id=' . $course_id . '&lession_id=' . $lessions[0]["id"]);
}


$lession_id = !empty($_GET["lession_id"]) ? $_GET["lession_id"] : $lessions[0]["id"];



$title = "" . $lessions[0]["course_name"];


// current lession
$sql = "SELECT * FROM lessions WHERE id = " . $lession_id;
$stmt = $conn->prepare($sql);
$stmt->execute();
$lession = $stmt->fetch(PDO::FETCH_ASSOC);

// check if lession
if (!$lession) {
    header('location: dashboard.php');
}

// check if lession id < user_course lession id
if ($lession["position"] > $user_course['lession_id']) {

    // if user course lession id = 0 then update lession id = lessions[0]
    if ($user_course['lession_id'] == 0) {
        $sql = "UPDATE user_courses SET lession_id = " . $lession["position"] . " WHERE user_id = " . $user['id'] . " AND course_id = " . $course_id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }


    // go to first lession
    header('location: course.php?id=' . $course_id . '&lession_id=' . $lession["id"]);
}

// create ul lessions
$ul_lessions = "";
foreach ($lessions as $lessionItem) {

    // check if lession id > user_course lession id disable 
    // check active current lession
    if ($lessionItem['position'] > $user_course['lession_id']) {
        $ul_lessions .= "<li class='nav-item list-group-item'>
        <a class='nav-link disabled d-flex justify-content-between align-items-center' href='course.php?id=" . $course_id . "&lession_id=" . $lessionItem['id'] . "'>" . $lessionItem['name'] . "<i class='fa-solid fa-lock'></i><i class='d-none-2 fa-solid fa-lock-open'></i><div class='d-none-2 form-warming-hv'>Để mở được bài học này, bạn cần xem hết video bài học trước!</div></a>
    </li>";
    } else if ($lessionItem['position'] == $lession["position"]) {
        $ul_lessions .= "<li class='nav-item list-group-item '>
        <a class='nav-link active d-flex justify-content-between align-items-center' href='course.php?id=" . $course_id . "&lession_id=" . $lessionItem['id'] . "'>" . $lessionItem['name'] . " <i class='fa-solid fa-eye'></i></a>

    </li>";
    } else {
        $ul_lessions .= "<li class='nav-item list-group-item'>
        <a class='nav-link link-dark d-flex justify-content-between align-items-center' href='course.php?id=" . $course_id . "&lession_id=" . $lessionItem['id'] . "'>" . $lessionItem['name'] . "<i class='fa-solid fa-circle-check' style='color: #1dbb00'></i></a>
    </li>";
    }
}


include "components/header.php";

?>
<style>
.link-dark .fa-lock {
    display: none;
}

.d-none-2 {
    display: none;
}

.link-dark .fa-lock-open {
    display: block;
}

.form-warming-hv {
    position: absolute;
    padding: 5px 10px;
    margin: 5px 5px 5px 0px;
    border-radius: 12px;
    background-color: #ebebeb;
}

.nav-item:hover .disabled .form-warming-hv {
    display: block;
}
</style>
<!-- include videojs from dns -->
<link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet" />

<body class="menu site-course">

    <div id="layout-wrapper">

        <?php

        include "components/topbar.php";
        ?>


        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">

                    <?php
                    include "components/navbar.php";
                    ?>

                    <div class="row">

                        <div class="col-12 col-md-8 p-0">
                            <video id="my-video" class="video-js vjs-16-9 vjs-fluid" controls preload="auto"
                                data-setup='{"fluid": true}'>
                                <source src="<?= $lession["video"] ?>" type="video/mp4" />

                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a
                                    web browser that
                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                        video</a>
                                </p>
                            </video>
                        </div>

                        <div class="d-none d-md-block col-md-4 pl-3">
                            <div class="form-course pb-2">
                                <!-- Create list menu lession -->
                                <div class="title-name-lessons">
                                    <h4 class="pl-3 pr-3 mt-3 content-course"><span>Nội dung khóa học</span><i
                                            class="fa-solid fa-pencil"></i></h4>
                                    <p class="pl-3 pr-3 ">Học theo thứ tự lần lượt từng bài</p>
                                </div>
                                <ul class="list-group name-lessons">
                                    <?= $ul_lessions ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <h3 class="name-video-title"><?= $lession['name'] ?></h3>
                        </div>

                        <div class="col-12 mt-2">
                            <ul class="nav nav-tabs mb-3 arrow-navtabs tab-study" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#home" role="tab"
                                        aria-selected="false">
                                        Nội dung bài học
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#settings" role="tab"
                                        aria-selected="true">
                                        Danh sách bài học
                                    </a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content style-tab-noi-dung-bai-hoc">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <?= $lession["content"] ?>
                                </div>

                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <ul class="list-group name-lessons">
                                        <?= $ul_lessions ?>
                                    </ul>
                                </div>
                            </div>


                            <!-- <video controls preload="auto" src="view-video.php" width="100%"></video>' -->


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- import axios from dns -->
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

        <!-- import jquery from dns -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <?php
        include "components/scripts.php";
        ?>

        <!-- Add script videojs from dns -->
        <script src="https://vjs.zencdn.net/7.8.4/video.js"></script>

        <script>
        // disable user f12, crtl + u, crtl + shift + i
        $(document).keydown(function(event) {
            if (event.keyCode == 123) { // Prevent F12
                return false;
            } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
                return false;
            } else if (event.ctrlKey && event.keyCode == 85) { // Prevent Ctrl+U
                return false;
            }
        });

        // disable right click
        $(document).bind("contextmenu", function(e) {
            e.preventDefault();
        });
        </script>


        <script>
        //jquery onload
        $(document).ready(function() {
            // videojs
            var player = videojs('my-video');

            var updateLession = false

            // check if player > 80%
            player.on('timeupdate', function() {
                var percent = Math.floor((100 / this.duration()) * this.currentTime());
                if (percent > 80 && !updateLession) {

                    axios.get('../api/updateLession.php?lession_id=<?= $lession_id ?>')
                        .then(function(response) {
                            updateLession = true

                            const {
                                status,
                                message
                            } = response.data
                            if (status) {
                                Toastify({
                                    text: message,
                                    duration: 3000
                                }).showToast();


                                // enable next lession
                                $('.nav-link.active').parent().next().find('.nav-link').removeClass(
                                    'disabled').addClass("link-dark")


                            }


                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }
            })
        })
        </script>

        <?php

        include "components/footer.php";
        ?>

</body>