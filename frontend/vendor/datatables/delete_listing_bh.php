<?php
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); 

    try {
        $sql = "DELETE FROM bh_listing WHERE bh_id = :id";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute(['id' => $id])) {
            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'error: Listing not found.';
            }
        } else {
            echo 'error: Execution failed.';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error: No ID provided.';
}