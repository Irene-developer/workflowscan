<?php
// Start the session
session_start();

// Include your database connection file
include 'include.php';

// Check if the username is set in session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch signature from signature table
    $signature = '';
    $sig_query = "SELECT signature FROM signature WHERE username = ?";
    $sig_stmt = $conn->prepare($sig_query);
    $sig_stmt->bind_param("s", $username);
    $sig_stmt->execute();
    $sig_result = $sig_stmt->get_result();

    if ($sig_result->num_rows > 0) {
        $sig_row = $sig_result->fetch_assoc();
        $signature = $sig_row['signature'];
    }

    $sig_stmt->close();
    echo json_encode(['signature' => $signature]);
} else {
    echo json_encode(['error' => 'Username not set in session']);
}

$conn->close();
?>
