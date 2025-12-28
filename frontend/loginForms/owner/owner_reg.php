<?php
session_start();
require_once __DIR__ . '/../../../backend/dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ownerName = trim($_POST['name']); 
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm'];
    $role      = $_POST['role'];

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    try {
        $conn->beginTransaction();

        // 1. Check if email already exists
        $checkStmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->rowCount() > 0) {
            $conn->rollBack();
            echo "<script>alert('Email already registered!'); window.history.back();</script>";
            exit;
        }

        // 2. Hash and Insert into 'users'
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email, 'password' => $hashedPassword, 'role' => $role]);

        $new_user_id = $conn->lastInsertId();

        // 3. AUTOMATIC INSERT INTO 'owner_details'
        // This is what ensures owner_profile.php works immediately
        $sqlDetails = "INSERT INTO owner_details (user_id, full_name) VALUES (:uid, :fname)";
        $stmtDetails = $conn->prepare($sqlDetails);
        $stmtDetails->execute(['uid' => $new_user_id, 'fname' => $ownerName]);

        $conn->commit();

        // 4. Redirect using the current folder path
        echo "<script>
                alert('Owner account created successfully!');
                window.location.href = 'owner_login.php?created=1';
              </script>";
        exit;

    } catch (PDOException $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        error_log($e->getMessage());
        die("Registration failed. Please contact admin.");
    }
}