<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Memo Request</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: "Open Sans", Arial, "Helvetica Neue", Helvetica, "Segoe UI", Roboto, "Droid Sans", "Fira Sans", "Lato", "Noto Sans", "PT Sans", "Ubuntu", Cantarell, "Gill Sans", "Lucida Grande", Tahoma, Verdana, "Geneva", "Trebuchet MS", "Century Gothic", "Franklin Gothic Medium", "Lucida Sans Unicode", "Arial Black", "Impact", sans-serif, "Courier New", Courier, "Lucida Console", Monaco, "Andale Mono", monospace, Georgia, "Times New Roman", Times, serif, "Palatino Linotype", "Book Antiqua", "MS Serif", "Comic Sans MS", "Comic Sans", cursive;
            background-color: #f4f4f9;
            margin: 0;
            padding: 10px;
        }

        .navigation-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #3385ff;
            font-size: 24px;
        }

        .fa-plus-circle {
            margin-right: 10px;
        }

        table {
            width: 99.9%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            padding-bottom: 100px;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #3385ff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            border-bottom: 1px solid #ddd;
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

        .td_layout {
            padding-left: 100px;
            width: 80px;
            display: flex;
            text-align: left;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 98%; /* Could be more or less, depending on screen size */
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

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            height: 80%;
            overflow-y: auto;
            z-index: 1001;
        }

        .popup-footer {
            text-align: right;
        }

    </style>
</head>
<body>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Status</th>
            <th>Request Type</th>
            <th>Position</th>
            <th>Requested Date</th>
            <th style="text-align:center;">Actions</th>
        </tr>
        <tbody>
            <?php
            include 'include.php';

            $sql = "SELECT request_id, name, department_name, request_type, position_name, request_date, status 
                    FROM asset_requests 
                    JOIN employee_access ON employee_access.id = asset_requests.employee_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["request_id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["department_name"] . "</td>";

                    $status = $row["status"];   
                    if (!empty($status)) {
                        if ($status == 'pending') {
                            echo '<td><span style="color: blue; background-color: #e6f0ff; padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: blue;">Pending</span></td>';
                        } elseif ($status == 'approved') {
                            echo '<td><span style="color: green; background-color: #e6ffe6; padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: green;">Approved</span></td>';
                        } elseif ($status == 'rejected') {
                            echo '<td><span style="color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red;">Declined</span></td>';
                        } else {
                            echo '<td>' . htmlspecialchars($status) . '</td>';
                        }
                    } else {
                        echo '<td><span style="color: blue;">Pending</span></td>';
                    }

                    echo "<td>" . $row["request_type"] . "</td>";
                    echo "<td>" . $row["position_name"] . "</td>";
                    echo "<td>" . $row["request_date"] . "</td>";

                    echo "<td class='td_layout'>
                            <a href='#' class='view-asset-request' data-id='" . $row['request_id'] . "' onclick='showPopup3(this);' style='color: #3385ff; text-decoration: none;'>
                                <li class='fas fa-eye'></li>
                            </a>
                          </td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No memos found</td></tr>";
            }

            if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                $id = intval($_GET['id']);

                $sql = "DELETE FROM memos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Memo deleted successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location = 'dashboard.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to delete memo',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>";
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Popup Modal HTML -->
    <div id="popupModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="popupContent"></div>
        </div>
    </div>

    <script>
        function showPopup3(element) {
            var id = element.getAttribute('data-id');
            
            // Fetch and display content for the popup
            fetchPopupContent(id);
            
            // Show the popup modal
            var modal = document.getElementById("popupModal");
            modal.style.display = "block";
          // Close the modal when the close button is clicked
    var closeButton = modal.getElementsByClassName("close")[0];
    closeButton.onclick = function() {
        modal.style.display = "none";
    };
        }

        function fetchPopupContent(id) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "view_request_asset.php?id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('popupContent').innerHTML = xhr.responseText;
                } else {
                    console.error('Failed to fetch asset details');
                }
            };
            xhr.send();
        }

       

        // Close the modal if clicked outside of the modal
        window.onclick = function(event) {
            var modal = document.getElementById("popupModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
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
