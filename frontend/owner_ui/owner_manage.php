<?php
$current_page = basename($_SERVER['PHP_SELF']);
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
            <a href="owner_profile.php" class="<?php echo ($current_page == 'owner_profile.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/circle-user-round.svg" alt="profile">
            </a>

            <a href="owner_home.php" class="<?php echo ($current_page == 'owner_home.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/house.svg" alt="home">
            </a>

            <a href="owner_search.php" class="<?php echo ($current_page == 'owner_search.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/search.svg" alt="search">
            </a>

            <a href="owner_map.php" class="<?php echo ($current_page == 'owner_map.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/map-pin-house.svg" alt="map">
            </a>

            <a href="owner_listings.php" class="<?php echo ($current_page == 'owner_listings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/pencil-line.svg" alt="listings">
            </a>

            <a href="owner_manage.php" class="<?php echo ($current_page == 'owner_manage.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/check-check.svg" alt="manage">
            </a>

            <a href="owner_settings.php" class="<?php echo ($current_page == 'owner_settings.php') ? 'active' : ''; ?>">
                <img class="icon" src="../icons/settings.svg" alt="settings">
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

    <div class="main-container">
        <div class="header">
            <h2>UEP DORMDASH</h2>
        </div>

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
                    <h3>Pending Requests</h3>
                    <div class="request-item active">
                        <img src="user1.jpg" alt="avatar">
                        <div class="req-info">
                            <strong>Zedek Cupido</strong>
                            <span>Virtual Meeting</span>
                        </div>
                        <div class="active-indicator"></div>
                    </div>
                    <div class="request-item">
                        <img src="user2.jpg" alt="avatar">
                        <div class="req-info">
                            <strong>Sinasabi ko na nga ba</strong>
                            <span>In-person visit</span>
                        </div>
                    </div>
                    <div class="request-item">
                        <img src="user3.jpg" alt="avatar">
                        <div class="req-info">
                            <strong>Batakmagrelapse</strong>
                            <span>Call Inquiry</span>
                        </div>
                    </div>
                </aside>

                <main class="detail-container">
                    <div class="detail-card">
                        <section class="detail-group">
                            <h2>Personal Details</h2>
                            <div class="detail-row">
                                <span class="label">Name:</span>
                                <span class="value">Zedek Cupido</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Mobile Number:</span>
                                <span class="value">1234567890</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Email Address:</span>
                                <span class="value">Secret@gmail.com</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Gender:</span>
                                <span class="value">Male</span>
                            </div>
                        </section>

                        <hr class="divider">

                        <section class="detail-group">
                            <h2>Scheduled Meeting</h2>
                            <div class="detail-row">
                                <span class="label">Meeting Type:</span>
                                <span class="value meeting-type">Virtual Meeting</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Preferred Date:</span>
                                <span class="value">10/11/2025</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Preferred Time:</span>
                                <span class="value">01:00 PM</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Meeting Place:</span>
                                <span class="value italic">"Kahit saan basta kasama ka <3"< /span>
                            </div>
                        </section>

                        <div class="action-buttons">
                            <button class="btn-approve">Approve</button>
                            <button class="btn-reject">Reject</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <div id="historyModal" class="modal-overlay">
        <div class="history-container">
            <div class="history-header-modal">
                <h1>History Request List</h1>
                <div class="header-actions">
                    <button class="btn-clear-all">Clear All</button>
                    <button class="btn-close-modal" onclick="closeHistory()">×</button>
                </div>
            </div>

            <div class="history-table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>#</th>
                            <th>Tenant Information</th>
                            <th>Notes/Description</th>
                            <th>Status</th>
                            <th>Date & Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="history-row selected">
                            <td><input type="checkbox" checked></td>
                            <td>4</td>
                            <td>
                                <div class="tenant-cell">
                                    <strong>Mara Santiago</strong>
                                    <span>09345678900</span>
                                </div>
                            </td>
                            <td class="notes-cell">Approved meeting for in-person visit...</td>
                            <td><span class="badge approved">Approved</span></td>
                            <td>10/15/2025</td>
                            <td><button class="btn-delete-row">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function openHistory() {
            document.getElementById('historyModal').style.display = 'flex';
        }

        function closeHistory() {
            document.getElementById('historyModal').style.display = 'none';
        }

        // Logout Function
        function handleLogout() {
            if(confirm("Log out of UEP DormDash?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>

</html>