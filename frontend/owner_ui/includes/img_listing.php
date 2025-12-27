<?php
session_start();

// Correct path to dbconnection.php
include __DIR__ . '/../../../backend/dbconnection.php';

// Make sure a file was uploaded
if (!isset($_FILES['listing_image']) || $_FILES['listing_image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo "No file uploaded or upload error.";
    exit;
}

$uploadDir = __DIR__ . "/../../../uploads/listings/";

// Ensure folder exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileTmp  = $_FILES['listing_image']['tmp_name'];
$ext      = strtolower(pathinfo($_FILES['listing_image']['name'], PATHINFO_EXTENSION));
$newName  = uniqid('bh_', true) . '.' . $ext;
$destination = $uploadDir . $newName;

// Move uploaded file
if (move_uploaded_file($fileTmp, $destination)) {
    // Return relative path for preview & form hidden input
    echo 'uploads/listings/' . $newName;
} else {
    http_response_code(500);
    echo "Failed to move uploaded file.";
}
?>
