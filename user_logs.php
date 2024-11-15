<?php
include 'include.php'; // Database connection
include 'session_timeout.php';
// Number of records per page
$limit = 10;

// Get current page or set default
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Calculate offset for SQL LIMIT
$offset = ($page - 1) * $limit;

// Fetch total number of records
$total_query = "SELECT COUNT(*) FROM user_logs";
$total_result = $conn->query($total_query);
$total_rows = $total_result->fetch_row()[0];

// Fetch user logs with pagination
$query = "SELECT u.id, e.username, u.time_logged_in, u.time_logged_out, u.ip_address, u.login_status 
          FROM user_logs u
          LEFT JOIN employee_access e ON u.employee_id = e.id
          ORDER BY u.time_logged_in DESC
          LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Calculate total pages
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <link rel="stylesheet" href="style_user_logs.css">
    <style type="text/css">
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    align-items: center;
    align-content: center;
}

h1 {
    text-align: center;
    font-size: 24px;
    margin-top: 20px;
    color: #333;
}

table {
    width: 98%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

table thead {
    background-color: #3385ff;
    color: #fff;
}

table thead tr th {
    padding: 10px;
    text-align: left;
    font-size: 14px;
}

table tbody tr {
    border-bottom: 1px solid #ddd;
}

table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tbody tr:hover {
    background-color: #f1f1f1;
}

table tbody tr td {
    padding: 10px;
    text-align: left;
    font-size: 14px;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a {
    color: #3385ff;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin: 0 4px;
    transition: background-color 0.3s ease;
}

.pagination a:hover {
    background-color: #3385ff;
    color: white;
    border-color: #3385ff;
}

.pagination a.active {
    background-color: #3385ff;
    color: white;
    border: 1px solid #3385ff;
    pointer-events: none;
}

/* Print Button and Page Range */
.print-section {
    display: flex;
    justify-content: flex-end;
    
    margin-bottom: 20px;
}

.print-section label {
    margin-right: 10px;
    font-size: 14px;
        align-content: center;
    align-items: center;
    
}

.print-section input[type="number"] {
    width: 60px;
    margin-right: 15px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;

}

.print-section button {
    padding: 8px 16px;
    background-color: #3385ff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.print-section button:hover {
    background-color: #2874cc;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        width: 100%;
        font-size: 12px;
    }
}

@media (max-width: 600px) {
    .pagination a {
        padding: 6px 12px;
        margin: 0 2px;
    }
}
.div_print{
display: flex;
align-items: center;
align-content: center;
justify-content: space-between;
padding: 20px;
}
.div_print button{
    padding: 8px 16px;
    background-color: #3385ff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}
    </style>


</head>
<body>
    <?php include 'header.php'; ?>
    <div class="div_print">
    
    <button onclick="goBack()">Back</button>

    <div class="print-section">
        <label for="startPage">Start Page:</label>
        <input type="number" id="startPage" min="1" value="1">
        <label for="endPage">End Page:</label>
        <input type="number" id="endPage" min="1" value="1">
        <button onclick="printTable()">Print</button>
    </div>
     </div>
    <table id="userLogsTable">
        <thead>
            <tr>
                <th>Username</th>
                <th>Time Logged In</th>
                <th>Time Logged Out</th>
                <th>IP Address</th>
                <th>Login Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($row['time_logged_in']); ?></td>
                <td><?php echo htmlspecialchars($row['time_logged_out'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                <td><?php echo htmlspecialchars($row['login_status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<!-- Pagination Links -->
<div class="pagination">
    <!-- Prev link -->
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
    <?php endif; ?>

    <!-- Display page numbers -->
    <?php
    $start_page = max(1, $page - 1);
    $end_page = min($total_pages, $page + 1);

    if ($start_page > 1) {
        echo '<a href="?page=1">1</a>';
        if ($start_page > 2) echo '<span>...</span>';
    }

    for ($i = $start_page; $i <= $end_page; $i++):
    ?>
        <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php
    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) echo '<span>...</span>';
        echo '<a href="?page=' . $total_pages . '">' . $total_pages . '</a>';
    }
    ?>

    <!-- Next link -->
    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
    <?php endif; ?>
</div>


    <script>
function printTable() {
    const startPage = parseInt(document.getElementById("startPage").value, 10);
    const endPage = parseInt(document.getElementById("endPage").value, 10);

    if (startPage && endPage && startPage <= endPage) {
        // Prepare the URL to fetch the data for the specified page range
        const url = `print_logs.php?startPage=${startPage}&endPage=${endPage}`;
        
        // Create a new XMLHttpRequest to fetch the data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write('<html><head><title>Print User Logs</title>');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h1>SERVICE Net User Logs (Page ' + startPage + ' to ' + endPage + ')</h1>');
                printWindow.document.write(xhr.responseText); // Write the fetched HTML
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            } else if (xhr.readyState == 4) {
                alert("Failed to fetch data. Please try again.");
            }
        };
        xhr.send();
    } else {
        alert("Please specify a valid page range.");
    }
}

    </script>

<script>
    function goBack() {
        window.location.href = 'dashboard.php';
    }
</script>
    <?php include 'footer.php'; ?>
</body>
</html>
