<?php
include("config.php");

// get user id from url
$id_lession = $_GET['id'];

// sql injection
$id_lession = addslashes($id_lession);

//check if session not contains user_id
checkUserLogged();

// get user from session
$userID = $_SESSION['user_id'];



// get user from userid 
$sql = "SELECT * FROM users WHERE id = " . $userID;

$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// check if $user not exist
if (!$user) {
    header('location: login.php');
}



// get lession from id_lession
$sql = "SELECT * FROM lessions WHERE id = " . $id_lession;

$stmt = $conn->prepare($sql);
$stmt->execute();
$lession = $stmt->fetch(PDO::FETCH_ASSOC);

// check if $lession not exist
if (!$lession) {
    die("Không tìm thấy bài học");
    header('location: dashboard.php');
}

// check user course
$sql = "SELECT * FROM user_courses WHERE user_id = " . $user['id'] . " AND course_id = " . $lession['course_id'];

// prepare sql
$stmt = $conn->prepare($sql);
$stmt->execute();
$check = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user not have course
if (!$check) {
    die("Bạn không có quyền truy cập bài học này");
    header('location: dashboard.php');
}

include "VideoStream.php";
$stream = new VideoStream("./videos/" . $lession["video"]);
$stream->start();
exit;
