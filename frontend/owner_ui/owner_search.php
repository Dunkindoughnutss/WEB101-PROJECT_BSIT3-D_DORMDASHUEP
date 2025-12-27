<?php
session_start();
// 1. Database Connection
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

$current_page = basename($_SERVER['PHP_SELF']);

// 2. Capture Search and Filter inputs from the URL
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$location_filter = isset($_GET['location']) ? $_GET['location'] : 'All Zones';
$min_price = (isset($_GET['min_price']) && $_GET['min_price'] !== '') ? $_GET['min_price'] : 0;
$max_price = (isset($_GET['max_price']) && $_GET['max_price'] !== '') ? $_GET['max_price'] : 999999;

try {
    // 3. Prepare the Base Query
    $sql = "SELECT l.*, 
            (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path 
            FROM bh_listing l 
            WHERE (l.title LIKE :query OR l.bh_description LIKE :query)";

    // Add Location filter logic
    if ($location_filter !== 'All Zones') {
        $sql .= " AND l.bh_address LIKE :location";
    }

    // Add Price filter logic
    $sql .= " AND l.monthly_rent BETWEEN :min AND :max";

    $stmt = $conn->prepare($sql);
    
    // Bind values
    $stmt->bindValue(':query', '%' . $search_query . '%');
    $stmt->bindValue(':min', $min_price);
    $stmt->bindValue(':max', $max_price);
    if ($location_filter !== 'All Zones') {
        $stmt->bindValue(':location', '%' . $location_filter . '%');
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalFound = count($results);

} catch (PDOException $e) {
    error_log($e->getMessage());
    $totalFound = 0;
}
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
            <form action="owner_search.php" method="GET">
                <div class="search-header">
                    <div>
                        <h1>Find Boarding Houses</h1>
                        <p class="listing-count">Explore other properties in the area</p>
                    </div>
                    <div class="main-search-bar">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by name or keyword...">
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>

                <div class="search-layout">
                    <aside class="filter-panel">
                        <h3>Filters</h3>

                        <div class="filter-group">
                            <label>Location</label>
                            <select name="location">
                                <option value="All Zones" <?php if($location_filter == 'All Zones') echo 'selected'; ?>>All Zones</option>
                                <option value="Zone 1" <?php if($location_filter == 'Zone 1') echo 'selected'; ?>>UEP Zone 1</option>
                                <option value="Zone 2" <?php if($location_filter == 'Zone 2') echo 'selected'; ?>>UEP Zone 2</option>
                                <option value="Zone 3" <?php if($location_filter == 'Zone 3') echo 'selected'; ?>>UEP Zone 3</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Price Range (Monthly)</label>
                            <div class="price-inputs">
                                <input type="number" name="min_price" value="<?php echo $min_price; ?>" placeholder="Min">
                                <input type="number" name="max_price" value="<?php echo ($max_price == 999999) ? '' : $max_price; ?>" placeholder="Max">
                            </div>
                        </div>

                        <button type="submit" class="btn-apply">Apply Filters</button>
                    </aside>

                    <main class="results-area">
                        <p class="results-count">Showing <?php echo $totalFound; ?> results</p>

                        <div class="results-grid">
                            <?php if ($totalFound > 0): ?>
                                <?php foreach ($results as $row): ?>
                                    <div class="result-card">
                                        <div class="res-image" style="background-image: url('../../uploads/listings/<?php echo basename($row['image_path'] ?? 'default.jpg'); ?>');"></div>
                                        <div class="res-info">
                                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                            <p class="res-price">₱<?php echo number_format($row['monthly_rent'], 2); ?> / month</p>
                                            <p class="res-loc">📍 <?php echo htmlspecialchars($row['bh_address']); ?></p>
                                            <div class="res-stats">
                                                <span>⭐ <?php echo htmlspecialchars($row['roomtype']); ?></span>
                                                <span class="availability <?php echo ($row['available_rooms'] <= 0) ? 'full' : ''; ?>">
                                                    <?php echo ($row['available_rooms'] <= 0) ? 'Full' : $row['available_rooms'] . ' Rooms left'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="padding: 20px; color: #666;">No results found matching your criteria.</div>
                            <?php endif; ?>
                        </div>
                    </main>
                </div>
            </form>
        </div>
    </div>

</body>
</html>