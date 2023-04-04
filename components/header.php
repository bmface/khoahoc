<!DOCTYPE html>
<html <?php


        // check current url contains /admin/

        // check current url contains login.php
        if (strpos($_SERVER['REQUEST_URI'], 'login.php') !== false) {
        } else if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
            echo ' lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"';
        } else {
            echo '  lang="en"
            data-layout="horizontal"
            data-layout-style=""
            data-layout-position="fixed"
            data-topbar="light"';
        }

        ?>>

<head>
        <meta charset="utf-8">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?= $title ?? "Hệ thống quản lý title" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $domain  ?>/assets/images/favicon.ico" />
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/790be31a0d.js" crossorigin="anonymous"></script>
    <!-- Layout config Js -->
    <script src="<?= $domain  ?>/assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?= $domain  ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= $domain  ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= $domain  ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?= $domain  ?>/assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- Css-->
    <link href="<?= $domain  ?>/assets/css/all.css" rel="stylesheet" type="text/css" />
    <link href="<?= $domain  ?>/assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="<?= $domain  ?>/assets/css/font.css" rel="stylesheet" type="text/css" />

    <link href="<?= $domain  ?>/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

    <link href="<?= $domain  ?>/assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
    <link href="<?= $domain  ?>/assets/libs/quill/quill.bubble.css" rel="stylesheet" type="text/css" />
    <link href="<?= $domain  ?>/assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />
    
    <!-- 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    -->
    
</head>