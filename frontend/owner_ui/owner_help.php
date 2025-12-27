<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_help.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo-section">
            <img src="../res/logo1.png" alt="UEP" class="logo-top">
        </div>

        <div class="nav-icons">
            <a href="owner_profile.php"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
            <a href="owner_home.php"><img class="icon" src="../icons/house.svg" alt="home"></a>
            <a href="owner_search.php"><img class="icon" src="../icons/search.svg" alt="search"></a>
            <a href="owner_map.php"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="owner_listings.php"><img class="icon" src="../icons/pencil-line.svg" alt="listings"></a>
            <a href="owner_manage.php"><img class="icon" src="../icons/check-check.svg" alt="manage"></a>
            <a href="owner_settings.php"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="owner_help.php" class="active"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <a href="owner_logout.php"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH | HELP CENTER</h2>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>How can we help?</h1>
                    <p class="listing-count">Browse FAQs or contact support</p>
                </div>
            </div>

            <div class="faq-section">
                <div class="faq-item">
                    <h3>How do I add a new listing?</h3>
                    <p>Go to the 'Listings' tab and click the 'Add New' button to fill out your property details.</p>
                </div>
                <div class="faq-item">
                    <h3>How do I manage my tenants?</h3>
                    <p>Use the 'Manage' tab to see current occupants, track payments, and update room availability.</p>
                </div>
            </div>

            <div class="contact-grid">
                <div class="contact-card">
                    <h3>Contact Admin</h3>
                    <p>Email: support@dormdash.uep.edu.ph</p>
                    <p>Phone: (055) 123-4567</p>
                </div>
                <div class="contact-card">
                    <h3>Office Hours</h3>
                    <p>Monday - Friday</p>
                    <p>8:00 AM - 5:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>