<?php
// Start the session securely
session_start();
include 'include.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit(); 
}

// Assign session variables to PHP variables safely
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$position_name = isset($_SESSION['Position_name']) ? $_SESSION['Position_name'] : '';
$department_name = isset($_SESSION['department_name']) ? $_SESSION['department_name'] : '';
$employee_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

// Include PHPMailer for sending emails
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define a function to send an email notification
function sendEmailNotification($email, $subject, $body) {
    $mail = new PHPMailer(true);
/*172.18.155.32
notifications@kcblbank.co.tz
Balancesheet@2024*/
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = '172.18.155.32';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'notifications@kcblbank.co.tz'; 
        $mail->Password   = 'Balancesheet@2026'; // Replace with actual password
       // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 25;

        // Recipients
        $mail->setFrom($mail->Username, 'System Notification');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}"); // Log the error instead of displaying it
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Access Form</title>

    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>     
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 95%;
        margin: 0 auto;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .input-wrapper {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }

    .dropdown-content-div {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        max-height: 160px; /* Limit height for 5 items (assuming each item is around 40px) */
        overflow-y: auto; /* Enable vertical scrolling */
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        cursor: pointer;
    }

    .dropdown-content-div div {
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .dropdown-content-div div:hover {
        background-color: #f1f1f1;
    }

    .div_access_head {
        display: flex; /* Use flexbox for alignment */
        justify-content: space-between; /* Space items evenly */
        align-items: center; /* Center items vertically */
        padding: 15px; /* Add padding around the container */
        margin-bottom: 20px; /* Space below the header */
        max-width: 95%;
    }

    .view-button {
        text-decoration: none; /* Remove underline from links */
        color: #ffffff; /* White text color */
        background-color: #3385ff; /* Button background color */
        padding: 10px 20px; /* Padding for button */
        border-radius: 5px; /* Rounded corners */
        transition: background-color 0.3s; /* Smooth background color change */
        font-weight: bold; /* Bold text */
        font-size: 16px; /* Adjust font size */
    }

    .view-button:hover {
        background-color: #007bff; /* Darker blue on hover */
    }

    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .hidden {
        display: none;
    }
</style>

</head>
<?php include 'header.php'; ?>
<body>

<div class="div_access_head">
    <a href="dashboard.php" class="view-button">Back</a>
   <a href="#" class="view-button" id="view-requests-button">View Your Requests</a>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="name" value="<?php echo htmlspecialchars($username); ?>">
    <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">
    <label for="department_name" class="hidden" >Department:</label>
    <input type="hidden" name="department_name" value="<?php echo htmlspecialchars($department_name); ?>" required><br>
    
    <label for="request_type" style="color: #3385ff">Request type:</label><br>
    <select id="request_type" name="request_type" required>
        <option value="create_new">Create New</option>
        <option value="disable_new">Disable</option>
        <option value="amend">Amend</option>
        <option value="reactivate_reset">Reactivate/Reset</option>
        <option value="delete">Delete</option>
    </select><br>

    <input type="hidden" name="designation" value="<?php echo htmlspecialchars($position_name); ?>" required>

    <label for="branch_hq" style="color: #3385ff">Branch/HQ:</label><br>
    <input type="text" id="branch_hq" name="branch_hq" required><br>

    <?php
    // Fetch system names from the kcbl_system table
    $sql = "SELECT system_name FROM kcbl_system";
    $result = $conn->query($sql);
    ?>
    
    <label for="system_name" style="color: #3385ff">System name:</label><br>
    <select id="system_name" name="system_name" required>
        <?php
        // Check if there are results and populate the dropdown
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['system_name']) . '">' . htmlspecialchars($row['system_name']) . '</option>';
            }
        } else {
            echo '<option value="">No systems available</option>';
        }
        ?>
    </select><br>

    <label for="justification" style="color: #3385ff">Justification:</label><br>
    <textarea id="justification" name="justification" rows="4" required></textarea><br>

    <input type="checkbox" id="accept_conditions" name="accept_conditions" required>
    <label for="accept_conditions">I have read and understand the policy and procedure governing the use of the system...</label><br>

    <label for="date" style="color: #3385ff">Date:</label><br>
    <input type="date" id="date" name="date" required><br>

<!-- Modal Structure -->
<div id="popup-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modal-body">
            <div id="data-table-container">
                <!-- Table or list of paginated content will be loaded here -->
                <table>
                    <!-- Table content loaded via AJAX here -->
                </table>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="input-wrapper">
        <!-- Left Side Input -->
        <div class="input-group" style="width: 48%;">
            <input type="text" name="supervisor1" class="form-control" id="session-input" placeholder="Click to fetch by session">





            <div id="session-dropdown" class="dropdown-content-div"></div>
        </div>

        <!-- Right Side Input -->
        <div class="input-group" style="width: 48%;">
            <input type="text" name="supervisor2"  class="form-control" id="ict-input" placeholder="Click to fetch by ICT Department">
            <div id="ict-dropdown" class="dropdown-content-div"></div>
        </div>
    </div>
</div>


    <input type="submit" value="Submit"></br>
</form>

<script>
    // Set today's date as default for date input
    document.getElementById("date").value = new Date().toISOString().slice(0, 10);
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $name = htmlspecialchars($_POST["name"]);
    $request_type = htmlspecialchars($_POST["request_type"]);
    $designation = htmlspecialchars($_POST["designation"]);
    $branch_hq = htmlspecialchars($_POST["branch_hq"]);
    $system_name = htmlspecialchars($_POST["system_name"]);
    $justification = htmlspecialchars($_POST["justification"]);
    $date = htmlspecialchars($_POST["date"]);
    $department_name = htmlspecialchars($_POST["department_name"]);
    $employee_id = htmlspecialchars($_POST["employee_id"]);
    
    // Capture the usernames from the input fields
    $supervisor1 = htmlspecialchars($_POST["supervisor1"]); // Username from left input
    $supervisor2 = htmlspecialchars($_POST["supervisor2"]); // Username from right input

    // Insert the data into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO user_input_data (name, request_type, designation, branch_hq, system_name, department_name, justification, date, employee_id, supervisor1, supervisor2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if the prepare statement is successful
    if ($stmt === false) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to prepare the SQL statement.'
            });
        </script>";
        error_log('SQL Error: ' . $conn->error);
        exit();
    }

    // Bind the parameters, including the supervisor usernames
    $stmt->bind_param("sssssssssss", $name, $request_type, $designation, $branch_hq, $system_name, $department_name, $justification, $date, $employee_id, $supervisor1, $supervisor2);

    if ($stmt->execute()) {
        // Get the last inserted ID for the new request
        $insertedId = $stmt->insert_id;

        // After successful insert, check if the supervisor1 exists in employee_access table
        $checkSupervisorStmt = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
        $checkSupervisorStmt->bind_param("s", $supervisor1);
        $checkSupervisorStmt->execute();
        $result = $checkSupervisorStmt->get_result();

        if ($result->num_rows > 0) {
            // If supervisor exists, fetch the email
            $supervisorData = $result->fetch_assoc();
            $supervisorEmail = $supervisorData['email'];

            // Prepare email notification for the supervisor
            $subject = 'Action Required: New System Access Request';
            $body = "
                <p>Dear $supervisor1,</p>
                <p>There is a new system access request from <strong>$name</strong> submitted on <strong>$date</strong> with the justification as below:</p>
                <p><strong>Justification:</strong> $justification</p>
                <p>This requires your action. Please log in to the system for quick action.</p>
                <p>Regards,</p>
                <p>KCBL ICT SUPPORT</p>
            ";

            // Send the notification email to the supervisor
            sendEmailNotification($supervisorEmail, $subject, $body);
        }

        $checkSupervisorStmt->close();

        // **Retrieve email of the person who submitted the request**
        $checkRequesterStmt = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
        $checkRequesterStmt->bind_param("s", $name);  // Assuming 'name' corresponds to the username in employee_access
        $checkRequesterStmt->execute();
        $resultRequester = $checkRequesterStmt->get_result();

        if ($resultRequester->num_rows > 0) {
            // If requester exists, fetch the email
            $requesterData = $resultRequester->fetch_assoc();
            $requester_email = $requesterData['email'];

            // Send an email to the requester (the person who submitted the request)
            $subject_to_requester = 'Confirmation: Your System Access Request Submitted';
            $body_to_requester = "
                <p>Dear $name,</p>
                <p>Your system access request has been successfully submitted on <strong>$date</strong> with the following justification:</p>
                <p><strong>Justification:</strong> $justification</p>
                <p>Your request is being processed and will be reviewed by the respective supervisor.</p>
                <p>Thank you for your submission!</p>
                <p>Regards,</p>
                <p>KCBL ICT SUPPORT</p>
            ";

            // Send confirmation email to the requester
            sendEmailNotification($requester_email, $subject_to_requester, $body_to_requester);
        }

        $checkRequesterStmt->close();

        // Notify the requester of successful submission
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Your request has been submitted successfully!',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard.php';
            }
        });
    </script>";

    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to submit your request. Please try again later.'
            });
        </script>";
        error_log('SQL Execution Error: ' . $stmt->error);
    }
    $stmt->close();
}
$conn->close(); // Close the connection
?>




<script>
    // Populate username into input field when clicking dropdown item
    function populateInputFromDropdown(inputElement, dropdownElement, department) {
        const inputRect = inputElement.getBoundingClientRect();
        dropdownElement.style.top = `${inputRect.top - dropdownElement.offsetHeight}px`; // Position above input
        dropdownElement.style.left = `${inputRect.left}px`;
        dropdownElement.style.width = `${inputRect.width}px`;

        // Fetch employee names and usernames based on department
        fetchNames(department).then(data => {
            dropdownElement.innerHTML = ''; // Clear any existing items
            data.forEach(item => {
                const div = document.createElement('div');
                div.textContent = `${item.name} (${item.username})`;
                
                // When dropdown item is clicked, populate input with username
                div.addEventListener('click', function() {
                    inputElement.value = item.username; // Populate the input field with the username
                    dropdownElement.style.display = 'none'; // Hide the dropdown
                });

                dropdownElement.appendChild(div);
            });
            dropdownElement.style.display = 'block'; // Show dropdown
        });
    }

    // Left Side Input (Session)
    document.getElementById('session-input').addEventListener('click', function() {
        const inputElement = this;
        const dropdownElement = document.getElementById('session-dropdown');
        populateInputFromDropdown(inputElement, dropdownElement, 'session');
    });

    // Right Side Input (ICT Department)
    document.getElementById('ict-input').addEventListener('click', function() {
        const inputElement = this;
        const dropdownElement = document.getElementById('ict-dropdown');
        populateInputFromDropdown(inputElement, dropdownElement, 'INFORMATION AND COMMUNICATION TECHNOLOGY');
    });

    // Function to fetch names from the server
    async function fetchNames(department) {
        // Fetching data from the PHP backend
        const response = await fetch('fetch_employee_supervisor.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ department_name: department })
        });
        return await response.json(); // Assuming the response is a JSON array of { name, username }
    }

    // Hide dropdowns when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.matches('input')) {
            document.getElementById('session-dropdown').style.display = 'none';
            document.getElementById('ict-dropdown').style.display = 'none';
        }
    });
</script>

<script>
// Get the modal
var modal = document.getElementById("popup-modal");

// Get the button that opens the modal
var btn = document.getElementById("view-requests-button");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function(event) {
    event.preventDefault(); // Prevent default button behavior

    // Fetch the content from my_system_access_request.php
    fetch('my_system_access_request.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            // Populate the modal body with the fetched content
            document.getElementById('data-table-container').innerHTML = data; // Fill table container
            modal.style.display = "block"; // Show the modal
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
            document.getElementById('modal-body').innerHTML = "<h2>Error loading content.</h2>";
            modal.style.display = "block"; // Show modal with error message
        });
}

// Close the modal when the user clicks on <span> (x)
span.onclick = function() {
    modal.style.display = "none";
}

// Close the modal if the user clicks anywhere outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Function to handle pagination and reload only the table content
function loadPage(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'my_system_access_request.php?page=' + page, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Target the table or container holding the paginated data
            document.getElementById('data-table-container').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

</script>

<?php include 'footer.php'; ?>
</body>
</html>
