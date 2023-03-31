<?php

session_start();
$password = "";
$username = "root";
try {
    $conn = new PDO('mysql:host=localhost;dbname=khoahoc', $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $ex) {
    die(json_encode(array('outcome' => false, 'message' => 'Unable to connect 1')));
}

// get current domain with http
$domain = "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'];
$current_url = "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$current_dir = __DIR__;
$root_dir = $_SERVER['DOCUMENT_ROOT'];


// function return active class if current url is in current file
function addActiveClass($name, $path)
{
    global $current_url;


    if ((strpos($current_url, $path) != false)) {
        return 'active';
    }
    if (basename($_SERVER['PHP_SELF']) == $name) {
        return 'active';
    }
    return '';
}


function checkRememberCookie()
{
    global $conn;

    // check isset session
    if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
        return;
    }

    // check cookie remember
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['token_browser']) && isset($_COOKIE['token']) && !isset($_COOKIE['phone'])) {
        $user_id = $_COOKIE['user_id'];
        $token_browser = $_COOKIE['token_browser'];
        $phone = $_COOKIE['phone'];
        $token = $_COOKIE['token'];


        // sql injection
        $user_id = addslashes($user_id);
        $token_browser = addslashes($token_browser);
        $phone = addslashes($phone);
        $token = addslashes($token);

        $sql = "SELECT * FROM users WHERE id = $user_id AND phone = '$phone' AND token = '$token'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $useragent = $_SERVER['HTTP_USER_AGENT'];

        // check if user exist
        if ($user) {

            //get device talbe with token, user_id, useragent
            $sql = "SELECT * FROM devices WHERE token = '$token_browser' AND user_id = '$user_id' AND useragent = '$useragent'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $device = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($device) {
                // set session user_id
                $_SESSION['user_id'] = $user_id;
                $_SESSION['token'] = $token;
                // reload page
                header('location: ' . $_SERVER['REQUEST_URI']);
            }
        }
    }
}

function checkUserLogged()
{

    global $conn;


    // get session user id from $_SESSION
    if (!isset($_COOKIE['user_id']) || !isset($_COOKIE['token'])) {

        // remove session
        unset($_SESSION['user_id']);
        unset($_SESSION['token']);
        unset($_SESSION['username']);

        header('location: login.php');
    }

    // check user exist from database with user id and token
    $sql = "SELECT * FROM users WHERE id = " . $_COOKIE['user_id'] . " and token='" . $_COOKIE['token'] . "' and is_locked = 0 ";

    // prepare sql
    $stmt = $conn->prepare($sql);

    // execute sql
    $stmt->execute();

    // get user from database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // check if user not exist
    if (!$user) {

        // remove session
        unset($_SESSION['user_id']);
        unset($_SESSION['token']);
        unset($_SESSION['username']);

        // remove cookie
        setcookie('user_id', '', time() - 3600);
        setcookie('token', '', time() - 3600);


        header('location: login.php');
    }

    //get token_browser from cookie
    $token_browser = $_COOKIE['token_browser'];

    // sql injection
    $token_browser = addslashes($token_browser);

    // check device
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $sql = "SELECT * FROM devices WHERE user_id = " . $_COOKIE['user_id'] . " and token = '$token_browser' and useragent = '$useragent'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$device) {
        unset($_SESSION['user_id']);
        unset($_SESSION['token']);

        // remove cookie
        setcookie('user_id', '', time() - 3600);
        setcookie('token', '', time() - 3600);

        header('location: login.php');
    }

    //set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['token'] = $user['token'];
    $_SESSION['username'] = $user['username'];
}


function updateTimeDevice()
{
    global $conn;

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $token_browser = $_COOKIE['token_browser'];
    $user_id = $_COOKIE['user_id'];

    // sql injection
    $useragent = addslashes($useragent);
    $token_browser = addslashes($token_browser);
    $user_id = addslashes($user_id);


    // check if device exist
    $sql = "SELECT * FROM devices WHERE user_id = $user_id AND token = '$token_browser' AND useragent = '$useragent'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$device) {
        return;
    }


    $sql = "UPDATE devices SET created_at = NOW() WHERE user_id = $user_id AND token = '$token_browser' AND useragent = '$useragent'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}