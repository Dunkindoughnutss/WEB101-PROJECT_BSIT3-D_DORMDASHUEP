<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Owner Settings - UEP DORMDASH</title>

    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_settings.css">
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

    </div>

    <div class="main-container">

        <div class="header">
            <h2>UEP DORMDASH</h2>
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
                        <input type="checkbox" checked>
                    </div>
                    <div class="setting-row">
                        <span>Email Updates</span>
                        <input type="checkbox">
                    </div>
                    <div class="setting-row">
                        <span>Dark Mode</span>
                        <input type="checkbox">
                    </div>
                </div>

                <div class="settings-section">
                    <h3>Privacy & Security</h3>
                    <button class="btn">Manage Login Devices</button>
                    <button class="btn danger">Clear Search History</button>
                </div>

                <div class="settings-section">
                    <h3>Help & Support</h3>
                    <button class="btn faq">FAQs</button>
                    <button class="btn" onclick="location.href='owner_help.html'">Contact Support</button>
                    <button class="btn">Report a Problem</button>
                </div>

                <div class="settings-section system-info">
                    <p><strong>UEP DormDash</strong></p>
                    <p>Version 1.0.0</p>
                    <p>© 2025 UEP DormDash</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelector(".faq").onclick = () => {
            alert(
                "Owner FAQs\n\n" +
                "• How do I post a new listing?\n" +
                "• How do I manage tenant requests?\n" +
                "• How can I edit my property location?\n" +
                "• How do I promote my boarding house?\n"
            );
        };
    </script>

</body>

</html>