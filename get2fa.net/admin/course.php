<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Quản lý khóa học";

include "../components/header.php";

// get all courses with users and pagination
$limit = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$offset = ($page - 1) * $limit;

//i have 2 tables: courses and user_courses
//i want to get all courses with count users in each course

$sql = "SELECT c.*, COUNT(uc.user_id) AS users_count FROM courses c LEFT JOIN user_courses uc ON c.id = uc.course_id GROUP BY c.id LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print courses
function printCourses($courses)
{
    global $domain;
    foreach ($courses as $course) {
        echo '<tr>';
        echo '<td class="fw-medium">' . $course['id'] . '</td>';
        if ($course['thumb']) {
            echo '<td><img src="' . $domain . '/public/images/' . $course['thumb'] . '" width="100" /></td>';
        } else {
            echo '<td><img src="/images/no-image.png" width="100" /></td>';
        }
        echo '<td>' . $course['name'] . '</td>';
        echo '<td>' . $course['description'] . '</td>';
        echo '<td>' . $course['users_count'] . '</td>';
        echo '<td>';
        echo '<a href="/admin/course/edit.php?id=' . $course['id'] . '" class="btn btn-primary ">Sửa</a>';
        echo '<button type="button" data-id="' . $course['id'] . '" class="btn btn-danger mx-2 btnDelete">Xóa</button>';
        echo '</td>';
        echo '</tr>';
    }
}


?>

<body>

    <div id="layout-wrapper">

        <?php include "./components/top-menu.php" ?>

        <?php include "./components/navbar.php" ?>

        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">
                    <?php include "./components/title.php" ?>

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                Danh sách khóa học
                            </h4>
                            <div class="flex-shrink-0">
                                <a href="../admin/course/add.php" class="btn btn-success">Thêm mới</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Thumb</th>
                                            <th scope="col">Tên</th>
                                            <th scope="col">Mô tả ngắn</th>
                                            <th scope="col">Tổng user</th>
                                            <th scope="col">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php printCourses($courses) ?>
                                    </tbody>
                                </table>
                                <!-- create html pagianter -->
                                <?php
                                $sql = "SELECT count(*) as total FROM courses";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                $totalPage = ceil($total / $limit);
                                ?>

                                <?php

                                //foreach and create html pagination 
                                if ($totalPage > 1) {
                                    echo '<ul class="pagination pagination-rounded justify-content-center mt-4">';
                                    for ($i = 1; $i <= $totalPage; $i++) {
                                        $active = $i == $page ? 'active' : '';
                                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="course.php?page=' . $i . '">' . $i . '</a></li>';
                                    }
                                    echo '</ul>';
                                }

                                ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>


        </div>


        <?php include "../components/scripts.php" ?>

        <script>
            $(document).ready(
                $(".btnDelete").click(function() {

                    //get data-id from button
                    const id = $(this).data("id");
                    console.log(id, "id")

                    Swal.fire({
                        title: 'Bạn có chắc không?',
                        text: "Bạn sẽ không thể hoàn nguyên điều này!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // axios delete
                            axios.get(`/admin/course/delete.php?id=${id}`)
                                .then(res => {
                                    console.log(res, "res")
                                    const {
                                        status,
                                        message
                                    } = res.data;

                                    if (status) {
                                        Swal.fire(
                                            'Đã xóa!',
                                            message,
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        })
                                    } else {
                                        Swal.fire(
                                            'Lỗi!',
                                            message,
                                            'error'
                                        )
                                    }
                                })
                                .catch(err => {
                                    console.log(err);
                                })
                        }
                    })

                })
            )
        </script>

        <?php include "../components/footer.php" ?>

    </div>



</body>