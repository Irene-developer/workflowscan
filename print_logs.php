<?php
include 'include.php'; // Database connection

// Get page range from query parameters
$startPage = isset($_GET['startPage']) ? (int)$_GET['startPage'] : 1;
$endPage = isset($_GET['endPage']) ? (int)$_GET['endPage'] : 1;

// Number of records per page
$limit = 10;

// Fetch user logs for each page in the range
$html = '';
for ($page = $startPage; $page <= $endPage; $page++) {
    $offset = ($page - 1) * $limit;

    // Fetch user logs
    $query = "SELECT u.id, e.username, u.time_logged_in, u.time_logged_out, u.ip_address, u.login_status 
              FROM user_logs u
              LEFT JOIN employee_access e ON u.employee_id = e.id
              ORDER BY u.time_logged_in DESC
              LIMIT $limit OFFSET $offset";
    $result = $conn->query($query);

    // Append HTML for the current page
    $html .= '<h2>Page ' . $page . '</h2>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>Username</th>
                    <th>Time Logged In</th>
                    <th>Time Logged Out</th>
                    <th>IP Address</th>
                    <th>Login Status</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['username'] ?? 'Unknown') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['time_logged_in']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['time_logged_out'] ?? 'N/A') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['ip_address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['login_status']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
}

// Print-specific styles
$html .= '
<style>
@media print {
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }

    h1, h2 {
        text-align: center;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 12px;
    }

    table thead {
        background-color: #3385ff;
        color: #fff;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    table th {
        background-color: #3385ff;
        color: #fff;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:nth-child(odd) {
        background-color: #fff;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    @page {
        size: auto;
        margin: 20mm;
    }

    .no-print {
        display: none;
    }
}
</style>
';

echo $html;
?>
