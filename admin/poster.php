<?php

include "../config.php";


//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}
$title = "Poster";

include "../components/header.php";

//check if exist post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //get data from post
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];
    //check if image is not empty
    if ($image['size'] > 0) {
        //upload image
        $image_name = time() . '-' . $image['name'];
        move_uploaded_file($image['tmp_name'], $root_dir .  "/public/images/" . $image_name);
    } else {
        $image_name = '';
    }

    //check data not empty
    if (empty($name) || empty($description)) {
        echo '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin</div>';
    } else {
        //insert course
        $sql = "INSERT INTO courses (name, description, thumb, virtual) VALUES (:name, :description, :thumb)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':thumb', $image_name);
        $stmt->execute();
        //redirect to course list
        header('location: ' . $domain . '/admin/course.php');
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
                                Thêm
                            </h4>

                        </div>

                        <div class="card-body">

                            <!-- Create form has inputs: name, description, price, image -->
                            <form action="<?= $domain ?>/admin/course/add.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên khóa học</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên khóa học">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Ảnh</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="mb-3">
                                    <img id="image-preview" src="<?= $domain . '/public/images/' . $course['thumb'] ?>" alt="" width="200">

                                </div>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </form>


                        </div>
                        
                        <div class="card-body">

                            <!-- Create form has inputs: name, description, price, image -->
                            <form action="<?= $domain ?>/admin/course/add.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên khóa học</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên khóa học">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Ảnh</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="mb-3">
                                    <img id="image-preview" src="<?= $domain . '/public/images/' . $course['thumb'] ?>" alt="" width="200">

                                </div>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </form>


                        </div>
                        
                        <div class="card-body">

                            <!-- Create form has inputs: name, description, price, image -->
                            <form action="<?= $domain ?>/admin/course/add.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên khóa học</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên khóa học">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Ảnh</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="mb-3">
                                    <img id="image-preview" src="<?= $domain . '/public/images/' . $course['thumb'] ?>" alt="" width="200">

                                </div>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </form>


                        </div>

                    </div>

                </div>

            </div>


        </div>


        <?php include "../components/scripts.php" ?>

        <script>
            // handle choose image and show preview
            document.getElementById('image').addEventListener('change', function() {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('image-preview');
                    output.src = reader.result;
                };
                reader.readAsDataURL(this.files[0]);
            });
        </script>

        <?php include "../components/footer.php" ?>

    </div>



</body>