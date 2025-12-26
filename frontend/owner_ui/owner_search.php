<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Boarding Houses - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_search.css">
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
            <div class="search-header">
                <div>
                    <h1>Find Boarding Houses</h1>
                    <p class="listing-count">Explore other properties in the area</p>
                </div>
                <div class="main-search-bar">
                    <input type="text" placeholder="Search by name or keyword (e.g. Parcon)...">
                    <button class="btn-search">Search</button>
                </div>
            </div>

            <div class="search-layout">
                <aside class="filter-panel">
                    <h3>Filters</h3>

                    <div class="filter-group">
                        <label>Location</label>
                        <select>
                            <option>All Zones</option>
                            <option>UEP Zone 1</option>
                            <option>UEP Zone 2</option>
                            <option>UEP Zone 3</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Price Range (Monthly)</label>
                        <div class="price-inputs">
                            <input type="number" placeholder="Min">
                            <input type="number" placeholder="Max">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Amenities</label>
                        <label class="check-item"><input type="checkbox"> Free Wi-Fi</label>
                        <label class="check-item"><input type="checkbox"> Own CR</label>
                        <label class="check-item"><input type="checkbox"> Aircon</label>
                    </div>

                    <button class="btn-apply">Apply Filters</button>
                </aside>

                <main class="results-area">
                    <p class="results-count">Showing 12 results</p>

                    <div class="results-grid">
                        <div class="result-card">
                            <div class="res-image" style="background-image: url('room1.jpg');"></div>
                            <div class="res-info">
                                <h3>Seaside Hostel</h3>
                                <p class="res-price">₱1,500 / month</p>
                                <p class="res-loc">📍 UEP Zone 1</p>
                                <div class="res-stats">
                                    <span>⭐ 4.5</span>
                                    <span class="availability">3 Rooms left</span>
                                </div>
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="res-image" style="background-image: url('room2.jpg');"></div>
                            <div class="res-info">
                                <h3>University Homes</h3>
                                <p class="res-price">₱3,000 / month</p>
                                <p class="res-loc">📍 UEP Zone 2</p>
                                <div class="res-stats">
                                    <span>⭐ 4.8</span>
                                    <span class="availability full">Full</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

</body>

</html>