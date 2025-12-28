<?php
/**
 * START SESSION
 * We must start the session to be able to destroy it.
 */
session_start();

/**
 * LOGOUT LOGIC
 * Removed the 'if confirm' check to fix account switching issues.
 * This ensures the session is killed immediately when the logout link is clicked.
 */

// 1. Unset all session variables
$_SESSION = array();

// 2. Delete the session cookie from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the server-side session
session_destroy();

/**
 * 4. REDIRECT
 * The path is updated to match your URL structure:
 * http://localhost/New folder/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/loginForms/owner/owner_login.php
 * * If logout is in 'frontend/owner_ui/', we need to go up TWO levels to reach the project root.
 */
header("Location: ../../loginForms/owner/owner_login.php?logged_out=1");
exit();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_logout.css">
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
            <a href="owner_manage.php" class="<?= ($current_page == 'owner_manage.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/check-check.svg" alt="manage">
            </a>
            <a href="owner_settings.php" class="<?= ($current_page == 'owner_settings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/settings.svg" alt="settings">
            </a>
        </div>

        <div class="bottom-icons">
            <a href="owner_help.php" class="<?= ($current_page == 'owner_help.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/message-circle-question-mark.svg" alt="help">
            </a>
            <a href="owner_logout.php" class="<?= ($current_page == 'owner_logout.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/log-out.svg" alt="logout">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH</h2>
        </div>

        <div class="content-wrapper">
            <div class="logout-box">
                <div class="logout-illustration" style="font-size: 60px; margin-bottom: 20px;">🚪</div>
                <h1>Ready to Leave?</h1>
                <p>Are you sure you want to log out of your owner account?</p>

                <div class="logout-actions">
                    <button class="btn-stay" onclick="location.href='owner_home.php'">Stay Logged In</button>
                    <button class="btn-confirm" onclick="location.href='owner_logout.php?confirm=true'">Yes, Logout</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>