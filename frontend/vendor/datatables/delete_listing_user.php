<?php
// Ensure this path is correct based on where this file is saved
include('../../../backend/dbconnection.php');

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = intval($_POST['id']); 

    try {

        $sql = "DELETE FROM users WHERE user_id = :user_id"; 
        $stmt = $conn->prepare($sql);   

        $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'error: user not found in database';
            }
        } else {
            echo 'error: execution failed';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error: no id provided';
}
?>