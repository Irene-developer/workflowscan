<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Start session
session_start();

// Assuming $Position_name is set in the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Check if the 'imprest_id' parameter is set in the URL
if(isset($_GET['imprest_id'])) {
    // Sanitize the id parameter to prevent SQL injection
    $imprest_id = intval($_GET['imprest_id']); // Assuming id is an integer
    
    // Include database connection
    include 'include.php'; // Adjust this to your database connection file path

    // Query to fetch memo details based on the provided id
    $sql = "SELECT * FROM imprest_safari WHERE imprest_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $imprest_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if memo with the provided id exists
    if($result->num_rows > 0) {
        // Fetch memo details
        $memo = $result->fetch_assoc();

        // Query to fetch memo details based on the provided id
        $sql = "SELECT * FROM imprest_action_safari WHERE imprest_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $imprest_id);
        $stmt->execute();
        $result_action = $stmt->get_result();
    
        $imprest_actions = array();

        // Create new PDF document
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Safari PDF');
        $pdf->SetSubject('Safari PDF');
        $pdf->SetKeywords('Safari, PDF, Safari');

        // Set margins
        $pdf->SetMargins(10, 10, 10, true);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', 'B', 12);
         // Add the logo and title
        $pdf->Image('KCBLLOGO.png', 15, 10, 30, 20, 'PNG'); // Replace with your logo path
        $pdf->Cell(0, 10, '(CBT BANK)', 0, 1, 'C');
        $pdf->Cell(0, 10, 'CO-OPERATIVE BANK OF TANZANIA', 0, 1, 'C');
        $pdf->Cell(0, 10, 'SAFARI IMPREST FORM', 0, 1, 'C');

        // Set font for TO, FROM, DATE, SUBJECT sections
        $pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(20, 10, 'TO:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);


        $pdf->SetFont('helvetica', '', 12);


        // Dynamic table content
        $html = '<table border="1" cellpadding="5">
            <tr>
                <th style="text-align: center; font-weight: bold;">Imprest ID</th>
                <th style="text-align: center;font-weight: bold; ">Name</th>
                <th style="text-align: center;font-weight: bold;">Designation</th>
                <th style="text-align: center;font-weight: bold;">Department</th>
            </tr>

            <tr>
                <td style="text-align: center;">' . $memo['imprest_id'] . '</td>
                <td style="text-align: center;">' . $memo['username'] . '</td>
                <td style="text-align: center;">' . $memo['Position_name'] . '</td>
                <td style="text-align: center;">' . $memo['department_name'] . '</td>
            </tr>
            <tr>
                <th style="text-align: center; font-weight: bold;">Branch</th>
                <th style="text-align: center; font-weight: bold;">Traveling To</th>
                <th style="text-align: center; font-weight: bold;">From Date</th>
                <th style="text-align: center; font-weight: bold;">To Date</th>
            </tr>
            <tr>
                <td style="text-align: center;">' . $memo['branch_name'] . '</td>
                <td style="text-align: center;">' . $memo['travelling_to'] . '</td>
                <td style="text-align: center;">' . $memo['Date_from'] . '</td>
                <td style="text-align: center;">' . $memo['Date_to'] . '</td>
            </tr>
            <tr>
                <th style="text-align: center; font-weight: bold;">Total Days</th>
                <th style="text-align: center; font-weight: bold;">Rate of Subsistence</th>
                <th style="text-align: center; font-weight: bold;">Requested Amount</th>
                <th style="text-align: center; font-weight: bold;">Outstanding Imprest Amount</th>
            </tr>
            <tr>
                <td style="text-align: center;">' . $memo['Days'] . '</td>
                <td style="text-align: center;">Tsh: ' . number_format($memo['Rate_of_Subsistence'], 0) . '/= </td>
                <td style="text-align: center;">Tsh: ' . number_format($memo['imprest_amount'], 0) . '/= </td>
                <td style="text-align: center;">Tsh: ' . number_format($memo['outstanding_imprest_amount'], 0) . '/= </td>
            </tr>
            <tr>
                <td colspan="4"><span style="font-weight: bold;">Imprest Amount:</span> Tsh: ' . number_format($memo['imprest_amount'], 0) . '/= </td>
            </tr>
            <tr>
                <td colspan="4"><span style="font-weight: bold;">Outstanding Imprest Amount:</span> Tsh: ' . number_format($memo['outstanding_imprest_amount'], 0) . '/= </td>
            </tr>
            <tr>
                <td colspan="4">Purpose: ' . $memo['imprest_safari_purpose'] . '</td>
            </tr>
            <tr>
                <td colspan="4">Actioned By:</td>
            </tr>
            <tr>
                <th style="text-align: center; font-weight: bold;">Position Name</th>
                <th style="text-align: center; font-weight: bold;">Status</th>
                <th style="text-align: center; font-weight: bold;">Comment</th>
                <th style="text-align: center; font-weight: bold;">Signature</th>
                <th style="text-align: center; font-weight: bold;">Date</th>
            </tr>';
        
        while($row = $result_action->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center;">' . $row['username'] . '</td>';
            $html .= '<td style="text-align: center;">' . $row['status'] . '</td>';
            $html .= '<td style="text-align: center;">' . $row['comment'] . '</td>';
            $html .= '<td style="text-align: center;"><img src="' . $row['signature_path'] . '" alt="Signature" style="max-width: 30px; max-height: 20px;"></td>';
            $html .= '<td style="text-align: center;">' . $row['date'] . '</td>'; 
            $html .= '</tr>';
        }

// Logic to display Author Name and Signature
$query_author = "SELECT CONCAT(ea.first_name, ' ', ea.last_name) AS author_name, s.signature_path
                 FROM employee_access ea
                 JOIN signature s ON ea.username = s.username
                 WHERE s.username = '" . $memo['username'] . "'";
                 
$result_author = $conn->query($query_author);

if ($result_author->num_rows > 0) {
    $author = $result_author->fetch_assoc();
    $author_name = $author['author_name'];
    $signature_path = $author['signature_path'];
} else {
    $author_name = 'N/A';  // Default if no author found
    $signature_path = 'N/A';  // Default if no signature found
}

// Adding the Author Name and Signature to the table
$html .= '<tr>
            <td colspan="2" style="font-weight: bold; text-align: center;">Author Name</td>
            <td colspan="2" style="font-weight: bold; text-align: center;">Signature</td>
          </tr>';
$html .= '<tr>
            <td colspan="2" style="text-align: center;">' . $author_name . '</td>
            <td colspan="2" style="text-align: center;"><img src="' . $signature_path . '" alt="Signature" style="max-width: 50px; max-height: 50px;"></td>
            <td></td>
          </tr>';


        $html .= '</table>';

        $pdf->writeHTML($html);

        // Footer: Response Required
        $pdf->SetFont('helvetica', 'B', 10);
        // Get the current date and time
        $current_datetime = date('Y-m-d H:i:s');

        // Add the current date and time to the PDF
        $pdf->Cell(0, 10, 'Generated on: ' . $current_datetime, 0, 1, 'R');


        // Output PDF to the browser
        $pdf->Output('expenditure_application_form.pdf', 'I');

        // Close database connection
        $conn->close();
    } else {
        echo "Imprest not found.";
    }
} else {
    // Redirect to an error page or homepage if 'imprest_id' parameter is not provided
    header("Location: error.php");
    exit();
}
?>
