<?php
// Include your database connection file
include 'include.php';

// Start session to access session variables
session_start();

// Get the position stored in the session
$session_position = $_SESSION['Position_name'];

// Query to fetch unique position names and associated names from the database, excluding the position in session
$sql = "SELECT p.Position_name, e.name, e.username 
        FROM position p 
        LEFT JOIN employee_access e ON p.position_id = e.position_id 
        WHERE p.Position_name != '$session_position'";
$result = $conn->query($sql);

// Array to store fetched data
$data = array();

// Fetch data and store in the array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store both Position_name and name in the array
        $data[] = array(
            'Position_name' => $row["Position_name"],
            'name' => $row["name"] ? $row["name"] : 'No name',
            'username' => $row["username"] ? $row["username"] : 'No username'
        );
    }
}

// Close the database connection
$conn->close();

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($data);
?>
