<?php
session_start();
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../loginForms/owner/ownerlogin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mapping form fields to your bh_listing columns
    $ownername = trim($_POST['full_name']); 
    $contact = trim($_POST['contact']);
    $new_password = $_POST['new_password'];

    try {
        $conn->beginTransaction();

        // Update the bh_listing table (where owner details currently live)
        // This updates all listings owned by this user to keep info consistent
        $stmt = $conn->prepare("UPDATE bh_listing SET ownername = :ownername, contact = :contact WHERE user_id = :user_id");
        $stmt->execute([
            ':ownername' => $ownername,
            ':contact'   => $contact,
            ':user_id'   => $user_id
        ]);

        // Handle Password Change (Updates the users table)
        if (!empty($new_password)) {
            $hashed_pw = password_hash($new_password, PASSWORD_DEFAULT);
            $pw_stmt = $conn->prepare("UPDATE users SET password = :pw WHERE user_id = :id");
            $pw_stmt->execute([':pw' => $hashed_pw, ':id' => $user_id]);
        }

        $conn->commit();
        header("Location: owner_profile.php?success=profile_updated");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        error_log($e->getMessage());
        header("Location: owner_profile.php?error=update_failed");
        exit();
    }
}