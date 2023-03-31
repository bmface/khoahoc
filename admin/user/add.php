<?php

include "../../config.php";

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Thêm tài khoản";

include "../../components/header.php";


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

// table user: username, phone, token, code, role

// check if method post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $token = $_POST['token'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // sql injection
    $username = htmlspecialchars($username);
    $phone = htmlspecialchars($phone);
    $token = htmlspecialchars($token);
    $role = htmlspecialchars($role);
    $password = htmlspecialchars($password);

    // md5 password


    // check if username is empty
    if (empty($username)) {
        echo '<div class="alert alert-danger">Vui lòng nhập tên tài khoản</div>';
    } else {
        // check if username exist
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo '<div class="alert alert-danger">Tên tài khoản đã tồn tại</div>';
        } else {
            // insert user
            $sql = "INSERT INTO users (username, phone, token, role, is_locked) VALUES ('$username', '$phone', '$token',  '$role',0)";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            // if role is admin then insert password
            if ($role == 'admin') {
                $user_id = $conn->lastInsertId();

                $password = md5($password);

                // update password for user
                $sql = "UPDATE users SET password = '$password' WHERE id = '$user_id'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }



            // get user_id
            $user_id = $conn->lastInsertId();

            // insert user_courses
            $courses = $_POST['courses'];
            foreach ($courses as $course_id) {
                $sql = "INSERT INTO user_courses (user_id, course_id) VALUES ('$user_id', '$course_id')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }

            // redirect to user list
            header('location: ' . $domain . '/admin/user.php');
        }
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

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                Thêm
                            </h4>

                        </div>

                        <div class="card-body">
                            <!-- html create form insert user -->
                            <form id="form" action="<?= $domain ?>/admin/user/add.php" method="post" enctype="multipart/form-data">

                                <!-- div inputs -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên tài khoản</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên tài khoản">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="token" class="form-label">Token</label>
                                            <input type="text" class="form-control" id="token" name="token" placeholder="Nhập token">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="Password" class="form-label">Password</label>
                                            <input type="text" class="form-control" id="Password" name="password" placeholder="Nhập password">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Vai trò</label>
                                            <select class="form-select" id="role" name="role">
                                                <option value="0">Người dùng</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- use check box courses -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Khóa học</label>

                                            <div class="container">

                                                <div class="row">

                                                    <?php printCourses($courses) ?>

                                                </div>

                                            </div>

                                        </div>
                                    </div>



                                    <!-- button submit -->
                                    <button type="submit" id="button" class="btn btn-primary">Thêm</button>


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