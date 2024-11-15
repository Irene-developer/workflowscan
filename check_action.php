<?php
// Include database connection
include 'include.php'; // Adjust this to your database connection file path

// Check if position_name and expId are set in the POST request
if(isset($_POST['position_name'], $_POST['expId'])) {
    // Sanitize the input to prevent SQL injection
    $position_name = mysqli_real_escape_string($conn, $_POST['position_name']);
    $expId = intval($_POST['expId']); // Assuming expId is an integer
    
    // Query to check if the action has already been taken by the same Position_name
    $sql_check_action = "SELECT COUNT(*) AS action_exists FROM imprest_action WHERE Position_name = ? AND imprest_id = ?";
    $stmt_check_action = $conn->prepare($sql_check_action);
    $stmt_check_action->bind_param("si", $position_name, $expId);
    $stmt_check_action->execute();
    $result_check_action = $stmt_check_action->get_result();
    $row_check_action = $result_check_action->fetch_assoc();

    if ($row_check_action['action_exists'] > 0) {
        // Action already taken by the same Position_name, return 'duplicate'
        echo 'duplicate';
    } else {
        // Action has not been taken by the same Position_name
        // You can perform additional actions here if needed
    }

    // Close statement and connection
    $stmt_check_action->close();
    $conn->close();
} else {
    // If position_name or expId is not set in the POST request, return an error message
    echo 'Error: position_name or expId not provided.';
}
?>
