<?php
// Include the database connection file
require_once('include.php'); // Ensure this file sets up $conn

// SQL query to count tickets by status
$sql = "SELECT 
            SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) AS Open,
            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS In_Progress,
            SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) AS Resolved,
            SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) AS Closed
        FROM log_tickets";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the data as an associative array
    $data = $result->fetch_assoc();

    // Transform the data into the format needed for the pie chart
    $chartData = [
        'labels' => ['Open', 'In Progress', 'Resolved', 'Closed'],
        'values' => [
            $data['Open'],
            $data['In_Progress'],
            $data['Resolved'],
            $data['Closed']
        ]
    ];

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Output the data as JSON
    echo json_encode($chartData);
} else {
    // Handle query error
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data']);
}
?>
