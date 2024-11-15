<?php
// Establish a connection to your MariaDB database
$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "system_access"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the submitted form
    $supervisor_action = $_POST["supervisor_action"];
    $supervisor_justification = $_POST["supervisor_justification"];
    $record_id = $_POST["record_id"]; // Assuming you've included this hidden input field in your HTML form
        $supervised_by = $_POST["supervised_by"]; // Retrieve the supervised_by value from the form


    // Update the database with supervisor action and justification
    $sql = "UPDATE user_input_data SET supervisor_action=?, supervisor_justification=?, supervised_by WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $supervisor_action, $supervisor_justification, $record_id, $supervised_by);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Supervisor action and justification updated successfully.";
    } else {
        echo "Error updating supervisor action and justification: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Retrieve data from the database
$sql = "SELECT * FROM user_input_data";
$result = $conn->query($sql);

// Check if there are any records
if ($result->num_rows > 0) {
    // Output table header with additional columns
    echo "<form id='accessForm' method='post' action=''>";
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Request Type</th><th>Designation</th><th>Branch/HQ</th><th>System Name</th><th>AS Role</th><th>Justification</th><th>Date</th><th>Supervisor Action</th><th>Supervisor Justification</th><th>Supervised by</th></tr>";

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["name"]. "</td>";
        echo "<td>" . $row["request_type"]. "</td>";
        echo "<td>" . $row["designation"]. "</td>";
        echo "<td>" . $row["branch_hq"]. "</td>";
        echo "<td>" . $row["system_name"]. "</td>";
        echo "<td>" . $row["as_role"]. "</td>";
        echo "<td>" . $row["justification"]. "</td>";
        echo "<td>" . $row["date"]. "</td>";
        // Hidden input field for record ID
        echo "<td>";
        echo "<select name='supervisor_action'>";
        echo "<option value='Accept'>Accept</option>";
        echo "<option value='Decline'>Decline</option>";
        echo "</select>";
        echo "</td>";
        // Supervisor Justification input field
        echo "<td><input type='text' name='supervisor_justification'></td>";
        echo "<td><input type='text' name='supervised_by'></td>";
        // Hidden input field for record ID
        echo "<input type='hidden' name='record_id' value='" . $row["id"] . "'>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>"; // Removed the submit button from here
} else {
    echo "No records found";
}

// Close the database connection
$conn->close();
?>
