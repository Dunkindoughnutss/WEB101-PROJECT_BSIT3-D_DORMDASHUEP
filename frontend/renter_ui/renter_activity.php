<?php
session_start();
require_once __DIR__ . '/../../backend/dbconnection.php';

$user_id = $_SESSION['user_id'] ?? null;

// --- HANDLE ACTIONS (CANCEL / REMOVE) ---
if ($user_id && isset($_POST['action']) && isset($_POST['reservation_id'])) {
    $res_id = $_POST['reservation_id'];
    $action = $_POST['action'];

    try {
        if ($action === 'cancel') {
            $stmt = $conn->prepare("DELETE FROM bh_reservations WHERE reservation_id = :rid AND user_id = :uid AND status = 'Pending'");
            $stmt->execute([':rid' => $res_id, ':uid' => $user_id]);
        } elseif ($action === 'remove') {
            $stmt = $conn->prepare("DELETE FROM bh_reservations WHERE reservation_id = :rid AND user_id = :uid AND status != 'Pending'");
            $stmt->execute([':rid' => $res_id, ':uid' => $user_id]);
        }
        header("Location: renter_activity.php");
        exit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

$stats = ['total' => 0, 'approved' => 0, 'pending' => 0, 'declined' => 0];
$activities = [];

if ($user_id) {
    try {
        $stmt_stats = $conn->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'Declined' THEN 1 ELSE 0 END) as declined
            FROM bh_reservations WHERE user_id = :uid
        ");
        $stmt_stats->execute([':uid' => $user_id]);
        $fetched_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
        if ($fetched_stats) $stats = $fetched_stats;

        $stmt_list = $conn->prepare("
            SELECT r.*, bh.title, bh.monthly_rent, bh.bh_address,
            u.email AS owner_email,
            od.contact_number AS owner_contact,
            od.full_name AS owner_name,
            (SELECT image_path FROM bh_images WHERE bh_id = bh.bh_id LIMIT 1) as main_image
            FROM bh_reservations r
            JOIN bh_listing bh ON r.bh_id = bh.bh_id
            JOIN users u ON bh.user_id = u.user_id
            LEFT JOIN owner_details od ON u.user_id = od.user_id
            WHERE r.user_id = :uid
            ORDER BY r.created_at DESC
        ");
        $stmt_list->execute([':uid' => $user_id]);
        $activities = $stmt_list->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Activity Log - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/renter_home.css">
    <link rel="stylesheet" href="css/renter_settings.css"> 
    <link rel="stylesheet" href="css/renter_activity.css"> 
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="logo1.png" alt="UEP" class="logo-top"></div>
        <div class="nav-icons">
            <a href="renter_profile.php" class="tab-link"><img class="icon" src="../icons/circle-user-round.svg"></a>
            <a href="renter_home.php" class="tab-link"><img class="icon" src="../icons/house.svg"></a>
            <a href="renter_search.php" class="tab-link"><img class="icon" src="../icons/search.svg"></a>
            <a href="renter_map.php" class="tab-link"><img class="icon" src="../icons/map-pin-house.svg"></a>
            <a href="renter_activity.php" class="tab-link active"><img class="icon" src="../icons/check-check.svg"></a>
            <a href="renter_settings.php" class="tab-link"><img class="icon" src="../icons/settings.svg"></a>
        </div>
        <div class="bottom-icons">
            <a href="renter_help.php" class="tab-link"><img class="icon" src="../icons/message-circle-question-mark.svg"></a>
            <a href="<?= $user_id ? 'logout.php' : '../loginForms/renter/login.php' ?>" class="tab-link">
                <img class="icon" src="../icons/<?= $user_id ? 'log-out' : 'log-out' ?>.svg">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header"><h2 class="brand-title">UEP DORMDASH</h2></div>

        <div class="scroll-area flex-center">
            <div class="settings-container-large">
                <div class="settings-header">
                    <h1>Renting Activity</h1>
                    <p>Manage and track your boarding house applications</p>
                </div>

                <div class="activity-stats-row">
                    <div class="stat-card">
                        <span class="stat-val"><?= str_pad($stats['total'] ?? 0, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="stat-lbl">Total Reservations</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-val approved-text"><?= str_pad($stats['approved'] ?? 0, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="stat-lbl">Approved</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-val pending-text"><?= str_pad($stats['pending'] ?? 0, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="stat-lbl">Pending</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-val declined-text"><?= str_pad($stats['declined'] ?? 0, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="stat-lbl">Declined</span>
                    </div>
                </div>

                <div class="activity-list">
                    <?php if (empty($activities)): ?>
                        <div class="no-activity">
                            <p>No renting activity found.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($activities as $act): 
                            $status = $act['status'];
                            $cardClass = "status-card-" . strtolower($status);
                            $bgClass = "is-" . strtolower($status);
                            
                            $statusTitle = ""; $statusDesc = ""; $statusIcon = "";

                            if ($status === 'Approved') {
                                $statusTitle = "Reservation Approved";
                                $statusDesc = "Owner has accepted your request. You can now proceed with the move-in details.";
                                $statusIcon = "✓";
                            } elseif ($status === 'Pending') {
                                $statusTitle = "Pending Review";
                                $statusDesc = "Your application is currently being reviewed by the boarding house owner.";
                                $statusIcon = "⌛";
                            } else {
                                $statusTitle = "Request Declined";
                                $statusDesc = "The room is currently undergoing maintenance and is unavailable.";
                                $statusIcon = "✕";
                            }
                        ?>
                            <div class="activity-card <?= $cardClass ?>">
                                <div class="card-inner">
                                    <div class="house-info">
                                        <div class="info-header">
                                            <h3><?= htmlspecialchars($act['title']) ?></h3>
                                            <span class="price-tag">₱<?= number_format($act['monthly_rent'], 0) ?>/mo</span>
                                        </div>
                                        <p class="loc">📍 <?= htmlspecialchars($act['bh_address']) ?></p>

                                        <div class="dynamic-status-box <?= $bgClass ?>">
                                            <div class="status-icon"><?= $statusIcon ?></div>
                                            <div>
                                                <p class="status-title"><?= $statusTitle ?></p>
                                                <p class="status-note"><?= $statusDesc ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-box">
                                        <form method="POST">
                                            <input type="hidden" name="reservation_id" value="<?= $act['reservation_id'] ?>">
                                            
                                            <?php if ($status === 'Approved'): ?>
                                                <button type="button" class="btn-text" 
                                                        onclick="showOwnerContact('<?= addslashes($act['owner_name']) ?>', '<?= $act['owner_contact'] ?>', '<?= $act['owner_email'] ?>')">
                                                        Contact Owner
                                                </button>
                                                
                                            <?php elseif ($status === 'Pending'): ?>
                                                <button type="submit" name="action" value="cancel" class="btn-secondary-action">Cancel Request</button>
                                                
                                            <?php else: ?>
                                                <button type="button" class="btn-primary-action" onclick="location.href='renter_search.php'">Find Alternative</button>
                                                <button type="submit" name="action" value="remove" class="btn-text delete">Remove from Log</button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

        <div id="contactModal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(3px);">
        <div style="background:#fff; margin:15% auto; padding:25px; border-radius:15px; width:90%; max-width:350px; text-align:center; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
            <span onclick="closeContactModal()" style="position:absolute; right:15px; top:10px; cursor:pointer; font-size:24px; color:#aaa;">&times;</span>
            <h3 style="margin-bottom:5px; color:#333;">Contact Owner</h3>
            <p id="modalOwnerName" style="font-weight:bold; color:#f39c12; margin-bottom:15px;"></p>
            <hr style="border:0; border-top:1px solid #eee; margin-bottom:15px;">
            <p style="margin:5px 0; color:#555;">📞 <span id="modalOwnerPhone"></span></p>
            <p style="margin:5px 0; color:#555;">✉️ <span id="modalOwnerEmail"></span></p>
            <button onclick="closeContactModal()" style="margin-top:20px; width:100%; padding:10px; border:none; border-radius:8px; background:#f39c12; color:white; font-weight:bold; cursor:pointer;">Close</button>
        </div>
    </div>

    <script>
    function showOwnerContact(name, phone, email) {
        document.getElementById('modalOwnerName').innerText = name || 'Owner';
        document.getElementById('modalOwnerPhone').innerText = phone || 'No contact provided';
        document.getElementById('modalOwnerEmail').innerText = email;
        document.getElementById('contactModal').style.display = 'block';
    }
    function closeContactModal() {
        document.getElementById('contactModal').style.display = 'none';
    }
    // Close if clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('contactModal');
        if (event.target == modal) modal.style.display = "none";
    }
    </script>

</body>
</html>