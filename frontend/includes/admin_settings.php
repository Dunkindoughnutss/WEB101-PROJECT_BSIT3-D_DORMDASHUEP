<div class="container-fluid">
    <form id="adminSettingsForm">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Admin Profile Settings</h6>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="admin-email">Email</label>
                        <input type="email" class="form-control" id="admin-email" placeholder="admin@uecp.edu">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="admin-password">New Password</label>
                        <input type="password" class="form-control" id="admin-password" placeholder="Enter new password">
                    </div>

                    <div class="col-sm-6">
                        <label for="confirm-admin-password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-admin-password" placeholder="Enter new password again">
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="save-admin-profile">Save Profile Changes</button>
                    <div id="profile-alert" class="mt-3"></div>
                </div>
            </div>
        </div>

 <?php
include '../../backend/dbconnection.php'; 

try {
    $db_status = ($conn instanceof PDO) ? "Online" : "Offline";
    $db_color = ($conn instanceof PDO) ? "text-success" : "text-danger";

    $avgStmt = $conn->prepare("SELECT AVG(monthly_rent) as avg_price FROM bh_listing");
    $avgStmt->execute();
    $avgRow = $avgStmt->fetch(PDO::FETCH_ASSOC);
    $average_rent = number_format($avgRow['avg_price'] ?? 0, 2);

    $logStmt = $conn->prepare("SELECT email, updated_at FROM users ORDER BY updated_at DESC LIMIT 5");
    $logStmt->execute();
    $logs = $logStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $db_status = "Offline (Error)";
    $db_color = "text-danger";
    $average_rent = "0.00";
    $logs = [];
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">System Overview</h1>
    </div>

    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                System Connectivity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Database: <?php echo $db_status; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-server fa-2x <?php echo $db_color; ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Avg. Market Rent (Current)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo $average_rent; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Activity & Audit Logs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User/Admin</th>
                            <th>Action</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="3" class="text-center">No recent activity found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($log['email']); ?></strong></td>
                                <td><span class="badge badge-info">Profile Updated</span></td>
                                <td><?php echo $log['updated_at']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    document.getElementById("save-admin-profile").addEventListener("click", function (e) {
        e.preventDefault();

        const email = document.getElementById("admin-email").value.trim();
        const pass = document.getElementById("admin-password").value.trim();
        const confirm = document.getElementById("confirm-admin-password").value.trim();
        const alertBox = document.getElementById("profile-alert");

        // Reset alert
        alertBox.innerHTML = "";

        // Validate passwords match
        if (pass !== "" && pass !== confirm) {
            alertBox.innerHTML = `<div class="alert alert-danger">Passwords do not match.</div>`;
            return;
        }

        // Show loading state
        alertBox.innerHTML = `<div class="alert alert-info">Updating...</div>`;

        fetch("../../backend/update_admin.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json" 
            },
            body: JSON.stringify({ 
                email: email, 
                pass: pass 
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            if (data.success) {
                alertBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
              // Clear password fields after success
                document.getElementById("admin-password").value = "";
                document.getElementById("confirm-admin-password").value = "";
            } else {
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(err => {
            console.error("Fetch Error:", err);
            alertBox.innerHTML = `<div class="alert alert-danger">Server error. Check console for details.</div>`;
        });
    });
</script>
