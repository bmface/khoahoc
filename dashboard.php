<?php
include 'config.php';

checkUserLogged();
checkRememberCookie();

updateTimeDevice();
$title = "Học quảng cáo nâng cao";


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
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
                      <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                      </div>
                      <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="slide-custom slide-1 d-block w-100">
                              <img src="/imgs/ggads.png" class="d-block slide-image" alt="Google Ads">
                              <div class="carousel-caption d-none d-md-block">
                                <h5 class="title-slide">Khóa học Super Target Pro</h5>
                                <p class="description-slide">Đây là khóa học đầy đủ và chi tiết nhất bạn có thể tìm thấy được ở trên Internet!</p>
                             </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="slide-custom slide-2 d-block w-100">
                              <img src="/imgs/ggads.png" class="d-block slide-image" alt="Facebook Ads">
                              <div class="carousel-caption d-none d-md-block">
                                <h5 class="title-slide">Thành Quả của Học Viên</h5>
                                <p class="description-slide">Để đạt được kết quả tốt trong mọi việc ta cần xác định mục tiêu rõ ràng cho việc đó. Học Online cũng không là ngoại lệ.</p>
                              </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="slide-custom slide-3 d-block w-100">
                              <img src="/imgs/ggads.png" class="d-block slide-image" alt="TikTok Ads">
                              <div class="carousel-caption d-none d-md-block">
                                <h5 class="title-slide">Xu hướng bán hàng 2023</h5>
                                <p class="description-slide">Bán hàng chưa bao giờ là dễ dàng. Nhưng sau khi học xong khóa học này, mọi thứ trở nên đơn giản.</p>
                              </div>
                            </div>
                        </div>
                      </div>
                      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <i class="fa-solid fa-chevron-left" style="font-size: 13px;color: #333;background: #fff;padding: 12px 14px;border-radius: 50%;box-shadow: 0 3px 6px rgba(0,0,0,.16);"></i>
                      </button>
                      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <i class="fa-solid fa-chevron-right" style="font-size: 13px;color: #333;background: #fff;padding: 12px 14px;border-radius: 50%;box-shadow: 0 3px 6px rgba(0,0,0,.16);"></i>
                      </button>
                    </div>





                    <div class="row main-study">
                        <h3 class="style-title-home mb-4">
                            Danh sách các khóa học
                        </h3>
                    
                        <?php printCardCourse() ?>
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