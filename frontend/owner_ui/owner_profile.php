<?php
session_start();
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

$user_id = $_SESSION['user_id'];

try {
    // We get the email from 'users' and name/contact from their latest listing
    $stmt = $conn->prepare("
        SELECT u.email, l.ownername, l.contact 
        FROM users u 
        LEFT JOIN bh_listing l ON u.user_id = l.user_id 
        WHERE u.user_id = :id 
        LIMIT 1
    ");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Owner Profile - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_profile.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="../res/logo1.png" alt="UEP" class="logo-top">
        </div>

        <div class="nav-icons">
            <a href="owner_profile.php" class="<?= ($current_page == 'owner_profile.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/circle-user-round.svg" alt="profile">
            </a>
            <a href="owner_home.php" class="<?= ($current_page == 'owner_home.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/house.svg" alt="home">
            </a>
            <a href="owner_search.php" class="<?= ($current_page == 'owner_search.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/search.svg" alt="search">
            </a>
            <a href="owner_map.php" class="<?= ($current_page == 'owner_map.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/map-pin-house.svg" alt="map">
            </a>
            <a href="owner_listings.php" class="<?= ($current_page == 'owner_listings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/pencil-line.svg" alt="listings">
            </a>
            <a href="owner_manage.php" class="<?= ($current_page == 'owner_manage.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/check-check.svg" alt="manage">
            </a>
            <a href="owner_settings.php" class="<?= ($current_page == 'owner_settings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/settings.svg" alt="settings">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH</h2>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>Profile Settings</h1>
                    <p class="listing-count">Logged in as: <strong><?= htmlspecialchars($user['email']); ?></strong></p>
                </div>
            </div>

            <form class="profile-form" action="update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="profile-layout">
                    <div class="profile-aside">
                        <div class="avatar-upload">
                            <div class="avatar-preview" style="background-image: url('<?= !empty($user['profile_image']) ? $user['profile_image'] : 'default_avatar.jpg' ?>');"></div>
                            <input type="file" name="profile_image" id="fileInput" style="display:none;">
                            <button type="button" class="btn-change-photo" onclick="document.getElementById('fileInput').click();">Change Photo</button>
                        </div>
                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? ''); ?>" placeholder="Enter username">
                        </div>
                    </div>

                    <div class="profile-main">
                        <div class="form-row">
                            <div class="input-group">
                                <label>Full Name</label>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? ''); ?>" placeholder="Full name">
                            </div>
                            <div class="input-group">
                                <label>Email Address</label>
                                <input type="email" value="<?= htmlspecialchars($user['email']); ?>" readonly style="background-color: #f5f5f5;">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact" value="<?= htmlspecialchars($user['contact_number'] ?? ''); ?>" placeholder="Phone number">
                            </div>
                            <div class="input-group">
                                <label>Business Name (Optional)</label>
                                <input type="text" name="business_name" value="<?= htmlspecialchars($user['business_name'] ?? ''); ?>" placeholder="Dormitory name">
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Home Address</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($user['home_address'] ?? ''); ?>" placeholder="Full address">
                        </div>

                        <div class="password-section">
                            <h3>Security</h3>
                            <div class="form-row">
                                <div class="input-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" placeholder="••••••••">
                                </div>
                                <div class="input-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" placeholder="Leave blank to keep current">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn-cancel">Reset Changes</button>
                            <button type="submit" class="btn-save">Save Information</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>