<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginForms/owner/owner_login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);
$success_msg = false;

// --- 1. HANDLE PROFILE & IMAGE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $contact   = trim($_POST['contact']);
    $business  = trim($_POST['business_name']);
    $address   = trim($_POST['address']);
    $new_pass  = $_POST['new_password'];
    $new_filename = null;

    // Handle Image Upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $upload_dir = __DIR__ . '/../../uploads/profiles/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $new_filename = "owner_" . $owner_id . "_" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $new_filename);
    }

    try {
        $conn->beginTransaction();

        // Update Users Table (Email)
        $stmt1 = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
        $stmt1->execute([$email, $owner_id]);

        // Update Password if provided
        if (!empty($new_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?")->execute([$hashed, $owner_id]);
        }

        // Update Owner Details (Using $new_filename if uploaded, otherwise keep existing)
        if ($new_filename) {
            $od_sql = "INSERT INTO owner_details (user_id, full_name, contact_number, business_name, home_address, profile_img) 
                       VALUES (:id, :fname, :contact, :bname, :addr, :img)
                       ON DUPLICATE KEY UPDATE 
                       full_name = VALUES(full_name), 
                       contact_number = VALUES(contact_number), 
                       business_name = VALUES(business_name), 
                       home_address = VALUES(home_address), 
                       profile_img = VALUES(profile_img)";
            $params = [':id'=>$owner_id, ':fname'=>$full_name, ':contact'=>$contact, ':bname'=>$business, ':addr'=>$address, ':img'=>$new_filename];
        } else {
            $od_sql = "INSERT INTO owner_details (user_id, full_name, contact_number, business_name, home_address) 
                       VALUES (:id, :fname, :contact, :bname, :addr)
                       ON DUPLICATE KEY UPDATE 
                       full_name = VALUES(full_name), 
                       contact_number = VALUES(contact_number), 
                       business_name = VALUES(business_name), 
                       home_address = VALUES(home_address)";
            $params = [':id'=>$owner_id, ':fname'=>$full_name, ':contact'=>$contact, ':bname'=>$business, ':addr'=>$address];
        }
        
        $conn->prepare($od_sql)->execute($params);

        $conn->commit();
        header("Location: owner_profile.php?msg=updated");
        exit();
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        error_log($e->getMessage());
    }
}

// --- 2. FETCH DATA ---
try {
    $fetch_sql = "SELECT u.email, od.full_name, od.contact_number, od.business_name, od.home_address, od.profile_img 
                  FROM users u 
                  LEFT JOIN owner_details od ON u.user_id = od.user_id 
                  WHERE u.user_id = :id";
    $stmt = $conn->prepare($fetch_sql);
    $stmt->execute([':id' => $owner_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fallback if details don't exist yet (new account)
    $display_name = $user_data['full_name'] ?? "Owner Name";
    $display_email = $user_data['email'] ?? "";
    $display_contact = $user_data['contact_number'] ?? "";
    $display_business = $user_data['business_name'] ?? "";
    $display_address = $user_data['home_address'] ?? "";

    // Image Path Logic
    if (!empty($user_data['profile_img'])) {
        $img_rel_path = "../../uploads/profiles/" . $user_data['profile_img'];
        $display_img = (file_exists(__DIR__ . "/" . $img_rel_path)) ? $img_rel_path . "?v=" . time() : "../../res/default_avatar.jpg";
    } else {
        $display_img = "../../res/default_avatar.jpg";
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Owner Profile - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_profile.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="../res/logo1.png" alt="UEP" class="logo-top"></div>
        <div class="nav-icons">
            <a href="owner_profile.php" class="active"><img class="icon" src="../icons/circle-user-round.svg"></a>
            <a href="owner_home.php"><img class="icon" src="../icons/house.svg"></a>
            <a href="owner_search.php"><img class="icon" src="../icons/search.svg"></a>
            <a href="owner_map.php"><img class="icon" src="../icons/map-pin-house.svg"></a>
            <a href="owner_listings.php"><img class="icon" src="../icons/pencil-line.svg"></a>
            <a href="owner_manage.php"><img class="icon" src="../icons/check-check.svg"></a>
            <a href="owner_settings.php"><img class="icon" src="../icons/settings.svg"></a>
        </div>
        <div class="bottom-icons">
            <a href="owner_help.php"><img class="icon" src="../icons/message-circle-question-mark.svg"></a>
            <a href="owner_logout.php"><img class="icon" src="../icons/log-out.svg"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header"><h2>UEP DORMDASH</h2></div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>Profile Settings</h1>
                    <p>Manage your account and business details</p>
                </div>
                <?php if(isset($_GET['msg'])): ?>
                    <span style="color: #27ae60; font-weight: bold;">✅ Profile updated successfully!</span>
                <?php endif; ?>
            </div>

            <form class="profile-form" method="POST" action="owner_profile.php" enctype="multipart/form-data">
                <div class="profile-layout">
                    <div class="profile-aside">
                        <div class="avatar-upload">
                            <div class="avatar-preview" id="prev" style="background-image: url('<?= $display_img ?>'); background-size: cover; background-position: center;"></div>
                            <input type="file" name="profile_pic" id="fileInput" hidden accept="image/*">
                            <button type="button" class="btn-change-photo" onclick="document.getElementById('fileInput').click()">Change Photo</button>
                        </div>
                        <div class="input-group">
                            <label>Account ID</label>
                            <input type="text" value="OWNER-<?= $owner_id ?>" disabled style="background: #f1f1f1; color: #666; cursor: not-allowed;">
                        </div>
                    </div>

                    <div class="profile-main">
                        <div class="form-row">
                            <div class="input-group">
                                <label>Full Name</label>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($display_name) ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($display_email) ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact" value="<?= htmlspecialchars($display_contact) ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Business Name (Optional)</label>
                                <input type="text" name="business_name" value="<?= htmlspecialchars($display_business) ?>">
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Home Address</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($display_address) ?>" required>
                        </div>

                        <div class="password-section">
                            <h3>Security</h3>
                            <div class="input-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" placeholder="Leave blank to keep current password">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-cancel" onclick="window.location.href='owner_home.php'">Cancel</button>
                            <button type="submit" name="update_profile" class="btn-save">Save Information</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live Preview Script for the profile picture
        document.getElementById('fileInput').onchange = function() {
            const [file] = this.files;
            if (file) {
                document.getElementById('prev').style.backgroundImage = `url(${URL.createObjectURL(file)})`;
            }
        };
    </script>
</body>
</html>