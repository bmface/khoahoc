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

$sql = "INSERT INTO `users` (`id`, `username`, `role`,`phone`, `password`, `created_at`) 
VALUES (NULL, 'admin', 'admin', '', '" . md5("admin") . "',CURRENT_TIMESTAMP);";

exceQuery($sql);

// factory 100 users
for ($i = 0; $i < 100; $i++) {
    $sql = "INSERT INTO `users` (`id`, `username`, `role`,`phone`, `password`, `created_at`) 
    VALUES (NULL, 'user" . $i . "', 'user','', '" . md5("user" . $i) . "',CURRENT_TIMESTAMP);";
    exceQuery($sql);
}
