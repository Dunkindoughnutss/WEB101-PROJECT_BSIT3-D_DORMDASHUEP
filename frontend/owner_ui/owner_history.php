<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>History Request List - UEP DORMDASH</title>
    <link rel="stylesheet" href="css/owner_home.css">
    <link rel="stylesheet" href="css/owner_history.css">
</head>

<body>

    <div class="sidebar">
        <div class="sidebar">
            <div class="logo-section">
                <img src="../res/logo1.png" alt="UEP" class="logo-top">
            </div>

            <div class="nav-icons">
                <a href="owner_profile.php"><img class="icon" src="../icons/circle-user-round.svg" alt="profile"></a>
                <a href="owner_home.php"><img class="icon" src="../icons/house.svg" alt="home"></a>
                <a href="owner_search.php"><img class="icon" src="../icons/search.svg" alt="search"></a>
                <a href="owner_map.php"><img class="icon" src="../icons/map-pin-house.svg" alt="map"></a>
                <a href="owner_listings.php"><img class="icon" src="../icons/pencil-line.svg" alt="listings"></a>
                <a href="owner_manage.php"><img class="icon" src="../icons/check-check.svg" alt="manage"></a>
                <a href="owner_settings.php"><img class="icon" src="../icons/settings.svg" alt="settings"></a>
            </div>

            <div class="bottom-icons">
                <a href="owner_help.php" class="active"><img class="icon" src="../icons/message-circle-question-mark.svg" alt="help"></a>
            </div>
        </div>

        <div class="main-container">
            <div class="header">
                <h2>UEP DORMDASH</h2>
            </div>

            <div class="history-content">
                <div class="history-header">
                    <h1>History Request List</h1>
                    <div class="header-actions">
                        <div class="search-box">
                            <input type="text" placeholder="Search by name...">
                        </div>
                        <button class="btn-clear">
                            <img src="trash_red.png" alt="" width="14"> Clear All
                        </button>
                    </div>
                </div>

                <div class="history-table-wrapper">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox"></th>
                                <th width="60">#</th>
                                <th>Tenant Information</th>
                                <th>Notes/Description</th>
                                <th>Status</th>
                                <th>Date & Time</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="history-row">
                                <td><input type="checkbox"></td>
                                <td>1</td>
                                <td>
                                    <div class="tenant-cell">
                                        <strong>Zedek Cupido</strong>
                                        <span>09123456789</span>
                                    </div>
                                </td>
                                <td class="notes-cell">Requesting for a virtual tour of Room 102...</td>
                                <td><span class="badge approved">Approved</span></td>
                                <td>
                                    <div class="date-cell">
                                        <strong>10/11/2025</strong>
                                        <span>8:00 am</span>
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <button class="btn-delete-text">Delete</button>
                                </td>
                            </tr>

                            <tr class="history-row">
                                <td><input type="checkbox"></td>
                                <td>2</td>
                                <td>
                                    <div class="tenant-cell">
                                        <strong>Mark Gonzales</strong>
                                        <span>09234567890</span>
                                    </div>
                                </td>
                                <td class="notes-cell">Inquired about the curfew policy...</td>
                                <td><span class="badge rejected">Rejected</span></td>
                                <td>
                                    <div class="date-cell">
                                        <strong>11/21/2025</strong>
                                        <span>9:00 am</span>
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <button class="btn-delete-text">Delete</button>
                                </td>
                            </tr>

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
                                <td>
                                    <div class="date-cell">
                                        <strong>10/15/2025</strong>
                                        <span>2:00 pm</span>
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <button class="btn-delete-text">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="table-footer">
                        <span class="page-count">1-10 of 97</span>
                        <div class="pagination-controls">
                            <span>Rows per page: 10</span>
                            <button class="nav-btn">&lt;</button>
                            <span class="current-page">1/10</span>
                            <button class="nav-btn">&gt;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>