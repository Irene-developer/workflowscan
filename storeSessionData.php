<?php
session_start();

// Function to handle JSON input
function getJSONInput() {
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve JSON data
    $data = getJSONInput();

    // Validate data
    if (isset($data['inputValues']) && is_array($data['inputValues'])) {
        // Sanitize and store data in the session
        $_SESSION['input_values'] = array_map('htmlspecialchars', $data['inputValues']);
        
        // Store data into individual approver session variables
        $_SESSION['approver1'] = isset($data['inputValues'][0]) ? htmlspecialchars($data['inputValues'][0]) : null;
        $_SESSION['approver2'] = isset($data['inputValues'][1]) ? htmlspecialchars($data['inputValues'][1]) : null;
        $_SESSION['approver3'] = isset($data['inputValues'][2]) ? htmlspecialchars($data['inputValues'][2]) : null;

        // Respond with success
        http_response_code(200);
        echo json_encode(['message' => 'Data stored successfully', 'status' => 'success']);
    } else {
        // Respond with error if data is invalid
        http_response_code(400);
        echo json_encode(['message' => 'Invalid data', 'status' => 'error']);
    }
} else {
    // Respond with error if method is not POST
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed', 'status' => 'error']);
}
?>
