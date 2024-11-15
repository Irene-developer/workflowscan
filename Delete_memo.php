<?php
// Include database configuration file
include 'include.php';

// Initialize response array
$response = array();

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and execute the SQL statement
    $sql = "DELETE FROM memos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Successfully deleted   
        $response['success'] = true;
        $response['message'] = "Record deleted successfully";
    } else {
        // Error occurred
        $response['success'] = false;
        $response['message'] = "Error deleting record: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = "No ID provided!";
}

// Encode response array to JSON and output
echo json_encode($response);
?>
