<!DOCTYPE html>
<html lang="en">
<?php
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <?php

    // phpinfo();
    // die();

    @ini_set('upload_max_size', '2G');

    @ini_set('post_max_size', '2G');

    @ini_set('max_execution_time', '300000');

    require './aws/aws-autoloader.php';

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


    // check if post
    if (isset($_POST['submit'])) {


        // check if file is uploaded
        if (isset($_FILES['file'])) {

            $video = $_FILES['file'];
            $video_name = $video['name'];
            $video_tmp_name = $video['tmp_name'];
            $video_size = $video['size'];
            $video_error = $video['error'];
            $video_type = $video['type'];


            $video_ext = explode('.', $video_name);
            $video_ext = strtolower(end($video_ext));

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

                // redirect to lession page
                // header('location: ' . $domain . '/admin/lession.php');
            } catch (Exception $e) {
                $error = "Error s3: " . $e->getMessage();
            }
        }
    }

    ?>


</head>

<body class="bg-gray-300 p-10">

    <label for="font-bold text-3xl text-red-500">
        <?= $error ?? "" ?>
    </label>

    <form action="" method="POST" class="grid grid-cols w-1/4" enctype="multipart/form-data">

        <!-- input file -->
        <input type="file" name="file" id="file" required>

        <!-- submit button -->
        <button type="submit" name="submit" class="font-bold bg-blue-500 mt-3 border text-white"
            value="submit">Gửi</button>

        <label for="" id="info"></label>

    </form>

    <script>
    // event change file and show filesize in label
    document.getElementById('file').addEventListener('change', function() {
        var file = this.files[0];
        var info = document.getElementById('info');

        // convert file size to GB
        var a = file.size / 1024 / 1024 / 1024;

        info.innerHTML = file.name + ' - ' + a + ' GB';
    });
    </script>


</body>

</html>