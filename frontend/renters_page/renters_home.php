<?php
require_once '../../backend/dbconnection.php'; 

try {
    // Fetch all columns (*) so the description and other details are available for the modal
    $stmt1 = $conn->prepare("SELECT * FROM bh_listing LIMIT 3");
    $stmt1->execute();
    $recommended = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT * FROM bh_listing ORDER BY created_at DESC LIMIT 3");
    $stmt2->execute();
    $new_listings = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $stmt3 = $conn->prepare("SELECT * FROM bh_listing ORDER BY title ASC");
    $stmt3->execute();
    $near_you = $stmt3->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UEP DormDash | Home</title>
    <link rel="stylesheet" href="css/renters_home.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="sidebar">
        <a href="renters_home.php" class="nav-link active">
            <img class="icon" src="../res/icons/icons8-home-96.png" alt="home">
        </a>
        <a href="renters_map.php" class="nav-link">
            <img class="icon" src="../res/icons/icons8-map-64.png" alt="map">
        </a>
        <a href="renters_activity.php" class="nav-link">
            <img class="icon" src="../res/icons/icons8-form-64.png" alt="activity">
        </a>
        <a href="renters_settings.php" class="nav-link">
            <img class="icon" src="../res/icons/icons8-settings-64.png" alt="settings">
        </a>
        <a href="renters_profile.php" class="nav-link">
            <img class="icon" src="../res/icons/icons8-profile-48.png" alt="profile">
        </a>
        
        <div class="logout-section">
            <a href="logout.php" class="nav-link">
                <img class="icon" src="../res/icons/icons8-logout-rounded-50.png" alt="logout">
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>UEP DORMDASH</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search for a location (e.g. Zone 1)...">
            </div>
        </div>

        <div class="section-title">Recommended for You</div>
        <div class="card-container">
            <?php foreach ($recommended as $bh): ?>
                <div class="card" style="cursor: pointer;" onclick='openBHModal(<?= json_encode($bh) ?>)'>
                    <div class="label">★ 4.0</div>
                    <div class="info-overlay">
                        <strong><?= htmlspecialchars($bh['title']) ?></strong><br>
                        ₱<?= number_format($bh['monthly_rent'], 2) ?>/mo<br>
                        <small><?= htmlspecialchars($bh['bh_address']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="section-title">New Listings</div>
        <div class="card-container">
            <?php foreach ($new_listings as $bh): ?>
                <div class="card" style="cursor: pointer;" onclick='openBHModal(<?= json_encode($bh) ?>)'>
                    <div class="label" style="background:#2ecc71; color:white;">NEW</div>
                    <div class="info-overlay">
                        <strong><?= htmlspecialchars($bh['title']) ?></strong><br>
                        ₱<?= number_format($bh['monthly_rent'], 2) ?>/mo<br>
                        <small><?= htmlspecialchars($bh['roomtype']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="section-title">Near You</div>
        <div class="near-you-container">
            <?php foreach ($near_you as $bh): ?>
                <div class="near-card" style="cursor: pointer;" onclick='openBHModal(<?= json_encode($bh) ?>)'>
                    <strong><?= htmlspecialchars($bh['title']) ?></strong><br>
                    <small style="color: #77b7ff; font-weight: bold;">₱<?= number_format($bh['monthly_rent'], 0) ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <footer class="main-footer">
            <span>Copyright &copy; UEP DORM DASH 2025</span>
        </footer>
    </div>
</body>
</html>