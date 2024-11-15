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
    $sql = "SELECT * FROM imprest_expenditure WHERE imprest_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $imprest_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if memo with the provided id exists
    if($result->num_rows > 0) {
        // Fetch memo details
        $memo = $result->fetch_assoc();

        // Query to fetch memo details based on the provided id
        $sql = "SELECT * FROM imprest_action WHERE imprest_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $imprest_id);
        $stmt->execute();
        $result_action = $stmt->get_result();
    
        $imprest_actions = array();

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
        $pdf->SetFont('helvetica', 'B', 12);
         // Add the logo and title
        $pdf->Image('KCBLLOGO.png', 15, 10, 30, 20, 'PNG'); // Replace with your logo path
        $pdf->Cell(0, 10, '(CBT BANK)', 0, 1, 'C');
        $pdf->Cell(0, 10, 'CO-OPERATIVE BANK OF TANZANIA', 0, 1, 'C');
        $pdf->Cell(0, 10, 'EXPENDITURE IMPREST FORM', 0, 1, 'C');

        // Set font for TO, FROM, DATE, SUBJECT sections
        $pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(20, 10, 'TO:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        //$pdf->Cell(0, 10, 'ALL COUNCILLORS', 0, 1);

        //$pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(20, 10, 'FROM:', 0, 0);
        //$pdf->SetFont('helvetica', '', 12);
        //$pdf->Cell(0, 10, 'EXECUTIVE MANAGER, PLANNING DIVISION', 0, 1);

        $pdf->SetFont('helvetica', '', 12);
        //$pdf->Cell(20, 10, 'DATE:', 0, 0);
        //$pdf->SetFont('helvetica', '', 12);
        //$pdf->Cell(0, 10, '13 December 2007', 0, 1);

        //$pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(20, 10, 'SUBJECT:', 0, 0);
        //$pdf->SetFont('helvetica', '', 12);
       // $pdf->Cell(0, 10, 'Items on Agenda for Planning Meeting No. 22/07 – 19 December 2007', 0, 1);

        // Add a line below "Additional information with NO CHANGE to Recommendation"
        //$pdf->SetFont('helvetica', 'I', 10);
        //$pdf->Cell(0, 10, 'Additional information with NO CHANGE to Recommendation', 1, 1, 'C');

        // Main content section
        //$pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(0, 10, 'ITEM 2', 0, 1);
        //$pdf->SetFont('helvetica', '', 12);
        //$pdf->MultiCell(0, 10, 'DA/953/2003/D - Cameron Brae Pty Ltd - Lot 2 DP 610018 (Nos. 69 - 73) Bay Road, Berowra - Section 96(1A) application to modify condition for an approved car park', 0, 'L', 0, 1);

        //$pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(0, 10, '1. DESCRIPTION OF DEVELOPMENT', 0, 1);

        //$pdf->SetFont('helvetica', 'B', 12);
        //$pdf->Cell(0, 10, '1.3 Proposed Development', 0, 1);

        //$pdf->SetFont('helvetica', '', 12);
        //$pdf->MultiCell(0, 10, "The applicant has requested that they be given until 4 February 2009 to comply with the deferred commencement conditions. The applicant advised the following:", 0, 'L', 0, 1);

        //$pdf->SetFont('helvetica', 'I', 12);
        //$pdf->MultiCell(0, 10, '"Cameron Brae has been progressing alternative solutions to the provision of car parking to serve the Berowra Water Marina. Specifically these solutions have focused on the development of the additional car parking deck at Dusthole Bay proposed in the adopted Plan of Management for Berowra Waters."', 0, 'L', 0, 1);

        // Dynamic table content
$html = '<table border="1" cellpadding="5">
    <tr>
        <th style="text-align: center; font-weight: bold;">Imprest ID</th>
        <th style="text-align: center; font-weight: bold;">Name</th>
        <th style="text-align: center; font-weight: bold;">Designation</th>
        <th style="text-align: center; font-weight: bold;">Department</th>
    </tr>
    <tr>
        <td style="text-align: center;">' . $memo['imprest_id'] . '</td>
        <td style="text-align: center;">' . $memo['username'] . '</td>
        <td style="text-align: center;">' . $memo['Position_name'] . '</td>
        <td style="text-align: center;">' . $memo['department_name'] . '</td>
    </tr>

            <tr>
                <td colspan="4">
                    <span style="font-weight: bold;">Imprest Amount:</span> 
                    Tsh: ' . number_format($memo['imprest_amount'], 0) . '/=
                </td>
            </tr>
            <tr>
                <td colspan="4"><span style="font-weight: bold;">Outstanding Imprest Amount:</span> Tsh: ' . number_format($memo['outstanding_imprest_amount'], 0) . '/= </td>
                
            </tr>
    <tr>
        <td colspan="4">Purpose: ' . $memo['imprest_purpose'] . '</td>
    </tr>
    <tr>
        <td colspan="4"><span style="font-weight: bold;">Actioned By:</span></td>
    </tr>
    <tr>
        <th style="text-align: center; font-weight: bold;">Position Name</th>
        <th style="text-align: center; font-weight: bold;">Status</th>
        <th style="text-align: center; font-weight: bold;">Comment</th>
        <th style="text-align: center; font-weight: bold;">Signature</th>
        
    </tr>';

// Loop through the result set for the actioned records
while ($row = $result_action->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td style="text-align: center;">' . $row['username'] . '</td>';
    $html .= '<td style="text-align: center;">' . $row['status'] . '</td>';
    $html .= '<td style="text-align: center;">' . $row['comment'] . '</td>';
    $html .= '<td style="text-align: center;"><img src="' . $row['signature_path'] . '" alt="Signature" style="width: 30px; height: 20px;"></td>';
    //$html .= '<td style="text-align: center;">' . $row['date'] . '</td>'; 
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
// Adding "From:" label, Author Name, and Signature in a letter-like layout, left-aligned with no margin
//$html .= '<div style="text-align: left; font-size: 12px; font-weight: bold; line-height: 1;">From:</div>';
$html .= '<br>From:<div style="text-align: left; font-size: 10px; font-weight: bold; line-height: 1.2;">' . $author_name . '<br><br><img src="' . $signature_path . '" alt="Signature" style="width: 60px; height: 30px;"></div>';
$html .= '<div style="text-align: left; line-height: 1.2;">
              
          </div>';

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
