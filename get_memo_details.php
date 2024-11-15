<?php
session_start();
// Include your database connection file
include 'include.php';

// Get memo ID from request
if (isset($_GET['id'])) {
    $memoId = intval($_GET['id']);

    // Fetch memo details
    $sql = "SELECT * FROM memos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $memo = $result->fetch_assoc();

    // Check if memo details are present
    if ($memo) {
        // Ensure memo_title exists
        $memo['through'] = isset($memo['through']) ? $memo['through'] : 'No title';
        echo json_encode($memo);
    } else {
        echo json_encode(['error' => 'No memo details found']);
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'No memo ID provided']);
}
