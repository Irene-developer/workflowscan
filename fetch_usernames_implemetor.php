<?php
// Include your database connection file
include 'include.php'; // Adjust this according to your project structure

header('Content-Type: application/json');

// Prepare the query to fetch usernames
$stmt = $conn->prepare("SELECT username FROM employee_access");
$stmt->execute();
$result = $stmt->get_result();

$usernames = [];
while ($row = $result->fetch_assoc()) {
    $usernames[] = $row; // Store each username in an array
}

$stmt->close();
$conn->close();

echo json_encode($usernames); // Return as JSON
?>
