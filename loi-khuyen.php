<?php
include 'config.php';

checkUserLogged();
checkRememberCookie();

updateTimeDevice();
$title = "Lộ trình học";


include 'components/header.php';

// get current user
$sql = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// get courses of current user with course and count lession of course
$sql = "SELECT c.*, COUNT(l.id) as lession_count FROM user_courses uc INNER JOIN courses c ON uc.course_id = c.id right join lessions l on l.course_id = c.id WHERE uc.user_id = " . $user["id"] . " GROUP BY c.id, c.name;";



$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

function printCardCourse()
{
    global $courses;
    global $domain;
    foreach ($courses as $course) {
        echo '<div class="col-xl-3 col-lg-4 col-md-6 mb-5">';

        //create image src from course
        $src = $domain . "/public/images/" . $course['thumb'];
        // check if course has image
        if ($course['thumb'] == null) {
            $src = $domain . "/assets/images/course/course-1.jpg";
        }

        echo '<div class="bg-hover">';
        echo '<div class="bg-hover-1">';
        echo '<img class="card-img-top" src="' . $src . '" alt="' . $course['name'] . '">';
        echo '</div>';
        echo '<a href="course.php?id=' . $course['id'] . '" class="btn btn-xem-khoa-hoc">Xem khóa học</a>';
        echo '</div>';
        echo '<div class="mt-3">';
        echo '<h4 class="card-title">' . $course['name'] . ' (' . $course["lession_count"] . ' bài)' . '</h4>';
        echo '<p class="card-text mb-2">' . $course['description'] . '</p>';
        echo '<i class="fa-solid fa-users">&ensp; ' . $course['virtual'] . '</i>';
        echo '</div>';
        echo '</div>';
    }
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
                            Lời khuyên
                        </h3>
                        
                     
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