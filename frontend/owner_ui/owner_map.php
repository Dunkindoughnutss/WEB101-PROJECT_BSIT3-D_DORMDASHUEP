<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Property Map - UEP DORMDASH</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_map.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo-section">
            <<img src="../res/logo1.png" alt="UEP" class="logo-top">
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
            <h2>UEP DORMDASH | MAP</h2>
        </div>

        <div class="content-wrapper map-view">
            <div class="content-header">
                <div>
                    <h1>Property Locations</h1>
                    <p class="listing-count">Tracking (2) Active Properties</p>
                </div>
            </div>

            <div class="map-container">
                <div id="map"></div>

                <div class="map-controls">
                    <div class="control-card">
                        <h3>Navigation 🛰️</h3>
                        <div class="btn-group">
                            <button class="map-btn" id="resetBtn" title="Reset View">🔄</button>
                            <button class="map-btn" id="trackBtn" title="Track Location">📍</button>
                        </div>
                        <small class="status-text" id="gps-status">GPS: Access Denied</small>
                    </div>

                    <div class="control-card">
                        <h3>Management</h3>
                        <button class="btn-add-pin" onclick="enableAddPinMode()">+ Pin New Property</button>
                        <small class="status-text">Click map after pressing</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="js/owner_map.js"></script>
</body>

</html>