<?php
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

//SETUP DATE FILTERS (Default to current year if not set)
$date_from = $_GET['date_from'] ?? date('Y-01-01');
$date_to   = $_GET['date_to'] ?? date('Y-12-31');

try {
    // FETCH SUMMARY DATA (Filtered by date where applicable)
    // Avg Rent & Total Rooms (General overview)
    $summary = $conn->query("SELECT AVG(monthly_rent) as avg_rent, SUM(available_rooms) as total_rooms FROM bh_listing")->fetch();

    // Total Inquiries/Meetings within date range
    $stmtLeads = $conn->prepare("SELECT COUNT(*) FROM renter_details WHERE date_time BETWEEN ? AND ?");
    $stmtLeads->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
    $inquiryCount = $stmtLeads->fetchColumn();

    // FETCH TABLE DATA
    // Listings Table
    $listings = $conn->query("SELECT * FROM bh_listing ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    
    // Meetings Table (Filtered by Date)
    $stmtMeet = $conn->prepare("SELECT * FROM renter_details WHERE date_time BETWEEN ? AND ? ORDER BY date_time DESC");
    $stmtMeet->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
    $meetings = $stmtMeet->fetchAll(PDO::FETCH_ASSOC);
    
    // Feedback Table
    $feedbacks = $conn->query("SELECT f.*, b.title FROM feedback f JOIN bh_listing b ON f.bh_id = b.bh_id ORDER BY f.fdbk_id DESC")->fetchAll(PDO::FETCH_ASSOC);

    // 5. PREPARE CHART DATA (Room Type Distribution)
    $chartData = $conn->query("SELECT roomtype, COUNT(*) as count FROM bh_listing GROUP BY roomtype")->fetchAll(PDO::FETCH_ASSOC);
    $roomLabels = array_column($chartData, 'roomtype');
    $roomCounts = array_column($chartData, 'count');

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Operational Reports</h1>
        <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-print fa-sm text-white-50"></i> Print / Save PDF
        </button>
    </div>

    
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Market Avg. Rent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo number_format($summary['avg_rent'] ?? 0, 2); ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-tag fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total System Vacancies</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $summary['total_rooms'] ?? 0; ?> Units</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-door-open fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Leads (In selected range)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $inquiryCount; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-check fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            
            <form method="GET" class="d-flex align-items-center mb-4">
                <div class="input-group mr-2" style="max-width: 250px;">
                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold">From</span></div>
                    <input type="date" name="date_from" class="form-control" value="<?php echo $date_from; ?>">
                </div>
                <div class="input-group mr-2" style="max-width: 250px;">
                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold">To</span></div>
                    <input type="date" name="date_to" class="form-control" value="<?php echo $date_to; ?>">
                </div>
                <button type="submit" class="btn btn-primary px-4">Apply Filters</button>
            </form>

            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#listings">Active Listings</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#meetings">Meeting Requests</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#feedback">Recent Feedback</a></li>
            </ul>

            <div class="tab-content border-left border-right border-bottom p-3 bg-white">
                <div class="tab-pane fade show active" id="listings">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Owner</th>
                                    <th>Rent</th>
                                    <th>Type</th>
                                    <th>Rooms</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($listings as $bh): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($bh['title']); ?></strong></td>
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

                <div class="tab-pane fade" id="meetings">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Renter Name</th>
                                    <th>Method</th>
                                    <th>Location/Link</th>
                                    <th>Date Scheduled</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($meetings)): ?>
                                    <tr><td colspan="4" class="text-center">No meetings found in this date range.</td></tr>
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

                <div class="tab-pane fade" id="feedback">
                    <div class="mt-3">
                        <?php foreach($feedbacks as $f): ?>
                            <div class="p-3 mb-2 bg-light border-left border-primary rounded">
                                <h6 class="font-weight-bold mb-1"><?php echo htmlspecialchars($f['title']); ?></h6>
                                <p class="small text-muted mb-0 italic">"<?php echo htmlspecialchars($f['comments']); ?>"</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            
            <div class="row mt-5">
                <div class="col-md-6 offset-md-3">
                    <h5 class="text-center font-weight-bold text-gray-800 mb-4">Room Type Distribution</h5>
                    <div style="height:250px;">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById("myPieChart");
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: <?php echo json_encode($roomLabels); ?>,
        datasets: [{
          data: <?php echo json_encode($roomCounts); ?>,
          backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        cutoutPercentage: 70,
        legend: { position: 'bottom' }
      }
    });
});
</script>