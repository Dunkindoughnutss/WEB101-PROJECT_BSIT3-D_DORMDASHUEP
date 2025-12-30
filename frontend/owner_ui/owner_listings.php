<?php
session_start();
include __DIR__ . '/../../backend/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginForms/owner/ownerlogin.php");
    exit();
}


$owner_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);
$listingSuccess = false; 

// --- 1. HANDLE EDIT MODE (FETCH DATA FOR FORM POPULATION) ---
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $stmt = $conn->prepare("SELECT * FROM bh_listing WHERE bh_id = ? AND user_id = ?");
    $stmt->execute([$_GET['edit_id'], $owner_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// --- 2. HANDLE DELETE ---
if (isset($_GET['delete_listing_id'])) {
    $del_id = (int)$_GET['delete_listing_id'];
    try {
        $conn->beginTransaction();

        // 1. Delete reservations linked to this listing first
        $conn->prepare("DELETE FROM bh_reservations WHERE bh_id = ?")->execute([$del_id]);

        // 2. Delete images linked to this listing
        $conn->prepare("DELETE FROM bh_images WHERE bh_id = ?")->execute([$del_id]);

        // 3. Finally, delete the listing itself
        $stmt = $conn->prepare("DELETE FROM bh_listing WHERE bh_id = ? AND user_id = ?");
        $stmt->execute([$del_id, $owner_id]);

        $conn->commit();
        header("Location: owner_listings.php?success=1");
        exit();
    } catch (PDOException $e) { 
        if ($conn->inTransaction()) $conn->rollBack();
        error_log($e->getMessage()); 
        die("Database Error: " . $e->getMessage()); 
    }
}

// --- 3. FORM PROCESSING (ADD OR UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $bh_id = !empty($_POST['bh_id']) ? $_POST['bh_id'] : null;

    try {
        $conn->beginTransaction();

        if ($bh_id) {
            // UPDATE EXISTING RECORD
            $stmt = $conn->prepare("UPDATE bh_listing SET title=?, bh_description=?, bh_address=?, monthly_rent=?, available_rooms=?, contact=?, room_type=?, preferred_gender=?, curfew_policy=? WHERE bh_id=? AND user_id=?");
            $stmt->execute([
                $_POST['title'], $_POST['bh_description'], $_POST['address'], $_POST['monthly_rent'], 
                $_POST['available_rooms'], $_POST['contact'], $_POST['room_type'], 
                $_POST['preferred_gender'], $_POST['curfew_policy'], $bh_id, $owner_id
            ]);
        } else {
            // INSERT NEW RECORD
            $stmt = $conn->prepare("INSERT INTO bh_listing (user_id, title, bh_description, bh_address, monthly_rent, available_rooms, contact, room_type, preferred_gender, curfew_policy) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $owner_id, $_POST['title'], $_POST['bh_description'], $_POST['address'], 
                $_POST['monthly_rent'], $_POST['available_rooms'], $_POST['contact'], 
                $_POST['room_type'], $_POST['preferred_gender'], $_POST['curfew_policy']
            ]);
            $bh_id = $conn->lastInsertId();
        }

        // Handle Image Uploads (Only if files are selected)
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploadDir = "../../uploads/listings/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $imgStmt = $conn->prepare("INSERT INTO bh_images (bh_id, image_path) VALUES (?, ?)");
            foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                if ($_FILES['images']['error'][$i] !== 0) continue;
                $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $newName = "bh_" . $bh_id . "_" . bin2hex(random_bytes(4)) . "." . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                    $imgStmt->execute([$bh_id, $newName]);
                }
            }
        }

        $conn->commit();
        header("Location: owner_listings.php?success=1");
        exit();
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        die("Error: " . $e->getMessage());
    }
}

// 4. Fetch Owner's Listings for the "My Listings" Modal
$l_stmt = $conn->prepare("SELECT l.*, (SELECT image_path FROM bh_images WHERE bh_id = l.bh_id LIMIT 1) AS image_path FROM bh_listing l WHERE l.user_id = ?");
$l_stmt->execute([$owner_id]);
$my_listings = $l_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= $edit_data ? 'Edit' : 'Add' ?> Listing - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_add_listing.css">
    <link rel="stylesheet" href="css/owner_manage_req.css">
    <style>
        .btn-view-my { background-color: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; border:none; }
        .listings-modal-table { width: 100%; border-collapse: collapse; margin-top: 15px;}
        .listings-modal-table th { background: #f4f4f4; padding: 10px; text-align: left; font-size: 14px;}
        .listings-modal-table td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle;}
        .img-thumb { width: 60px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;}
        .btn-manage-edit { background: #4a90e2; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; display: inline-block;}
        .btn-manage-delete { background: #e74c3c; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; display: inline-block; margin-left: 5px;}
        .cancel-edit-btn { background: #95a5a6; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-right: 10px;}
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="../res/logo1.png" class="logo-top"></div>
        <div class="nav-icons">
            <a href="owner_profile.php"><img class="icon" src="../icons/circle-user-round.svg"></a>
            <a href="owner_home.php"><img class="icon" src="../icons/house.svg"></a>
            <a href="owner_search.php"><img class="icon" src="../icons/search.svg"></a>
            <a href="owner_map.php"><img class="icon" src="../icons/map-pin-house.svg"></a>
            <a href="owner_listings.php" class="active"><img class="icon" src="../icons/pencil-line.svg"></a>
            <a href="owner_manage.php"><img class="icon" src="../icons/check-check.svg"></a>
            <a href="owner_settings.php"><img class="icon" src="../icons/settings.svg"></a>
        </div>
        <div class="bottom-icons">
            <a href="owner_help.php" class="<?= ($current_page == 'owner_help.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/message-circle-question-mark.svg" alt="help">
            </a>
            <a href="owner_logout.php"><img class="icon" src="../icons/log-out.svg"></a>
        </div>
    </div>

    <div class="main-container">
        <div class="header"><h2>UEP DORMDASH</h2></div>

        <div class="content-wrapper">
            <div class="form-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1><?= $edit_data ? 'Update' : 'Add New' ?> <b>Listing</b></h1>
                <div>
                    <?php if($edit_data): ?>
                        <a href="owner_listings.php" class="cancel-edit-btn">Cancel Edit</a>
                    <?php endif; ?>
                    <button class="btn-view-my" onclick="openListings()">My Listings</button>
                </div>
            </div>

            <form class="listing-form" action="owner_listings.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="bh_id" value="<?= htmlspecialchars($edit_data['bh_id'] ?? '') ?>">
                
                <section class="form-section">
                    <h3>Listing Details</h3>
                    <div class="input-group">
                        <label>Listing Title</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($edit_data['title'] ?? '') ?>" placeholder="e.g. Parcon Dormitory" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>Monthly Rent (₱)</label>
                            <input type="number" name="monthly_rent" value="<?= htmlspecialchars($edit_data['monthly_rent'] ?? '') ?>" required>
                        </div>
                        <div class="input-group">
                            <label>Available Rooms</label>
                            <input type="number" name="available_rooms" value="<?= htmlspecialchars($edit_data['available_rooms'] ?? '1') ?>" required>
                        </div>
                        <div class="input-group">
                            <label>Contact Number</label>
                            <input type="text" name="contact" value="<?= htmlspecialchars($edit_data['contact'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Complete Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($edit_data['bh_address'] ?? '') ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="bh_description" rows="4" required><?= htmlspecialchars($edit_data['bh_description'] ?? '') ?></textarea>
                    </div>
                    
                    <label>Add New Photos <?= $edit_data ? '(Optional)' : '' ?></label>
                    <input type="file" name="images[]" multiple accept="image/*" <?= $edit_data ? '' : 'required' ?>>
                    <?php if($edit_data): ?><p style="font-size: 12px; color: #e67e22;">Picking new files will add them to your current gallery.</p><?php endif; ?>
                </section>

                <div class="checkbox-grid">
                    <div class="check-column">
                        <h4>Room Type</h4>
                        <label><input type="radio" name="room_type" value="Single Room" <?= ($edit_data['room_type'] ?? 'Single Room') == 'Single Room' ? 'checked' : '' ?>> Single Room</label>
                        <label><input type="radio" name="room_type" value="Studio Room" <?= ($edit_data['room_type'] ?? '') == 'Studio Room' ? 'checked' : '' ?>> Studio Type</label>
                    </div>
                    <div class="check-column">
                        <h4>Preferred Gender</h4>
                        <label><input type="radio" name="preferred_gender" value="Male only" <?= ($edit_data['preferred_gender'] ?? '') == 'Male only' ? 'checked' : '' ?>> Male</label>
                        <label><input type="radio" name="preferred_gender" value="Female only" <?= ($edit_data['preferred_gender'] ?? 'Female only') == 'Female only' ? 'checked' : '' ?>> Female</label>
                        <label><input type="radio" name="preferred_gender" value="Mixed" <?= ($edit_data['preferred_gender'] ?? '') == 'Mixed' ? 'checked' : '' ?>> Mixed</label>
                    </div>
                    <div class="check-column">
                        <h4>Curfew Policy</h4>
                        <label><input type="radio" name="curfew_policy" value="No Curfew" <?= ($edit_data['curfew_policy'] ?? 'No Curfew') == 'No Curfew' ? 'checked' : '' ?>> No Curfew</label>
                        <label><input type="radio" name="curfew_policy" value="With Curfew" <?= ($edit_data['curfew_policy'] ?? '') == 'With Curfew' ? 'checked' : '' ?>> With Curfew</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn-submit" style="width: 100%; height: 50px; font-size: 16px;">
                        <?= $edit_data ? 'Save Changes' : 'Publish Listing' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="listingsModal" class="modal-overlay" style="display: none;">
        <div class="history-container" style="max-width: 850px; width: 90%;">
            <div class="history-header-modal">
                <h1>My Registered Listings</h1>
                <button class="btn-close-modal" onclick="closeListings()">×</button>
            </div>
            <div class="history-table-wrapper">
                <table class="listings-modal-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Address</th>
                            <th>Rent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($my_listings)): ?>
                            <tr><td colspan="5" style="text-align:center;">No listings found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($my_listings as $l): ?>
                            <tr>
                                <td><img src="../../uploads/listings/<?= $l['image_path'] ?? 'default.jpg' ?>" class="img-thumb"></td>
                                <td><strong><?= htmlspecialchars($l['title']) ?></strong></td>
                                <td style="font-size: 13px; color: #666;"><?= htmlspecialchars($l['bh_address']) ?></td>
                                <td>₱<?= number_format($l['monthly_rent'], 0) ?></td>
                                <td style="white-space: nowrap;">
                                    <a href="owner_listings.php?edit_id=<?= $l['bh_id'] ?>" class="btn-manage-edit">Edit</a>
                                    <a href="owner_listings.php?delete_listing_id=<?= $l['bh_id'] ?>" class="btn-manage-delete" onclick="return confirm('Delete this listing permanently?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">Action successful!</div>

    <script>
        function openListings() { document.getElementById('listingsModal').style.display = 'flex'; }
        function closeListings() { document.getElementById('listingsModal').style.display = 'none'; }
        
        <?php if(isset($_GET['success'])): ?>
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => { 
                toast.classList.remove('show'); 
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 2500);
        <?php endif; ?>
    </script>
</body>
</html>