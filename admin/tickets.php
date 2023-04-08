<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}

updateTimeDevice();
$title = "Tickets";


include '../components/header.php';

// get current user
$sql = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// get all tickets of user and last reply order by id desc add paginate
$limit = 20;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$offset = ($page - 1) * $limit;

// get tickets with pagination
$sql = "SELECT t.*, u.username FROM tickets t LEFT JOIN users u ON t.user_id = u.id ORDER BY t.id DESC LIMIT $limit OFFSET $offset";


$stmt = $conn->prepare($sql);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// render tickets
function renderTickets()
{
    global $tickets;
    foreach ($tickets as $ticket) {

        // get status
        $status = $ticket['status'];

        // badge status
        $badge = '';

        // check if status contains close
        if (!strpos($ticket['status'], 'đóng')) {

            $status = '<span class="badge bg-secondary">' . $status . '</span>';
        } else {
            // badge danger
            $status = '<span class="badge bg-danger">' . $status . '</span>';
        }


        echo '<tr>
        <td class="id">
            <a href="ticket.php?id=' . $ticket['id'] . '" class="text-body fw-bold">#' . $ticket['id'] . '</a>
        </td>
        <td class="tasks_name">
            <a href="ticket.php?id=' . $ticket['id'] . '" class="text-body fw-bold">' . $ticket['subject'] . '</a>
        </td>
        <td class="client_name">
            <a href="ticket.php?id=' . $ticket['id'] . '" class="text-body fw-bold">' . $ticket['username'] . '</a>
        </td>
        <td class="assignedto">
           ' .
            $status
            . '
        </td>
        <td class="create_date">
            <a href="ticket.php?id=' . $ticket['id'] . '" class="text-body fw-bold">' . $ticket['created_at'] . '</a>
        </td>
        <td class="due_date">
            <a href="ticket.php?id=' . $ticket['id'] . '" class="text-body fw-bold">' . $ticket['updated_at'] . '</a>
        </td>
        <td class="action">
            <a href="ticket.php?id=' . $ticket['id'] . '" class="btn btn-primary btn-sm">Xem</a>
        </td>
    </tr>';
    }
}



?>

<body class="menu">
    <div id="layout-wrapper">

        <?php include "./components/top-menu.php" ?>

        <?php include "./components/navbar.php" ?>


        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">
                    <div class="row main-study">
                        <h3 class="style-title-home mb-4">
                            Hỗ trợ
                        </h3>

                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">
                                    Danh sách tickets
                                </h4>
                            </div>

                            <div class="card-body">

                                <div class="table-responsive table-card mb-4">
                                    <table class="table align-middle table-nowrap mb-0" id="ticketTable">
                                        <thead>
                                            <tr>

                                                <th class="sort" data-sort="id">ID</th>
                                                <th class="sort" data-sort="tasks_name">Tiêu đề</th>
                                                <th class="sort" data-sort="client_name">Người gửi</th>
                                                <th class="sort" data-sort="assignedto">
                                                    Trạng thái
                                                </th>
                                                <th class="sort" data-sort="create_date">
                                                    Ngày tạo
                                                </th>
                                                <th class="sort" data-sort="due_date">Ngày cập nhập</th>
                                                <th class="sort" data-sort="action"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php renderTickets(); ?>
                                        </tbody>
                                    </table>

                                    <!-- create html pagianter -->
                                    <?php
                                    $sql = "SELECT count(*) as total FROM tickets";
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
                                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="tickets.php?page=' . $i . '">' . $i . '</a></li>';
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
        </div>
    </div>


    </div>

    <?php
    include "../components/scripts.php";
    include "../components/footer.php";
    ?>



</body>

<?php
?>