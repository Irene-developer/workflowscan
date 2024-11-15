<?php
// Assuming you have a database connection established already

// Retrieve memo_id, Position_name, and action from POST data
$memoId = $_POST['memo_id'];
$positionName = $_POST['Position_name'];
$action = $_POST['action'];

// Function to check if the combination of memo_id and Position_name exists for the specified action
function isActionAllowed($memoId, $positionName, $action) {
    // Add your database query here to check if the combination exists in the memo_action table
    // Prepare your SQL statement
    $sql = "SELECT * FROM memo_action WHERE memo_id = ? AND Position_name = ? AND status = ?";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $memoId, $positionName, $action);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Combination exists for the specified action
        return true;
    } else {
        // Combination does not exist for the specified action
        return false;
    }
}

// Check if the combination of memo_id and Position_name exists for the specified action
$isAllowed = isActionAllowed($memoId, $positionName, $action);

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode(array('allowed' => $isAllowed));
?>
