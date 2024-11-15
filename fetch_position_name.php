<?php
// Establish a database connection
include 'include.php';

// Check if position_id is provided via POST request
if (isset($_POST['position_id'])) {
    // Sanitize the input
    $position_id = mysqli_real_escape_string($conn, $_POST['position_id']);

    // SQL query to fetch position name based on position_id
    $sql = "SELECT Position_name FROM positions WHERE position_id = '$position_id'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch position name from the result set
        $row = $result->fetch_assoc();
        $Position_name = $row['Position_name'];
        
        // Return position name as JSON response
        echo json_encode($Position_name);
    } else {
        // Position not found
        echo "Position not found";
    }
} else {
    // Position ID not provided
    echo "Position ID not provided";
}

// Close connection
$conn->close();
?>
