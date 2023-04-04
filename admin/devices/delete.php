<?php

include "../../config.php";

//check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('location: login.php');
}

// get id from url
$id = $_GET['id'];

// delete device
$sql = "DELETE FROM devices WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();


// redirect to lession page
header('location: ' . $domain . '/admin/devices.php');
