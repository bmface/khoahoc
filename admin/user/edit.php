<?php

include "../../config.php";

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Sửa tài khoản";

include "../../components/header.php";

// get user id
$user_id = $_GET['id'];

//check user id not empty
if (empty($user_id)) {
    header('location: ' . $base_url . '/admin/user/list.php');
}

// get user by id
$sql = "SELECT * FROM users WHERE id = $user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user not exist
if (!$user) {
    header('location: ' . $base_url . '/admin/user/list.php');
}

// get user's courses by id user
$sql = "SELECT * FROM user_courses WHERE user_id = $user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$user_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// check user is admin
if ($user['role'] == 'admin') {
    $courses = [];
} else {
    //get all courses
    $sql = "SELECT * FROM courses";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// foreach courses and print html to use click select
function printCourses($courses)
{
    global $user_courses;
    $user_courses = array_column($user_courses, 'course_id');

    foreach ($courses as $course) {

        echo '<div class="form-check mb-3 col">';

        // check if course is user's course 
        if (in_array($course['id'], $user_courses)) {
            echo '<input class="form-check-input" type="checkbox" id="formCheck' . $course["id"] . '" value="' . $course["id"] . '" name="courses[]" checked>';
        } else {
            echo '<input class="form-check-input" type="checkbox" id="formCheck' . $course["id"] . '" value="' . $course["id"] . '" name="courses[]" >';
        }
        echo '<label class="form-check-label" for="formCheck' . $course["id"] . '">';
        echo $course['name'];
        echo '</label>';
        echo '</div>';
    }
}

// table user: username, phone, token, code, role

$error = '';

// check if method post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {




    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $block = $_POST['block'] ? 1 : 0;

    // check if username is empty
    if (empty($username)) {
        $error = '<div class="alert alert-danger">Vui lòng nhập tên tài khoản</div>';
    } else {
        // check if username exist
        $sql = "SELECT * FROM users WHERE username = '$username' AND id != '$user_id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $usercheck = $stmt->fetch(PDO::FETCH_ASSOC);




        // update user 
        $sql = "UPDATE users SET  phone = '$phone', is_locked = '$block' WHERE id = '$user_id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // check if password then update password
        if (!empty($password)) {
            $password = md5($password);
            $sql = "UPDATE users SET password = '$password' WHERE id = '$user_id'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

        // get user_id
        $user_id = $user["id"];
        // sync user_courses table
        // delete user_courses
        $sql = "DELETE FROM user_courses WHERE user_id = '$user_id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // insert user_courses
        $courses = $_POST['courses'];


        foreach ($courses as $course_id) {

            // check if course_id is empty
            if (empty($course_id)) {
                continue;
            }



            // check course_id exist
            $sql = "SELECT * FROM courses WHERE id = '$course_id'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$course) {
                continue;
            }

            $sql = "INSERT INTO user_courses (user_id, course_id) VALUES ('$user_id', '$course_id')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

        // redirect to user list
        header('location: ' . $domain . '/admin/user.php');
    }
}
// check user is admin
if ($user['role'] == 'admin') {
    $courses = [];
} else {
    //get all courses
    $sql = "SELECT * FROM courses";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                Cập nhập
                            </h4>

                        </div>

                        <div class="card-body">
                            <!-- html create form insert user -->
                            <form id="form" action="<?= $current_url ?>" method="post" enctype="multipart/form-data">

                                <!-- div inputs -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên tài khoản</label>
                                            <input type="text" readonly class="form-control" id="username" name="username" value="<?= $user["username"] ?>" placeholder="Nhập tên tài khoản">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="<?= $user["phone"] ?>" placeholder="Nhập số điện thoại">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Mật khẩu</label>
                                            <input type="password" class="form-control" id="phone" name="password" value="" placeholder="Nhập mật khẩu">
                                        </div>
                                    </div>

                                    <div class=" col-6">

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Khóa tài khoản</label>
                                            <div class="form-check form-switch">

                                                <!-- input checkbox -->

                                                <input class="form-check-input" name="block" type="checkbox" value="1" <?= $user["is_locked"] ? "checked" : "" ?> id="checkbox1">
                                                <label class="form-check-label" for="checkbox1">Chọn nếu
                                                    muốn khóa</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>




                                <!-- use check box courses -->
                                <div class="col-md-12">
                                    <div class="mb-3">

                                        <label for="role" class="form-label">Khóa học</label>

                                        <div class="container">
                                            <div class="row">
                                                <?php printCourses($courses) ?></div>
                                        </div>
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