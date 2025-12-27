<?php
$current_page = basename($_SERVER['PHP_SELF']);

// 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    // Exit owner_ui, enter loginForms/owner/
    header("Location: ../loginForms/owner/ownerlogin.php");
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
//
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Owner Settings - UEP DORMDASH</title>

    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_settings.css">
    <style>
        /* DASHBOARD TOGGLE SWITCHES */
        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 22px;
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #e2e8f0;
            transition: .3s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px; width: 16px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        input:checked + .slider { background-color: #f39c12; }
        input:checked + .slider:before { transform: translateX(22px); }

        /* BUTTON POLISH */
        .settings-section .btn {
            width: 100%;
            text-align: left;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #edf2f7;
            background: #fff;
            color: #4a5568;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
        }

        .settings-section .btn:hover {
            background: #f8fafc;
            border-color: #cbd5e0;
            transform: translateX(3px);
        }

        .btn.danger:hover {
            background: #fff5f5;
            color: #c53030;
            border-color: #feb2b2;
        }

        /* LOGOUT BUTTON */
        .logout-container {
            margin-top: 20px;
            padding-top: 10px;
        }

        .btn-logout {
            background: #fff;
            color: #e53e3e;
            border: 2px solid #fed7d7;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #e53e3e;
            color: #fff;
            border-color: #e53e3e;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="../res/logo1.png" alt="UEP" class="logo-top">
        </div>

        <div class="nav-icons">
            <a href="owner_profile.php" class="<?php echo ($current_page == 'owner_profile.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/circle-user-round.svg" alt="profile">
            </a>
            <a href="owner_home.php" class="<?php echo ($current_page == 'owner_home.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/house.svg" alt="home">
            </a>
            <a href="owner_search.php" class="<?php echo ($current_page == 'owner_search.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/search.svg" alt="search">
            </a>
            <a href="owner_map.php" class="<?php echo ($current_page == 'owner_map.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/map-pin-house.svg" alt="map">
            </a>
            <a href="owner_listings.php" class="<?php echo ($current_page == 'owner_listings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/pencil-line.svg" alt="listings">
            </a>
            <a href="owner_manage.php" class="<?php echo ($current_page == 'owner_manage.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/check-check.svg" alt="manage">
            </a>
            <a href="owner_settings.php" class="<?php echo ($current_page == 'owner_settings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/settings.svg" alt="settings">
            </a>
        </div>

        <div class="bottom-icons">
            <a href="owner_help.php" class="<?= ($current_page == 'owner_help.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/message-circle-question-mark.svg">
            </a>
            <a href="logout.php">
                <img class="icon" src="../icons/log-out.svg">
            </a>
        </div>


    </div>

    <div class="main-container">

        <div class="header">
            <h2>UEP DORMDASH</span></h2>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>Settings</h1>
                    <p class="listing-count">Manage your dashboard preferences</p>
                </div>
            </div>

            <div class="settings-grid">

                <div class="settings-section">
                    <h3>App Preferences</h3>
                    <div class="setting-row">
                        <span>Enable Notifications</span>
                        <label class="switch">
                            <input type="checkbox" checked onchange="savePreference('Notifications')">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="setting-row">
                        <span>Email Updates</span>
                        <label class="switch">
                            <input type="checkbox" onchange="savePreference('Email Updates')">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="setting-row">
                        <span>Dark Mode</span>
                        <label class="switch">
                            <input type="checkbox" onchange="toggleDarkMode(this)">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>Privacy & Security</h3>
                    <button class="btn" onclick="alert('Redirecting to device management...')">Manage Login Devices</button>
                    <button class="btn danger" onclick="confirmClearHistory()">Clear Search History</button>
                </div>

                <div class="settings-section">
                    <h3>Help & Support</h3>
                    <button class="btn faq">FAQs</button>
                    <button class="btn" onclick="location.href='owner_help.html'">Contact Support</button>
                    <button class="btn" onclick="alert('Opening report ticket...')">Report a Problem</button>
                </div>

                <div class="settings-section system-info">
                    <h3>System</h3>
                    <div class="logout-container">
                        <button class="btn-logout" onclick="handleLogout()">Logout</button>
                    </div>
                    <div style="margin-top: 20px; color: #a0aec0; font-size: 0.85rem;">
                        <p><strong>UEP DormDash</strong> v1.0.0</p>
                        <p>© 2025 UEP DormDash</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // FAQ Functionality
        document.querySelector(".faq").onclick = () => {
            alert(
                "Owner FAQs\n\n" +
                "• How do I post a new listing?\n" +
                "• How do I manage tenant requests?\n" +
                "• How can I edit my property location?\n" +
                "• How do I promote my boarding house?\n"
            );
        };

        // Real-time Preference Feedback
        function savePreference(type) {
            console.log(type + " setting updated.");
            // Add AJAX here later to save to DB
        }

        // Dark Mode Toggle Placeholder
        function toggleDarkMode(element) {
            if(element.checked) {
                alert("Dark Mode currently unavailable.");
            }
        }

        //  Clear History Confirmation
        function confirmClearHistory() {
            if(confirm("Are you sure you want to clear your search history? This cannot be undone.")) {
                alert("Search history cleared.");
            }
        }

        // Functional Logout
        function handleLogout() {
            if(confirm("Log out of UEP DormDash?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>

</body>
</html>