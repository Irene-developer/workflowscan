<?php
include 'include.php';

// Retrieve memo_id and Position_name from POST data
$memoId = $_POST['memo_id'];
$positionName = $_POST['Position_name'];

// Function to check if the Position_name has already approved for the given memo_id
function hasPositionApproved($memoId, $positionName) {
    // Add your database connection here

    // Prepare your SQL statement
    $sql = "SELECT * FROM memo_action WHERE memo_id = ? AND Position_name = ? AND status = 'approved'";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $memoId, $positionName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Position_name has already approved for the given memo_id
        return true;
    } else {
        // Position_name has not yet approved for the given memo_id
        return false;
    }
}

// Check if the Position_name has already approved for the given memo_id
try {
    $hasApproved = hasPositionApproved($memoId, $positionName);
    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode(array('approved' => $hasApproved));
} catch (Exception $e) {
    // Handle any exceptions and return an error response
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(array('error' => $e->getMessage()));
}
?>
