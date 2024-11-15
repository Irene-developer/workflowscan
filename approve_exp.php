<?php 
// Start the session
session_start();

// Check if the department_name and Position_name are set in session
if (isset($_SESSION['department_name']) && isset($_SESSION['username'])) {
    // Retrieve department_name and Position_name from session
    $department_name = $_SESSION['department_name'];
    $username = $_SESSION['username'];

    // Include database connection
    include 'include.php';

    // Query the database to retrieve rows where Approver1 is the current user
    $sql = "SELECT * FROM imprest_expenditure 
WHERE branch_name = 'BRANCH' 
AND Approver1 = ? AND status = 'pending'
";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_approver1 = $stmt->get_result();
    $stmt->close();

    // Query the database to retrieve rows where Approver2 is the current user
    $sqlh = "SELECT * FROM imprest_expenditure 
WHERE (branch_name = 'HQ' AND status = 'pending') 
OR (branch_name = 'HQ' AND Approver2 = ? AND status = 'approved') 
OR (branch_name = 'BRANCH' AND Approver2 = ? AND status = 'approved')

";

    $stmt = $conn->prepare($sqlh);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result_approver2 = $stmt->get_result();
    $stmt->close();

    // Combine the results of Approver1 and Approver2
    $result = array_merge($result_approver1->fetch_all(MYSQLI_ASSOC), $result_approver2->fetch_all(MYSQLI_ASSOC));

    // Check if there are any matching rows
    if (!empty($result)) {
        // Output the table structure
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Memo Request</title>
            <link rel='stylesheet' type='text/css' href='https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
            <link href='assets/css/responsive.css' rel='stylesheet' type='text/css'/>
              <script src='assets/plugins/jquery-1.8.3.min.js' type='text/javascript'></script> 
    <script src='assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js' type='text/javascript'></script> 
    <script src='assets/plugins/bootstrap/js/bootstrap.min.js' type='text/javascript'></script> 
    <script src='assets/plugins/breakpoints.js' type='text/javascript'></script> 
    <script src='assets/plugins/jquery-unveil/jquery.unveil.min.js' type='text/javascript'></script> 
    <script src='assets/plugins/jquery-block-ui/jqueryblockui.js' type='text/javascript'></script> 
    <script src='assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js' type='text/javascript'></script>
    <script src='assets/plugins/pace/pace.min.js' type='text/javascript'></script>  
    <script src='assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js' type='text/javascript'></script>
    <script src='assets/js/core.js' type='text/javascript'></script> 
    <script src='assets/js/chat.js' type='text/javascript'></script> 
    <script src='assets/js/demo.js' type='text/javascript'></script> 
            <style>
                table {
                    width: 98%;
                    border-collapse: collapse;
                    border: 1px solid #ddd;
                    margin-top: 10px; /* Border color */
                }

                th, td {
                    padding: 8px;
                    text-align: center;
                }

                th {
                    background-color: #3385ff; /* Background color for header */
                    color: white;
                }

                tr:nth-child(even) {
                    background-color: #f2f2f2; /* Background color for even rows */
                }

                tr:hover {
                    background-color: #ddd; /* Background color on hover */
                }

                a.navigation-link {
                    margin-left: 1440px;
                    color: #3385ff;
                    max-width: 20px;
                    text-decoration: none;
                    background-color: transparent;
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
                    <th>Date</th>
                    <th>View</th>
                </tr>
                <tbody>";

        // Iterate through each row and display data in table rows
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row["imprest_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["department_name"] . "</td>";

            // Assuming $status variable holds the status value
            $status = $row["status"];// Initialize $status variable

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

            echo "<td>" . $row["date"] . "</td>";
            echo "<td style='cursor: pointer;' onclick=\"window.location.href = 'viewExp.php?imprest_id=" . $row['imprest_id'] . "&username=" . urlencode($username) . "'\"><i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i></td>";


            echo "</tr>";
        }

        // Close the table structure
        echo "</tbody>
            </table>

            <script>
                function addmemoreq() {
                    var amount = prompt('Enter the amount:');
                    if (amount !== null) {
                        // Proceed with adding the expenditure request
                        // You can add further processing here
                    }
                }
            </script>
        </body>
        </html>";
    } else {
        // If no rows returned from the query, display a message
        echo "<p style='color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-color: red; max-width: 98%;'>No Imprest found:</p>";
    }

    // Close connection
    $conn->close();
} else {
    // Handle the case when department_name or Position_name is not set in session
    echo "<p>Department Name or Position Name not found!</p>";
}
?>
