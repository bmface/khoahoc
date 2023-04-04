<?php
include "config.php";

// check if not exist key = 123456789
if (!isset($_GET['key']) || $_GET['key'] != '123456789') {
    echo "Access denied";
    exit;
}

// check if not exist admin in table
$sql = "SELECT * FROM users WHERE role = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    return;
}

// create user admin 
$sql = "INSERT INTO users (username, role, password, phone) VALUES ('admin', 'admin', '" . md5("admin") . "', '')";
$stmt = $conn->prepare($sql);
$stmt->execute();
