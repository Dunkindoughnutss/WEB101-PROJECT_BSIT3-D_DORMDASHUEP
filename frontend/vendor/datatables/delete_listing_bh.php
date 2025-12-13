<?php
include('../dbconnection.php');

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // sanitize input

    $sql = "DELETE FROM listings WHERE id = :id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute(['id' => $id])) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
