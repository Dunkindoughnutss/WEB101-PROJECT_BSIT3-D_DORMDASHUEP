<?php
session_start(); // Keep this to check if someone is logged in for the sidebar
require_once __DIR__ . '/../../backend/dbconnection.php';

// --- SECURITY CHECK REMOVED ---
// Guests can now view this page without being redirected to login.php

try {
    // Fetch all listings to display on the map
    $stmt = $conn->query("SELECT bh_id, title, bh_address, monthly_rent FROM bh_listing");
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert PHP array to JSON for JavaScript use
    $map_data_json = json_encode($listings);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Property Map - UEP DORMDASH</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_map.css">
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
            <a href="renter_map.php" class="tab-link active"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="renter_activity.php" class="tab-link"><img class="icon" src="../icons/check-check.svg" alt="activity"></a>
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="renter_logout.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
            <?php else: ?>
                <a href="../loginForms/renter/login.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="login"></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH | MAP</h2>
        </div>

        <div class="scroll-area map-view">
            <div class="map-page-header">
                <h1>Explore Locations</h1>
                <p>Locate boarding houses around UEP Campus</p>
            </div>

            <div class="map-container-wrapper">
                <div id="map"></div>

                <div class="map-controls">
                    <div class="control-card">
                        <h3>Navigation 🛰️</h3>
                        <div class="btn-group">
                            <button class="map-btn" id="resetBtn" title="Reset View">🔄</button>
                            <button class="map-btn" id="trackBtn" title="Track My Location">📍</button>
                        </div>
                        <small class="status-text" id="gps-status">GPS: Ready</small>
                    </div>

                    <div class="control-card">
                        <h3>Tools</h3>
                        <button class="btn-add-pin" id="addPinBtn">+ Pin Interest Point</button>
                        <small class="status-text">Click map to mark a spot</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const bhLocations = <?= $map_data_json ?>;
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="js/renter_map.js"></script>
</body>
</html>