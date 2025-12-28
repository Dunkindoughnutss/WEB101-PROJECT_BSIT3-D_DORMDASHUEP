<?php
session_start();

/**
 * 1. IMMEDIATE LOGOUT LOGIC
 * No 'if confirm' check - clicking logout should always kill the session
 * to prevent account cross-over issues.
 */

// Clear session variables
$_SESSION = array();

// Clear Cookies to prevent the browser from remembering the old session ID
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session on the server
session_destroy();

/**
 * 2. REDIRECT
 * Sends the user back to the Renter login page
 */
header("Location: ../loginForms/renter/login.php?logged_out=1");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Logout - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_logout.css">
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
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <a href="renter_logout.php" class="tab-link active"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH</h2>
        </div>

        <div class="scroll-area flex-center">
            <div class="logout-container-large">
                <div class="logout-header">
                    <h1>Ready to Leave?</h1>
                    <p>Are you sure you want to log out of your renter account?</p>
                </div>

                <div class="logout-content">
                    <div class="logout-illustration">
                        <div class="door-icon" style="font-size: 80px;">🚪</div>
                    </div>
                    
                    <div class="logout-actions">
                        <button class="btn-stay" onclick="window.location.href='renter_home.php'">Stay Logged In</button>
                        <button class="btn-confirm" onclick="window.location.href='renter_logout.php?confirm=true'">Yes, Logout</button>
                    </div>
                </div>

                <div class="system-footer">
                    <p>Thank you for using UEP DormDash • See you again soon!</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>