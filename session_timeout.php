<?php
// File: session_timeout.php

session_start(); // Start or resume a session

// Set timeout duration in seconds (e.g., 600 seconds = 10 minutes)
$timeout_duration = 180; 

// Check if the "last activity" timestamp exists
if (isset($_SESSION['LAST_ACTIVITY'])) {
    // Calculate the session's lifetime
    $elapsed_time = time() - $_SESSION['LAST_ACTIVITY'];
    
    // Check if the session has timed out
    if ($elapsed_time > $timeout_duration) {
        // Session has expired, destroy it
        session_unset();     // Unset all session variables
        session_destroy();   // Destroy the session
        header("Location: logout_session.php"); // Redirect to logout page or login
        exit(); // Ensure the script stops running after the redirect
    }
}

// Update "last activity" timestamp
$_SESSION['LAST_ACTIVITY'] = time();

// Additional code for your application here, e.g., displaying content to logged-in users
?>
