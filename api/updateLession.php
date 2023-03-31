<?php

// inlucde config
include "../config.php";

checkUserLogged();

// get lession id from request
$lession_id = $_GET['lession_id'];

// check if lession id not exist
if (!isset($lession_id)) {
    die(json_encode(["status" => false, "message" => "Không tìm thấy bài học"]));
}

// check lession exist
$sql = "SELECT * FROM lessions WHERE id = " . $lession_id;
$stmt = $conn->prepare($sql);
$stmt->execute();
$lession = $stmt->fetch(PDO::FETCH_ASSOC);

// check if lession not exist
if (!$lession) {
    die(json_encode(["status" => false, "message" => "Không tìm thấy bài học"]));
}


// get course from lession
$sql = "SELECT * FROM courses WHERE id = " . $lession['course_id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// check if course not exist
if (!$course) {
    die(json_encode(["status" => false, "message" => "Không tìm thấy khóa học"]));
}

// get user_course
$sql = "SELECT * FROM user_courses WHERE user_id = " . $_SESSION['user_id'] . " AND course_id = " . $course['id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$user_course = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user not have course
if (!$user_course) {
    die(json_encode(["status" => false, "message" => "Bạn không có quyền truy cập khóa học này"]));
}

// check if lession_id > user_course lession_id
if ($lession["position"] >= $user_course['lession_id']) {

    // get next lession
    $sql = "SELECT * FROM lessions WHERE position > " .$lession["position"] . " AND course_id = " . $course['id'] . " ORDER BY position ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $next_lession = $stmt->fetch(PDO::FETCH_ASSOC);

    // check if next lession not exist
    if (!$next_lession) {
        die(json_encode(["status" => false, "message" => "Không tìm thấy bài học"]));
    }

    // update user_course lession_id to next lession id
    $sql = "UPDATE user_courses SET lession_id = " . $next_lession['position'] . " WHERE id = " . $user_course['id'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}


// die json success
die(json_encode(["status" => true, "message" => "Đã mở khóa bài học tiếp theo"]));
