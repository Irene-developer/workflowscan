<?php
// Include your database connection
include 'include.php';

// Check if position_name is provided in the POST request
if(isset($_POST['Position_name'])) {
    $positionName = $_POST['Position_name'];

    // Prepare and execute SQL query to check if Position_name exists in memo_action table
    $sql = "SELECT COUNT(*) AS count FROM memo_action WHERE Position_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $positionName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Respond with JSON indicating whether Position_name exists or not
    echo json_encode($row['count'] > 0 ? "exists" : "not exists");

    // Close database connection and statement
    $stmt->close();
    $conn->close();
} else {
    // Handle case where position_name is not provided
    echo json_encode(array("error" => "Position_name not provided"));
}
?>
