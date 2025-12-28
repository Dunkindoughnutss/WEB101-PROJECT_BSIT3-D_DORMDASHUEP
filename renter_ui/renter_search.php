<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

// 1. Sanitize and Get filter inputs
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$location = isset($_GET['location']) ? $_GET['location'] : 'All Zones';
$min_price = (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) ? $_GET['min_price'] : 0;
$max_price = (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) ? $_GET['max_price'] : 100000;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

try {
    // 2. Build Base SQL
    $sql = "SELECT * FROM bh_listing WHERE (title LIKE :search OR bh_description LIKE :search)";
    
    if ($location !== 'All Zones') {
        $sql .= " AND bh_address LIKE :location";
    }
    
    $sql .= " AND monthly_rent BETWEEN :min AND :max";
    
    // 3. Apply Dynamic Sorting
    switch ($sort) {
        case 'price_low':
            $sql .= " ORDER BY monthly_rent ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY monthly_rent DESC";
            break;
        case 'oldest':
            $sql .= " ORDER BY created_at ASC";
            break;
        default:
            $sql .= " ORDER BY created_at DESC";
            break;
    }

    $stmt = $conn->prepare($sql);
    
    // 4. Bind Parameters
    $search_term = "%$search_query%";
    $stmt->bindParam(':search', $search_term);
    $stmt->bindParam(':min', $min_price);
    $stmt->bindParam(':max', $max_price);
    
    if ($location !== 'All Zones') {
        $loc_term = "%$location%";
        $stmt->bindParam(':location', $loc_term);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($results);

} catch (PDOException $e) {
    die("Search Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_search.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="../../frontend/renter_ui/logo.png" alt="UEP" class="logo-top">
        </div>
        
        <div class="nav-icons">
            <a href="renter_profile.php" class="tab-link"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
            <a href="renter_home.php" class="tab-link"><img class="icon" src="../icons/house.svg" alt="home"></a>
            <a href="renter_search.php" class="tab-link active"><img class="icon" src="../icons/search.svg" alt="search"></a>
            <a href="renter_map.php" class="tab-link"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="renter_activity.php" class="tab-link"><img class="icon" src="../icons/check-check.svg" alt="activity"></a>
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <a href="renter_logout.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH</h2>
        </div>

        <div class="scroll-area">
            <form action="renter_search.php" method="GET" id="searchForm">
                <div class="search-header-box">
                    <h1>Find your next home</h1>
                    <div class="main-search-bar">
                        <input type="text" name="query" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search by name or keyword...">
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>

                <div class="search-layout">
                    <aside class="filter-panel">
                        <h3>Filters</h3>
                        
                        <div class="filter-group">
                            <label>Location</label>
                            <select name="location">
                                <option value="All Zones" <?= $location == 'All Zones' ? 'selected' : '' ?>>All Zones</option>
                                <option value="Zone 1" <?= $location == 'Zone 1' ? 'selected' : '' ?>>Zone 1</option>
                                <option value="Zone 2" <?= $location == 'Zone 2' ? 'selected' : '' ?>>Zone 2</option>
                                <option value="Zone 3" <?= $location == 'Zone 3' ? 'selected' : '' ?>>Zone 3</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Price Range (Monthly)</label>
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="Min" value="<?= $min_price > 0 ? $min_price : '' ?>">
                                <input type="number" name="max_price" placeholder="Max" value="<?= $max_price < 100000 ? $max_price : '' ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn-apply">Apply Filters</button>
                        <a href="renter_search.php" class="clear-link" style="display:block; text-align:center; margin-top:10px; color:#666; font-size:0.8rem; text-decoration:none;">Clear Filters</a>
                    </aside>

                    <main class="results-area">
                        <div class="results-top-bar">
                            <p class="results-count">Showing <?= $count ?> results</p>
                            <select name="sort" class="sort-select" onchange="document.getElementById('searchForm').submit();">
                                <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Sort by: Newest</option>
                                <option value="oldest" <?= $sort == 'oldest' ? 'selected' : '' ?>>Sort by: Oldest</option>
                                <option value="price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                            </select>
                        </div>
            </form> <div class="results-grid">
                            <?php if($count > 0): ?>
                                <?php foreach($results as $row): ?>
                                <div class="result-card" onclick="location.href='view_bh.php?id=<?= $row['bh_id'] ?>'" style="cursor:pointer;">
                                    <div class="res-image" style="background-image: url('../../res/room_placeholder.jpg');">
                                        <?php if(isset($row['created_at']) && (strtotime($row['created_at']) > strtotime('-7 days'))): ?>
                                            <span class="res-badge">New</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="res-info">
                                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                                        <p class="res-price">₱<?= number_format($row['monthly_rent']) ?> / month</p>
                                        <p class="res-loc">📍 <?= htmlspecialchars($row['bh_address']) ?></p>
                                        <div class="res-stats">
                                            <span>⭐ 4.0</span>
                                            <span class="availability <?= ($row['available_rooms'] ?? 0) == 0 ? 'full' : '' ?>">
                                                <?= ($row['available_rooms'] ?? 0) > 0 ? $row['available_rooms'] . " Rooms left" : "Full" ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="grid-column: span 3; text-align:center; padding: 50px; color:white; background: rgba(255,255,255,0.1); border-radius: 20px;">
                                    <h3>No results found</h3>
                                    <p>Try searching for something else or clearing your filters.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </main>
                </div>
        </div>
    </div>
</body>
</html>