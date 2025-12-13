<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header("Content-Type: application/json");
session_start();

require_once "../dbconnection.php"; // adjust path

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

$email = trim($data['email']);
$pass = trim($data['pass']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit;
}

// Get admin_id from session
$admin_id = $_SESSION["user_id"] ?? null;
if (!$admin_id) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

try {
    if ($pass === "") {
        // Update ONLY email
        $sql = "UPDATE admin_table SET email = :email WHERE user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":email" => $email,
            ":id" => $admin_id
        ]);

        echo json_encode(["success" => true, "message" => "Email updated successfully."]);
    } else {
        // Update BOTH email & password
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "UPDATE admin_table SET email = :email, password = :pass WHERE user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":email" => $email,
            ":pass" => $hashed,
            ":id" => $admin_id  // FIXED: use $admin_id
        ]);

        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit;
}
?>
