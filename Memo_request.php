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
    max-width:100%;
    margin-top: 0px;
    background-color: #fff;
    padding: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            padding: 10px;
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

        a.navigation-link {
            margin-left: 1200px;
            color: #3385ff;
            max-width: 10px;
            text-decoration: none;
            background-color: transparent;
        }

        .td_layout {
            padding-left: 200px;
            width: 180px;
            display: flex;
            text-align: left;
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
    max-width: 50px; /* Set the maximum width you desire */
    white-space: normal; /* Allows the text to wrap */
    word-wrap: break-word; /* Breaks long words if necessary */
    text-align: left; /* Optional: Aligns the text to the left */
}
                .add_memo_link {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    cursor: pointer; 
                    max-width: 99%;
                }

                .add_memo_link a {
                    width: 20px;
                    
                }
    </style>
</head>
<body>
            <div class='add_memo_link'>
                <p></p>
                <a href='create_memo.php'>
                    <li class='fa fa-plus-circle' style='color: #3385ff;'></li>
                </a>
            </div>


    <table>
        <tr>
            <th>ID</th>
            <th class="subject-column">Subject</th>
            <!--th>Department</th-->
            <th>Status</th>
            <th>Actioned By</th>
            <th>Date</th>
            <th style="text-align:center;">Actions</th>
        </tr>
        <tbody>
            <?php
            // Include your database connection file
            include 'include.php';

            // Check if the department_name, Position_name, and username are set in session
            if (isset($_SESSION['username'])) {
                // Retrieve department_name, Position_name, and username from session
               // $department_name = $_SESSION['department_name'];
               // $Position_name = $_SESSION['Position_name'];
                $username = $_SESSION['username'];

             $sql = "SELECT *
                              FROM memos m
                        
                        WHERE username = ?";


                /* Execute the SQL query to fetch data from the "memos" table
                $sql = "SELECT m.*, e.department_name
                        FROM memos m
                        INNER JOIN employee_access e 
                            ON m.departmentName = e.department_name
                            AND CONCAT(e.first_name, ' ', e.last_name) = m.username
                        WHERE e.department_name = ? AND CONCAT(e.first_name, ' ', e.last_name) = ?";*/

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are rows returned from the query
                if ($result->num_rows > 0) {
                    // Iterate through each row and display data in table rows
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td class='subject-column'>" . $row["subject"] . "</td>";
                        //echo "<td>" . $row["departmentName"] . "</td>";

                        // Display status with corresponding class
                        $status = $row["status"];
                       if (!empty($status)) {
                        if ($status == 'pending') {
                            echo '<td><span style="color: blue; background-color: #e6f0ff; padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: blue;">Pending</span></td>';
                        } elseif ($status == 'approved') {
                            echo '<td><span style="color: green; background-color: #e6ffe6; padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: green;">Approved</span></td>';
                        } elseif ($status == 'declined') {
                            echo '<td><span style="color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red;">Declined</span></td>';
                        } else {
                            echo '<td>' . htmlspecialchars($status) . '</td>';
                        }
                    } else {
                        echo '<td><span style="color: blue;">Pending</span></td>';
                    }
                        // Define the sequence
$sequence = [
    'through', 'through2', 'through3', 'through4', 'through5', 
    'through6', 'through7', 'through8', 'through9', 'through10','to'
];

echo "<td>
    <div class='dropdown'>
        <button class='dropdown-button' style='background-color: white; border: none;'>" . htmlspecialchars($row["approved_by"]) . "</button>
        <div class='dropdown-content'>";

// Fetch additional details for the dropdown menu
$memo_id = $row['id'];
$memo_sql = "SELECT * FROM memos WHERE id = ?";
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

                        echo "<td class='td_layout'>
                            <a href='view_request_memo.php?id=" . $row['id'] . "'><i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i></a>";

                        if ($status == 'pending') {
                            echo "<a href='update_memo.php?id=" . $row['id'] . "' >
                                    <i class='fa fa-edit' data-toggle='tooltip' title='Edit' style='color: #5cd65c;'></i>
                                  </a>
                                  <a href='Memo_request.php?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\");'>
                                    <i class='fa fa-trash' data-toggle='tooltip' title='Delete' style='color: #ff3333;'></i>
                                  </a>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // If no rows returned from the query, display a message
                    echo "<tr style='color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red;'><td colspan='7'>No memos found</td></tr>";
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
                                window.location.href = 'dashboard.php'; // Redirect to your page
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
                                window.location.href = 'dashboard.php'; // Redirect to your page
                            });
                          </script>";
                }
            }

            // Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
