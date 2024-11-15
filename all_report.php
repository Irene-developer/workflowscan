<?php
// Start the session
//session_start();

include 'include.php';
include('session_timeout.php');

// Initialize counts
$declinedCount = 0;
$pendingCount = 0;
$approvedCount = 0;
$imprestCount = 0;
$totalPages = 0;
// Fetch data based on the report type
$reportType = isset($_GET['report_type']) ? $_GET['report_type'] : '';

// Pagination variables
$recordsPerPage = 4;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$data = [];
$headers = [];

// Fetch all records for printing
$printData = [];

if ($reportType === 'memo') {
    // Fetch the counts of each status
    $statusQuery = "SELECT 
                        COUNT(CASE WHEN status = 'declined' THEN 1 END) AS declinedCount,
                        COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pendingCount,
                        COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approvedCount
                    FROM memos";
    $statusResult = $conn->query($statusQuery);

    if ($statusResult->num_rows > 0) {
        $statusData = $statusResult->fetch_assoc();
        $declinedCount = $statusData['declinedCount'];
        $pendingCount = $statusData['pendingCount'];
        $approvedCount = $statusData['approvedCount'];
    }

    // Fetch memo data with pagination
    $sql = "SELECT * FROM memos LIMIT $offset, $recordsPerPage";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch column names for headers
        $field_info = $result->fetch_fields();
        foreach ($field_info as $val) {
            $headers[] = $val->name;
        }

        // Fetch data rows
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Fetch all records for printing
    $printSql = "SELECT id, date, username, subject, status FROM memos";
    $printResult = $conn->query($printSql);

    if ($printResult->num_rows > 0) {
        while ($row = $printResult->fetch_assoc()) {
            $printData[] = $row;
        }
    }

    // Calculate total number of pages
    $totalRecordsQuery = "SELECT COUNT(*) AS total FROM memos";
    $totalRecordsResult = $conn->query($totalRecordsQuery);
    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

} elseif ($reportType === 'imprest_expenditure') {
    // Fetch the counts for imprest expenditure
    $imprestQuery = "SELECT COUNT(*) AS imprestCount FROM imprest_expenditure";
    $imprestResult = $conn->query($imprestQuery);

    if ($imprestResult->num_rows > 0) {
        $imprestData = $imprestResult->fetch_assoc();
        $imprestCount = $imprestData['imprestCount'];
    }

    // Fetch imprest expenditure data with pagination
    $sql = "SELECT * FROM imprest_expenditure LIMIT $offset, $recordsPerPage";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch column names for headers
        $field_info = $result->fetch_fields();
        foreach ($field_info as $val) {
            $headers[] = $val->name;
        }

        // Fetch data rows
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Fetch all records for printing
    $printSql = "SELECT id, date, description, amount FROM imprest_expenditure";
    $printResult = $conn->query($printSql);

    if ($printResult->num_rows > 0) {
        while ($row = $printResult->fetch_assoc()) {
            $printData[] = $row;
        }
    }

    // Calculate total number of pages
    $totalRecordsQuery = "SELECT COUNT(*) AS total FROM imprest_expenditure";
    $totalRecordsResult = $conn->query($totalRecordsQuery);
    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Memo Request Reports</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" type="text/css" href="styles_all_report2.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Add a hover effect for better interactivity */
        .status-cell:hover {
            opacity: 0.9;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .pagination a:hover {
            background-color: #f1f1f1;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .btn-print {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <form method="GET" action="">
        <!-- Back button to return to the dashboard -->
        <a href="dashboard.php" class="button">Back</a>

        <!-- Date filter -->
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
        </div>

        <!-- Subject filter -->
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" value="<?php echo isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : ''; ?>">
        </div>

        <!-- Report Type filter -->
        <div class="form-group">
            <label for="report_type">Report Type:</label>
            <select id="report_type" name="report_type" onchange="this.form.submit()">
                <option value="">Select Type</option>
                <option value="memo" <?php if($reportType == 'memo') echo 'selected'; ?>>Memo</option>
            </select>
        </div>

        <!-- Filter button -->
        <button type="submit" class="btn-filter">Filter</button>
        <button type="button" onclick="printTable()" class="btn-print">Print Table</button>
    </form>

    <div class="content-container">
        <!-- Left side: Pie Chart Div -->
        <div class="left-section">
            <div class="chart-container">
                <canvas id="reportChart"></canvas>
            </div>
        </div>

        <!-- Right side: Table Div -->
        <div class="right-section">

            <table id="dataTable">
                <thead>
                    <tr>
                        <?php if ($reportType === 'memo'): ?>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Status</th>
                        <?php elseif ($reportType === 'imprest_expenditure'): ?>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reportType === 'memo'): ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td class="status-cell 
                                    <?php
                                        if ($row['status'] === 'pending') {
                                            echo 'status-pending';
                                        } elseif ($row['status'] === 'declined') {
                                            echo 'status-declined';
                                        } elseif ($row['status'] === 'approved') {
                                            echo 'status-approved';
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?report_type=memo&page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?report_type=memo&page=<?php echo $i; ?>" class="<?php if ($i == $currentPage) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?report_type=memo&page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Pie chart data with actual statistics
        const data = {
            labels: ['Declined', 'Pending', 'Approved'],
            datasets: [{
                label: 'Report Statistics',
                data: [<?php echo $declinedCount; ?>, <?php echo $pendingCount; ?>, <?php echo $approvedCount; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Pie chart options
        const options = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        };

        // Create pie chart
        const ctx = document.getElementById('reportChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: options
        });

        // Print table function
        function printTable() {
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Table</title>');
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f4f4f4; }</style>');
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h2 style="color: #3385ff; font-family: \'Open Sans\', Arial, sans-serif; font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0; padding: 10px; border-bottom: 2px solid #3385ff;" >KCBL MEMOS</h2>');
            printWindow.document.write('<table><thead><tr>');
            printWindow.document.write('<th>ID</th>');
            printWindow.document.write('<th>Username</th>');
            printWindow.document.write('<th>Date</th>');
            printWindow.document.write('<th>Subject</th>');
            printWindow.document.write('<th>Status</th>');
            printWindow.document.write('</tr></thead><tbody>');
            <?php foreach ($printData as $row): ?>
                printWindow.document.write('<tr>');
                printWindow.document.write('<td><?php echo htmlspecialchars($row['id']); ?></td>');
                printWindow.document.write('<td><?php echo htmlspecialchars($row['username']); ?></td>');
                printWindow.document.write('<td><?php echo htmlspecialchars($row['date']); ?></td>');
                printWindow.document.write('<td><?php echo htmlspecialchars($row['subject']); ?></td>');
                printWindow.document.write('<td><?php echo htmlspecialchars($row['status']); ?></td>');
                printWindow.document.write('</tr>');
            <?php endforeach; ?>
            printWindow.document.write('</tbody></table>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</body>
</html>
