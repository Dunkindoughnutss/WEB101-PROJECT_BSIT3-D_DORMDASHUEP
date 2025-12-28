<?php
session_start();
require_once "../../backend/dbconnection.php";
$current_page = basename($_SERVER['PHP_SELF']);
$owner_id = $_SESSION['user_id'] ?? null;

if (!$owner_id) {
    header("Location: ../loginForms/owner/owner_login.php");
    exit();
}

// --- 1. HANDLE APPROVE / REJECT ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
    $res_id = $_POST['reservation_id'];
    $new_status = ($_POST['action'] === 'approve') ? 'Approved' : 'Declined';

    try {
        $update_stmt = $conn->prepare("UPDATE bh_reservations SET status = ?, updated_at = NOW() WHERE reservation_id = ?");
        $update_stmt->execute([$new_status, $res_id]);
        header("Location: owner_manage.php?msg=" . strtolower($new_status));
        exit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

// --- 2. FETCH PENDING REQUESTS ---
$stmt = $conn->prepare("
    SELECT r.*, u.email, od.renterName as full_name, od.contact as contact_number, od.profile_img, bh.title as bh_name
    FROM bh_reservations r
    JOIN bh_listing bh ON r.bh_id = bh.bh_id
    JOIN users u ON r.user_id = u.user_id
    LEFT JOIN renter_details od ON u.user_id = od.user_id
    WHERE bh.user_id = ? AND r.status = 'Pending'
    ORDER BY r.created_at ASC
");
$stmt->execute([$owner_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- 3. FETCH HISTORY (Updated to include Email) ---
$hist_stmt = $conn->prepare("
    SELECT r.*, od.renterName, od.contact, u.email, bh.title as bh_name
    FROM bh_reservations r
    JOIN bh_listing bh ON r.bh_id = bh.bh_id
    JOIN users u ON r.user_id = u.user_id
    LEFT JOIN renter_details od ON r.user_id = od.user_id
    WHERE bh.user_id = ? AND r.status != 'Pending'
    ORDER BY r.updated_at DESC
");
$hist_stmt->execute([$owner_id]);
$history = $hist_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Requests - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_manage_req.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="../res/logo1.png" alt="UEP" class="logo-top">
        </div>
        <div class="nav-icons">
            <a href="owner_profile.php" class="<?= ($current_page == 'owner_profile.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/circle-user-round.svg"></a>
            <a href="owner_home.php" class="<?= ($current_page == 'owner_home.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/house.svg"></a>
            <a href="owner_search.php" class="<?= ($current_page == 'owner_search.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/search.svg"></a>
            <a href="owner_map.php" class="<?= ($current_page == 'owner_map.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/map-pin-house.svg"></a>
            <a href="owner_listings.php" class="<?= ($current_page == 'owner_listings.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/pencil-line.svg"></a>
            <a href="owner_manage.php" class="<?= ($current_page == 'owner_manage.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/check-check.svg"></a>
            <a href="owner_settings.php" class="<?= ($current_page == 'owner_settings.php') ? 'active' : ''; ?>"><img class="icon" src="../icons/settings.svg"></a>
        </div>
        <div class="bottom-icons">
            <a href="owner_help.php"><img class="icon" src="../icons/message-circle-question-mark.svg"></a>
            <a href="javascript:void(0);" onclick="handleLogout();">
                <img class="icon" src="../icons/log-out.svg" alt="Logout">
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="header"><h2>UEP DORMDASH</h2></div>

        <div class="content-wrapper">
            <div class="content-header">
                <div>
                    <h1>Manage Request List</h1>
                    <p class="listing-count">Pending approvals for your properties</p>
                </div>
                <button class="btn-history" onclick="openHistory()">View History</button>
            </div>

            <div class="split-view">
                <aside class="request-list">
                    <h3>Pending Requests (<?= count($requests) ?>)</h3>
                    <?php if (empty($requests)): ?>
                        <div style="padding:20px; color:gray; text-align:center;">No pending requests.</div>
                    <?php else: ?>
                        <?php foreach ($requests as $index => $req): ?>
                            <div class="request-item <?= $index === 0 ? 'active' : '' ?>" onclick="selectRequest(<?= htmlspecialchars(json_encode($req)) ?>, this)">
                                <img src="<?= !empty($req['profile_img']) ? '../../uploads/profiles/'.$req['profile_img'] : '../../res/default_avatar.jpg' ?>" alt="avatar">
                                <div class="req-info">
                                    <strong><?= htmlspecialchars($req['full_name'] ?? 'New Renter') ?></strong>
                                    <span><?= htmlspecialchars($req['bh_name']) ?></span>
                                </div>
                                <div class="active-indicator"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </aside>

                <main class="detail-container">
                    <?php if (!empty($requests)): ?>
                    <div class="detail-card">
                        <form method="POST" action="owner_manage.php">
                            <input type="hidden" name="reservation_id" id="view_res_id" value="<?= $requests[0]['reservation_id'] ?>">
                            
                            <section class="detail-group">
                                <h2>Tenant Details</h2>
                                <div class="detail-row"><span class="label">Name:</span> <span class="value" id="view_name"><?= htmlspecialchars($requests[0]['full_name'] ?? 'N/A') ?></span></div>
                                <div class="detail-row"><span class="label">Mobile:</span> <span class="value" id="view_contact"><?= htmlspecialchars($requests[0]['contact_number'] ?? 'N/A') ?></span></div>
                                <div class="detail-row"><span class="label">Email:</span> <span class="value" id="view_email"><?= htmlspecialchars($requests[0]['email']) ?></span></div>
                            </section>

                            <hr class="divider">

                            <section class="detail-group">
                                <h2>Property Information</h2>
                                <div class="detail-row"><span class="label">Boarding House:</span> <span class="value" id="view_bh_name"><?= htmlspecialchars($requests[0]['bh_name']) ?></span></div>
                                <div class="detail-row"><span class="label">Date Applied:</span> <span class="value"><?= date('M d, Y', strtotime($requests[0]['created_at'])) ?></span></div>
                            </section>

                            <div class="action-buttons">
                                <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn-reject">Reject</button>
                            </div>
                        </form>
                    </div>
                    <?php else: ?>
                        <div class="detail-card" style="display:flex; align-items:center; justify-content:center; height:300px; color:gray;">
                            <p>Select a request from the list to view details.</p>
                        </div>
                    <?php endif; ?>
                </main>
            </div>
        </div>
    </div>

    <div id="historyModal" class="modal-overlay">
        <div class="history-container" style="max-width: 90%; width: 1000px;">
            <div class="history-header-modal">
                <h1>History Request List</h1>
                <button class="btn-close-modal" onclick="closeHistory()">×</button>
            </div>
            <div class="history-table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Renter</th>
                            <th>Property</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Date Processed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($history)): ?>
                            <tr><td colspan="6" style="text-align: center;">No history found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($history as $h): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($h['renterName'] ?? 'N/A') ?></strong></td>
                                <td><?= htmlspecialchars($h['bh_name']) ?></td>
                                <td><span class="badge <?= strtolower($h['status']) ?>"><?= $h['status'] ?></span></td>
                                <td><?= htmlspecialchars($h['contact'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($h['email'] ?? 'N/A') ?></td>
                                <td><?= date('M d, Y', strtotime($h['updated_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function selectRequest(data, element) {
            document.querySelectorAll('.request-item').forEach(el => el.classList.remove('active'));
            element.classList.add('active');

            document.getElementById('view_res_id').value = data.reservation_id;
            document.getElementById('view_name').innerText = data.full_name || 'N/A';
            document.getElementById('view_contact').innerText = data.contact_number || 'N/A';
            document.getElementById('view_email').innerText = data.email;
            document.getElementById('view_bh_name').innerText = data.bh_name;
        }

        function openHistory() { document.getElementById('historyModal').style.display = 'flex'; }
        function closeHistory() { document.getElementById('historyModal').style.display = 'none'; }
    </script>

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