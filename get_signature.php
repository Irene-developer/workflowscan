<?php
include 'include.php';
// Start session
session_start();

// Check if user is logged in and username is set in session
if(isset($_SESSION['username'])) {
    // Assuming you have a database connection already established
    // Fetch the signature from the database based on the username in the session
    $username = $_SESSION['username'];

    // Example query: Fetch signature_path from the signature table based on the username
    // Replace 'your_query_here' with your actual SQL query
    $query = "SELECT signature_path FROM signature WHERE username = '$username'";
    
    // Execute the query
    // Assuming you're using mysqli
    $result = mysqli_query($conn, $query);

    if($result) {
        // Fetch the signature path from the result
        $row = mysqli_fetch_assoc($result);
        $signature_path = $row['signature_path'];

        // Output the signature path
        echo $signature_path;
    } else {
        // Error handling if the query fails
        echo "Error fetching signature";
    }
} else {
    // Redirect or handle if user is not logged in
    echo "User not logged in";
}
?>
