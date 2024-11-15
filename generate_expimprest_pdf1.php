<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Assuming $username is set in the session
$Position_name = isset($_SESSION['Position_name']) ? $_SESSION['Position_name'] : '';

// Check if the 'imprest_id' parameter is set in the URL
if(isset($_GET['imprest_id'])) {
    // Sanitize the id parameter to prevent SQL injection
    $memoimprest_id = intval($_GET['imprest_id']); // Assuming id is an integer
    
    // Include database connection
    include 'include.php'; // Adjust this to your database connection file path

    // Query to fetch memo details based on the provided id
    $sql = "SELECT * FROM imprest_expenditure WHERE imprest_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memoimprest_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if memo with the provided id exists
    if($result->num_rows > 0) {
        // Fetch memo details
        $memo = $result->fetch_assoc();

        // Create new PDF document
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Expenditure PDF');
        $pdf->SetSubject('Expenditure PDF');
        $pdf->SetKeywords('Expenditure, PDF, Expenditure');

        // Set margins
        $pdf->SetMargins(10, 10, 10, true);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Write memo details to PDF
        $html = "
            <h1 style='text-align: center;'>Expenditure Details</h1>
            <table border='1'>
            <tr>
            <td>Imprest ID:</td>
            <td>{$memo['imprest_id']}</td>
            <td>Name:</td>
            <td>{$memo['username']}</td>
        </tr>
        <tr>
            <td>Date:</td>
            <td>{$memo['date']}</td>
            <td>Department Name:</td>
            <td>{$memo['department_name']}</td>
        </tr>
        </table>
            <p>Department Name: {$memo['department_name']}</p>
            <p>Imprest Amount: {$memo['imprest_amount']}</p>
            <p>Outstanding Amount: {$memo['outstanding_imprest_amount']}</p>
            <p>Imprest Purpose: {$memo['imprest_purpose']}</p>
        ";

        $pdf->writeHTML($html);

        // Output PDF to the browser
        $pdf->Output('memo.pdf', 'I');

        // Close database connection
        $conn->close();
    } else {
        echo "Memo not found.";
    }
} else {
    // Redirect to an error page or homepage if 'imprest_id' parameter is not provided
    header("Location: error.php");
    exit();
}
?>
