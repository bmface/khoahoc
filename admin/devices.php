<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Quản lý Thiết bị";

include "../components/header.php";

// get all users, count course of user and pagination
$limit = 20;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$offset = ($page - 1) * $limit;

// check if exist search 
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT d.*, u.username FROM devices d LEFT JOIN users u ON d.user_id = u.id WHERE u.username LIKE '%$search%' ORDER BY d.id DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // select devices with forgin key user_id
    $sql = "SELECT d.*, u.username FROM devices d LEFT JOIN users u ON d.user_id = u.id ORDER BY d.id DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// function gen html table, devices has username of user, angentuser, token
function printDevices($devices)
{
    global $domain;
    foreach ($devices as $device) {
        echo '<tr>';
        echo '<td class="fw-medium">' . $device['id'] . '</td>';
        echo '<td>' . $device['username'] . '</td>';
        echo '<td>' . $device['useragent'] . '</td>';
        echo '<td>' . $device['token'] . '</td>';
        echo '<td>' . $device['created_at'] . '</td>';

        echo '<td>';
        echo '<a href="' . $domain . '/admin/devices/delete.php?id=' . $device['id'] . '" class="btn btn-danger">Xóa</a>';
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
                                Danh sách thiết bị
                            </h4>

                        </div>

                        <div class="card-body">

                            <!-- input and button for search -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <form action="" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm theo tên người dùng" aria-describedby="button-addon2" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                            <button class="btn btn-primary" type="submit" id="button-addon2">Tìm
                                                kiếm</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="table-responsive">



                                    <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Useragent</th>
                                                <th>Token</th>
                                                <th>Thời gian</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php printDevices($devices) ?>
                                        </tbody>
                                    </table>

                                    <?php

                                    // paginate with query search
                                    if (isset($_GET['search'])) {
                                        $search = $_GET['search'];
                                        $sql = "SELECT COUNT(*) FROM devices d LEFT JOIN users u ON d.user_id = u.id WHERE u.username LIKE '%$search%'";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $total = $stmt->fetchColumn();
                                    } else {
                                        // paginate without query search
                                        $sql = "SELECT COUNT(*) FROM devices";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $total = $stmt->fetchColumn();
                                    }
                                    $totalPage = ceil($total / $limit);

                                    ?>

                                    <div class="text-center mt-5">
                                        <?php
                                        // render html pagination
                                        $totalPage = ceil($total / $limit);
                                        if ($totalPage > 1) {

                                            if (isset($_GET['search'])) {
                                                $search = $_GET['search'];
                                                if ($page > 1) {
                                                    echo '<a href="?page=' . ($page - 1) . '&search=' . $search . '" class="btn btn-primary">Trang trước</a>';
                                                }
                                                for ($i = 1; $i <= $totalPage; $i++) {
                                                    if ($i == $page) {
                                                        echo '<a href="?page=' . $i . '&search=' . $search . '" class="btn btn-primary">' . $i . '</a>';
                                                    } else {
                                                        echo '<a href="?page=' . $i . '&search=' . $search . '" class="btn btn-primary">' . $i . '</a>';
                                                    }
                                                }
                                                if ($page < $totalPage) {
                                                    echo '<a href="?page=' . ($page + 1) . '&search=' . $search . '" class="btn btn-primary">Trang sau</a>';
                                                }
                                            } else {
                                                if ($page > 1) {
                                                    echo '<a href="?page=' . ($page - 1) . '" class="btn btn-primary">Trang trước</a>';
                                                }
                                                for ($i = 1; $i <= $totalPage; $i++) {
                                                    if ($i == $page) {
                                                        echo '<a href="?page=' . $i . '" class="btn btn-primary">' . $i . '</a>';
                                                    } else {
                                                        echo '<a href="?page=' . $i . '" class="btn btn-primary">' . $i . '</a>';
                                                    }
                                                }
                                                if ($page < $totalPage) {
                                                    echo '<a href="?page=' . ($page + 1) . '" class="btn btn-primary">Trang sau</a>';
                                                }
                                            }
                                        }


                                        ?>
                                    </div>




                                </div>
                            </div>

                        </div>

                    </div>

                </div>


            </div>


            <?php include "../components/scripts.php" ?>
            <?php include "../components/footer.php" ?>

        </div>



</body>