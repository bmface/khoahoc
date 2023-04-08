<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}

updateTimeDevice();
$title = "Ticket";


include '../components/header.php';
// get current user
$sql = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// get id from url
$id = $_GET['id'];

// get only ticket by id and user_id
$sql = "SELECT * FROM tickets WHERE id = " . $id;;
$stmt = $conn->prepare($sql);
$stmt->execute();
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

// check if ticket not exist
if (!$ticket) {
    header('location: tickets.php');
}

// check close ticket
if (isset($_GET['close'])) {
    // update status ticket
    $sql = "UPDATE tickets SET status = 'Support đã đóng' WHERE id = " . $id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // redirect to tickets
    header('location: tickets.php');
}

// check if exist method Post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check if ticket status contains close
    if (strpos($ticket['status'], 'Support đã đóng') !== false) {
        // redirect to tickets
    }


    // get message from form
    $message = $_POST['message'];

    // check if message is empty
    if (empty($message)) {
        $error = "Vui lòng nhập nội dung";
    } else {
        // sql injection
        $message = htmlspecialchars($message);

        // insert reply to database
        $sql = "INSERT INTO ticket_replies (ticket_id, user_id, message) VALUES (" . $id . ", " . $_SESSION['user_id'] . ", '" . $message . "')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // update status ticket
        $sql = "UPDATE tickets SET status = 'Support trả lời' WHERE id = " . $id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // reload page
        header('location: ' . $_SERVER['REQUEST_URI']);
    }
}



// get all replies of ticket
$sql = "SELECT ticket_replies.*, users.username as username FROM ticket_replies LEFT JOIN users ON ticket_replies.user_id = users.id WHERE ticket_replies.ticket_id = " . $id . " ORDER BY ticket_replies.id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
function removeHtmlTags($string)
{
    return strip_tags($string);
}
// fucntion render replies
function renderReplies()
{

    // remove html tags in message 


    global $replies;
    foreach ($replies as $reply) {
        echo '<div class="flex-grow-1 ms-3">';
        echo '<h5 class="fs-13">';
        echo  $reply['username'];
        echo '<small class="text-muted ml-2">' . $reply["created_at"] . '</small>';
        echo '</h5>';
        echo '<p class="text-muted">';
        echo removeHtmlTags($reply["message"]);
        echo '</p>';
        echo '</div>';
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
                            # <?php echo $ticket['id'] ?> - <?php echo strip_tags($ticket['subject']) ?>
                        </h3>

                        <div class="card">



                            <div class="card-header align-items-center d-flex justify-content-between ">
                                <!-- span badget status, timecreate -->
                                <div class="left">

                                    <?php

                                    // check if status is contains close
                                    if (strpos($ticket['status'], 'đóng') !== false) {
                                        echo '<span class="badge bg-soft-danger text-danger p-2">';
                                        echo $ticket['status'];
                                        echo '</span>';
                                    } else {
                                        echo '<span class="badge bg-soft-success text-success p-2">';
                                        echo $ticket['status'];
                                        echo '</span>';
                                    }

                                    ?>



                                    <!-- badge outline created_at -->
                                    <span class=" p-2 m-2">
                                        <!-- created_at -->
                                        <?php echo $ticket['created_at'] ?>
                                    </span>
                                </div>

                                <div class="right">
                                    <?php
                                    // check if status is not contains close
                                    if (!strpos($ticket['status'], 'đóng')) {
                                        echo '<a href="?id=' . $ticket['id'] . '&close=true" class="btn btn-danger">Đóng</a>';
                                    }
                                    ?>
                                    <a href="tickets.php" class="btn btn-primary">Quay lại</a>
                                </div>



                            </div>

                            <div class="card-body p-4">
                                <h6 class="fw-semibold text-uppercase mb-3">
                                    Nôi dung
                                </h6>
                                <p class="text-muted">
                                    <?php echo strip_tags($ticket['message'])    ?>
                                </p>

                            </div>
                            <hr />

                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Trả lời</h5>
                                <?php renderReplies() ?>
                            </div>

                            <?php
                            // check if status is not contains close
                            if (!strpos($ticket['status'], 'đóng')) {

                            ?>

                                <form action="" method="POST" class="mt-3">
                                    <div class="row g-3">
                                        <div class="col-lg-12">
                                            <label for="exampleFormControlTextarea1" class="form-label">Trả lời</label>
                                            <textarea class="form-control bg-light border-light" name="message" id="exampleFormControlTextarea1" rows="3" placeholder="Nhập nội dung"></textarea>
                                        </div>
                                        <div class="col-lg-12 text-end">
                                            <button class="btn btn-success">Gửi</button>
                                        </div>
                                    </div>
                                </form>
                            <?php
                            }
                            ?>
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