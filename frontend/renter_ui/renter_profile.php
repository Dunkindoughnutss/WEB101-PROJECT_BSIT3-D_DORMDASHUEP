<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

$user_id = $_SESSION['user_id'] ?? null;
$success_msg = false;
$error_msg = "";

// 1. Handle Profile Update
if ($user_id && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    
    $name    = trim($_POST['fullName'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $new_filename = null;

    // Handle Image Upload logic
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === 0) {
        $upload_dir = __DIR__ . '/../../uploads/profiles/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed)) {
            $new_filename = "renter_" . $user_id . "_" . time() . "." . $file_ext;
            if (!move_uploaded_file($_FILES['profile_img']['tmp_name'], $upload_dir . $new_filename)) {
                $new_filename = null;
            }
        }
    }

    // 2. Database Save Logic
    try {
        $conn->beginTransaction(); 

        if ($new_filename) {
            // Update BOTH text and image
            $sql = "INSERT INTO renter_details (user_id, renterName, contact, address, profile_img) 
                    VALUES (:uid, :name, :contact, :addr, :img)
                    ON DUPLICATE KEY UPDATE 
                    renterName = :name, contact = :contact, address = :addr, profile_img = :img";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':uid' => $user_id, ':name' => $name, ':contact' => $contact, ':addr' => $address, ':img' => $new_filename]);
        } else {
            // Update text ONLY
            $sql = "INSERT INTO renter_details (user_id, renterName, contact, address) 
                    VALUES (:uid, :name, :contact, :addr)
                    ON DUPLICATE KEY UPDATE 
                    renterName = :name, contact = :contact, address = :addr";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':uid' => $user_id, ':name' => $name, ':contact' => $contact, ':addr' => $address]);
        }
        
        $conn->commit();
        $success_msg = true;
    } catch (PDOException $e) {
        $conn->rollBack();
        $error_msg = "Update failed: " . $e->getMessage();
    }
}

// 3. Fetch Current Data (Refresh display after update)
try {
    if ($user_id) {
        $query = "SELECT u.email, r.renterName, r.contact, r.address, r.profile_img 
                  FROM users u 
                  LEFT JOIN renter_details r ON u.user_id = r.user_id 
                  WHERE u.user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Map database columns to display variables
        $display_name = $user_data['renterName'] ?? "";
        $email        = $user_data['email'] ?? "Not available";
        $contact_val  = $user_data['contact'] ?? "";
        $address_val  = $user_data['address'] ?? "";
        
        if (!empty($user_data['profile_img'])) {
            $image_path = "../../uploads/profiles/" . $user_data['profile_img'];
            if (file_exists(__DIR__ . "/" . $image_path)) {
                $profile_img = $image_path . "?v=" . time();
            } else {
                $profile_img = "user_avatar.jpg"; 
            }
        } else {
            $profile_img = "user_avatar.jpg";
        }
    } else {
        $display_name = "Guest User";
        $contact_val = "";
        $address_val = "";
        $profile_img = "user_avatar.jpg";
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Renter Profile - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_settings.css">
    <link rel="stylesheet" href="css/renter_profile.css">
    <style>
        .profile-avatar {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f0f0f0;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="logo1.png" alt="UEP" class="logo-top"></div>
        <div class="nav-icons">
            <a href="renter_profile.php" class="tab-link active"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
            <a href="renter_home.php" class="tab-link"><img class="icon" src="../icons/house.svg" alt="home"></a>
            <a href="renter_search.php" class="tab-link"><img class="icon" src="../icons/search.svg" alt="search"></a>
            <a href="renter_map.php" class="tab-link"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="renter_activity.php" class="tab-link"><img class="icon" src="../icons/check-check.svg" alt="activity"></a>
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>
        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <a href="<?= $user_id ? 'logout.php' : '../loginForms/renter/login.php' ?>" class="tab-link">
                <img class="icon" src="../icons/<?= $user_id ? 'log-out' : 'log-out' ?>.svg" alt="auth">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header"><h2 class="brand-title">UEP DORMDASH</h2></div>

        <div class="scroll-area flex-center">
            <div id="saveMessage" class="success-toast <?= $success_msg ? 'show' : '' ?>">Profile updated successfully!</div>
            <?php if ($error_msg): ?>
                <div class="error-toast show" style="background-color: #e74c3c; color: white; padding: 10px; border-radius: 5px; margin-bottom: 10px;"><?= $error_msg ?></div>
            <?php endif; ?>

            <div class="settings-container-large">
                <form method="POST" action="renter_profile.php" enctype="multipart/form-data">
                    <div class="settings-header profile-header-flex">
                        <div class="profile-main-info">
                            <h1>User Profile</h1>
                            <p>View and manage your personal identity and contact details</p>
                        </div>
                        <div class="profile-avatar-wrapper">
                            <div class="profile-avatar" id="avatarPreview" style="background-image: url('<?= $profile_img ?>');">
                                <?php if ($user_id): ?>
                                    <input type="file" name="profile_img" id="fileInput" style="display:none;" accept="image/*">
                                    <button type="button" class="edit-avatar-btn" onclick="document.getElementById('fileInput').click();">📷</button>
                                <?php endif; ?>
                            </div>
                            <h2 class="user-name-display"><?= htmlspecialchars($display_name ?: 'New User') ?></h2>
                        </div>
                    </div>

                    <div class="settings-grid-wide">
                        <div class="settings-card">
                            <h3 class="card-label">Identity</h3>
                            <div class="detail-item-box">
                                <label>Full Name</label>
                                <input type="text" name="fullName" value="<?= htmlspecialchars($display_name) ?>" class="profile-input" readonly required>
                            </div>
                            <div class="detail-item-box">
                                <label>Status</label>
                                <p class="status-tag"><?= $user_id ? 'Verified Renter' : 'Guest' ?></p>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h3 class="card-label">Contact Information</h3>
                            <div class="detail-item-box">
                                <label>Email Address</label>
                                <input type="email" value="<?= htmlspecialchars($email) ?>" class="profile-input" readonly disabled>
                            </div>
                            <div class="detail-item-box">
                                <label>Contact Number</label>
                                <input type="text" name="contact" value="<?= htmlspecialchars($contact_val) ?>" class="profile-input" readonly>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h3 class="card-label">Address</h3>
                            <div class="detail-item-box">
                                <label>Home Address</label>
                                <input type="text" name="address" value="<?= htmlspecialchars($address_val) ?>" class="profile-input" readonly>
                            </div>
                            
                            <div class="profile-actions-container">
                                <?php if ($user_id): ?>
                                    <button type="button" id="editBtn" class="btn-action">Edit Profile</button>
                                    <button type="submit" name="save_profile" id="saveBtn" class="btn-action save-highlight" style="display: none;">Save Changes</button>
                                <?php else: ?>
                                    <button type="button" class="btn-action" onclick="location.href='../loginForms/renter/login.php'">Log in to Edit</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const fileInput = document.getElementById('fileInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const inputs = document.querySelectorAll('.profile-input:not([disabled])');

        if (fileInput) {
            fileInput.onchange = function() {
                const [file] = this.files;
                if (file) {
                    avatarPreview.style.backgroundImage = `url(${URL.createObjectURL(file)})`;
                    saveBtn.style.display = 'block';
                }
            };
        }

        if (editBtn) {
            editBtn.addEventListener('click', () => {
                inputs.forEach(input => {
                    input.readOnly = false;
                    input.classList.add('editing');
                });
                editBtn.style.display = 'none';
                saveBtn.style.display = 'block';
                inputs[0].focus();
            });
        }

        const toast = document.getElementById('saveMessage');
        if(toast && toast.classList.contains('show')) {
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }
    </script>
</body>
</html>