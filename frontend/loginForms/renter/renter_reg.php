<?php
session_start();

// 1. Connection Path
// This goes from frontend/loginForms/renter/ -> frontend/ -> root -> backend/dbconnection.php
$db_path = __DIR__ . '/../../../backend/dbconnection.php';

if (file_exists($db_path)) {
    require_once $db_path;
} else {
    die("Database connection file missing at: " . $db_path);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $renterName = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm'];
    $role       = $_POST['role'] ?? 'renter'; 

    // Basic Validation
    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    try {
        // 2. Check if email already exists
        $checkStmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->rowCount() > 0) {
            echo "<script>alert('Email already registered!'); window.history.back();</script>";
            exit;
        }

        // 3. Start a Transaction 
        // This ensures if the user table update works but the renter_details fails, nothing is saved.
        $conn->beginTransaction();

        // 4. Insert into 'users' table
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sqlUser = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmtUser = $conn->prepare($sqlUser);
        
        $stmtUser->execute([
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => $role
        ]);

        // 5. Get the new User ID
        $newUserId = $conn->lastInsertId();

        // 6. Insert into 'renter_details' table
        // Matches your SQL: (renter_id, user_id, renterName, contact, gender, etc.)
        $sqlDetails = "INSERT INTO renter_details (user_id, renterName) VALUES (:user_id, :name)";
        $stmtDetails = $conn->prepare($sqlDetails);
        $stmtDetails->execute([
            'user_id' => $newUserId,
            'name'    => $renterName
        ]);

        // 7. Commit the changes
        $conn->commit();

        echo "<script>
                alert('Renter account created successfully!');
                window.location.href = 'login.php';
              </script>";

    } catch (PDOException $e) {
        // Rollback transaction if something goes wrong
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        // Log the error and show a user-friendly message
        error_log("Registration Error: " . $e->getMessage());
        echo "Registration Error. Please check your database connection or if the email is already in use.";
    }
}
?>