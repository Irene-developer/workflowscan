<?php
session_start();
include 'include.php'; // Include your database connection

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$username = $_SESSION['username'];
$memo_id = intval($_GET['memo_id']); // Fetch the memo_id from the query parameters

// Prepare and execute the query
$query = "SELECT comment FROM added_through_memo_comment WHERE username = ? AND memo_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $username, $memo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'comment' => $row['comment']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No comment found']);
}

$stmt->close();
$conn->close();
?>