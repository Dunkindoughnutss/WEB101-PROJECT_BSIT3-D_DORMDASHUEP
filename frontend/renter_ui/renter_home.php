<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php'; 

try {
    // 1. Updated query to JOIN bh_images to get the main thumbnail for Recommended
    $stmt_rec = $conn->query("
        SELECT l.*, (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path 
        FROM bh_listing l ORDER BY RAND() LIMIT 5");
    $recommended = $stmt_rec->fetchAll(PDO::FETCH_ASSOC);

    // 2. Updated query for Near You
    $stmt_near = $conn->query("
        SELECT l.*, (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path 
        FROM bh_listing l LIMIT 4");
    $near_you = $stmt_near->fetchAll(PDO::FETCH_ASSOC);

    // 3. Updated query for New Listings
    $stmt_new = $conn->query("
        SELECT l.*, (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path 
        FROM bh_listing l ORDER BY created_at DESC LIMIT 5");
    $new_listings = $stmt_new->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Function to handle the image path validation
function getListingImage($path) {
    $img_base = "../../uploads/listings/";
    if (!empty($path) && file_exists(__DIR__ . "/" . $img_base . $path)) {
        return $img_base . $path;
    }
    return "../../uploads/listings/default.jpg"; // Your fallback image
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Renter Home - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="logo1.png" alt="UEP" class="logo-top">
        </div>
        
        <div class="nav-icons">
            <a href="renter_profile.php" class="tab-link"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
            <a href="renter_home.php" class="tab-link active"><img class="icon" src="../icons/house.svg" alt="home"></a>
            <a href="renter_search.php" class="tab-link"><img class="icon" src="../icons/search.svg" alt="search"></a>
            <a href="renter_map.php" class="tab-link"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
            <a href="renter_activity.php" class="tab-link"><img class="icon" src="../icons/check-check.svg" alt="activity"></a>
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
        </div>

        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            <a href="logout.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH</h2>
            <form action="renter_search.php" method="GET" class="search-container">
                <input type="text" name="query" placeholder="Search by Zone or Name..." class="search-input">
                <span class="search-icon">🔍</span>
            </form>
        </div>

        <div class="scroll-area">
            
            <section class="section">
                <h3 class="section-label">Recommended Boarding Houses</h3>
                <div class="horizontal-scroll">
                    <?php foreach ($recommended as $bh): 
                        $img = getListingImage($bh['image_path']);
                    ?>
                    <div class="card featured" onclick="location.href='view_bh.php?id=<?= $bh['bh_id'] ?>'" 
                         style="background-image: linear-gradient(to top, rgba(0,0,0,0.8), transparent), url('<?= $img ?>'); cursor: pointer;">
                        <span class="badge rating">★ 4.0</span>
                        <div class="card-text">
                            <p class="card-price">₱ <?= number_format($bh['monthly_rent']) ?>/Month</p>
                            <h4 class="card-title"><?= htmlspecialchars($bh['title']) ?></h4>
                            <p class="card-sub"><?= htmlspecialchars($bh['bh_address']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="section">
                <h3 class="section-label">Near You</h3>
                <div class="near-grid">
                    <?php foreach ($near_you as $bh): 
                        $img = getListingImage($bh['image_path']);
                    ?>
                    <div class="mini-card" onclick="location.href='view_bh.php?id=<?= $bh['bh_id'] ?>'" style="cursor: pointer;">
                        <div class="mini-thumb" style="background-image: url('<?= $img ?>');"></div>
                        <div class="mini-info">
                            <h5><?= htmlspecialchars($bh['title']) ?></h5>
                            <p>PHP <?= number_format($bh['monthly_rent'], 2) ?>/month</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="section">
                <h3 class="section-label">New Listings</h3>
                <div class="horizontal-scroll">
                    <?php foreach ($new_listings as $bh): 
                        $img = getListingImage($bh['image_path']);
                    ?>
                    <div class="card featured" onclick="location.href='view_bh.php?id=<?= $bh['bh_id'] ?>'" 
                         style="background-image: linear-gradient(to top, rgba(0,0,0,0.8), transparent), url('<?= $img ?>'); cursor: pointer;">
                        <span class="badge rating">★ NEW</span>
                        <div class="card-text">
                            <p class="card-price">₱ <?= number_format($bh['monthly_rent']) ?>/Month</p>
                            <h4 class="card-title"><?= htmlspecialchars($bh['title']) ?></h4>
                            <p class="card-sub"><?= htmlspecialchars($bh['bh_address']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>