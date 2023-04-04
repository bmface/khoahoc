<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "OTP";

include "../components/header.php";

// get all users, count course of user and pagination
$limit = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$offset = ($page - 1) * $limit;

// get all otp with user and pagination
$sql = "SELECT o.*, u.username FROM otps o
        LEFT JOIN users u ON u.id = o.user_id
        ORDER BY o.id DESC
        LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute();
$otps = $stmt->fetchAll(PDO::FETCH_ASSOC);

// gen html table with column username, otp, time
function printOtps($otps)
{
    foreach ($otps as $otp) {
        echo '<tr>';
        echo '<td class="fw-medium">' . $otp['username'] . '</td>';
        echo '<td>' . $otp['otp'] . '</td>';
        echo '<td>' . $otp['created_at'] . '</td>';
        echo '<td>' . ($otp['success'] ? "Thành công" : "Không") . '</td>';
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
                                Danh sách OTP
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>

                                            <th scope="col">Username</th>
                                            <th scope="col">OTP</th>
                                            <th scope="col">Thời gian</th>
                                            <th scope="col">Thành công</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php printOtps($otps) ?>
                                    </tbody>
                                </table>

                                <!-- create html pagianter -->
                                <?php
                                $sql = "SELECT count(*) as total FROM otps order by id desc";
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
                                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="otp.php?page=' . $i . '">' . $i . '</a></li>';
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
        <?php include "../components/footer.php" ?>

    </div>



</body>