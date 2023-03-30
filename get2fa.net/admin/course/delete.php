<?php

include '../../config.php';

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}


// get id from url
$id = $_GET['id'];

// check if course exist
$sql = "SELECT * FROM courses WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    echo json_encode([
        'status' => false,
        'message' => 'Không tìm thấy bài học'
    ]);
    die();
}



// delete user_course by course id
$sql = "DELETE FROM user_courses WHERE course_id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();

// delete lession by course id
$sql = "DELETE FROM lessions WHERE course_id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();

// delete course
$sql = "DELETE FROM courses WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();

echo json_encode([
    'status' => true,
    'message' => 'Xóa khóa học thành công'
]);
