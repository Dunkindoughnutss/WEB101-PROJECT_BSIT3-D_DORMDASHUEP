<?php
session_start();

// Only owners can access this
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
//     die("Access denied: Only owners can add listings.");
// }

// Database connection
$host = "localhost";
$dbname = "dormdash_final_v1";
$username = "root";
// XAMPP's default MySQL root user typically has an empty password.
// If you've set a root password, replace the value below with it,
// or create a dedicated DB user for the web app (recommended).
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch(PDOException $e) {
    // Do not expose raw DB errors in production. Show actionable hint.
    die("Connection failed: unable to connect to the database. Please verify DB credentials and that MySQL is running.");
}

// Check if form is submitted
if(isset($_POST['submit'])) {

    // Logged-in owner
    // $user_id = $_SESSION['user_id'];

    // Form fields (must match your form names)
    $ownername = $_POST['ownername'];
    $contact = $_POST['contact'];
    $title = $_POST['title'];
    $monthly_rent = $_POST['monthly_rent'];
    $available_rooms = $_POST['available_rooms'];
    $bh_address = $_POST['address'];
    $bh_description = $_POST['bh_description'];
    $curfew_policy = $_POST['curfew_policy'];
    $amenities = $_POST['amenities'];
    $roomtype = $_POST['room_type'];
    $preferred_gender = $_POST['preferred_gender'];

    try {

        // Insert into bh_listing table
        $stmt = $pdo->prepare("
            INSERT INTO bh_listing 
            (
                user_id, ownername, contact, title, bh_description,
                monthly_rent, bh_address, available_rooms, roomtype,
                amenities, preferred_gender, curfew_policy
            )
            VALUES 
            (
                :user_id, :ownername, :contact, :title, :bh_description,
                :monthly_rent, :bh_address, :available_rooms, :roomtype,
                :amenities, :preferred_gender, :curfew_policy
            )
        ");

        $user_id = 1;

        // Insert amenities as a comma-separated string into bh_listing
        $amenities = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : '';

        $pdo->beginTransaction();

        $stmt->execute([
            ':user_id' => $user_id,
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

        // Get the inserted listing ID
        $bh_id = $pdo->lastInsertId();

        // Handle uploaded images (if any)
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $allowedExt = ['jpg','jpeg','png','gif'];
            $maxSize = 5 * 1024 * 1024; // 5 MB

            $imgStmt = $pdo->prepare("INSERT INTO bh_images (bh_id, image_path) VALUES (:bh_id, :image_path)");

            // Loop through files
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                $error = $_FILES['images']['error'][$i];
                if ($error !== UPLOAD_ERR_OK) continue; // skip errored uploads

                $tmpName = $_FILES['images']['tmp_name'][$i];
                $origName = basename($_FILES['images']['name'][$i]);
                $size = $_FILES['images']['size'][$i];

                if ($size > $maxSize) continue; // skip oversized

                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) continue; // skip invalid types

                // Create a unique filename
                $newName = uniqid('img_', true) . '.' . $ext;
                $destination = $uploadDir . DIRECTORY_SEPARATOR . $newName;

                if (move_uploaded_file($tmpName, $destination)) {
                    // Store web-accessible path relative to project root
                    $webPath = 'uploads/' . $newName;
                    $imgStmt->execute([':bh_id' => $bh_id, ':image_path' => $webPath]);
                }
            }
        }

        $pdo->commit();

        // Redirect back to the form with a success flag so the UI can show a toast.
        header('Location: owner_home.html?success=1');
        exit;

    } catch(Exception $e) {
        echo "Failed to add listing: " . $e->getMessage();
    }

} else {
    echo "Form not submitted.";
}
?>
