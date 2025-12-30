<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = '3307'; 
$DB_NAME = 'dormdash_final_v1';
$DB_USER = 'root';
$DB_PASS = 'newpassword'; 

try {
    // Adding the port clearly in the DSN string
    $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";
    $conn = new PDO($dsn, $DB_USER, $DB_PASS);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Uncomment this to test
} catch (PDOException $e) {
    // This will tell us EXACTLY what is wrong
    echo "Connection Error: " . $e->getMessage();
    exit;
}
?>