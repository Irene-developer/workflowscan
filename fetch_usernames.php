<?php
include 'include.php';

// Query to fetch usernames
$sql = "SELECT username FROM employee_access"; // Replace with your actual table name and column name
$result = $conn->query($sql);

$usernames = array();
if ($result->num_rows > 0) {
    // Fetch usernames from the result set
    while ($row = $result->fetch_assoc()) {
        $usernames[] = $row['username'];
    }
}

// Close database connection
$conn->close();

// Convert the usernames array to JSON and output it
echo json_encode($usernames);
?>
