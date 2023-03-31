<?php

include '../../config.php';

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}

//get user id from url
$user_id = $_GET['id'];

//check user id not empty
if (empty($user_id)) {
    echo json_encode([
        'status' => false,
        'message' => 'Không tìm thấy tài khoản'
    ]);
    die();
}

// get user by id
$sql = "SELECT * FROM users WHERE id = $user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user not exist
if (!$user) {
    echo json_encode([
        'status' => false,
        'message' => 'Không tìm thấy tài khoản'
    ]);
    die();
}

// delete all user_course
$sql = "DELETE FROM user_courses WHERE user_id = $user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();

// delete user
$sql = "DELETE FROM users WHERE id = $user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();

echo json_encode([
    'status' => true,
    'message' => 'Xóa tài khoản thành công'
]);
