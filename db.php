<?php

//check if not exist key or not equal 123456789
if (!isset($_GET['key']) || $_GET['key'] != '123456789') {
    echo "Access denied";
    exit;
}


include "config.php";

function exceQuery($sql)
{
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt;
}

// write query to create courses table with id, name, description, created_at, thumb,tag    
$sql = "CREATE TABLE IF NOT EXISTS courses (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    thumb VARCHAR(255) NOT NULL,
    tag VARCHAR(255) NOT NULL
)";
exceQuery($sql);

// write query to create lessions table with id, name, content, video, courser_id is forign key, created_at
$sql = "CREATE TABLE IF NOT EXISTS lessions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    video VARCHAR(255) NOT NULL,
    course_id INT(6) UNSIGNED NOT NULL,
    position INT(6) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

exceQuery($sql);

// write query to create users table with id, username, role, password, phone, created_at
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    password VARCHAR(255) NULL,
    phone VARCHAR(255) NOT NULL,
    token VARCHAR(255) NULL,
    is_locked INT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

exceQuery($sql);




// write query to create devices table with id, data, user_id,created_at
$sql = "CREATE TABLE IF NOT EXISTS devices (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    useragent VARCHAR(255) NOT NULL,
    user_id INT(6) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
exceQuery($sql);

// create table user_courses with id, user_id, course_id, lession_id created_at
$sql = "CREATE TABLE IF NOT EXISTS user_courses (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    course_id INT(6) UNSIGNED NOT NULL,
    lession_id INT(6) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
exceQuery($sql);
