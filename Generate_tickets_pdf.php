<?php
require_once('tcpdf/tcpdf.php'); 

// Create new PDF document
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Ticket Details Report');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default header data
$pdf->SetHeaderData('', 0, 'Ticket Details Report', 'ICT Department');

// Set header and footer fonts
$pdf->setHeaderFont(Array('helvetica', '', 10));
$pdf->setFooterFont(Array('helvetica', '', 8));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Fetch ticket details from the database
include 'include.php'; // Ensure this file sets up $conn

// SQL query to join log_tickets with employee_access
$sql = "
    SELECT log_tickets.*, employee_access.username AS assigned_username 
    FROM log_tickets 
    LEFT JOIN employee_access ON employee_access.id = log_tickets.assigned_to
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Initialize HTML content for the PDF
$html = '<h1 style="text-align: center;">Ticket Details Report</h1>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
$html .= '<thead>
            <tr style="background-color: #f2f2f2;">
                <th>Ticket ID</th>
                <th>TITLE</th>
                <th>CATEGORY</th>
                <th>URGENCY</th>
                <th>CREATED AT</th>
                <th>STATUS</th>
                <th>FILE ATTACHED</th>
                <th>ASSIGNED TO</th>
            </tr>
          </thead>';
$html .= '<tbody>';

// Check if any tickets are found
if ($result->num_rows > 0) {
    // Output each ticket's details in table rows
    while ($ticket = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($ticket['ticket_id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($ticket['title']) . '</td>';
        $html .= '<td>' . htmlspecialchars($ticket['category']) . '</td>';
        $html .= '<td>' . htmlspecialchars($ticket['urgency']) . '</td>';
        $html .= '<td>' . htmlspecialchars($ticket['created_at']) . '</td>';
        $html .= '<td>' . htmlspecialchars($ticket['status']) . '</td>';
        $html .= '<td>' . (empty($ticket['file_path']) ? 'None' : htmlspecialchars($ticket['file_path'])) . '</td>';
        $html .= '<td>' . (empty($ticket['assigned_username']) ? 'Unassigned' : htmlspecialchars($ticket['assigned_username'])) . '</td>';
        $html .= '</tr>';
    }
} else {
    // If no tickets are found
    $html .= '<tr><td colspan="8">No details found.</td></tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Output ticket details
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('ticket_details_report.pdf', 'I');
?>
