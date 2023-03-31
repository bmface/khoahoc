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
$title = "Chỉnh sửa khóa học";

include "../../components/header.php";

//get id from url
$id = $_GET['id'];
//check not empty id
if (empty($id)) {
    header('location: ' . $domain . '/admin/lession.php');
}

//get lession with course name by id
$sql = "SELECT lessions.*, courses.name as course_name FROM lessions INNER JOIN courses ON lessions.course_id = courses.id WHERE lessions.id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$lession = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lession) {
    header('location: ' . $domain . '/admin/lession.php');
}

//get all courses
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

//create html select courses
function printCourses($courses)
{
    global $lession;
    foreach ($courses as $course) {
        $selected = '';
        if ($course['id'] == $lession['course_id']) {
            $selected = 'selected';
        }
        echo '<option value="' . $course['id'] . '" ' . $selected . '>' . $course['name'] . '</option>';
    }
}

//check if form submit
if (isset($_POST['name']) && isset($_POST['course']) && isset($_POST['content']) && isset($_POST['position'])) {
    $name = $_POST['name'];
    $course_id = $_POST['course'];
    $content = $_POST['content'];
    $position = $_POST['position'];



    //check not empty name and course_id
    if (empty($name) || empty($course_id)) {
        echo '<script>alert("Vui lòng nhập đầy đủ thông tin")</script>';
    } else {
        $isError = false;

        // check if upload video and not null


        //check if upload video 
        if (isset($_FILES['video']) && !empty($_FILES['video']['name'])) {
            $video = $_FILES['video'];
            $video_name = $video['name'];
            $video_tmp_name = $video['tmp_name'];
            $video_size = $video['size'];
            $video_error = $video['error'];

            //check if upload video
            if ($video_error == 0) {
                //check video size
                if ($video_size < 1000000000) {
                    //check video type
                    $video_ext = explode('.', $video_name);
                    $video_ext = strtolower(end($video_ext));
                    $allowed = array('mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv');
                    if (in_array($video_ext, $allowed)) {
                        //upload video
                        $video_name_new = uniqid('', true) . '.' . $video_ext;
                        $video_destination = $root_dir . '/videos/' . $video_name_new;
                        move_uploaded_file($video_tmp_name, $video_destination);
                        //update lession

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

                            $video_name_new = $result["@metadata"]["effectiveUri"];
                        } catch (Exception $e) {
                            $isError = true;
                            $error = "Error s3: " . $e->getMessage();
                        }
                    } else {
                        echo '<script>alert("Vui lòng chọn đúng định dạng video")</script>';
                        $isError = true;
                    }
                } else {
                    echo '<script>alert("Vùng lưu trữ video không đủ")</script>';
                    $isError = true;
                }
            } else {
                echo '<script>alert("Có lỗi xảy ra khi tải video")</script>';
                $isError = true;
            }
        }

        if (!$isError) {

            // check if video_name_new is null
            if (empty($video_name_new)) {
                $video_name_new = $lession['video'];
            }

            $sql = "UPDATE lessions SET name = :name, course_id = :course_id, video = :video, content = :content, position = :position WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':video', $video_name_new);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':position', $position);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header('location: ' . $domain . '/admin/lession.php');
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
                                Chỉnh sửa
                            </h4>

                        </div>

                        <div class="card-body">

                            <!-- Form edit lession -->
                            <form id="form" action="<?= $current_url ?>" method="post" enctype="multipart/form-data">

                                <!-- div input name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên bài học</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?= $lession["name"] ?>" required>
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
                                        value="<?= $lession["position"]  ?>" id="">

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
                                        <?= $lession["content"] ?>
                                    </div>

                                    <input type="hidden" id="content" name="content">

                                </div>

                                <!-- button submit -->
                                <button type="button" id="button" class="btn btn-primary">Sửa</button>


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