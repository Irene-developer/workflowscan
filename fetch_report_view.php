<?php
// Database connection parameters
include 'include.php';
// SQL query to fetch data
$sql = "SELECT * FROM report_view";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();

// Return data as JSON for the JavaScript to use
header('Content-Type: application/json');
echo json_encode($data);
?>
