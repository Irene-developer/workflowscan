<?php
// Include database connection
include 'include.php'; // Make sure to include your database connection file

// Check if 'search_date' is set in the GET parameters
if (isset($_GET['search_date'])) {
    $searchDate = $_GET['search_date'];

    // Prepare and execute the SQL query
    $sql = "SELECT * FROM memos WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $searchDate); // Assuming 'date_column' is the column storing the datetime in the database
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and display the results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display each row of data
            echo "ID: " . $row['id'] . " - Username: " . $row['username'] . " - Date: " . $row['date_column'] . "<br>";
        }
    } else {
        echo "No results found for the specified date.";
    }
    $stmt->close();
} else {
    echo "No date specified.";
}

// Close the database connection
$conn->close();
?>
