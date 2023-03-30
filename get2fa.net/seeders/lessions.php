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

// factory 100 lessions
for ($i = 0; $i < 200; $i++) {
    $courseId = $courseIds[rand(0, count($courseIds) - 1)]['id'];
    $sql = "INSERT INTO `lessions` (`id`, `name`, `content`, `video`, `course_id`, `created_at`) 
    VALUES (NULL, 'Lession " . $i . "', 'Content " . $i . "','https://www.youtube.com/watch?v=9bZkp7q19f0','" . $courseId . "',CURRENT_TIMESTAMP);";
    exceQuery($sql);
}
