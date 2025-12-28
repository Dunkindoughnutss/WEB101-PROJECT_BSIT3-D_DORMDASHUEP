<?php
session_start();
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $renterName     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $role     = $_POST['role'];

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    
    try {
        $checkStmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->rowCount() > 0) {
            echo "<script>alert('Email already registered!'); window.history.back();</script>";
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $conn->prepare($sql);
        
        $success = $stmt->execute([
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => $role
        ]);

        if ($success) {
            echo "<script>
                    alert('Renter account created successfully!');
                    window.location.href = 'login.php';
                  </script>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>