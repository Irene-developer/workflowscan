<?php
// Include the TCPDF library
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    // Page header
    public function Header() {
        // Set font
        $this->SetFont('times', 'B', 24); // Increased font size to 24
        
        // Get current page width
        $pageWidth = $this->getPageWidth();
        // Get current page number
        $pageNumber = $this->getPage();
        if ($pageNumber == 1) { 
            // Logo
            $logoWidth = 60; // Adjust the width of the logo as needed
            $logoHeight = 35; // Adjust the height of the logo as needed
            $logoX = 10; // Adjust the X position of the logo as needed
            $logoY = 2; // Adjust the Y position of the logo as needed
            $this->Image('KCBLLOGO.png', $logoX, $logoY, $logoWidth, $logoHeight); // Output logo
            
            // Text
            $this->SetFont('times', 'B', 12); // Set font for the text
            $this->SetXY($logoWidth + 30, $logoY + 1); // Set position for the text, adjusted Y position
            $this->Cell($pageWidth - $logoWidth - 60, $logoHeight, 'INTERNAL MEMO', 0, 0, 'R'); // Output text
            
            // Draw a vertical line between the logo and the text
            $this->Line($logoX + $logoWidth + 30, $logoY, $logoX + $logoWidth + 30, $logoY + $logoHeight + 7.2);
            $this->Line($logoX + $logoWidth + 30, $logoY, $logoX + $logoWidth + 30, $logoY + $logoHeight + 7.2);
            $this->Line($logoX + $logoWidth + 30, $logoY, $logoX + $logoWidth + 30, $logoY + $logoHeight + 7.2);
            
            // Add border around the header
            $this->Rect($logoX, $logoY, $pageWidth - 19, $logoHeight + 7, 'D');
            $this->Rect($logoX, $logoY, $pageWidth - 19, $logoHeight + 7, 'D');
            $this->Rect($logoX, $logoY, $pageWidth - 19, $logoHeight + 7, 'D');
            
            // Add space after header for better layout
            $this->Ln(60); // Increased space for better layout
        } else {
            // Adjust Y position to create margin for subsequent pages
            $this->SetY(100);
        }
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Check if the 'id' parameter is set in the URL
if(isset($_GET['id'])) {
    // Sanitize the id parameter to prevent SQL injection
    $memo_id = intval($_GET['id']); // Assuming id is an integer
    
    // Include database connection
    include 'include.php'; // Adjust this to your database connection file path

    // Query to fetch memo details based on the provided id from the memos table
    $sql_memo = "SELECT * FROM memos WHERE id = ?";
    $stmt_memo = $conn->prepare($sql_memo);
    $stmt_memo->bind_param("i", $memo_id);
    $stmt_memo->execute();
    $result_memo = $stmt_memo->get_result();
    
    // Check if memo with the provided id exists
    if($result_memo->num_rows > 0) {
        // Fetch memo details
        $memo = $result_memo->fetch_assoc();

        // Query to fetch details from memo_action table
        $sql_action = "SELECT * FROM memo_action WHERE memo_id = ?";
        $stmt_action = $conn->prepare($sql_action);
        $stmt_action->bind_param("i", $memo_id);
        $stmt_action->execute();
        $result_action = $stmt_action->get_result();

        // Store memo action details in an array
        $memo_actions = array();
        while($row_action = $result_action->fetch_assoc()) {
            $memo_actions[] = $row_action;
        }

        // Create a new TCPDF instance
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Memo');
        $pdf->SetSubject('Memo PDF');
        $pdf->SetKeywords('Memo, PDF');

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('times', '', 14);

        // Add memo action details to the PDF
        $pdf->Ln(34); // Move to the next line
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(45, 13, 'RefNo: ' . $memo['refNo'], 1, 0, 'L'); // Adjust width as needed
        $pdf->Cell(70, 13, 'Date: ' . $memo['date'], 1, 0, 'L');
        $pdf->Cell(76, 13, 'Classification: ' . $memo['classfication'], 1, 0, 'L');
        $pdf->Ln(13); // Move to the next line
   // Construct the HTML for "To," "Ufs," and "From" values
// Initialize an array to store through values
$throughValues = array();

// Loop through each through field in the memo
foreach ($memo as $key => $value) {
    // Check if the key starts with 'through' and the value is not empty
    if (strpos($key, 'through') === 0 && !empty($value)) {
        // Extract the number from the key
        $number = substr($key, strlen('through'));
        // Add the through value to the array
        $throughValues[$number] = $value;
    }
}

// Construct the HTML for through values with corresponding numbers
$throughHTML = '';
foreach ($throughValues as $number => $value) {
    // Add "through" label with number and through value
    $throughHTML .= 'Ufs' . '' . $number . ': ' . $value . '<br>';
}

// Construct the HTML for "To," "Ufs," and "From" values with borders
$html = '
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="width: 33.333%; border-right: 1px solid black;">To: ' . $memo['to'] . '</td>
        <td style="width: 33.333%; border-right: 1px solid black;">' . $throughHTML . '</td>
        <td style="width: 33.333%;">From: ' . $memo['from'] . '</td>
    </tr>
</table>';

// Write the HTML to the PDF
$pdf->writeHTMLCell(191, 13, '', '', $html, 1, 1, 0, true, 'L', true);

        //$pdf->Cell(67, 13, 'From: ' . $memo['from'], 1, 1, 'L'); // Move to the next line after this row
        $pdf->Ln(0);
        $pdf->SetFont('Helvetica', 'B', 12); // Set font to bold for "Subject:"
       $subjectHTML = '<table style="width: 100%; ">
                    <tr>
                        <td>Subject: ' . $memo['subject'] . '</td>
                    </tr>
                </table>';

$pdf->writeHTMLCell(191, 20, '', '', $subjectHTML, 1, 1, 0, true, 'L', true);

        $pdf->Ln(1);
        $pdf->SetFont('Helvetica', '', 12);
        // Add memo content to the PDF
       // Create HTML content for the content within a table
$contentHTML = '
    <table cellspacing="0">
        <tr>
            <td>' . $memo['content'] . '</td>
        </tr>
    </table>
';

// Write the content using HTML
$pdf->writeHTML($contentHTML, true, false, true, false, '');

        $pdf->Ln(0);
        
        // Add actions to the PDF
        $pdf->SetFont('Helvetica', 'B', 12); // Set font to bold for "Actioned By:"
        $pdf->Write(0, 'Actioned By: ', '', 0, 'L', true, 0, false, false, 0); // Write "Actioned By: "
        $pdf->Ln(1); // Add spacing after the header

        // Loop through each action
        $html = '<table style="border-collapse: collapse; width: 100%;" border="1">';
        $html .= '<thead><tr>';
        $html .= '<th style="border: 1px solid #000; text-align: center;">Name</th>';
        $html .= '<th style="border: 1px solid #000; text-align: center;">Status</th>';
        $html .= '<th style="border: 1px solid #000; text-align: center;">Comment</th>';
        $html .= '<th style="border: 1px solid #000; text-align: center;">Signature</th>';
         
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        foreach ($memo_actions as $action) {
            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #000; text-align: center;">' . $action['username'] . '</td>';
            $html .= '<td style="border: 1px solid #000; text-align: center;">' . $action['status'] . '</td>';
            $html .= '<td style="border: 1px solid #000; text-align: center;">' . $action['comment'] . '</td>';
            $html .= '<td style="border: 1px solid #000; text-align: center; padding: 5px;">';
            if (!empty($action['signature_path'])) {
                $html .= '<img src="' . $action['signature_path'] . '" alt="Signature" style="max-width: 30px; max-height: 20px;">';
            }

            $html .= '</td>';
           
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
            <td colspan="2" style="font-weight: bold; text-align: center; padding-top: 15px;">Author Name</td>
            <td colspan="2" style="font-weight: bold; text-align: center;">Signature</td>
          </tr>';
$html .= '<tr>
            <td colspan="2" style="text-align: center;">' . $author_name . '</td>
            <td colspan="2" style="text-align: center;"><img src="' . $signature_path . '" alt="Signature" style="max-width: 50px; max-height: 50px;"></td>
            
          </tr>';

        $html .= '</tbody></table>';
        // Add HTML content to the PDF
        $pdf->writeHTML($html);

        // Output the PDF as a file
        $pdf->Output('memo_' . $memo_id . '.pdf', 'D');
        
        // Close statement and connection
        $stmt_memo->close();
        $stmt_action->close();
        $conn->close();
    } else {
        echo "Memo not found.";
    }
} else {
    // Redirect to an error page or homepage if 'id' parameter is not provided
    header("Location: error.php");
    exit();
}
?>
