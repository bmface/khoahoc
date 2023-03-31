<?php

include "../config.php";

// factory lession
function createLession($name, $content, $course_id)
{
    global $conn;
    $sql = "INSERT INTO lessions (name, content, course_id) VALUES (:name, :content, :course_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
}

// get all course
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get only course_id
function getCourseId($courses)
{
    $course_ids = [];
    foreach ($courses as $course) {
        $course_ids[] = $course['id'];
    }
    return $course_ids;
}

$courseIds = getCourseId($courses);

// for each course_id create 100 lessions
foreach ($courseIds as $courseId) {
    for ($i = 0; $i < 100; $i++) {
        $name = "Lession $i";
        $content = "Content $i";
        createLession($name, $content, $courseId);
    }
}
