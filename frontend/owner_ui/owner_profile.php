<?php
$current_page = basename($_SERVER['PHP_SELF']);
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
                    <h1>Profile Settings</h1>
                    <p class="listing-count">Manage your account and business details</p>
                </div>
            </div>

            <form class="profile-form">
                <div class="profile-layout">
                    <div class="profile-aside">
                        <div class="avatar-upload">
                            <div class="avatar-preview" style="background-image: url('default_avatar.jpg');"></div>
                            <button type="button" class="btn-change-photo">Change Photo</button>
                        </div>
                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" value="Owner_User2024" placeholder="Enter username">
                        </div>
                    </div>

                    <div class="profile-main">
                        <div class="form-row">
                            <div class="input-group">
                                <label>Full Name</label>
                                <input type="text" value="Maria Clara" placeholder="Full name">
                            </div>
                            <div class="input-group">
                                <label>Email Address</label>
                                <input type="email" value="mclara@email.com" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label>Contact Number</label>
                                <input type="text" value="09123456789" placeholder="Phone number">
                            </div>
                            <div class="input-group">
                                <label>Business Name (Optional)</label>
                                <input type="text" value="Clara's Housing" placeholder="Dormitory name">
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Home Address</label>
                            <input type="text" value="UEP Zone 1, Catarman" placeholder="Full address">
                        </div>

                        <div class="password-section">
                            <h3>Security</h3>
                            <div class="form-row">
                                <div class="input-group">
                                    <label>Current Password</label>
                                    <input type="password" placeholder="••••••••">
                                </div>
                                <div class="input-group">
                                    <label>New Password</label>
                                    <input type="password" placeholder="Leave blank to keep current">
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