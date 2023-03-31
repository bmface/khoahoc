<?php

include "../../config.php";

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}

// get id from url
$id = $_GET['id'];

//sql injection
$id = htmlspecialchars($id);

//check exist lession
$sql = "SELECT * FROM lessions WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lession = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lession) {
    echo json_encode([
        'status' => false,
        'message' => 'Không tìm thấy bài học'
    ]);
    die();
}


// delete lession
$sql = "DELETE FROM lessions WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();

echo json_encode([
    'status' => true,
    'message' => 'Xóa bài học thành công'
]);
