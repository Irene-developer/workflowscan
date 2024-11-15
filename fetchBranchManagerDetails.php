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
        WHERE p.Position_name LIKE '%BRANCH MANAGER%' 
           OR p.Position_name LIKE '%FINANCE%'";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(array('error' => 'Query preparation failed.'));
    $conn->close();
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to hold the fetched data
$data = array();

// Check if the query was successful
if ($result === FALSE) {
    echo json_encode(array('error' => 'Query execution failed.'));
    $stmt->close();
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

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
