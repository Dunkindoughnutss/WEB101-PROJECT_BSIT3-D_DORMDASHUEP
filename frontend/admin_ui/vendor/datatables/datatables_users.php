<?php
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

// SQL adjusted to ensure numerical order from the database level
$sql = "SELECT u.user_id, u.email, r.renterName 
        FROM users u
        LEFT JOIN renter_details r ON u.user_id = r.user_id
        WHERE u.role = 'renter'
        ORDER BY u.user_id ASC";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $rows = [];
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<div id="table-wrapper2">
    <table id="myTable2" class="display">
        <thead>
            <tr>
                <th style="display:none;">ID</th> 
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td style="display:none;"><?= $row['user_id'] ?></td>
                <td>
                    <?php 
                        if (!empty($row['renterName'])) {
                            echo htmlspecialchars($row['renterName']);
                        } else {
                            // Professional fallback using the ID you suggested
                            echo '<span class="text-muted">User #' . htmlspecialchars($row['user_id']) . '</span>';
                            echo ' <span class="badge-pending">Account Only</span>';
                        }
                    ?>
                </td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <button class="delete-btn2" data-id="<?= $row['user_id'] ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
.badge-pending {
    background-color: #fce4ec;
    color: #d81b60;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
    text-transform: uppercase;
    margin-left: 5px;
}

#myTable2 th, #myTable2 td {
    text-align: left;
    vertical-align: middle;
    border: 1px solid #f8f5f5;
    padding: 8px;
}
#myTable2 th:nth-child(4), #myTable2 td:nth-child(4) { /* Shifted index due to hidden col */
    width: 120px;
    text-align: center;
    white-space: nowrap;
}
#myTable2 thead th {
    background-color: #64a1fb;
    color: white;
}
#myTable2 tbody tr:hover {
    background-color: #f1f1f1;
}
#myTable2 {
    border: 1px solid #f9f8f8;
    border-radius: 10px;
    overflow: hidden;
    width: 100%;
}
.delete-btn2 {
    background-color: #e74c3c;
    color: white;
    padding: 4px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}
.delete-btn2:hover { 
    background-color: #c0392b; 
}
#table-wrapper2{
    margin: 20px auto;
    margin-top: 0px;
    max-width: 100%;
    padding: 10px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.text-muted {
    color: #888;
    font-style: italic;
}
</style>

<script>
$(document).ready(function () {
    var table2 = $('#myTable2').DataTable({
        pageLength: 8,       
        lengthChange: false, 
        responsive: true,
        // Force sorting by the hidden ID column (index 0)
        "order": [[0, "asc"]], 
        "columnDefs": [
            // Target the hidden ID column to ensure it remains invisible but sortable
            { "targets": 0, "visible": false, "searchable": false }
        ]
    });

    $('#searchInput').on('keyup', function () {
        table2.search(this.value).draw();
    });

    $('#myTable2').on('click', '.delete-btn2', function () {
        var button = $(this);
        var id = button.data('id');

        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: 'vendor/datatables/delete_listing_user.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response == 'success') {
                        table2.row(button.parents('tr')).remove().draw();
                        alert('User deleted successfully!');
                    } else {
                        alert('Failed to delete user.');
                    }
                },
                error: function() {
                    alert('Error connecting to server.');
                }
            });
        }
    });
});
</script>