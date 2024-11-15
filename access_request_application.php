
<?php
// Start the session securely
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Assign session variable to a PHP variable
$username = $_SESSION['username'];

// Database connection
require 'include.php'; // Adjust the path as necessary

//email logic
// Assign session variables to PHP variables safely
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$position_name = isset($_SESSION['Position_name']) ? $_SESSION['Position_name'] : '';
$department_name = isset($_SESSION['department_name']) ? $_SESSION['department_name'] : '';
$employee_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

/*172.18.155.32
notifications@kcblbank.co.tz
Balancesheet@2025
*/


// Include PHPMailer for sending emails
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define a function to send an email notification
function sendEmailNotification($email, $subject, $body) {
    $mail = new PHPMailer(true);

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

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filtering variables
$date_filter = isset($_POST['date_filter']) ? $_POST['date_filter'] : '';
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';
$system_name_filter = isset($_POST['system_name_filter']) ? $_POST['system_name_filter'] : '';

// Prepare the base SQL query for fetching system access requests
$sql = "SELECT * FROM user_input_data WHERE ((supervisor1 = ? AND status = 'Pending to first approver') OR (supervisor2 = ? AND status = 'Pending to second approver'))";

// Filtering logic (only add WHERE conditions if the filter is set)
$params = [$username, $username]; // For supervisor1 and supervisor2 comparisons
$types = "ss"; // Two string types for username comparison

if (!empty($date_filter)) {
    $sql .= " AND date = ?";
    $params[] = $date_filter;
    $types .= "s";
}
if (!empty($status_filter)) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}
if (!empty($system_name_filter)) {
    $sql .= " AND system_name = ?";
    $params[] = $system_name_filter;
    $types .= "s";
}

$sql .= " LIMIT ? OFFSET ?"; // Add pagination at the end
$params[] = $limit;
$params[] = $offset;
$types .= "ii"; // Integer types for LIMIT and OFFSET

// Prepare the statement and bind the dynamic parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of records for pagination (this part is also modified)
$total_sql = "SELECT COUNT(*) FROM user_input_data WHERE ((supervisor1 = ? AND status = 'Pending to first approver') OR (supervisor2 = ? AND status = 'Pending to second approver'))";
$total_params = [$username, $username];
$total_types = "ss";

if (!empty($date_filter)) {
    $total_sql .= " AND date = ?";
    $total_params[] = $date_filter;
    $total_types .= "s";
}
if (!empty($status_filter)) {
    $total_sql .= " AND status = ?";
    $total_params[] = $status_filter;
    $total_types .= "s";
}
if (!empty($system_name_filter)) {
    $total_sql .= " AND system_name = ?";
    $total_params[] = $system_name_filter;
    $total_types .= "s";
}

// Prepare the total count statement
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param($total_types, ...$total_params);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_count = $total_result->fetch_row()[0];
$total_pages = ceil($total_count / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My System Access Requests</title>
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
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
        padding: 5px;
    }
    h2 {
        text-align: center;
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #3385ff;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    .pagination {
        text-align: center;
        margin-top: 20px;
    }
    .pagination a {
        margin: 0 5px;
        padding: 8px 12px;
        background-color: #3385ff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .pagination a.active {
        background-color: #4CAF50;
    }
    .filter-container {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }
    .filter-container input,
    .filter-container select {
        padding: 10px;
        margin-right: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .action-button {
        background-color: #3385ff;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    /* Popup container */
    .popup {
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 50%; /* Center the popup */
        top: 50%; /* Center the popup */
        transform: translate(-50%, -50%); /* Translate for perfect centering */
        width: 70%; /* Set a width for the popup */
        background-color: white; /* Background color */
        border: 1px solid #ccc; /* Border around the popup */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        padding: 20px; /* Padding inside the popup */
        display: none; /* Hidden by default */
    }

    /* Close button */
    .popup .close {
        position: absolute; /* Position it in the corner */
        top: 10px; /* 10px from the top */
        right: 20px; /* 20px from the right */
        font-size: 24px; /* Font size for close button */
        color: #333; /* Color of close button */
        cursor: pointer; /* Cursor pointer on hover */
    }

    /* Title */
    .popup h3 {
        margin: 0; /* Remove default margin */
        padding-bottom: 15px; /* Space below the title */
        font-size: 18px; /* Font size for title */
        color: #3385ff; /* Title color */
    }

    /* Form elements */
    .popup form {
        display: flex; /* Use flexbox for form layout */
        flex-direction: column; /* Stack elements vertically */
    }

    /* Textarea styling */
    .popup textarea {
        width: 97%; /* Full width */
        height: 80px; /* Height of textarea */
        margin-bottom: 15px; /* Space below the textarea */
        padding: 10px; /* Padding inside textarea */
        border: 1px solid #ccc; /* Border color */
        border-radius: 5px; /* Rounded corners */
        resize: none; /* Disable resizing */
    }

    /* Select dropdown */
    .popup select {
        padding: 10px; /* Padding inside select */
        border: 1px solid #ccc; /* Border color */
        border-radius: 5px; /* Rounded corners */
        margin-bottom: 15px; /* Space below the select */
    }

    /* Username input */
    #implementorUsername {
        width: 97%; /* Full width */
        padding: 10px; /* Padding inside input */
        border: 1px solid #ccc; /* Border color */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        margin-bottom: 10px;
    }

    /* Dropdown list */
    .dropdown-content-div {
        position: absolute; /* Position dropdown */
        background-color: white; /* Background color */
        border: 1px solid #ccc; /* Border color */
        border-radius: 5px; /* Rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        z-index: 1001; /* Ensure dropdown is on top */
        width: calc(100% - 20px); /* Width of dropdown */
        max-height: 200px; /* Maximum height for dropdown */
        overflow-y: auto; /* Scroll if overflow */
    }

    /* Individual dropdown items */
    .dropdown-content-div div {
        padding: 10px; /* Padding inside each item */
        cursor: pointer; /* Pointer cursor on hover */
    }

    /* Dropdown item hover effect */
    .dropdown-content-div div:hover {
        background-color: #f0f0f0; /* Light grey background on hover */
    }

    /* Submit button */
    .action-button {
        padding: 10px 15px; /* Padding for button */
        border: none; /* No border */
        border-radius: 5px; /* Rounded corners */
        background-color: #3385ff; /* Button color */
        color: white; /* Text color */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s; /* Smooth transition */
    }

    /* Button hover effect */
    .action-button:hover {
        background-color: #007bff; /* Darker shade on hover */
    }

    /* View button */
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


    </style>
</head>
<?php include 'header.php'; ?>
<body>

<h2>All System Access Requests</h2>

<div class="filter-container">
    <a href="dashboard.php" class="view-button">Back</a>
    <form method="POST" action="">
        
        <input type="date" name="date_filter" placeholder="Filter by Date" value="<?php echo htmlspecialchars($date_filter); ?>">

        <select name="status_filter">
            <option value="">Select Status</option>
            <option value="Approved" <?php if ($status_filter == 'Approved') echo 'selected'; ?>>Approved</option>
            <option value="Rejected" <?php if ($status_filter == 'Rejected') echo 'selected'; ?>>Rejected</option>
            <option value="Pending to second approver" <?php if ($status_filter == 'Pending to second approver') echo 'selected'; ?>>Pending to second approver</option>
            <option value="Pending to first approver" <?php if ($status_filter == 'Pending to first approver') echo 'selected'; ?>>Pending to first approver</option>
        </select>

        <input type="text" name="system_name_filter" placeholder="Filter by System Name" value="<?php echo htmlspecialchars($system_name_filter); ?>">
        <button type="submit" class="action-button">Filter</button>
    </form>
</div>

<table id="requestTable">
    <tr>
        <th>From</th>
        <th>Request Type</th>
        <th>System Name</th>
        <th>Justification</th>
        <th>Status</th>
        <th>Actioned by</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php
    // Check if there are results and populate the table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['request_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['system_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['justification']) . '</td>';
            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
            echo '<td>' . htmlspecialchars($row['supervised_by']) . '</td>';
            echo '<td>' . htmlspecialchars($row['date']) . '</td>';
            echo '<td><button class="action-button" onclick="openPopup(' . $row['id'] . ')">Action</button></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">No requests found.</td></tr>';
    }
    ?>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="#" class="<?php echo ($i == $page) ? 'active' : ''; ?>" onclick="loadPage(<?php echo $i; ?>)"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<?php
// Start the session
// session_start(); // Uncomment this line if session management is required

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    // Get the current username from the session
    $username = $_SESSION['username'];
    $supervisor2 = $_SESSION['username'];
    $supervised_by = $_SESSION['username']; // Capture the current session's username
    // Capture form data
    $request_id = $_POST['request_id'];
    $comment = $_POST['comment'];
    $action = $_POST['action'];

    // Fetch the request to check who is supervisor1 and supervisor2
    $stmt = $conn->prepare("SELECT supervisor1, supervisor2 FROM user_input_data WHERE id = ?");
    $stmt->bind_param('i', $request_id);
    $stmt->execute();
    $stmt->bind_result($supervisor1, $supervisor2);
    $stmt->fetch();
    $stmt->close();

    // Set the status variable
    $status = null;

    // Determine which supervisor is taking action and update accordingly
    if ($username === $supervisor1) {
        // Supervisor 1 is taking action
        $status = ($action === 'approve') ? 'Pending to second approver' : 'Rejected';

        // Update the request with Supervisor 1's decision
        $stmt = $conn->prepare("UPDATE user_input_data SET status = ?, comments_for_supervisor1 = ?, supervised_by = ? WHERE id = ?");
        $stmt->bind_param('sssi', $status, $comment, $supervised_by, $request_id); // Correct bind_param signature
        $stmt->execute();

        if ($stmt) {
                    // Fetch the username of the requester from user_input_data table
        $stmt_fetch = $conn->prepare("SELECT name FROM user_input_data WHERE id = ?");
        $stmt_fetch->bind_param('i', $request_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($requester_username);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Check if the username from user_input_data matches the username in employee_access
        $stmt_email = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
        $stmt_email->bind_param('s', $requester_username);
        $stmt_email->execute();
        $stmt_email->bind_result($requester_email);
        $stmt_email->fetch();
        $stmt_email->close();


        // Send email notification to the requester
        if (!empty($requester_email)) {
            $subject = "Your System Access Request Has Been Actioned";
            $body = "
                <p>Dear $requester_username,</p>

                <p>Your system access request with ID $request_id has been actioned by $username (Supervisor ).</p>

                <p>Please login to the system to view the feedback in detail.</p>

                <p>Thanks & Regards,</p>
                <p>KCBL_ICT_SUPPORT</p>
            ";

            sendEmailNotification($requester_email, $subject, $body); // Assuming sendEmailNotification is a pre-existing function
        }

//for supervisor2

        // Fetch the username of the requester from user_input_data table
        $stmt_fetch = $conn->prepare("SELECT supervisor2 FROM user_input_data WHERE id = ?");
        $stmt_fetch->bind_param('i', $request_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($supervisor2);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Check if the username from user_input_data matches the username in employee_access
        $stmt_email = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
        $stmt_email->bind_param('s', $supervisor2);
        $stmt_email->execute();
        $stmt_email->bind_result($supervisor2_email);
        $stmt_email->fetch();
        $stmt_email->close();

        // Send email notification to the implementor
        if (!empty($supervisor2_email)) {
            $subject = "System Access Request Second Approval ";
            $body = "
                <p>Dear $supervisor2,</p>

                <p>There is a system access request with ID $request_id that has been actioned by $username.</p>
                <p>Requires Your action.</p>
                <p>Please login to the system to take action on the request.</p>

                <p>Thanks & Regards,</p>
                <p>KCBL_ICT_SUPPORT</p>
            ";

            sendEmailNotification($supervisor2_email, $subject, $body); // Assuming sendEmailNotification is a pre-existing function
        }
echo "<script>
        Swal.fire({
            title: 'Update Successful!',
            text: 'The request has been updated successfully.',
            icon: 'success',
            confirmButtonText: 'Okay'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard.php';
            }
        });
    </script>";
        } else {
echo "<script>
        Swal.fire({
            title: 'Update Failed!',
            text: 'There was an error updating the request. Please try again.',
            icon: 'error',
            confirmButtonText: 'Okay'
        }).then(() => {
            window.location.href = 'dashboard.php';
        });
    </script>";
        }
        $stmt->close();
    } elseif ($username === $supervisor2) {
    // Supervisor 2 is taking action
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    // Assume you have an input field for the implementor username
    $implementor_username = $_POST['implementor_username']; // Get the implementor username from input

    // Update the request with Supervisor 2's decision
    $stmt = $conn->prepare("UPDATE user_input_data SET status = ?, comments_for_supervisor2 = ?, supervised_by = ? , implementor = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $status, $comment, $supervised_by, $implementor_username, $request_id);
    $stmt->execute();

    if ($stmt) {
        // Fetch the username of the requester from user_input_data table
        $stmt_fetch = $conn->prepare("SELECT name FROM user_input_data WHERE id = ?");
        $stmt_fetch->bind_param('i', $request_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($requester_username);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Check if the username from user_input_data matches the username in employee_access
        $stmt_email = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
        $stmt_email->bind_param('s', $requester_username);
        $stmt_email->execute();
        $stmt_email->bind_result($requester_email);
        $stmt_email->fetch();
        $stmt_email->close();

//for implementor

        // Fetch the username of the requester from user_input_data table
        $stmt_fetch = $conn->prepare("SELECT implementor FROM user_input_data WHERE id = ?");
        $stmt_fetch->bind_param('i', $request_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($implementor_username);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Check if the username from user_input_data matches the username in employee_access
        $stmt_email = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
        $stmt_email->bind_param('s', $implementor_username);
        $stmt_email->execute();
        $stmt_email->bind_result($implementor_email);
        $stmt_email->fetch();
        $stmt_email->close();

        // Send email notification to the requester
        if (!empty($requester_email)) {
            $subject = "Your System Access Request Has Been Actioned";
            $body = "
                <p>Dear $requester_username,</p>

                <p>Your system access request with ID $request_id has been actioned by $username (Supervisor 2).</p>
                <p>Please login to the system to view the feedback in detail.</p>

                <p>Thanks & Regards,</p>
                <p>KCBL_ICT_SUPPORT</p>
            ";

            sendEmailNotification($requester_email, $subject, $body); // Assuming sendEmailNotification is a pre-existing function
        }
        // Send email notification to the implementor
        if (!empty($implementor_email)) {
            $subject = "Your System Access Request Has Been Actioned";
            $body = "
                <p>Dear $implementor_username,</p>

                <p>You have been assigned to detail system access request with ID $request_id that has been actioned by $username.</p>
                <p>Please login to the system to make follow-up on the request.</p>

                <p>Thanks & Regards,</p>
                <p>KCBL_ICT_SUPPORT</p>
            ";

            sendEmailNotification($implementor_email, $subject, $body); // Assuming sendEmailNotification is a pre-existing function
        }
        // Success message
echo "<script>
        Swal.fire({
            title: 'Update Successful!',
            text: 'The request has been updated successfully.',
            icon: 'success',
            confirmButtonText: 'Okay'
        }).then(() => {
            window.location.href = 'dashboard.php';
        });
    </script>";
    } else {
            echo "<script>
        Swal.fire({
            title: 'Update Failed!',
            text: 'There was an error updating the request. Please try again.',
            icon: 'error',
            confirmButtonText: 'Okay'
        }).then(() => {
            window.location.href = 'dashboard.php';
        });
    </script>";
    }

    $stmt->close();
} else {
    // The user is neither supervisor1 nor supervisor2
    echo "<script>
        Swal.fire({
            title: 'Permission Denied',
            text: 'You do not have permission to take action on this request.',
            icon: 'error',
            confirmButtonText: 'Okay'
        }).then(() => {
            window.location.href = '" . $_SERVER['PHP_SELF'] . "?page=" . $page . "';
        });
    </script>";
    exit(); // Prevent further processing
}

}

// Ensure no output before this point to avoid "headers already sent" warning
?>



<div class="popup" id="actionPopup" style="display: none;">
    <span class="close" onclick="closePopup()">&times;</span>
    <h3>Action Request by <?php echo htmlspecialchars($username); ?></h3>
    <form id="actionForm" method="POST">
        <!-- Hidden input to store the request ID -->
        <input type="hidden" name="request_id" id="requestId">
        <textarea name="comment" placeholder="Add a comment" required></textarea>
        <select name="action" id="actionSelect" required onchange="toggleUsernameInput()">
            <option value="approve1">Choose</option>
            <option value="approve">Approve</option>
            <option value="reject">Reject</option>
        </select>

        <!-- Username selection input -->
        <div id="usernameDiv" style="display: none;">
            <h3>Assign task</h3>
            <input type="text" name="implementor_username" id="implementorUsername" placeholder="Select Implementor" readonly onclick="showDropdown()">
            <div id="dropdownList" class="dropdown-content-div" style="display: none;"></div>
        </div>

        <button type="submit" class="action-button">Submit</button>
    </form>
</div>

<script>
    function toggleUsernameInput() {
        const action = document.getElementById('actionSelect').value;
        const usernameDiv = document.getElementById('usernameDiv');
        
        if (action === 'approve') {
            usernameDiv.style.display = 'block';
        } else {
            usernameDiv.style.display = 'none';
            document.getElementById('implementorUsername').value = ''; // Clear input if not needed
            document.getElementById('dropdownList').style.display = 'none'; // Hide dropdown
        }
    }

    function showDropdown() {
        const dropdown = document.getElementById('dropdownList');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle dropdown visibility
    }

    function selectUsername(username) {
        document.getElementById('implementorUsername').value = username; // Set the input value
        document.getElementById('dropdownList').style.display = 'none'; // Hide dropdown after selection
    }

    // Fetch usernames from the database when the dropdown is clicked
    document.getElementById('implementorUsername').addEventListener('focus', function() {
        fetchUsernames();
    });

    function fetchUsernames() {
        // Use AJAX to fetch usernames from employee_access
        const dropdown = document.getElementById('dropdownList');
        dropdown.innerHTML = ''; // Clear previous options

        fetch('fetch_usernames_implemetor.php') // Adjust this URL to your actual endpoint
            .then(response => response.json())
            .then(data => {
                data.forEach(user => {
                    const div = document.createElement('div');
                    div.innerText = user.username; // Adjust based on your database structure
                    div.onclick = function() { selectUsername(user.username); };
                    dropdown.appendChild(div);
                });
                dropdown.style.display = 'block'; // Show the dropdown
            })
            .catch(error => console.error('Error fetching usernames:', error));
    }
</script>

<script>
    function loadPage(page) {
        window.location.href = "?page=" + page;
    }

    function openPopup(id) {
        document.getElementById('requestId').value = id;
        document.getElementById('actionPopup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('actionPopup').style.display = 'none';
    }
</script>
<?php include 'footer.php'; ?>

</body>
</html>
