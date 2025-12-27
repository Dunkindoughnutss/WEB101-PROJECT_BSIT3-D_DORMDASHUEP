<?php
session_start();

// Correct path to your dbconnection.php
include __DIR__ . '/../../../backend/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Capture text fields
    $ownername        = $_POST['ownername'];
    $contact          = $_POST['contact'];
    $title            = $_POST['title'];
    $monthly_rent     = $_POST['monthly_rent'];
    $available_rooms  = $_POST['available_rooms'];
    $bh_address       = $_POST['address'];
    $bh_description   = $_POST['bh_description'];
    $curfew_policy    = $_POST['curfew_policy'];
    $roomtype         = $_POST['room_type'];
    $preferred_gender = $_POST['preferred_gender'];
    $amenities        = isset($_POST['amenities']) ? implode(",", $_POST['amenities']) : '';

    // 2. Handle the Image File Upload
    $imageFileName = null; // Default value

    if (isset($_FILES['images']) && $_FILES['images']['error'] === 0) {
        $file = $_FILES['images'];
        
        // Define where to save the file
        // Note: Using absolute path or ensuring correct relative path to the uploads folder
        $uploadDir = "../../../uploads/listings/";
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique name to prevent overwriting
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $imageFileName = "bh_" . time() . "_" . uniqid() . "." . $ext;
        $destination = $uploadDir . $imageFileName;

        // Move the file from PHP's temporary folder to your listings folder
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            die("❌ Failed to move uploaded file. Check folder permissions.");
        }
    } else {
        die("❌ No image selected or there was an upload error.");
    }

    try {
        $conn->beginTransaction();

        // 3. Insert the listing including the image_path
        $stmt = $conn->prepare("
            INSERT INTO bh_listing 
            (user_id, ownername, contact, title, bh_description, monthly_rent, bh_address, available_rooms, roomtype, amenities, preferred_gender, curfew_policy, image_path)
            VALUES 
            (:user_id, :ownername, :contact, :title, :bh_description, :monthly_rent, :bh_address, :available_rooms, :roomtype, :amenities, :preferred_gender, :curfew_policy, :image_path)
        ");

        $stmt->execute([
            ':user_id'          => $_SESSION['user_id'] ?? 1, 
            ':ownername'        => $ownername,
            ':contact'          => $contact,
            ':title'            => $title,
            ':bh_description'   => $bh_description,
            ':monthly_rent'     => $monthly_rent,
            ':bh_address'       => $bh_address,
            ':available_rooms'  => $available_rooms,
            ':roomtype'         => $roomtype,
            ':amenities'        => $amenities,
            ':preferred_gender' => $preferred_gender,
            ':curfew_policy'    => $curfew_policy,
            ':image_path'       => $imageFileName // The unique filename we generated
        ]);

        $conn->commit();

        // 4. Success Redirect
        header("Location: ../owner_home.php?success=1");
        exit;
        
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        // If DB fails, you might want to delete the uploaded image file here to keep it clean
        die("❌ Database Error: " . $e->getMessage());
    }
}