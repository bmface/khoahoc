<?php

include "../../config.php";

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Nhập tài khoản";

include "../../components/header.php";


// table user: username, phone, token, code, role

$error = '';

//get all courses
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach courses and print html to use click select
function printCourses($courses)
{
    foreach ($courses as $course) {

        echo '<div class="form-check mb-3 col">';
        echo '<input class="form-check-input" type="checkbox" id="formCheck' . $course["id"] . '" value="' . $course["id"] . '" name="courses[]" >';
        echo '<label class="form-check-label" for="formCheck' . $course["id"] . '">';
        echo $course['name'];
        echo '</label>';
        echo '</div>';
    }
}

// check if method post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $items = $_POST['items'];
    $items = explode("\r\n", $items);

    $courses_post = $_POST['courses'];

    // unique $items
    $items = array_unique($items);

    // foreach and check item has username in database
    $check = false;
    foreach ($items as $item) {

        // remove space
        $item = trim($item);


        // check if exist in users where username = $item
        $sql = "SELECT * FROM users WHERE username = '$item'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // if exist
        if ($user) {
            $check = true;
            $error = '<div class="alert alert-danger">Tài khoản ' . $item . ' đã tồn tại</div>';
            break;
        }
    }

    // check course exist in database
    foreach ($courses_post as $course) {
        $sql = "SELECT * FROM courses WHERE id = '$course'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        // if not exist
        if (!$course) {
            $check = true;
            $error = '<div class="alert alert-danger">Khóa học không tồn tại</div>';
            break;
        }
    }


    // if check = false
    if (!$check) {
        // foreach and insert user
        foreach ($items as $item) {
            // remove space
            $item = trim($item);

            // insert user
            $sql = "INSERT INTO users (username, phone, token, role) VALUES ('$item', '', '', 'user')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            // add user to course
            $sql = "SELECT * FROM users WHERE username = '$item'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($courses_post as $course) {
                $sql = "INSERT INTO user_courses (user_id, course_id) VALUES ('$user[id]', '$course')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
        }

        $error = '<div class="alert alert-success">Thêm thành công</div>';
    }
}


?>


<body>

    <div id="layout-wrapper">

        <?php include "../components/top-menu.php" ?>

        <?php include "../components/navbar.php" ?>

        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">

                    <?php include "../components/title.php" ?>

                    <?= $error ?? "" ?>

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                Import
                            </h4>

                        </div>

                        <div class="card-body">
                            <!-- html create form insert user -->
                            <form id="form" action="<?= $current_url ?>" method="post" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nhập danh sách</label>
                                    <textarea name="items" id="" cols="30" rows="20" class="form-control"><?php
                                                                                                            if (isset($_POST['items'])) {
                                                                                                                echo $_POST['items'];
                                                                                                            }
                                                                                                            ?></textarea>
                                </div>

                                <div class="mb-3">

                                    <label for="course">Chọn khóa học</label>
                                    <div class="row">
                                        <?php printCourses($courses) ?>
                                    </div>

                                </div>


                                <!-- button submit -->
                                <button type="submit" id="button" class="btn btn-primary">Lưu</button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


        </div>

        <?php include "../../components/scripts.php" ?>
        <?php include "../../components/footer.php" ?>

    </div>



</body>