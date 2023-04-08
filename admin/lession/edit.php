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
if (isset($_POST['name']) && isset($_POST['course']) && isset($_POST['video']) && isset($_POST['content']) && isset($_POST['position'])) {
    $name = $_POST['name'];
    $course_id = $_POST['course'];
    $content = $_POST['content'];
    $position = $_POST['position'];
    $video = $_POST['video'];


    // sql injection
    $name = htmlspecialchars($name);
    $course_id = htmlspecialchars($course_id);
    $content = htmlspecialchars($content);
    $position = htmlspecialchars($position);
    $video = htmlspecialchars($video);



    //check if not empty name, course, content, video
    if (empty($name) || empty($course_id) || empty($content) || empty($position) || empty($video)) {
        $error = '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin</div>';
    } else {
        $sql = "UPDATE lessions SET name = :name, course_id = :course_id, video = :video, content = :content, position = :position WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':video', $video);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('location: ' . $domain . '/admin/lession.php');
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

                    <?php echo isset($error) ? $error : ""  ?>

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
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $lession["name"] ?>" required>
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
                                    <input type="number" name="position" placeholder="Vị trí" class="form-control" value="<?= $lession["position"]  ?>" id="">

                                </div>


                                <!-- div up load video -->
                                <div class="mb-3">
                                    <label for="video" class="form-label">Video</label>
                                    <input type="text" class="form-control" id="video" name="video" value="<?= $lession["video"] ?>" required>
                                </div>

                                <!-- div input content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Nội dung</label>

                                    <div class="snow-editor" style="height: 300px">
                                        <?= html_entity_decode($lession["content"]) ?>
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