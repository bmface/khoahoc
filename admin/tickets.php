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

// get all tickets of user and last reply order by id desc
$sql = "SELECT tickets.*, users.username as username,
 ticket_replies.message as last_reply_content,
  ticket_replies.created_at as last_reply_created_at 
  FROM 
  tickets LEFT JOIN users ON tickets.user_id = users.id 
  LEFT JOIN ticket_replies ON tickets.id = ticket_replies.ticket_id 
  GROUP BY tickets.id ORDER BY tickets.id DESC";

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
            <a href="ticket.php?id=' . $ticket['id'] . '" class="text-body fw-bold">' . $ticket['last_reply_created_at'] . '</a>
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