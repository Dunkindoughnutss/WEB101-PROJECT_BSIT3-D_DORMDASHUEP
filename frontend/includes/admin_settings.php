<<<<<<< Updated upstream
               <div class="container-fluid">
                    <form>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Admin Profile Settings</h6>
                            </div>

                            <div class="card-body">

                                <!-- Email -->
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="admin-email">Email</label>
                                        <input type="email" class="form-control" id="admin-email" value="admin@uecp.edu">
                                    </div>
                                </div>

                                <!-- Password + Confirm Password -->
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
                                    <button class="btn btn-primary" id="save-admin-profile">Save Changes</button>
                                    <div id="profile-alert" class="mt-3"></div>
                                </div>

                            </div>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">System & Application Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label for="default-currency">Default Currency</label>
                                        <select class="form-control" id="default-currency">
                                            <option>PHP - Philippine Peso</option>
                                            <option>USD - US Dollar</option>
                                            <option>EUR - Euro</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="email-notifications">Email Notifications</label>
                                        <select class="form-control" id="email-notifications">
                                            <option>All Alerts</option>
                                            <option>Critical Only</option>
                                            <option>Off</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label for="default-theme">Default Theme</label>
                                        <select class="form-control" id="default-theme">
                                            <option>Light Mode</option>
                                            <option>Dark Mode</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="data-retention">Data Retention Period (Months)</label>
                                        <input type="number" class="form-control" id="data-retention" value="12">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>

                </div>
                </div>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

<script>
    document.getElementById("save-admin-profile").addEventListener("click", function () {
=======
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

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System & Application Settings</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="default-currency">Default Currency</label>
                        <select class="form-control" id="default-currency">
                            <option>PHP - Philippine Peso</option>
                            <option>USD - US Dollar</option>
                            <option>EUR - Euro</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="email-notifications">Email Notifications</label>
                        <select class="form-control" id="email-notifications">
                            <option>All Alerts</option>
                            <option>Critical Only</option>
                            <option>Off</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="default-theme">Default Theme</label>
                        <select class="form-control" id="default-theme">
                            <option>Light Mode</option>
                            <option>Dark Mode</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="data-retention">Data Retention Period (Months)</label>
                        <input type="number" class="form-control" id="data-retention" value="12">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Save All Settings
            </button>
        </div>
    </form>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    document.getElementById("save-admin-profile").addEventListener("click", function (e) {
        e.preventDefault();
>>>>>>> Stashed changes

        const email = document.getElementById("admin-email").value.trim();
        const pass = document.getElementById("admin-password").value.trim();
        const confirm = document.getElementById("confirm-admin-password").value.trim();
        const alertBox = document.getElementById("profile-alert");

<<<<<<< Updated upstream
        // Clear previous alerts
=======
        // Reset alert
>>>>>>> Stashed changes
        alertBox.innerHTML = "";

        // Validate passwords match
        if (pass !== "" && pass !== confirm) {
            alertBox.innerHTML = `<div class="alert alert-danger">Passwords do not match.</div>`;
            return;
        }

<<<<<<< Updated upstream
        fetch("update_admin.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, pass })
        })
        .then(res => res.json())
        .then(data => {
            alertBox.innerHTML = `<div class="alert alert-${data.success ? "success" : "danger"}">${data.message}</div>`;
        })
        .catch(() => {
            alertBox.innerHTML = `<div class="alert alert-danger">An error occurred.</div>`;
        });
    });
</script>

=======
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
>>>>>>> Stashed changes
