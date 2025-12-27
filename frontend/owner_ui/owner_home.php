<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

// IMAGE DISPLAY TEST

$test_path = "../../uploads/listings/";
if (is_dir($test_path)) {
    echo "✅ PHP can see the folder.<br>";
    $files = glob($test_path . "*");
    echo "Found " . count($files) . " files in the folder.";
} else {
    echo "❌ PHP CANNOT find the folder at: " . realpath($test_path);
}

// ====================\\\

// Active sidebar
$current_page = basename($_SERVER['PHP_SELF']);

// TEMP: replace with $_SESSION['user_id']
$user_id = 1;

try {
    // We join bh_listing with bh_images to get the first image for each listing
    $stmt = $conn->prepare("
        SELECT l.*, 
               (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path 
        FROM bh_listing l 
        WHERE l.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalListings = count($listings);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Owner Dashboard - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo-section">
            <img src="../res/logo1.png" alt="UEP" class="logo-top">
        </div>

        <div class="nav-icons">
            <a href="owner_profile.php" class="<?= ($current_page == 'owner_profile.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/circle-user-round.svg">
            </a>

            <a href="owner_home.php" class="<?= ($current_page == 'owner_home.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/house.svg">
            </a>

            <a href="owner_search.php" class="<?= ($current_page == 'owner_search.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/search.svg">
            </a>

            <a href="owner_map.php" class="<?= ($current_page == 'owner_map.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/map-pin-house.svg">
            </a>

            <a href="owner_listings.php" class="<?= ($current_page == 'owner_listings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/pencil-line.svg">
            </a>

            <a href="owner_manage.php" class="<?= ($current_page == 'owner_manage.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/check-check.svg">
            </a>

            <a href="owner_settings.php" class="<?= ($current_page == 'owner_settings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/settings.svg">
            </a>
        </div>

        <div class="bottom-icons">
            <a href="owner_help.php" class="<?= ($current_page == 'owner_help.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/message-circle-question-mark.svg">
            </a>
            <a href="javascript:void(0);" onclick="handleLogout();">
                <img class="icon" src="../icons/log-out.svg" alt="Logout">
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH</h2>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>Listings</h1>
                    <p class="listing-count">No. (<?= $totalListings ?>) of Listings</p>
                </div>
            </div>


            <!-- LISTING GRID -->

            <div class="listings-grid">

                <?php if ($totalListings > 0): ?>
                    <?php foreach ($listings as $row): ?>
                        <div class="listing-card">

                            <div class="listing-image"
                                style="background-image: url('../../uploads/listings/<?= basename(htmlspecialchars($row['image_path'])); ?>');">
                            </div>

                            <div class="listing-details">
                                <div class="details-top">
                                    <h3><?= htmlspecialchars($row['title']); ?></h3>

                                    <div class="actions">
                                        <a href="edit_listing.php?id=<?= $row['bh_id']; ?>">
                                            <img src="edit_small.png" alt="edit">
                                        </a>
                                        <a href="delete_listing.php?id=<?= $row['bh_id']; ?>"
                                            onclick="return confirm('Are you sure?')">
                                            <img src="delete_small.png" alt="delete">
                                        </a>
                                    </div>
                                </div>

                                <p><strong>Description</strong><br>
                                    <?= htmlspecialchars($row['bh_description']); ?>
                                </p>

                                <p><strong>Address</strong><br>
                                    <?= htmlspecialchars($row['bh_address']); ?>
                                </p>

                                <p><strong>Contacts:</strong><br>
                                    <?= htmlspecialchars($row['contact']); ?>
                                </p>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No listings found.</p>
                <?php endif; ?>

            </div>

        </div>
    </div>

<script>
        // Logout Function
        function handleLogout() {
            if(confirm("Log out of UEP DormDash?")) {
                window.location.href = 'logout.php';
            }
        }
</script>

</body>
</html>