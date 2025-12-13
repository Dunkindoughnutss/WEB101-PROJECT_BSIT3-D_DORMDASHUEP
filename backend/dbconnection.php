<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = 3306;
$DB_NAME = 'dormdash_final_v1';
$DB_USER = 'root';
$DB_PASS = 'not_artyyy01';

try {
    $conn = new PDO("mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4",
                    $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
