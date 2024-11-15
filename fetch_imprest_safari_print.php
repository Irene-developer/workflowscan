<?php
// fetch_imprest_safari.php
header('Content-Type: application/json');

include 'include.php';

// Query the imprest_safari table
$sql = "SELECT imprest_id, username, Date_from, Date_to, Days, travelling_to, status FROM imprest_safari";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

// Return JSON data
echo json_encode($data);
?>
