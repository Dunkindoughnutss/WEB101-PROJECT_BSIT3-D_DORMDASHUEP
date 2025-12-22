<?php
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php'; // Main Database

$total_bh = 0;
try {
    $stmt = $conn->query("SELECT COUNT(*) AS total_bh FROM bh_listing");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $total_bh = $row['total_bh'];
    }
} catch (PDOException $e) {
    $total_bh = 0;
}

$total_renters = 0;
try {
    $stmt = $conn->query("SELECT COUNT(*) AS total_renters FROM users WHERE role = 'renter'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $total_renters = $row['total_renters'];
    }
} catch (PDOException $e) {
    $total_renters = 0;
}

// Recent Owners (from bh_listing)
$recent_owners = [];
try {
    $stmt = $conn->query("
        SELECT ownername, created_at
        FROM bh_listing
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $recent_owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recent_owners = [];
}

// Recent Renters (from renter_details)
$recent_renters = [];
try {
    $stmt = $conn->query("
        SELECT renterName, created_at
        FROM renter_details
        ORDER BY date_time DESC
        LIMIT 5
    ");
    $recent_renters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recent_renters = [];
}
?>



<!-- Page Heading -->
<div class="mb-4">
    <h1 class="h3 text-gray-800">Dashboard</h1>
    <h3 class="h5 text-gray-600 mt-2">Welcome to UEP DORM DASH!</h3>

</div>

<div class="row">
<!-- Renters available -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Renters Available
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $total_renters; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BH available -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Boarding Houses Available
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $total_bh; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


<div class="card shadow mb-2">
    <div class="card-body">
        <h6 class="font-weight-bold text-primary mb-3">Recent Owners</h6>
        <div class="list-group list-group-flush">
            <?php if(empty($recent_owners)): ?>
                <div class="list-group-item text-gray-600">No recent owners.</div>
            <?php else: ?>
                <?php foreach($recent_owners as $owner): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center text-gray-800">
                        <div>
                            <i class="fas fa-user-circle mr-2 text-gray-500"></i>
                            <strong><?= htmlspecialchars($owner['ownername']) ?></strong>
                        </div>
                        <small class="text-gray-500"><?= date('M d', strtotime($owner['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card shadow mb-2">
    <div class="card-body">
        <h6 class="font-weight-bold text-primary mb-3">Recent Renters</h6>
        <div class="list-group list-group-flush">
            <?php
            $stmt = $conn->query("SELECT renterName, date_time FROM renter_details ORDER BY date_time DESC LIMIT 5");
            $recent_renters = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($recent_renters as $renter) {
                echo '<div class="list-group-item d-flex justify-content-between align-items-center text-gray-800">
                        <div><i class="fas fa-user-circle mr-2 text-gray-500"></i> <strong>'
                        . htmlspecialchars($renter['renterName']) .
                        '</strong></div>
                        <small class="text-gray-500">' . date('M d', strtotime($renter['date_time'])) . '</small>
                      </div>';
            }
            ?>
        </div>
    </div>
</div>


