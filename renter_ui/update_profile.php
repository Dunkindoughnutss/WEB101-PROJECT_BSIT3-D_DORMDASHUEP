<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['renterName'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];

    try {
        // Check if renter details exist
        $check = $conn->prepare("SELECT user_id FROM renter_details WHERE user_id = ?");
        $check->execute([$user_id]);

        if ($check->fetch()) {
            // Update
            $sql = "UPDATE renter_details SET renterName = ?, gender = ?, contact = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $gender, $contact, $user_id]);
        } else {
            // Insert
            $sql = "INSERT INTO renter_details (user_id, renterName, gender, contact) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id, $name, $gender, $contact]);
        }

        header("Location: renter_profile.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}