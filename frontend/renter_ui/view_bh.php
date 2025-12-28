<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php'; 

if (!isset($_GET['id'])) {
    header("Location: renter_home.php");
    exit();
}

$bh_id = $_GET['id'];

try {
    // 1. Updated query to include the image subquery from bh_images
    $stmt = $conn->prepare("
        SELECT l.*, 
        (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path 
        FROM bh_listing l 
        WHERE l.bh_id = ?
    ");
    $stmt->execute([$bh_id]);
    $bh = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bh) {
        die("Boarding house not found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// 2. Helper function logic for image path validation
$img_base = "../../uploads/listings/";
$img_filename = $bh['image_path'] ?? 'default.jpg';
$img_full_path = $img_base . $img_filename;

// Check if file exists physically, otherwise use default
if (!file_exists(__DIR__ . "/" . $img_full_path) || empty($bh['image_path'])) {
    $display_img = "../../uploads/listings/default.jpg";
} else {
    $display_img = $img_full_path;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($bh['title']) ?> - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/view_bh.css">
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
            <a href="renter_logout.php" class="tab-link"><img class="icon" src="../icons/log-out.svg" alt="logout"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header">
            <h2 class="brand-title">UEP DORMDASH</h2>
        </div>

        <div class="scroll-area">
            <a href="renter_home.php" class="back-btn">← Back to Listings</a>

            <div class="details-container">
                <div class="hero-img" style="background-image: url('<?= htmlspecialchars($display_img) ?>?v=<?= time() ?>');"></div>

                <div class="info-grid">
                    <div class="details-left">
                        <h1><?= htmlspecialchars($bh['title']) ?></h1>
                        <p class="location-text">📍 <?= htmlspecialchars($bh['bh_address']) ?></p>
                        
                        <div class="content-block">
                            <h3>Description</h3>
                            <p class="description-text"><?= nl2br(htmlspecialchars($bh['bh_description'])) ?></p>
                        </div>

                        <div class="content-block">
                            <h3>Amenities</h3>
                            <div class="amenities-list">
                                <?php 
                                $amenities = explode(',', $bh['amenities']);
                                foreach($amenities as $item) {
                                    if(!empty(trim($item))) {
                                        echo '<span class="amenity-badge">'.trim(htmlspecialchars($item)).'</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="details-right">
                        <div class="sticky-card">
                            <div class="price-text">₱<?= number_format($bh['monthly_rent']) ?> <small>/mo</small></div>
                            
                            <div class="meta-info">
                                <div class="meta-item"><strong>Room Type:</strong> <span><?= htmlspecialchars($bh['roomtype']) ?></span></div>
                                <div class="meta-item"><strong>Gender:</strong> <span><?= htmlspecialchars($bh['preferred_gender']) ?></span></div>
                                <div class="meta-item"><strong>Policy:</strong> <span><?= htmlspecialchars($bh['curfew_policy']) ?></span></div>
                                <div class="meta-item"><strong>Available:</strong> <span><?= htmlspecialchars($bh['available_rooms']) ?> slots</span></div>
                            </div>

                            <div class="owner-contact-box">
                                <p class="owner-label">Property Owner</p>
                                <p class="owner-name"><?= htmlspecialchars($bh['ownername']) ?></p>
                                <p class="owner-phone"><?= htmlspecialchars($bh['contact']) ?></p>
                            </div>

                            <form action="booking_process.php" method="POST">
                                <input type="hidden" name="bh_id" value="<?= $bh['bh_id'] ?>">
                                <button type="submit" class="rent-now-btn">Request to Rent</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>