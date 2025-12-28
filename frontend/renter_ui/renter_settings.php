<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

/**
 * SECURITY CHECK REMOVED
 * Page is now public, but we identify if a user is logged in
 */
$user_id = $_SESSION['user_id'] ?? null;

// Default Settings (Guest/Default view)
$settings = [
    'push_notifications' => 1,
    'email_updates' => 1
];

if ($user_id) {
    try {
        // Fetch from Database
        $stmt = $conn->prepare("SELECT push_notifications, email_updates FROM user_settings WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);
        $db_settings = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($db_settings) {
            $settings = $db_settings;
        } else {
            // Create defaults if record doesn't exist for a logged-in user
            $insert = $conn->prepare("INSERT INTO user_settings (user_id, push_notifications, email_updates) VALUES (:uid, 1, 1)");
            $insert->execute([':uid' => $user_id]);
        }
    } catch (PDOException $e) {
        error_log("Settings error: " . $e->getMessage());
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_settings.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="logo.png" alt="UEP" class="logo-top">
        </div>
        
        <div class="nav-icons">
            <a href="renter_profile.php" class="tab-link"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
            <a href="renter_home.php" class="tab-link"><img class="icon" src="../icons/house.svg" alt="home"></a>
            <a href="renter_search.php" class="tab-link"><img class="icon" src="../icons/search.svg" alt="search"></a>
            <a href="renter_map.php" class="tab-link"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="renter_activity.php" class="tab-link"><img class="icon" src="../icons/check-check.svg" alt="activity"></a>
            <a href="renter_settings.php" class="tab-link active"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <?php if ($user_id): ?>
                <a href="logout.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
            <?php else: ?>
                <a href="../loginForms/renter/login.php" class="tab-link"><img class="icon" src="../icons/log-in.svg" alt="login"></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH</h2>
        </div>

        <div class="scroll-area flex-center">
            <div class="settings-container-large">
                <div class="settings-header">
                    <h1>Settings</h1>
                    <p>Manage your account preferences</p>
                    <?php if (!$user_id): ?>
                        <p style="color: #e67e22; font-size: 0.85rem; margin-top: 5px;"><strong>Guest Mode:</strong> Settings cannot be saved without an account.</p>
                    <?php endif; ?>
                </div>

                <div class="settings-grid-wide">
                    <div class="settings-card">
                        <h3 class="card-label">App Preferences</h3>
                        <div class="setting-row">
                            <span>Push Notifications</span>
                            <label class="switch">
                                <input type="checkbox" class="setting-toggle" data-column="push_notifications" 
                                    <?= $settings['push_notifications'] ? 'checked' : '' ?> 
                                    <?= !$user_id ? 'disabled' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <span>Email Updates</span>
                            <label class="switch">
                                <input type="checkbox" class="setting-toggle" data-column="email_updates" 
                                    <?= $settings['email_updates'] ? 'checked' : '' ?>
                                    <?= !$user_id ? 'disabled' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-label">Account Security</h3>
                        <?php if ($user_id): ?>
                            <button class="btn-action">Change Password</button>
                            <button class="btn-action danger">Clear Search History</button>
                        <?php else: ?>
                            <button class="btn-action" onclick="location.href='../loginForms/renter/login.php'">Log in to Secure Account</button>
                        <?php endif; ?>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-label">Support</h3>
                        <button class="btn-action faq-btn">Renter FAQs</button>
                        <button class="btn-action">Report a Problem</button>
                    </div>
                </div>

                <div class="system-footer">
                    <p>UEP DormDash v1.0.0 • <?= $user_id ? 'Verified Renter' : 'Guest' ?> Dashboard • © 2025</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.setting-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                // If user is guest, do nothing (should be disabled anyway)
                if (this.disabled) return;

                const column = this.dataset.column;
                const value = this.checked ? 1 : 0;

                fetch('update_preference.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `column=${column}&value=${value}`
                })
                .then(res => res.text())
                .then(data => console.log("Preference updated"));
            });
        });
    </script>
</body>
</html>