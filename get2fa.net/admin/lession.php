<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Quản lý bài học";

include "../components/header.php";



// get all lessions with pagination
$limit = 20;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$offset = ($page - 1) * $limit;

//get all courses
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// check if exist course_id then filter lessions by course_id
$course_id = 0;
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
}

// if course id = 0 then set course_id is first course
if ($course_id == 0 && count($courses) > 0) {
    $course_id = $courses[0]['id'];
}

// get all lessions with course's name filter by course_id order by id desc
$sql = "SELECT l.*, c.name AS course_name FROM lessions l LEFT JOIN courses c ON l.course_id = c.id WHERE l.course_id = $course_id ORDER BY l.position ASC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// create html table lessions
function printLessions($lessions)
{
    global $domain;
    foreach ($lessions as $lession) {
        echo '<tr>';
        echo '<td class="fw-medium">' . $lession['id'] . '</td>';
        echo '<td>' . $lession['name'] . '</td>';

        // get first 100 characters of content
        $content = $lession['content'];
        if (strlen($content) > 40) {
            $content = substr($content, 0, 40) . '...';
        }

        echo '<td>' . $content . '</td>';

        // add video column
        if ($lession['video']) {
            echo '<td>Có</td>';
        } else {
            echo '<td>Không</td>';
        }

        echo '<td>' . $lession['course_name'] . '</td>';
        echo '<td>' . $lession['position'] . '</td>';
        echo '<td>';
        echo '<a href="' . $domain . '/admin/lession/edit.php?id=' . $lession['id'] . '" class="btn btn-primary ">Sửa</a>';
        echo '<button data-id="' . $lession["id"] . '" class="btn btn-danger mx-2 btnDelete">Xóa</button>';
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
                                Danh sách bài học
                            </h4>
                            <div class="flex-shrink-0">
                                <a href="../admin/lession/add.php" class="btn btn-success">Thêm mới</a>
                            </div>
                        </div>

                        <div class="card-body">


                            <ul class="nav nav-tabs">


                                <?php

                                //foreach courses and creaet li 
                                foreach ($courses as $course) {

                                    // check if course_id is equal to course id then add active class
                                    if ($course_id == $course['id']) {
                                        echo '<li class="nav-item">';
                                        echo '<a class="nav-link active" href="' . $domain . '/admin/lession.php?course_id=' . $course['id'] . '">' . $course['name'] . '</a>';
                                        echo '</li>';
                                        continue;
                                    }

                                    echo '<li class="nav-item">';
                                    echo '<a class="nav-link" href="' . $domain . '/admin/lession.php?course_id=' . $course['id'] . '">' . $course['name'] . '</a>';
                                    echo '</li>';
                                }

                                ?>



                            </ul>




                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Tên</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Video</th>
                                            <th scope="col">Khóa học</th>
                                            <th scope="col">Vị trí</th>
                                            <th scope="col">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php printLessions($lessions) ?>
                                    </tbody>
                                </table>

                                <!-- create html pagianter -->
                                <?php

                                // $sql select count lesstion where course_id = $course_id
                                $sql = "SELECT count(*) as total FROM lessions WHERE course_id = $course_id order by position desc";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                $totalPage = ceil($total / $limit);
                                ?>

                                <nav aria-label="...">
                                    <ul class="pagination pagination-lg text-center d-flex justify-content-center">



                                        <?php

                                        // foreach total page and create html paginate, get 3 page before and after current page and add first and last page
                                        $start = $page - 3;
                                        $end = $page + 3;

                                        if ($start < 1) {
                                            $start = 1;
                                        }

                                        if ($end > $totalPage) {
                                            $end = $totalPage;
                                        }

                                        if ($start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="' . $domain . '/admin/lession.php?course_id=' . $course_id . '&page=1">First</a></li>';
                                        }

                                        for ($i = $start; $i <= $end; $i++) {

                                            // check if page is equal to $i then add active class
                                            if ($page == $i) {
                                                echo '<li class="page-item active" aria-current="page">';
                                                echo '<span class="page-link">';
                                                echo $i;
                                                echo '<span class="sr-only">(current)</span>';
                                                echo '</span>';
                                                echo '</li>';
                                                continue;
                                            }

                                            echo '<li class="page-item"><a class="page-link" href="' . $domain . '/admin/lession.php?course_id=' . $course_id . '&page=' . $i . '">' . $i . '</a></li>';
                                        }

                                        if ($end < $totalPage) {
                                            echo '<li class="page-item"><a class="page-link" href="' . $domain . '/admin/lession.php?course_id=' . $course_id . '&page=' . $totalPage . '">Last</a></li>';
                                        }





                                        ?>


                                    </ul>
                                </nav>


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
                            axios.get(`/admin/lession/delete.php?id=${id}`)
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