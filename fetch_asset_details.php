<?php
// Include database connection
include 'include.php';

// Get employee_id from AJAX request
$employee_id = $_POST['employee_id'];

// Sanitize input to prevent SQL injection
$employee_id = mysqli_real_escape_string($conn, $employee_id);

// Fetch asset request details from database
$sql = "SELECT * FROM asset_requests WHERE employee_id = ? ORDER BY request_id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $employee_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any records are found
if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr>
            <th>Request ID</th>
            <th>Request Type</th>
            <th>New Asset Type (New)</th>
            <th>New Asset Details</th>
            <th>New Asset Type (Shifting)</th>
            <th>Current Asset Tag</th>
            <th>New Asset Tag</th>
            <th>Exchange Reason</th>
            <th>Asset Tag to Shift</th>
            <th>From Location</th>
            <th>New Location</th>
            <th>Additional Info</th>
            <th>Request Date</th>
            <th>Status</th>
          </tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['request_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['request_type']) . '</td>';
        echo '<td>' . htmlspecialchars($row['new_asset_type']) . '</td>';
        echo '<td>' . htmlspecialchars($row['new_asset_details']) . '</td>';
        echo '<td>' . htmlspecialchars($row['new_asset_type_shift']) . '</td>';
        echo '<td>' . htmlspecialchars($row['current_asset_tag']) . '</td>';
        echo '<td>' . htmlspecialchars($row['new_asset_tag']) . '</td>';
        echo '<td>' . htmlspecialchars($row['exchange_reason']) . '</td>';
        echo '<td>' . htmlspecialchars($row['asset_tag_to_shift']) . '</td>';
        echo '<td>' . htmlspecialchars($row['from_location']) . '</td>';
        echo '<td>' . htmlspecialchars($row['new_location']) . '</td>';
        echo '<td>' . htmlspecialchars($row['additional_info']) . '</td>';
        echo '<td>' . htmlspecialchars($row['request_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No requests found.';
}

$stmt->close();
$conn->close();
?>
