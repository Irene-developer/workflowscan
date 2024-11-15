<?php
// Start the session
//session_start();

// Include database connection
include 'include.php';
include 'header.php'; 
include('session_timeout.php');

// Function to sanitize input (optional but recommended)
function sanitize_input($data) {
    return htmlspecialchars(strip_tags($data));
}

// Check if ID and Position_name are set in the query parameters
if (isset($_GET['id']) && isset($_GET['username'])) {
    // Sanitize inputs
    $id = sanitize_input($_GET['id']);
    $username = sanitize_input($_GET['username']);

    // Query the database to fetch retirement request details
    $sql = "SELECT * FROM retirement WHERE id = ? AND (Approver1 = ? OR Approver2 = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id, $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Define status text based on ApproveActions value
        switch ($row['ApproveActions']) {
            case 1:
                $status_text = "Waiting for Final Approval";
                $status_color = "blue";
                break;
            case 2:
                $status_text = "Approved";
                $status_color = "green";
                break;
            case 3:
                $status_text = "Pending Retirement";
                $status_color = "orange";
                break;
            case 0:
                $status_text = "Declined";
                $status_color = "red";
                break;
            default:
                $status_text = "Unknown";
                $status_color = "gray";
                break;
        }

        // Format date for display
        $date = date('F j, Y', strtotime($row['date']));

        // Display attached images (assuming images are stored as URLs)
        $uploaded_files = $row['uploaded_files']; // Assuming uploaded_files is stored as comma-separated URLs

        // Explode URLs into an array
        $files_array = explode(',', $uploaded_files);

        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <link rel='shortcut icon' type='x-icon' href='KCBLLOGO.PNG'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>View Retirement Request</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
           <link rel='stylesheet' type='text/css' href='https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
            <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin:0;
    }

    .container {
        
        display: flex;
        flex-direction: column;
        align-items: stretch;
        max-width: 95%;
        margin: 0 auto;
        
        padding: 20px;
      
    }

    .header {
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .personal-info,
    .attached-files,
    .actions {
        padding: 4px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        display: flex;
        flex-direction: column;
    }

    .personal-info {
        flex-basis: calc(100% / 7);
        background-color: #f9f9f9;
    }

    .attached-files {
        flex-basis: calc(100% / 2);
        padding: 2px;
        background-color: #f0f0f0;
        flex-grow: 1;
        justify-content: center;
        align-items: center;
    }

    .actions {
        flex-basis: calc(100% / 7 - 10px);
        padding: 10px;
        background-color: #e9e9e9;
    }

    .attached-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        max-width: 100%;
        max-height: 100%;
        overflow: auto;
    }

    .attached-images img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .details {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        background-color: #fff;
        padding: 5px;
        width: 90%;
    }

    .details strong {
        font-weight: bold;
    }

    .status {
        font-weight: bold;
        color: {$status_color};
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: auto;
        height: 100%;
    }

    .action-button {
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
    }

    .approve {
        background-color: #4caf50; /* Green */
    }

    .decline {
        background-color: #f44336; /* Red */
    }

    .close-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        text-decoration: none;
        color: #3385ff;
    }

    /* Popup styles */
    .popup {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .popup-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
    }

    .popup-content h2 {
        margin-bottom: 10px;
    }

    .popup-content form {
        display: flex;
        flex-direction: column;
    }

    .popup-content textarea {
        margin-bottom: 10px;
        height: 150px;
        resize: vertical;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .popup-content input[type='submit'] {
        padding: 10px 20px;
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .popup-content input[type='submit']:hover {
        background-color: #45a049;
    }

    /* Container styles for flexbox */
    .button-container {
        display: flex;
        gap: 10px; /* Adjust the gap between buttons */
    }

    /* Base button styles */
    button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        outline: none;
        transition: background-color 0.3s ease;
    }

    /* Styles for the Approve button */
    button[name='approve'] {
        background-color: #4CAF50; /* Green */
        color: white;
    }

    /* Styles for the Decline button */
    button[name='decline'] {
        background-color: #f44336; /* Red */
        color: white;
    }

    /* Hover effect */
    button:hover {
        opacity: 0.8;
    }

    /* Personal info container */
    .personal-info {
        max-width: 600px;
       
        padding: 20px;
       
        background-color: #f9f9f9;
        
    }

    .personal-info h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: 600;
    }

    .details {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .details th {
        background-color: #3385ff;
        color: white;
        padding: 10px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #ddd;
    }

    .details td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
        color: #555;
    }

    .details tr:hover {
        background-color: #f1f1f1;
    }

    .details tr:last-child td {
        border-bottom: none;
    }

    .status {
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 4px;
        color: #3385ff;
        
    }

    .status.pending {
        background-color: #ff9800; /* Orange for pending status */
    }

    .status.approved {
        background-color: #4caf50; /* Green for approved status */
    }

    .status.declined {
        background-color: #f44336; /* Red for declined status */
    }
</style>

        </head>

        <body>
            <div class='container'>

                <div class='header'>
                
                    <div class='personal-info'>

                        <h2 style = 'text-align: center'>Personal Information</h2>
                        <table class='details' border='1'>
                        <tr style='padding: 3px'><th  style='background-color: #3385ff; color: white'>Field</th><th  style='background-color: #3385ff; color: white'>Value</th></tr>
                            <tr><td><strong>ID:</td><td></strong> {$row['id']}</td></tr>
                            <tr><td><strong>Name:</td><td></strong> {$row['applicant_name']}</td></tr>
                            <tr><td><strong>Department:</td><td></strong> {$row['department']}</td></tr>
                            <tr><td><strong>Status:</td><td></strong> <span class='status'>{$status_text}</span></td></tr>
                            <tr><td><strong>Date:</td><td></strong> {$date}</td></tr>
                            <!-- Add more details as needed -->

                        </table>
                    </div>
                    <div class='attached-files'>
                        <h2>Attached Files</h2>
                        <div class='attached-images'>";

       // Iterate through uploaded image URLs and create <img> tags
foreach ($files_array as $key => $image_url) {
    // Escaping the image URL and key to prevent XSS
    $escaped_url = htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8');
    $alt_text = 'Image ' . ($key + 1);
    echo "<img src='{$escaped_url}' alt='" . htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8') . "'>";
}

        echo "
                        </div>
                    </div>
                    <div class='actions'>
                        <h2 style='text-align: center;'>Actions</h2>
                        <div class='action-buttons'>
                            <a class='action-button approve' href='javascript:void(0);' onclick='showPopup();'>Take Actions</a>
                            <a class='action-button approve' href='dashboard.php'>Back</a>
                        </div>
                    </div>
                </div>

            </div>
            

            <div id='popup' class='popup'>
                <div class='popup-content'>
                    <span class='close' onclick='hidePopup();'>&times;</span>
                    <h2>Approval Comment</h2>
                    <form method='post' action='process_retire_approval.php'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='hidden' name='username' value='{$username}'>
                        <textarea name='comments' placeholder='Add your comment here' required></textarea>
                        <div class='button-container'>
    <button type='submit' name='approve'>Approve</button>
    <button type='submit' name='decline'>Decline</button>
</div>
                    </form>
                </div>
            </div>
            <script>
                function showPopup() {
                    document.getElementById('popup').style.display = 'block';
                }

                function hidePopup() {
                    document.getElementById('popup').style.display = 'none';
                }
            </script>

        </body>
        </html>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Not Found',
                text: 'No retirement request found with ID $id.'
            }).then(function() {
                window.history.back();
            });
        </script>";
    }

    // Close statement
    $stmt->close();
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Parameters',
            text: 'Required parameters are missing.'
        }).then(function() {
            window.history.back();
        });
    </script>";
}

// Close connection
$conn->close();
?>
 <?php include 'footer.php'; ?>