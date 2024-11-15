<?php
// fetch_request_details.php

include 'include.php'; // Ensure you include your database connection

if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Prepare and execute the query to fetch the details
    $stmt = $conn->prepare("SELECT * FROM user_input_data WHERE id = ?");
    $stmt->bind_param('i', $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data); // Return the data as JSON
    } else {
        echo json_encode(["error" => "No data found."]);
    }

    $stmt->close();
}
$conn->close();
?>
