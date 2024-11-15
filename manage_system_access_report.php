<?php
// Start the session securely
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require 'include.php'; // Adjust the path as necessary

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filtering variables
$date_filter = isset($_POST['date_filter']) ? $_POST['date_filter'] : '';
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';
$system_name_filter = isset($_POST['system_name_filter']) ? $_POST['system_name_filter'] : '';

// Prepare the base SQL query for fetching system access requests
$sql = "SELECT * FROM user_input_data WHERE 1=1"; // Use WHERE 1=1 to easily append filters

// Filtering logic (only add WHERE conditions if the filter is set)
$params = []; // For dynamic filtering
$types = '';  // Bind param types

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

// Add pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii"; // Integer types for LIMIT and OFFSET

// Prepare the statement and bind the dynamic parameters
$stmt = $conn->prepare($sql);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of records for pagination (this part is also modified)
$total_sql = "SELECT COUNT(*) FROM user_input_data WHERE 1=1";

// Apply filters to the total count query
$total_params = [];
$total_types = '';

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
if (!empty($total_types)) {
    $total_stmt->bind_param($total_types, ...$total_params);
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

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
.report-heading {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 28px;
        font-weight: bold;
        color: #3385ff; /* Blue color */
        text-align: center; /* Center align */
        margin-bottom: 20px; /* Add space below */
        padding: 10px;
        background-color: #f7f9fc; /* Light background */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        letter-spacing: 1px; /* Slight letter spacing for readability */
    }

    </style>
</head>
<?php include 'header.php'; ?>
<body>

<h2 class="report-heading">System Access Requests Report</h2>

<div class="filter-container">
    <a href="dashboard.php" class="view-button">Back</a>
    <form method="POST" action="">
        
        <input type="date" name="date_filter" placeholder="Filter by Date" value="<?php echo htmlspecialchars($date_filter); ?>">

        <select name="status_filter">
            <option value="">Select Status</option>
            <option value="Done" <?php if ($status_filter == 'Done') echo 'selected'; ?>>Done</option>
            <option value="In Progress" <?php if ($status_filter == 'In Progress') echo 'selected'; ?>>In Progress</option>
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
        <th>Request Type</th>
        <th>System Name</th>
        <th>Justification</th>
        <th>Status</th>
        <th>Actioned by</th>
        <th>Date</th>
        <th>View</th>
    </tr>
    <?php
    // Check if there are results and populate the table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['request_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['system_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['justification']) . '</td>';
            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
            echo '<td>' . htmlspecialchars($row['supervised_by']) . '</td>';
            echo '<td>' . htmlspecialchars($row['date']) . '</td>';
            echo '<td><button class="action-button" onclick="openPopup(' . $row['id'] . ')">View</button></td>';
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

<div class="popup" id="actionPopup" style="display: none;">
    <span class="close" onclick="closePopup()">&times;</span>
    <h3>SYSTEM ACCESS REQUEST REPORT</h3>
    <form id="actionForm" method="POST">
        <!-- Hidden input to store the request ID -->
        <input type="hidden" name="request_id" id="requestId">
    </form>
</div>


<script>
    function loadPage(page) {
        window.location.href = "?page=" + page;
    }

    function openPopup(id) {
        // Set the request ID in the hidden input
        document.getElementById('requestId').value = id;

        // Fetch the request details
        fetch('fetch_system_request_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'request_id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('actionPopup').innerHTML = "<p>Error: " + data.error + "</p>";
            } else {
                // Populate the popup with the fetched data
                let detailsHtml = `<h4 

                style='background-color: #3385ff; 
                padding: 5px; text-align: center;
                color: white;
                border-radius: 0.2em;
                '>SYSTEM ACCESS REQUEST DETAILS:</h4>
                                   <p style='padding: 3px;'><strong>Name:</br></strong> ${data.name}</p>
                                   <p><strong>Request Type:</br></strong> ${data.request_type}</p>
                                   <p><strong>Designation:</br></strong> ${data.designation}</p>
                                   <p><strong>Branch HQ:</br></strong> ${data.branch_hq}</p>
                                   <p><strong>System Name:</br></strong> ${data.system_name}</p>
                                   <p><strong>Justification:</br></strong> ${data.justification}</p>
                                   <p><strong>Date:</br></strong> ${data.date}</p>
                                   <p><strong>Employee ID:</br></strong> ${data.employee_id}</p>`;
                // Set the popup content
                document.getElementById('actionPopup').innerHTML = detailsHtml + '<span class="close" onclick="closePopup()">&times;</span>';
            }
            // Show the popup
            document.getElementById('actionPopup').style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('actionPopup').innerHTML = "<p>Error fetching request details.</p><span class='close' onclick='closePopup()'>&times;</span>";
            document.getElementById('actionPopup').style.display = 'block';
        });
    }

    function closePopup() {
        document.getElementById('actionPopup').style.display = 'none';
        location.reload(); // Refresh the page
    }
</script>

<?php include 'footer.php'; ?>
</body>
</html>
