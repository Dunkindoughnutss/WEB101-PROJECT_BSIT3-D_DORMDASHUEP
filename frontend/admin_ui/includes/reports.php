<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">

            <!-- Date Filters -->
            <div class="d-flex justify-content-start align-items-center mb-4">
                <div class="input-group mr-3" style="max-width: 200px;">
                    <input type="date" class="form-control" value="2023-01-01">
                </div>
                <div class="input-group mr-3" style="max-width: 200px;">
                    <input type="date" class="form-control" value="2023-12-31">
                </div>
                <button class="btn btn-primary ml-auto">
                    <i class="fas fa-plus"></i> Generate Report
                </button>
            </div>

            <!-- Tabs -->
            <div class="report-type-tabs nav nav-tabs" id="reportTabs" role="tablist">
                <a class="nav-link active" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab">Monthly Summary</a>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="reportTabsContent">

                <!-- Monthly Summary Tab -->
                <div class="tab-pane fade show active" id="monthly" role="tabpanel">

                    <h4 class="small font-weight-bold mt-4">Recent Monthly Reports</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Date Generated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Monthly Summary - Dec 2023</td>
                                    <td>(2024-456-791)</td>
                                    <td class="text-nowrap">
                                        <a href="#" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Booking Trends - Q4</td>
                                    <td>2023-10-27</td>
                                    <td class="text-nowrap">
                                        <a href="#" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Revenue by Property - 2023</td>
                                    <td>2023-12-31</td>
                                    <td class="text-nowrap">
                                        <a href="#" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Chart -->
                    <h4 class="small font-weight-bold mt-5">Monthly Bookings for 2023</h4>
                    <div class="chart-area card shadow mt-3">
                        <div class="card-body">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>

                </div> 

            </div> 

        </div> 
    </div>

</div> 

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" to end your session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/demo/chart-area-demo.js"></script>
