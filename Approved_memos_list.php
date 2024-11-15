<?php
// Start the session
include('session_timeout.php');
//session_start();
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
    <style>
    body {
        font-family: "Open Sans", Arial, "Helvetica Neue", Helvetica, "Segoe UI", Roboto, "Droid Sans", "Fira Sans", "Lato", "Noto Sans", "PT Sans", "Ubuntu", Cantarell, "Gill Sans", "Lucida Grande", Tahoma, Verdana, "Geneva", "Trebuchet MS", "Century Gothic", "Franklin Gothic Medium", "Lucida Sans Unicode", "Arial Black", "Impact", sans-serif;
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
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        overflow: hidden;
        padding-bottom: 100px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: solid #ddd;
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
        display: flex;
        justify-content: flex-start;
        padding-left: px; /* Adjusted padding */
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 16;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropdown-button {
        background-color: #3e8e41;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000; /* Below the popup */
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
        z-index: 1001; /* Higher than the overlay */
    }

    .popup-footer {
        text-align: right;
    }

    th.subject-column {
        max-width: 10px; /* Adjusted maximum width */
        white-space: normal; /* Allows the text to wrap */
        word-wrap: break-word; /* Breaks long words if necessary */
        text-align: left; /* Aligns the text to the left */
    }
     form {
        margin: 20px auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        max-width: 800px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 250px;
    }

    label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        flex-basis: 30%; /* Adjusts label width */
        text-align: right;
    }

    input[type="date"], input[type="text"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        flex: 1;
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        background-color: #3385ff;
        color: #ffffff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        
    }
.button{
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        background-color: #3385ff;
        color: #ffffff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
       
        text-decoration: none;
    

}
    button:hover {
        background-color: #0056b3;
    }
</style>

</head>
<?php include  'header.php'; ?>
<body>

<form method="GET" action="">
<a href="dashboard.php" class="button">Back</a>
    <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" value="<?php echo isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : ''; ?>">
    </div>

    <button type="submit">Filter</button>
</form>


    <table>
        <tr>
            <th>ID</th>
            <th class="subject-column">Subject</th>
            <th>Status</th>
            <th>Actioned By</th>
            <th>Date</th>
            <th style="text-align:center;">Actions</th>
        </tr>
        <tbody>
            <?php
            // Include your database connection file
            include 'include.php';

            // Check if the username is set in session
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                
                // Prepare the base SQL query
                $sql = "SELECT * FROM memos WHERE final_approvail_of_To = 1 AND username = ?";

                // Add filtering conditions if specified
                if (isset($_GET['date']) && !empty($_GET['date'])) {
                    $date = $_GET['date'];
                    $sql .= " AND DATE(date) = ?";
                }
                if (isset($_GET['subject']) && !empty($_GET['subject'])) {
                    $subject = $_GET['subject'];
                    $sql .= " AND subject LIKE ?";
                }
                
                $stmt = $conn->prepare($sql);

                // Bind parameters
                if (isset($_GET['date']) && !empty($_GET['date']) && isset($_GET['subject']) && !empty($_GET['subject'])) {
                    $stmt->bind_param("sss", $username, $date, $subject);
                } elseif (isset($_GET['date']) && !empty($_GET['date'])) {
                    $stmt->bind_param("ss", $username, $date);
                } elseif (isset($_GET['subject']) && !empty($_GET['subject'])) {
                    $subject = '%' . $_GET['subject'] . '%';
                    $stmt->bind_param("ss", $username, $subject);
                } else {
                    $stmt->bind_param("s", $username);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are rows returned from the query
                if ($result->num_rows > 0) {
                    // Iterate through each row and display data in table rows
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td class='subject-column'>" . $row["subject"] . "</td>";

                        // Display status with corresponding class
                        $status = $row["status"];
                        if (!empty($status)) {
                            if ($status == 'pending') {
                                echo '<td><span style="color: blue; background-color: #e6f0ff; padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: blue;">Pending</span></td>';
                            } elseif ($status == 'approved') {
                                echo '<td><span style="color: green; background-color: #e6ffe6; padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: green;">Approved</span></td>';
                            } elseif ($status == 'declined') {
                                echo '<td><span style="color: red; background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red;">Declined</span></td>';
                            } else {
                                echo '<td>' . htmlspecialchars($status) . '</td>';
                            }
                        } else {
                            echo '<td><span style="color: blue;">Pending</span></td>';
                        }

                        // Define the sequence
                        $sequence = [
                            'through', 'through2', 'through3', 'through4', 'through5', 
                            'through6', 'through7', 'through8', 'through9', 'through10', 'to'
                        ];

                        echo "<td>
                            <div class='dropdown'>
                                <button class='dropdown-button' style='background-color: white; border: none; color: #3385FF'>" . htmlspecialchars($row["approved_by"]) . "</button>
                                <div class='dropdown-content'>";

                        // Fetch additional details for the dropdown menu
                        $memo_id = $row['id'];
                        $memo_sql = "SELECT * FROM memos WHERE id = ? AND final_approvail_of_To = 1 ";
                        $memo_stmt = $conn->prepare($memo_sql);
                        $memo_stmt->bind_param("i", $memo_id);
                        $memo_stmt->execute();
                        $memo_result = $memo_stmt->get_result();

                        if ($status == 'pending') {
                            echo "<a href='#'>Waiting for First Approve Action</a>";
                        } elseif ($memo_result->num_rows > 0) {
                            while ($memo = $memo_result->fetch_assoc()) {
                                // Check if approved_by exists
                                $current_value = htmlspecialchars($row["approved_by"]);

                                // Find the current position in the sequence
                                $next_value_found = false;
                                foreach ($sequence as $key => $field) {
                                    if ($memo[$field] == $current_value) {
                                        $next_value_found = true;
                                        continue; // Skip the current value
                                    }
                                    if ($next_value_found && isset($memo[$field]) && !empty($memo[$field])) {
                                        $next_value = htmlspecialchars($memo[$field]);
                                        echo "<a href='#' style='text-align: center;'><span style='color: green; background-color: #e6ffe6; padding: 1px; border: 1px solid; border-radius: 0.2em; border-color: green; margin-bottom: 50px;'>Next To</span> $next_value</a>";
                                        break; // Exit after finding the next value
                                    }
                                }
                                // If no next value found, show a placeholder
                                if (!$next_value_found) {
                                    echo "<a href='#'>No next value</a>";
                                }
                            }
                        } else {
                            // Handle the case where no memos are found
                            echo "<a href='#'>No memos found</a>";
                        }

                        $memo_stmt->close();

                        echo "</div>
                            </div>
                        </td>";
                        echo "<td>" . $row["date"] . "</td>";

                        echo "<td class='td_layout' style='text-align: center;'>
                            <a href='view_request_memo.php?id=" . $row['id'] . "'><i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i></a>";

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // If no rows returned from the query, display a message
                    echo "<tr style='color: red; background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red;'><td colspan='7'>No memos found</td></tr>";
                }
            }

            // Check if delete action is triggered
            if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                $id = intval($_GET['id']);
                
                // Your database connection setup (assuming $conn is your mysqli connection)
                $sql = "DELETE FROM memos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    echo "<script> 
                            Swal.fire({
                                title: 'Success',
                                text: 'Memo deleted successfully',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then(() => {
                                window.location.href = 'Approved_memos_list.php'; // Redirect to the same page
                            });
                          </script>";
                } else {
                    echo "<script> 
                            Swal.fire({
                                title: 'Failed',
                                text: 'Failed to delete record!',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            }).then(() => {
                                window.location.href = 'Approved_memos_list.php'; // Redirect to the same page
                            });
                          </script>";
                }
            }

            // Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>
