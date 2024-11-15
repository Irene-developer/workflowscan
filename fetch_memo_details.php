<?php
include 'include.php';
// Assuming you have a database connection established already

// Perform a query to fetch memo details
$sql = "SELECT memo_id, Position_name FROM memo_action LIMIT 1"; // Adjust your query as needed

$result = mysqli_query($connection, $sql);

if (!$result) {
    // If query fails, handle the error
    $error = mysqli_error($connection);
    echo json_encode(array('error' => $error));
    exit;
}

// Fetch the memo details
$row = mysqli_fetch_assoc($result);
if (!$row) {
    // If no memo details found, handle accordingly
    echo json_encode(array('error' => 'No memo details found'));
    exit;
}

// Return the memo details as JSON response
echo json_encode($row);

// Close the connection
mysqli_close($connection);
?>
