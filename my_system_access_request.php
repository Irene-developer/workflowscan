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

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch system access requests for the logged-in user with pagination
$sql = "SELECT * FROM user_input_data WHERE name = ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $username, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of records for pagination
$total_sql = "SELECT COUNT(*) FROM user_input_data WHERE name = ?";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param("s", $username);
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>

<h2>My System Access Requests</h2>

<table id="requestTable">
    <tr>
        <th>Request Type</th>
        <th>System Name</th>
        <th>Justification</th>
        <th>Status</th>
        <th>Actioned by</th>
        <th>Date</th>
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
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6">No requests found.</td></tr>';
    }
    ?>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="#" class="<?php echo ($i == $page) ? 'active' : ''; ?>" onclick="loadPage(<?php echo $i; ?>)">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<script>
function loadPage(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'my_system_access_request.php?page=' + page, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.body.innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
</script>

</body>
</html>

<?php
$stmt->close();
$total_stmt->close();
$conn->close();
?>
