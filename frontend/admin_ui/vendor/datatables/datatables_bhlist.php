<?php
require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

// Make sure to include `id` for deletion
$sql = "SELECT bh_id, title, bh_address, ownername FROM bh_listing";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $rows = [];
}
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- Table wrapper -->
<div id="table-wrapper">
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Owner</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['bh_address']) ?></td>
                <td><?= htmlspecialchars($row['ownername']) ?></td>
                <td>
                    <button class="delete-btn" data-id="<?= $row['bh_id'] ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<style>
#myTable th, #myTable td {
    text-align: left;
    vertical-align: middle;
    border: 1px solid #f8f5f5;
    padding: 8px;
}
#myTable th:nth-child(4), #myTable td:nth-child(4) {
    width: 120px;
    text-align: center;
    white-space: nowrap;
}
#myTable thead th {
    background-color: #64a1fb;
    color: white;
}
#myTable tbody tr:hover {
    background-color: #f1f1f1;
}
#myTable {
    border: 1px solid #f9f8f8;
    border-radius: 10px;
    overflow: hidden;
    width: 100%;
}
.delete-btn {
    background-color: #e74c3c;
    color: white;
    padding: 4px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}
.delete-btn:hover { 
    background-color: #c0392b; 
}
#table-wrapper{
    margin: 20px auto;
    margin-top: 0px;
    max-width: 100%;
    padding: 10px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>

<script>
$(document).ready(function () {
    // Initialize DataTable
    var table = $('#myTable').DataTable({
        pageLength: 8,       
        lengthChange: false, 
        responsive: true
    });

    // Connect external search bar from bh_admin.php
    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Delete row with AJAX
    $('#myTable').on('click', '.delete-btn', function () {
        var button = $(this);
        var id = button.data('id');

        if (confirm("Are you sure you want to delete this listing?")) {
            $.ajax({
                url: 'vendor/datatables/delete_listing_bh.php', // PHP file to handle deletion
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response == 'success') {
                        // Remove row from DataTable
                        table.row(button.parents('tr')).remove().draw();
                        alert('Listing deleted successfully!');
                    } else {
                        alert('Failed to delete listing.');
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
