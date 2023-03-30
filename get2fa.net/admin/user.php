<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Quản lý tài khoản";

include "../components/header.php";

// get all users, count course of user and pagination
$limit = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$offset = ($page - 1) * $limit;

// get all users, cousers user of with pagination and search
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT u.*, count(c.id) as course_count FROM users u LEFT JOIN user_courses c ON u.id = c.user_id WHERE u.username LIKE '%$search%' GROUP BY u.id ORDER BY u.id DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT u.*, count(c.id) as course_count FROM users u LEFT JOIN user_courses c ON u.id = c.user_id GROUP BY u.id ORDER BY u.id DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// function gen html table
function printUsers($users)
{
    global $domain;
    foreach ($users as $user) {
        echo '<tr>';
        echo '<td class="fw-medium">' . $user['id'] . '</td>';
        echo '<td>' . $user['username'] . '</td>';
        echo '<td>' . $user['phone'] . '</td>';
        echo '<td>' . $user['course_count'] . '</td>';
        echo '<td>' . ($user['is_locked'] ? 'Khóa' : 'Không') . '</td>';
        echo '<td>';
        echo '<a href="' . $domain . '/admin/user/edit.php?id=' . $user['id'] . '" class="btn btn-primary">Sửa</a>';
        echo '<button type="button" data-id="' . $user["id"] . '" class="btn btn-danger mx-2 btnDelete">Xóa</button>';
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
                                Danh sách tài khoản
                            </h4>
                            <div class="flex-shrink-0">
                                <a href="../admin/user/import.php" class="btn btn-success me-2">Nhập</a>
                                <a href="../admin/user/add.php" class="btn btn-success">Thêm mới</a>
                            </div>
                        </div>

                        <div class="card-body">

                            <!-- input and button form search -->
                            <form action="" method="get" class="d-flex mb-3">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>

                                            <th scope="col">ID</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Khóa học</th>
                                            <th scope="col">Trạng thái</th>
                                            <th scope="col">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php printUsers($users) ?>
                                    </tbody>
                                </table>




                                <?php

                                // panigate with search
                                if (isset($_GET['search'])) {
                                    $search = $_GET['search'];
                                    $sql = "SELECT count(*) as total FROM users WHERE username LIKE '%$search%'";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                } else {
                                    $sql = "SELECT count(*) as total FROM users";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                }

                                $totalPage = ceil($total / $limit);

                                ?>

                                <?php

                                //foreach and create html pagination 
                                if ($totalPage > 1) {
                                    echo '<ul class="pagination pagination-rounded justify-content-center mt-4">';
                                    for ($i = 1; $i <= $totalPage; $i++) {
                                        $active = $i == $page ? 'active' : '';

                                        // echo li with page and search
                                        if (isset($_GET['search'])) {
                                            $search = $_GET['search'];
                                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '&search=' . $search . '">' . $i . '</a></li>';
                                        } else {
                                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                        }
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
                            axios.get(`/admin/user/delete.php?id=${id}`)
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