<?php
// Include your database connection file
include 'include.php'; 

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $request_id = isset($_POST['request_id']) ? trim($_POST['request_id']) : '';
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Validate input
    if (empty($request_id) || empty($comment)) {
        http_response_code(400); // Bad request
        echo 'Invalid input.';
        exit();
    }

    // Prepare SQL statement
    if ($stmt = $conn->prepare("UPDATE asset_requests SET comment = ?, status = 'rejected' WHERE request_id = ?")) {
        $stmt->bind_param('si', $comment, $request_id); // Bind $comment as string and $request_id as integer

        // Execute the statement
        if ($stmt->execute()) {
            echo 'Success';
            echo ' Request ID: ' . htmlspecialchars($request_id); // Show Request ID
        } else {
            http_response_code(500); // Internal Server Error
            echo 'Error updating the request.';
        }

        // Close the statement
        $stmt->close();
    } else {
        http_response_code(500); // Internal Server Error
        echo 'Error preparing the SQL statement.';
    }

    // Close the connection
    $conn->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo 'Method not allowed.';
}
?>
