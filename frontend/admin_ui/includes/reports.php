<?php
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

try {
    // FETCH SUMMARY DATA
    // Avg Rent & Total Rooms (Current snapshot of all listings)
    $summary = $conn->query("SELECT AVG(monthly_rent) as avg_rent, SUM(available_rooms) as total_rooms FROM bh_listing")->fetch(PDO::FETCH_ASSOC);

    // Count Leads/Inquiries specifically within the selected date range
    $stmtLeads = $conn->prepare("SELECT COUNT(*) FROM renter_details WHERE date_time BETWEEN ? AND ?");
    $inquiryCount = $stmtLeads->fetchColumn();

    // FETCH TABLE DATA
    // Meetings Log (Filtered by Date)
    $stmtMeet = $conn->prepare("SELECT * FROM renter_details WHERE date_time BETWEEN ? AND ? ORDER BY date_time DESC");
    $meetings = $stmtMeet->fetchAll(PDO::FETCH_ASSOC);
    
    // All Listings (General report)
    $listings = $conn->query("SELECT * FROM bh_listing ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

    // Feedback joined with Listings to see House Titles
    $feedbacks = $conn->query("SELECT f.*, b.title FROM feedback f JOIN bh_listing b ON f.bh_id = b.bh_id ORDER BY f.fdbk_id DESC")->fetchAll(PDO::FETCH_ASSOC);

    // PREPARE CHART DATA
    $chartStmt = $conn->query("SELECT roomtype, COUNT(*) as count FROM bh_listing GROUP BY roomtype");
    $chartRaw = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    $roomLabels = array_column($chartRaw, 'roomtype');
    $roomCounts = array_column($chartRaw, 'count');

} catch (PDOException $e) {
    die("Query Failed: " . $e->getMessage());
}
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Boarding House Reports</h1>
        <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate PDF
        </button>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Avg. Monthly Rent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo number_format($summary['avg_rent'] ?? 0, 2); ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-coins fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Vacancies</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $summary['total_rooms'] ?? 0; ?> Rooms</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-bed fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Leads (Filtered)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $inquiryCount; ?> Inquiries</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-tag fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Report Filters & Data</h6>
        </div>
        <div class="card-body">
            

            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" data-toggle="tab" href="#tab-meetings">Meetings Log (Leads)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" data-toggle="tab" href="#tab-listings">Active BH Listings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" data-toggle="tab" href="#tab-feedback">Feedback Feed</a>
                </li>
            </ul>

            <div class="tab-content border-left border-right border-bottom p-4 bg-white">
                
                <div class="tab-pane fade show active" id="tab-meetings">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>Renter Name</th>
                                    <th>Meeting Type</th>
                                    <th>Place/Link</th>
                                    <th>Scheduled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($meetings)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">No leads found in this date range.</td></tr>
                                <?php endif; ?>
                                <?php foreach($meetings as $m): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($m['renterName']); ?></td>
                                    <td><span class="badge badge-primary"><?php echo $m['meetingtype']; ?></span></td>
                                    <td><?php echo htmlspecialchars($m['meetingplace']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($m['date_time'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-listings">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>House Title</th>
                                    <th>Owner</th>
                                    <th>Rent</th>
                                    <th>Type</th>
                                    <th>Rooms Left</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($listings as $bh): ?>
                                <tr>
                                    <td class="font-weight-bold text-dark"><?php echo htmlspecialchars($bh['title']); ?></td>
                                    <td><?php echo htmlspecialchars($bh['ownername']); ?></td>
                                    <td>₱<?php echo number_format($bh['monthly_rent'], 2); ?></td>
                                    <td><?php echo $bh['roomtype']; ?></td>
                                    <td><?php echo $bh['available_rooms']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-feedback">
                    <?php foreach($feedbacks as $f): ?>
                        <div class="alert alert-light border-left-warning mb-2 shadow-sm">
                            <small class="text-uppercase font-weight-bold text-warning"><?php echo htmlspecialchars($f['title']); ?></small>
                            <p class="mb-0">"<?php echo htmlspecialchars($f['comments']); ?>"</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

<div class="row mt-5">
    <div class="col-lg-6 offset-lg-3">
        <h6 class="m-0 font-weight-bold text-primary mb-4 text-center">Room Type Distribution (Inventory)</h6>
        
        <?php 
        if (empty($chartRaw)) {
            echo '<div class="alert alert-light text-center">No data available to display distribution.</div>';
        } else {
            // Calculate total so we can show percentages
            $totalListings = array_sum($roomCounts);
            
            $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
            $i = 0;

            foreach($chartRaw as $row): 
                $percentage = ($totalListings > 0) ? ($row['count'] / $totalListings) * 100 : 0;
                $currentColor = $colors[$i % count($colors)];
                $i++;
        ?>
            <div class="mb-4">
                <div class="small font-weight-bold text-dark">
                    <?php echo htmlspecialchars($row['roomtype']); ?> 
                    <span class="float-right"><?php echo $row['count']; ?> Listings</span>
                </div>
                <div class="progress shadow-sm" style="height: 20px;">
                    <div class="progress-bar <?php echo $currentColor; ?>" 
                         role="progressbar" 
                         style="width: <?php echo $percentage; ?>%" 
                         aria-valuenow="<?php echo $percentage; ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                         <?php echo round($percentage); ?>%
                    </div>
                </div>
            </div>
        <?php 
            endforeach; 
        } 
        ?>
    </div>
</div>

        </div>
    </div>
</div>
