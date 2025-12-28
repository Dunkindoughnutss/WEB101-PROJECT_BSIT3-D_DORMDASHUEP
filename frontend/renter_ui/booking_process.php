<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginForms/renter/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bh_id'])) {
    $renter_id = $_SESSION['user_id'];
    $bh_id = $_POST['bh_id'];

    try {
        // 1. Check if the user already has a pending request for this house to prevent duplicates
        $check = $conn->prepare("SELECT reservation_id FROM bh_reservations WHERE user_id = ? AND bh_id = ? AND status = 'Pending'");
        $check->execute([$renter_id, $bh_id]);
        
        if ($check->fetch()) {
            header("Location: renter_activity.php?info=already_requested");
            exit();
        }

        // 2. Insert the reservation into the database
        $sql = "INSERT INTO bh_reservations (user_id, bh_id, status) VALUES (?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$renter_id, $bh_id]);

        // 3. Success! Redirect to Renter Activity page
        header("Location: renter_activity.php?success=requested");
        exit();

    } catch (PDOException $e) {
        die("Booking Error: " . $e->getMessage());
    }
} else {
    header("Location: renter_home.php");
    exit();
}