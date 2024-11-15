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

if ($reportType === 'imprest_expenditure') {
    // Fetch the counts of each status
    $statusQuery = "SELECT 
                        COUNT(CASE WHEN status = 'declined' THEN 1 END) AS declinedCount,
                        COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pendingCount,
                        COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approvedCount
                    FROM imprest_expenditure";
    $statusResult = $conn->query($statusQuery);

    if ($statusResult->num_rows > 0) {
        $statusData = $statusResult->fetch_assoc();
        $declinedCount = $statusData['declinedCount'];
        $pendingCount = $statusData['pendingCount'];
        $approvedCount = $statusData['approvedCount'];
    }

    // Fetch memo data with pagination
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
    $printSql = "SELECT imprest_id, date, username, imprest_purpose, status FROM imprest_expenditure";
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
    <title>Approved Memo Request</title>
    
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
                <option value="imprest_expenditure" <?php if($reportType == 'imprest_expenditure') echo 'selected'; ?>>Imprest Expenditure</option>
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
                        <?php if ($reportType === 'imprest_expenditure'): ?>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Date</th>
                            <th>Status</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reportType === 'imprest_expenditure'): ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['imprest_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
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
                    <a href="?report_type=imprest_expenditure&page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?report_type=imprest_expenditure&page=<?php echo $i; ?>" class="<?php if ($i == $currentPage) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?report_type=imprest_expenditure&page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Pie Chart logic
        const ctx = document.getElementById('reportChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Pending', 'Declined'],
                datasets: [{
                    label: 'Status Count',
                    data: [<?php echo $approvedCount; ?>, <?php echo $pendingCount; ?>, <?php echo $declinedCount; ?>],
                    backgroundColor: ['#85e085', '#3385ff', '#dc3545'],
                }]
            }
        });

function printTable() {
    const printWindow = window.open('', '', 'height=600,width=800');

    // Open HTML document
    printWindow.document.write('<html><head><title>Print Table</title>');

    // Link to external stylesheet
    printWindow.document.write('<link rel="stylesheet" type="text/css" href="styles_all_report.css">');

    // Custom inline styles for the table and header
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f4f4f4; }</style>');

    // Close head and start body
    printWindow.document.write('</head><body>');

    // Add custom header
    printWindow.document.write('<h2 style="color: #3385ff; font-family: \'Open Sans\', Arial, sans-serif; font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0; padding: 10px; border-bottom: 2px solid #3385ff;">KCBL IMPREST EXPENDITURE</h2>');

    // Add the table content by copying the existing table
    printWindow.document.write(document.getElementById('dataTable').outerHTML);

    // Close the HTML document
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    // Trigger the print dialog
    printWindow.print();
}

    </script>
</body>
</html>