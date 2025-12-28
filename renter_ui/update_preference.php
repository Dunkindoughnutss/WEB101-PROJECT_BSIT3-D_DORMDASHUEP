<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $column = $_POST['column'];
    $value = (int)$_POST['value'];

    // Whitelist columns for security
    $allowed = ['push_notifications', 'dark_mode', 'email_updates'];
    if (!in_array($column, $allowed)) exit();

    try {
        $stmt = $conn->prepare("UPDATE user_settings SET $column = :val WHERE user_id = :uid");
        $stmt->execute([':val' => $value, ':uid' => $uid]);
        echo "Success";
    } catch (PDOException $e) {
        http_response_code(500);
    }
}