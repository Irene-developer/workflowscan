<?php
// Include your database connection settings
include 'include.php';

// Process POST data
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['ids']) && is_array($input['ids']) && !empty($input['ids'])) {
    $ids = $input['ids'];


    // Prepare statement
    $stmt = $conn->prepare("DELETE FROM position WHERE position_id = ?");

    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Execute statement for each ID
    try {
        $conn->begin_transaction();

        foreach ($ids as $id) {
            $stmt->bind_param('i', $id); // 'i' for integer
            $stmt->execute();
        }

        $conn->commit();

        // Return success response
        $response = ['success' => true];
    } catch (Exception $e) {
        // Handle database error
        $conn->rollback();
        $response = ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    $response = ['success' => false, 'message' => 'No valid IDs provided'];
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
