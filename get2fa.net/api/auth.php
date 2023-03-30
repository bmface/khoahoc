<?php

require_once '../config.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

// convert post data to array
$postdata = json_decode($postdata, true);

// check if method post
if (isset($postdata) && !empty($postdata)) {
    // get username, phone and token
    $username = $postdata['username'];
    $phone = $postdata['phone'];
    $token_browser = $postdata['token'];

    // sql injection
    $username = htmlspecialchars($username);
    $phone = htmlspecialchars($phone);
    $token_browser = htmlspecialchars($token_browser);

    // check if username, phone, token_browser empty
    if (empty($username) || empty($phone) || empty($token_browser)) {
        echo json_encode(["status" => false, 'message' => 'Vui lòng điền vào tất cả các trường']);
        exit;
    }

    // check if phone if syntax not of phone
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo json_encode(["status" => false, 'message' => 'Số điện thoại không hợp lệ']);
        exit;
    }


    // get user with username
    $sql = "SELECT * FROM users WHERE username = '$username' AND role != 'admin' ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // check if $user not exist 
    if (!$user) {
        echo json_encode(["status" => false, 'message' => 'Tài khoản không tồn tại']);
        exit;
    }

    // check if phone not match
    if (!empty($user["phone"]) && $user['phone'] != $phone) {
        echo json_encode(["status" => false, 'message' => 'Số điện thoại không đúng']);
        exit;
    }

    // check if user is locked
    if ($user['is_locked'] == 1) {
        echo json_encode(["status" => false, 'message' => 'Tài khoản đã bị khóa']);
        exit;
    }

    // get devices with user_id
    $user_id = $user["id"];
    $sql = "SELECT * FROM devices WHERE user_id = '$user_id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // check if token and useragent not exist in devices
    $check = false;
    foreach ($devices as $device) {
        if ($device['token'] == $token_browser && $device['useragent'] == $_SERVER['HTTP_USER_AGENT']) {
            $check = true;
        }
    }

    // check if $check is false and devices count =5
    if (!$check && count($devices) == 5) {
        echo json_encode(["status" => false, 'message' => 'Tài khoản đã đăng nhập trên 5 thiết bị']);
        exit;
    }

    // update phone if not exist
    if (empty($user["phone"])) {
        $sql = "UPDATE users SET phone = '$phone' WHERE id = '$user_id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // if check is false then insert device
    if (!$check) {
        $sql = "INSERT INTO devices (user_id, token, useragent) VALUES ('$user_id', '$token_browser', '$_SERVER[HTTP_USER_AGENT]')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }


    //create unique token for user
    $token_auth = md5(uniqid(rand(), true));

    // update token in table users
    $sql = "UPDATE users SET token = '$token_auth' WHERE id = '$user_id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    //set session user_id and token
    $_SESSION['user_id'] = $user_id;
    $_SESSION['token'] = $token_auth;

    //set session username
    $_SESSION['username'] = $user['username'];




    //set cookie user_id|phone|token
    setcookie("user_id", $user_id, time() + (86400 * 30), "/");
    setcookie("phone", $phone, time() + (86400 * 30), "/");
    setcookie("token_browser", $token_browser, time() + (86400 * 30), "/");
    setcookie("token", $token_auth, time() + (86400 * 30), "/");

    // return user
    echo json_encode(['status' => true, "message" => "Đăng nhập thành công"]);
    exit;
}
