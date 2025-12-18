<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header("Content-Type: application/json");
session_start();

require_once "dbconnection.php"; 

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid request data."]);
    exit;
}

$email = trim($data['email']);
$pass = trim($data['pass']);

// Get user_id from session (Make sure this matches your login.php session key!)
// $admin_id = $_SESSION["user_id"] ?? null;
$admin_id = 1;

if (!$admin_id) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

try {
    if ($pass === "") {
        $sql = "UPDATE users SET email = :email WHERE user_id = :id AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":email" => $email,
            ":id" => $admin_id
        ]);
        echo json_encode(["success" => true, "message" => "Email updated successfully."]);
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET email = :email, password = :pass WHERE user_id = :id AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":email" => $email,
            ":pass" => $hashed,
            ":id" => $admin_id
        ]);

        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit;
}
?>
