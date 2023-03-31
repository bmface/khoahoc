<?php



include "../../config.php";

require '../../aws/aws-autoloader.php';

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;

$credentials = new Credentials('009cb166bfa11e21c87d', 'EMnDN8HUUZYLQPeUCND2usD3Z6A0ns+Lb8aw3b9C');
// Instantiate the S3 client
$s3 = new S3Client([
    'version' => 'latest',
    'region' => 'pvn',
    'endpoint' => 'https://s3-north.viettelidc.com.vn',
    'credentials' => $credentials,
    'use_path_style_endpoint' => true,
]);


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Thêm khóa học";

include "../../components/header.php";


//get all courses
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// gen html select courses
function printCourses($courses)
{
    foreach ($courses as $course) {
        echo '<option value="' . $course['id'] . '">' . $course['name'] . '</option>';
    }
}

// check if post data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // check if not empty post name, course, content, video
    if (empty($_POST['name']) || empty($_POST['course']) || empty($_POST['content']) || empty($_FILES['video']) || empty($_POST['position'])) {
        $error = '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin</div>';
    }

    $name = $_POST['name'];
    $course_id = $_POST['course'];
    $content = $_POST['content'];
    $position = $_POST['position'];

    // upload video
    $video = $_FILES['video'];
    $video_name = $video['name'];
    $video_tmp_name = $video['tmp_name'];
    $video_size = $video['size'];
    $video_error = $video['error'];
    $video_type = $video['type'];

    // get extension of video
    $video_ext = explode('.', $video_name);
    $video_ext = strtolower(end($video_ext));

    // allow extension
    $allowed = ['mp4', 'avi', 'mov', 'wmv'];

    //check course_id in $courses
    $course_exist = false;
    foreach ($courses as $course) {
        if ($course['id'] == $course_id) {
            $course_exist = true;
        }
    }

    $error = "";




    // check if content is empty
    if (empty($content)) {
        $error = '<div class="alert alert-danger">Vui lòng nhập nội dung bài học</div>';
    } else if (!$course_exist) {
        $error = '<div class="alert alert-danger">Khóa học không tồn tại</div>';
    } else {
        // check if video is valid
        if (in_array($video_ext, $allowed)) {
            if ($video_error === 0) {
                if ($video_size < 1000000000) {
                    // create new name for video
                    $video_name_new = uniqid('', true) . '.' . $video_ext;
                    $video_destination = $root_dir . '/videos/' . $video_name_new;

                    // upload video
                    move_uploaded_file($video_tmp_name, $video_destination);
                    try {
                        // Upload a file to Amazon S3
                        $result = $s3->putObject([
                            'Bucket' => 'video',
                            'Key' => $video_name_new,
                            'Body' => fopen('../../videos/' . $video_name_new, 'r'),
                            'ACL'    => 'public-read',
                        ]);

                        //unlink
                        unlink($video_destination);

                        // insert lession to database
                        $sql = "INSERT INTO lessions (name, course_id, video, content,position) VALUES (?, ?, ?, ?,?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$name, $course_id,  $result["@metadata"]["effectiveUri"], $content, $position]);

                        // redirect to lession page
                        // header('location: ' . $domain . '/admin/lession.php');
                    } catch (Exception $e) {
                        $error = "Error s3: " . $e->getMessage();
                    }
                } else {
                    $error = '<div class="alert alert-danger">Tệp quá lớn</div>';
                }
            } else {
                $error = '<div class="alert alert-danger">Có một lỗi khi tải lên tệp của bạn</div>';
            }
        } else {
            $error = '<div class="alert alert-danger">Bạn không thể tải lên các tệp thuộc loại này</div>';
        }
    }
}


?>


<body>

    <div id="layout-wrapper">

        <?php include "../components/top-menu.php" ?>

        <?php include "../components/navbar.php" ?>

        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">
                    <?php include "../components/title.php" ?>

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                Thêm
                            </h4>

                        </div>

                        <div class="card-body">
                            <form id="form" action="<?= $domain ?>/admin/lession/add.php" method="post"
                                enctype="multipart/form-data">

                                <!-- div input name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên bài học</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <!-- div choose course -->
                                <div class="mb-3">
                                    <label for="course" class="form-label">Khóa học</label>
                                    <select class="form-select" id="course" name="course" required>
                                        <option value="">Chọn khóa học</option>
                                        <?php printCourses($courses) ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="course" class="form-label">Vị trí</label>
                                    <input type="number" name="position" placeholder="Vị trí" class="form-control"
                                        name="" id="">

                                </div>

                                <!-- div up load video -->
                                <div class="mb-3">
                                    <label for="video" class="form-label">Video</label>
                                    <input class="form-control" type="file" id="video" name="video" required>
                                </div>

                                <!-- div input content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Nội dung</label>

                                    <div class="snow-editor" style="height: 300px">

                                    </div>

                                    <input type="hidden" id="content" name="content">



                                </div>

                                <!-- button submit -->
                                <button type="button" id="button" class="btn btn-primary">Thêm</button>


                            </form>
                        </div>

                    </div>

                </div>

            </div>


        </div>

        <?php include "../../components/scripts.php" ?>

        <?php

        //include file javascript  with echo
        echo '<script src="' . $domain . '/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>';
        echo '<script src="' . $domain . '/assets/libs/quill/quill.min.js"></script>';
        echo '<script src="' . $domain . '/assets/js/pages/form-editor.init.js"></script>';
        ?>

        <script>
        // add content field to form when submit
        const form = document.querySelector('#form');
        const content = document.querySelector('#content');
        const editor = document.querySelector('.snow-editor');



        // add event click to button
        const button = document.querySelector('#button');
        button.addEventListener('click', function() {
            const html = editor.children[0].innerHTML;
            console.log(html, "html")
            content.value = html;
            form.submit();
        })
        </script>




        <?php include "../../components/footer.php" ?>

    </div>



</body>