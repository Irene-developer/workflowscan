<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Start session
session_start();




// Assuming $Position_name is set in the session
$Position_name = isset($_SESSION['Position_name']) ? $_SESSION['Position_name'] : '';

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
        // Fetching and displaying memo details in HTML table rows



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

        // Add header
        $pdf->Cell(0, 10, 'KILIMANJARO CO-OPERATIVE BANK (KCBL)', 0, 1, 'C');
        $pdf->Cell(0, 10, 'IMPREST EXPENDITURE FORM', 0, 1, 'C');
         
       $html = '<table border="1">
    
        <tr>
            <th style="text-align: center;">Imprest ID</th>
            <th style="text-align: center;">Name</th>
            <th style="text-align: center;">Department</th>
            <th style="text-align: center;">Date</th>
            <th style="text-align: center;">Outstanding Imprest Amount</th>
        </tr>
   
    <tbody>
        <tr>
            <td style="text-align: center;">' . $memo['imprest_id'] . '</td>
            <td style="text-align: center;">' . $memo['username'] . '</td>
            <td style="text-align: center;">' . $memo['department_name'] . '</td>
            <td style="text-align: center;">' . $memo['date'] . '</td>
            <td style="text-align: center;">' . $memo['outstanding_imprest_amount'] . '</td>
        </tr>
        <tr>
            <td colspan="5">Imprest Amount: ' . $memo['imprest_amount'] . '</td>
        </tr>
       
        <tr>
            <td colspan="5" style="text-align: center">Purpose:'. $memo['imprest_purpose'] . '</td>
        </tr>
        <tr>
            <td colspan="5">Actioned By:</td>
        </tr>

        <tr>
            <th style="text-align: center;">Position Name</th>
            <th style="text-align: center;">Status</th>
            <th style="text-align: center;">Comment</th>
            <th style="text-align: center;">Signature</th>
            <th style="text-align: center;">Date</th>
        </tr>';
        
while($row = $result_action->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td style="text-align: center;">' . $row['Position_name'] . '</td>'; // Assuming 'Position_name' is a column in your table
    $html .= '<td style="text-align: center;">' . $row['status'] . '</td>'; // Assuming 'status' is a column in your table
    $html .= '<td style="text-align: center;">'.$row['comment'].'</td>'; // You can replace 'Comment' with the appropriate column name from your table
    $html .= '<td style="text-align: center; padding: 5px;"><img src="' . $row['signature_path'] . '" alt="Signature" style="max-width: 30px; max-height: 20px;"></td>'; // Assuming 'signature_path' is a column in your table
    $html .= '<td style="text-align: center;">'.$row['date'].'</td>'; 
    $html .= '</tr>';
}

$html .= '</tbody>
</table>';

$pdf->writeHTML($html);

        // Output PDF to the browser
        $pdf->Output('expenditure_application_form.pdf', 'I');

        // Close database connection
        $conn->close();
    } else {
        echo "Imprest not found.";
    }
}
 else {
    // Redirect to an error page or homepage if 'imprest_id' parameter is not provided
    header("Location: error.php");
    exit();
}
?>
