<?php
session_start();
require_once "../../backend/dbconnection.php";

$current_page = basename($_SERVER['PHP_SELF']);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginForms/owner/ownerlogin.php");
    exit();
}

// --- SEARCH & FILTER LOGIC ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$zone = isset($_GET['zone']) ? $_GET['zone'] : 'All Zones';
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : 999999;
$amenities = isset($_GET['amenities']) ? $_GET['amenities'] : [];

// Base Query
$queryStr = "SELECT l.*, 
            (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) as main_image 
            FROM bh_listing l WHERE 1=1";
$params = [];

// Apply Search Text
if ($search !== '') {
    $queryStr .= " AND (l.title LIKE :search OR l.bh_description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

// Apply Zone Filter
if ($zone !== 'All Zones') {
    $queryStr .= " AND l.bh_address LIKE :zone";
    $params[':zone'] = '%' . $zone . '%';
}

// Apply Price Filter
$queryStr .= " AND l.monthly_rent >= :min AND l.monthly_rent <= :max";
$params[':min'] = $min_price;
$params[':max'] = $max_price;

// Apply Amenities Filter (Searching within the description or specific columns if you have them)
// For this example, we assume you might have columns for these or search the description
foreach ($amenities as $index => $amenity) {
    $key = ":amenity" . $index;
    $queryStr .= " AND (l.bh_description LIKE $key OR l.title LIKE $key)";
    $params[$key] = '%' . $amenity . '%';
}

$stmt = $conn->prepare($queryStr);
$stmt->execute($params);
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <a href="owner_profile.php" class="<?= ($current_page == 'owner_profile.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/circle-user-round.svg" alt="profile">
            </a>
            <a href="owner_home.php" class="<?= ($current_page == 'owner_home.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/house.svg" alt="home">
            </a>
            <a href="owner_search.php" class="<?= ($current_page == 'owner_search.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/search.svg" alt="search">
            </a>
            <a href="owner_map.php" class="<?= ($current_page == 'owner_map.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/map-pin-house.svg" alt="map">
            </a>
            <a href="owner_listings.php" class="<?= ($current_page == 'owner_listings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/pencil-line.svg" alt="listings">
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
            <a href="owner_logout.php">
                <img class="icon" src="../icons/log-out.svg" alt="logout">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH</h2>
        </div>

        <div class="content-wrapper">
            <form action="owner_search.php" method="GET">
                <div class="search-header">
                    <div>
                        <h1>Find Boarding Houses</h1>
                        <p class="listing-count">Explore other properties in the area</p>
                    </div>
                    <div class="main-search-bar">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or keyword...">
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>

                <div class="search-layout">
                    <aside class="filter-panel">
                        <h3>Filters</h3>

                        <div class="filter-group">
                            <label>Location</label>
                            <select name="zone">
                                <option <?= $zone == 'All Zones' ? 'selected' : '' ?>>All Zones</option>
                                <option <?= $zone == 'Zone 1' ? 'selected' : '' ?>>Zone 1</option>
                                <option <?= $zone == 'Zone 2' ? 'selected' : '' ?>>Zone 2</option>
                                <option <?= $zone == 'Zone 3' ? 'selected' : '' ?>>Zone 3</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Price Range (Monthly)</label>
                            <div class="price-inputs">
                                <input type="number" name="min_price" value="<?= $min_price ?>" placeholder="Min">
                                <input type="number" name="max_price" value="<?= $max_price == 999999 ? '' : $max_price ?>" placeholder="Max">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Amenities</label>
                            <label class="check-item"><input type="checkbox" name="amenities[]" value="WiFi" <?= in_array('WiFi', $amenities) ? 'checked' : '' ?>> Free Wi-Fi</label>
                            <label class="check-item"><input type="checkbox" name="amenities[]" value="CR" <?= in_array('CR', $amenities) ? 'checked' : '' ?>> Own CR</label>
                            <label class="check-item"><input type="checkbox" name="amenities[]" value="Aircon" <?= in_array('Aircon', $amenities) ? 'checked' : '' ?>> Aircon</label>
                        </div>

                        <button type="submit" class="btn-apply">Apply Filters</button>
                        <a href="owner_search.php" style="display:block; text-align:center; margin-top:10px; font-size:12px; color:gray;">Clear All</a>
                    </aside>

                    <main class="results-area">
                        <p class="results-count">Showing <?= count($listings) ?> results</p>

                        <div class="results-grid">
                            <?php if (count($listings) > 0): ?>
                                <?php foreach ($listings as $bh): ?>
                                    <div class="result-card">
                                        <div class="res-image" style="background-image: url('../../uploads/listings/<?= !empty($bh['main_image']) ? $bh['main_image'] : 'default.jpg' ?>');"></div>
                                        <div class="res-info">
                                            <h3><?= htmlspecialchars($bh['title']) ?></h3>
                                            <p class="res-price">₱<?= number_format($bh['monthly_rent'], 2) ?> / month</p>
                                            <p class="res-loc">📍 <?= htmlspecialchars($bh['bh_address']) ?></p>
                                            <div class="res-stats">
                                                <span class="availability"><?= $bh['available_rooms'] ?> Rooms left</span>
                                                <button type="button" class="btn-view" onclick="window.location.href='view_listing.php?id=<?= $bh['bh_id'] ?>'">View Info</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="grid-column: 1/-1; text-align:center; padding: 50px;">No boarding houses match your criteria.</p>
                            <?php endif; ?>
                        </div>
                    </main>
                </div>
            </form>
        </div>
    </div>

</body>
</html>