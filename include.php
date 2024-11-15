<?php  
  // Your database connection details
    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $dbname = "access_form";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>