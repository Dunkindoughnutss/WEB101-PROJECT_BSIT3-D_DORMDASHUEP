<?php
session_start(); 
require_once "../../backend/dbconnection.php";
$current_page = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION['user_id'] ?? null;

// Redirect if not logged in
if (!$user_id) {
    header("Location: ../loginForms/owner/owner_login.php");
    exit();
}

try {   
    $query = "
        SELECT l.*, COUNT(r.reservation_id) AS total_reservations,
        (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path
        FROM bh_listing l
        LEFT JOIN bh_reservations r ON l.bh_id = r.bh_id AND r.status = 'Approved'
        GROUP BY l.bh_id
        ORDER BY total_reservations DESC, l.bh_id ASC LIMIT 10";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $topListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $topListings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Top Performers - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <style>
        .rank-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ffc107;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            z-index: 10;
            border: 2px solid #fff;
        }
        .res-count {
            color: #28a745;
            font-weight: bold;
            font-size: 0.9rem;
            background: #e8f5e9;
            padding: 4px 8px;
            border-radius: 5px;
        }
        .listing-image {
            background-size: cover;
            background-position: center;
            height: 220px;
            width: 100%;
            border-bottom: 3px solid #f0f0f0;
        }
        .listings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }
        .listing-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .listing-card:hover {
            transform: translateY(-5px);
        }
        .listing-details {
            padding: 20px;
        }
        .details-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .details-top h3 { margin: 0; color: #333; font-size: 1.2rem; }
        .detail-row {
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #555;
            line-height: 1.4;
        }
        .no-data {
            text-align: center;
            padding: 50px;
            grid-column: 1 / -1;
            color: #888;
        }
    </style>
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
            <h2>UEP DORMDASH | Leaderboard</h2>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>Top Boarding Houses</h1>
                    <p class="listing-count">Current platform ranking based on total successful reservations</p>
                </div>
            </div>

            <div class="listings-grid">
                <?php if (!empty($topListings)): ?>
                    <?php foreach ($topListings as $index => $row): 
                        $img_filename = $row['image_path'] ?? 'default.jpg';
                        $img_path = "../../uploads/listings/" . $img_filename;
                        
                        // Path validation
                        if (!file_exists($img_path) || empty($row['image_path'])) {
                            $img_path = "../../uploads/listings/default.jpg";
                        }
                    ?>
                        <div class="listing-card" style="position: relative;">
                            <div class="rank-badge">#<?= $index + 1 ?></div>
                            <div class="listing-image"
                                 style="background-image: url('<?= htmlspecialchars($img_path) ?>?t=<?= time() ?>');">
                            </div>
                            <div class="listing-details">
                                <div class="details-top">
                                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                                    <span class="res-count"><?= $row['total_reservations'] ?> Approved</span>
                                </div>
                                <div class="detail-row">
                                    <strong>Address:</strong><br><?= htmlspecialchars($row['bh_address']); ?>
                                </div>
                                <div class="detail-row">
                                    <strong>Monthly Rent:</strong><br>₱<?= number_format($row['monthly_rent'], 2); ?>
                                </div>
                                <div class="detail-row">
                                    <strong>Platform Rating:</strong><br>⭐ 4.5 / 5.0 
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data">
                        <p>No listings are currently ranked. Check back later!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>