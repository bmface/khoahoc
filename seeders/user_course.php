<?php
include "../config.php";

// check if not exist key
if (!isset($_GET['key']) || $_GET['key'] != '123456789') {
    echo "Access denied";
    exit;
}

function exceQuery($sql)
{
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt;
}

// get all id of course table
$sql = "SELECT id FROM courses";
$stmt = exceQuery($sql);
$courseIds = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get all id of user
$sql = "SELECT id FROM users";
$stmt = exceQuery($sql);
$userIds = $stmt->fetchAll(PDO::FETCH_ASSOC);

// factory 130 user_course
for ($i = 0; $i < 130; $i++) {
    $courseId = $courseIds[rand(0, count($courseIds) - 1)]['id'];
    $userId = $userIds[rand(0, count($userIds) - 1)]['id'];
    $sql = "INSERT INTO `user_courses` (`id`, `user_id`, `course_id`,`lession_id`, `created_at`) 
    VALUES (NULL, '" . $userId . "', '" . $courseId . "',0,CURRENT_TIMESTAMP);";
    exceQuery($sql);
}
