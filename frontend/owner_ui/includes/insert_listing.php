<?php
session_start();

// 1. Correct Path to your dbconnection.php
// From frontend/owner_ui/includes/ to backend/ is 3 steps up
include __DIR__ . '/../../../backend/dbconnection.php';

if (isset($_POST['submit'])) {

    // 2. Capture Form Fields
    $ownername = $_POST['ownername'];
    $contact = $_POST['contact'];
    $title = $_POST['title'];
    $monthly_rent = $_POST['monthly_rent'];
    $available_rooms = $_POST['available_rooms'];
    $bh_address = $_POST['address'];
    $bh_description = $_POST['bh_description'];
    $curfew_policy = $_POST['curfew_policy'];
    $roomtype = $_POST['room_type'];
    $preferred_gender = $_POST['preferred_gender'];
    $amenities = isset($_POST['amenities']) ? implode(",", $_POST['amenities']) : '';

    try {
        // Use $conn because that is what is defined in your dbconnection.php
        $conn->beginTransaction();

        $stmt = $conn->prepare("
            INSERT INTO bh_listing 
            (user_id, ownername, contact, title, bh_description, monthly_rent, bh_address, available_rooms, roomtype, amenities, preferred_gender, curfew_policy)
            VALUES 
            (:user_id, :ownername, :contact, :title, :bh_description, :monthly_rent, :bh_address, :available_rooms, :roomtype, :amenities, :preferred_gender, :curfew_policy)
        ");

        $stmt->execute([
            ':user_id' => 1,
            ':ownername' => $ownername,
            ':contact' => $contact,
            ':title' => $title,
            ':bh_description' => $bh_description,
            ':monthly_rent' => $monthly_rent,
            ':bh_address' => $bh_address,
            ':available_rooms' => $available_rooms,
            ':roomtype' => $roomtype,
            ':amenities' => $amenities,
            ':preferred_gender' => $preferred_gender,
            ':curfew_policy' => $curfew_policy
        ]);

        $bh_id = $conn->lastInsertId();

        // 3. Handle Images
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {

            // Set upload folder (inside your project)
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/uploads/listings/";

            // Create folder if it doesn't exist
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    die("❌ Failed to create folder: $uploadDir");
                }
            }

            $imgStmt = $conn->prepare("INSERT INTO bh_images (bh_id, image_path) VALUES (:bh_id, :image_path)");

            // Loop through uploaded files
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

                // Skip files with errors
                if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                    echo "❌ Error uploading file: " . $_FILES['images']['name'][$i] . " | Error code: " . $_FILES['images']['error'][$i] . "<br>";
                    continue;
                }

                // Generate unique filename
                $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $newName = uniqid('bh_', true) . '.' . $ext;
                $destination = $uploadDir . $newName;

                // Move the file to upload folder
                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $destination)) {
                    // Save relative path to DB
                    $dbPath = 'uploads/listings/' . $newName;
                    $imgStmt->execute([':bh_id' => $bh_id, ':image_path' => $dbPath]);
                    echo "✅ Uploaded: $dbPath<br>";
                } else {
                    echo "❌ Failed to move file: " . $_FILES['images']['name'][$i] . "<br>";
                    echo "Destination folder: $uploadDir<br>";
                }
            }
        } else {
            echo "❌ No files uploaded.<br>";
        }


        $conn->commit();
        // Redirect back to home
        header('Location: ../owner_home.php?success=1');
        exit;
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die("❌ Database Error: " . $e->getMessage());
    }
}
