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

// factory 10 courses
for ($i = 0; $i < 10; $i++) {
    $sql = "INSERT INTO `courses` (`id`, `name`, `description`, `thumb`, `tag`, `created_at`) 
    VALUES (NULL, 'Course " . $i . "', 'Description " . $i . "','https://picsum.photos/200/300','tag" . $i . "',CURRENT_TIMESTAMP);";
    exceQuery($sql);
}
