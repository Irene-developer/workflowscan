<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include database connection
include 'include.php';

// Check if connection was successful
if (!$conn) {
    echo json_encode(array('error' => 'Database connection failed.'));
    exit();
}

// SQL query to fetch branch manager details
$sql = "SELECT p.Position_name, e.name, e.username 
        FROM position p 
        LEFT JOIN employee_access e 
        ON p.position_id = e.position_id 
        WHERE p.Position_name LIKE '%FINANCE%'";

// Execute the query
$result = $conn->query($sql);

// Initialize an array to hold the fetched data
$data = array();

// Check if the query was successful
if ($result === FALSE) {
    echo json_encode(array('error' => 'Query failed.'));
    $conn->close();
    exit();
}

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch each row and add it to the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Encode the data array as JSON and print it
echo json_encode($data);

// Close the database connection
$conn->close();
?>
