<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: Arial;
        }
    </style>
</head>
<body><?php
// Database configuration
include 'include.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $name = sanitize_input($conn, $_POST["name"]);
    $businessUnit = sanitize_input($conn, $_POST["business_unit"]);
    $phoneNumber = sanitize_input($conn, $_POST["phone_number"]);
    $emailAddress = sanitize_input($conn, $_POST["email_address"]);
    $branch = sanitize_input($conn, $_POST["branch"]);
    $reportingDate = $_POST["reporting_date"];
    $incidentDate = $_POST["incident_date"];
    $discoveryDate = $_POST["discovery_date"];
    $impactDateFrom = $_POST["impact_date_from"];
    $impactDateTo = $_POST["impact_date_to"];
    $whatHappened = sanitize_input($conn, $_POST["what_happened"]);
    $howItHappened = sanitize_input($conn, $_POST["how_it_happened"]);
    $impactsToBusiness = sanitize_input($conn, $_POST["impacts_to_business"]);
    $rootCauseSelect = sanitize_input($conn, $_POST["root_cause_select"]);
    $rootCauseInput = isset($_POST["root_cause_input"]) ? sanitize_input($conn, $_POST["root_cause_input"]) : "";
    $actionsTaken = sanitize_input($conn, $_POST["actions_taken"]);
    $status = sanitize_input($conn, $_POST["status"]);
    $priority = sanitize_input($conn, $_POST["priority"]);

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO incidents (name, business_unit, phone_number, email_address, branch, reporting_date, incident_date, discovery_date, impact_date_from, impact_date_to, what_happened, how_it_happened, impacts_to_business, root_cause_select, root_cause_input, actions_taken, status, priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssssss", $name, $businessUnit, $phoneNumber, $emailAddress, $branch, $reportingDate, $incidentDate, $discoveryDate, $impactDateFrom, $impactDateTo, $whatHappened, $howItHappened, $impactsToBusiness, $rootCauseSelect, $rootCauseInput, $actionsTaken, $status, $priority);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'New incident Reported Successfully.'
                    }).then(function() {
                        window.location = 'dashboard.php'; // Redirect to a new page if needed
                    });
                 </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form is not submitted, redirect to the form page
    header("Location: index.html");
    exit;
}

// Function to sanitize input
function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}
?>
</body>
</html>