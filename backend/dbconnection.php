<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = 3306; // Your specific port
$DB_NAME = 'dormdash_final_v1';
$DB_USER = 'root';
$DB_PASS = 'not_artyyy01'; // Ensure this matches what you set in phpMyAdmin

try {
    // Note: We pass $DB_PASS as the third argument here
    $conn = new PDO("mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4", 
                    $DB_USER, 
                    $DB_PASS);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>