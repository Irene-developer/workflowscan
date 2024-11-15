<?php
// Include database connection
include 'include.php';

// Start the PHP session
session_start();

// Check if the username is set in the session
if(isset($_SESSION['username'])) {
    // If username is set, retrieve it
    $username = $_SESSION['username'];

    // Prepare and execute SQL query to fetch signature URL based on the username
    $sql = "SELECT signature_path FROM signature WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // If a row is found, fetch the signature URL
        $row = mysqli_fetch_assoc($result);
        $signatureURL = $row['signature_path'];

        // Return the signature URL as response
        echo $signatureURL;
    } else {
        // If no row is found, return an empty string or an appropriate message
        echo "Signature URL not found for the user.";
    }
} else {
    // If username is not set in the session, return an error message
    echo "Error: Username not set in the session.";
}

// Close database connection
mysqli_close($conn);
?>
