<?php
// Start the session
session_start();

// Include your database connection file
include 'include.php';

// Check if request_id is set in the query string
if (isset($_GET['id'])) {
    $request_id = intval($_GET['id']);
    
    // Prepare the SQL query to fetch asset request details
    $sql = "SELECT request_id, name, department_name, request_type, new_asset_type, new_asset_details, new_asset_type_shift, current_asset_tag, new_asset_tag, exchange_reason, asset_tag_to_shift, from_location, new_location, additional_info, request_date, status 
            FROM asset_requests 
            JOIN employee_access ON employee_access.id = asset_requests.employee_id 
            WHERE request_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if a row is returned from the query
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No details found for the specified request',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'dashboard.php';
            });
        </script>";
        exit;
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Invalid request',
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location = 'dashboard.php';
        });
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Asset Request</title>
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Include SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: "Open Sans", Arial, "Helvetica Neue", Helvetica, "Segoe UI", Roboto, "Droid Sans", "Fira Sans", "Lato", "Noto Sans", "PT Sans", "Ubuntu", Cantarell, "Gill Sans", "Lucida Grande", Tahoma, Verdana, "Geneva", "Trebuchet MS", "Century Gothic", "Franklin Gothic Medium", "Lucida Sans Unicode", "Arial Black", "Impact", sans-serif, "Courier New", Courier, "Lucida Console", Monaco, "Andale Mono", monospace, Georgia, "Times New Roman", Times, serif, "Palatino Linotype", "Book Antiqua", "MS Serif", "Comic Sans MS", "Comic Sans", cursive;
            background-color: #f4f4f9;
            margin: 0;
            padding: 10px;
        }

        .container {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3385ff;
            color: #fff;
        }

        .status-pending {
            color: blue;
            font-weight: bold;
        }

        .status-approved {
            color: green;
            font-weight: bold;
        }

        .status-declined {
            color: red;
            font-weight: bold;
        }

        .no-value {
            background-color: red;
            color: white;
        }

        .has-value {
            background-color: #d4edda; /* Light green */
    color: #155724;
        }

        .back-button {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            background-color: #3385ff;
            color: #fff;
            border-radius: 5px;
        }
        .container-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; /* Space between buttons */
        }

        .container-action-buttons a {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .back-button {
            background-color: #3385ff;
        }

        .back-button:hover {
            background-color: #0066cc;
        }

        .appr-button {
            background-color: #4CAF50; /* Green */
        }

        .appr-button:hover {
            background-color: #45a049;
        }

        .decl-button {
            background-color: #f44336; /* Red */
        }

        .decl-button:hover {
            background-color: #e53935;
        }
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-buttons {
            text-align: right;
        }

        .modal-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-left: 10px;
            cursor: pointer;
        }

        .modal-buttons .cancel-button {
            background-color: #f44336; /* Red */
            color: white;
        }

        .modal-buttons .submit-button {
            background-color: #4CAF50; /* Green */
            color: white;
        }
        .request-id-container {
    margin-bottom: 15px;
    /* Add any other styling you need */
}
#request_id {
    width: 100%;
    padding: 8px;
    font-size: 16px;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>View Asset Request</h2>
        <table>
            <?php
            // Function to determine CSS class and value for each cell
            function checkValue($value) {
                if (empty($value)) {
                    return ['class' => 'no-value', 'value' => 'N/A', 'skip' => true];
                } else {
                    return ['class' => 'has-value', 'value' => htmlspecialchars($value), 'skip' => false];
                }
            }

            // Check if $row is set and not null
            if (isset($row)) {
                $fields = [
                    'Request ID' => $row['request_id'],
                    'Name' => $row['name'],
                    'Department' => $row['department_name'],
                    'Request Type' => $row['request_type'],
                    'New Asset Type' => $row['new_asset_type'],
                    'New Asset Details' => $row['new_asset_details'],
                    'New Asset Type Shift' => $row['new_asset_type_shift'],
                    'Current Asset Tag' => $row['current_asset_tag'],
                    'New Asset Tag' => $row['new_asset_tag'],
                    'Exchange Reason' => $row['exchange_reason'],
                    'Asset Tag to Shift' => $row['asset_tag_to_shift'],
                    'From Location' => $row['from_location'],
                    'New Location' => $row['new_location'],
                    'Additional Info' => $row['additional_info'],
                    'Request Date' => $row['request_date'],
                    'Status' => $row['status']
                ];

                // Flag to indicate if any cell should be skipped
                $shouldDisplayRow = false;

                // Check each field and decide whether to display the row
                foreach ($fields as $label => $value) {
                    $result = checkValue($value);
                    if (!$result['skip']) {
                        $shouldDisplayRow = true;
                        break; // No need to check further if at least one cell has value
                    }
                }

                // Display the row only if at least one cell is not empty
                if ($shouldDisplayRow) {
                    foreach ($fields as $label => $value) {
                        $result = checkValue($value);
                        if (!$result['skip']) {
                            echo "<tr>
                                    <th>{$label}</th>
                                    <td class='{$result['class']}'>{$result['value']}</td>
                                </tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='2'>No data available</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No data available</td></tr>";
            }
            ?>
        </table>

<!-- The Modal for approve -->
<div id="approvalModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Approval Comment</h2>

        <div class="request-id-container" id="request_id_container">
            <input type="hidden" id="request_id" name="request_id" value="<?php $_SESSION['request_id'] = $request_id;    echo htmlspecialchars($_SESSION['request_id']); ?>">
        </div>
        <textarea id="approvalComment" rows="5" style="width: 100%;"></textarea>
        <div class="modal-buttons">
            <button class="submit-button" onclick="submitComment()">Submit</button>
            <button class="cancel-button" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

             <!-- The Modal for decline -->
    <div id="declineModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModald()">&times;</span>
            <h2>Decline Comment</h2>
        <div class="request-id-container" id="request_id_container">
            <input type="hidden" id="request_id" name="request_id" value="<?php $_SESSION['request_id'] = $request_id;    echo htmlspecialchars($_SESSION['request_id']); ?>">
        </div>
            <textarea id="declineComment" rows="5" style="width: 100%;"></textarea>
            <div class="modal-buttons">
                <button class="submit-button" onclick="submitCommentc()">Submit</button>
                <button class="cancel-button" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>


        <div class="container-action-buttons">
          <a href="dashboard.php" class="back-button">Back</a>  
          <a href="#" class="appr-button" onclick="openApprText()">Approve</a>
          <a href="#" class="decl-button" onclick="openDeclText()">Decline</a>
        </div>
        
    </div>

<script>
function openApprText() {
    document.getElementById('approvalModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('approvalModal').style.display = 'none';
}

function submitComment() {
    const comment = document.getElementById('approvalComment').value;
    const request_id = document.getElementById('request_id').value;
    
    if (comment.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Please enter a comment.',
        });
        return;
    }

    // Perform AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_request_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                 // Successfully updated
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Comment submitted and status updated for Request ID: ' + request_id,
                }).then(() => {
                    closeModal();
                    // Optionally, you can refresh the page or update the UI here
                });
            } else {
                 Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating the request.',
                });
            }
        }
    };
    xhr.send(`request_id=${encodeURIComponent(request_id)}&comment=${encodeURIComponent(comment)}`);
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById('approvalModal')) {
        closeModal();
    }
}
</script>

    <script>
        function openDeclText() {
            document.getElementById('declineModal').style.display = 'flex';
        }

        function closeModald() {
            document.getElementById('declineModal').style.display = 'none';
        }

        function submitCommentc() {
    const comment = document.getElementById('declineComment').value;
    const request_id = document.getElementById('request_id').value;
    
    if (comment.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Please enter a comment.',
        });
        return;
    }

    // Perform AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_request_status_decline.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                 // Successfully updated
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Comment submitted and status updated for Request ID: ' + request_id,
                }).then(() => {
                    closeModald();
                    // Optionally, you can refresh the page or update the UI here
                });
            } else {
                 Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating the request.',
                });
            }
        }
    };
    xhr.send(`request_id=${encodeURIComponent(request_id)}&comment=${encodeURIComponent(comment)}`);
}

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target === document.getElementById('declineModal')) {
                closeModald();
            }
        }
    </script>
</body>
</html>
