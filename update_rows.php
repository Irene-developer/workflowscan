<?php
// Include your database connection settings
include 'include.php';

// Process POST data
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input) && !empty($input)) {
    // Extract row data
    $positionId = $input['id'];
    $positionName = $input['updatePositionName'];
    $departmentName = $input['updateDepartmentName'];

    // Prepare statement
    $stmt = $conn->prepare("UPDATE position SET Position_name = ?, Department_name = ? WHERE position_id = ?");

    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    try {
        // Bind parameters and execute statement
        $stmt->bind_param('ssi', $positionName, $departmentName, $positionId);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            // Handle case where no rows were affected (optional)
            throw new Exception('No rows updated.');
        }

        // Commit transaction
        $conn->commit();

        // Return success response
        $response = ['success' => true];
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $response = ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    $response = ['success' => false, 'message' => 'No valid data provided'];
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
