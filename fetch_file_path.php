<?php
// Include database connection
include 'include.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $memo_id = intval($_GET['id']);

    // Prepare and execute SQL query to fetch file path
    $sql = "SELECT file_path FROM memos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $memo = $result->fetch_assoc();
        $file_path = $memo['file_path'];

        // Return the file path in JSON format
        echo json_encode([
            'success' => true,
            'file_path' => $file_path
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'File path not found'
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No ID provided'
    ]);
}
?>
