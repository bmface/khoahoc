<?php
include 'config.php';

checkUserLogged();
checkRememberCookie();

updateTimeDevice();
$title = "Tickets";


include 'components/header.php';

// get current user
$sql = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// check if exist post
if (isset($_POST['title']) && isset($_POST['content'])) {
    // get post data
    $title = $_POST['title'];
    $content = $_POST['content'];

    // sql injection
    $title = addslashes($title);
    $content = addslashes($content);

    // insert ticket with subject and message and status open   
    $sql = "INSERT INTO tickets (subject,message, status, user_id) VALUES ('$title', '$content','open', " . $_SESSION['user_id'] . ")";
    $stmt = $conn->prepare($sql);
    $stmt->execute();


    // echo  swal fire success
    echo "<script>
        Swal.fire({
            icon: 'success',
                        title: 'Thành công',
                        text: message,
        })
    </script>";


    // redirect to tickets page
    header('location: tickets.php');
}



?>

<body class="menu">
    <div id="layout-wrapper">

        <?php

        include "components/topbar.php";
        include "components/navbar.php";

        ?>


        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">
                    <div class="row main-study">
                        <h3 class="style-title-home mb-4">
                            Mở yêu cầu hỗ trợ
                        </h3>

                        <div class="card">


                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tiêu đề</label>
                                        <input type="text" class="form-control" id="name" name="title" required="">
                                    </div>

                                    <!-- write input content -->
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Nội dung</label>
                                        <textarea class="form-control" name="content" id=" content" rows="3" required=""></textarea>
                                    </div>

                                    <!-- button submit -->
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Gửi</button>
                                    </div>


                                </form>


                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <?php
    include "components/scripts.php";
    include "components/footer.php";
    ?>



</body>

<?php
?>