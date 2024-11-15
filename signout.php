<?php
// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['empployee_id'])) {
    // If logged in, destroy the session
    session_unset();
    session_destroy();
    // Redirect to the index page or any other desired page
    header("Location: index.php");
    exit();
} else {
    // If not logged in, redirect to the index page
    header("Location: index.php");
    exit();
}
?>
