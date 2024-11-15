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
    $sql = "SELECT * FROM retirement WHERE Approver1 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_approver1 = $stmt->get_result();
    $stmt->close();

    // Query the database to retrieve rows where Approver2 is the current user and ApproveActions is 1
    $sql = "SELECT * FROM retirement WHERE Approver2 = ? AND ApproveActions = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
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
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["applicant_name"] . "</td>";
            echo "<td>" . $row["department"] . "</td>";

            // Determine status based on ApproveActions value
            switch ($row["ApproveActions"]) {
                case 3:
                    $status = "Pending Retirement";
                    break;
                case 1:
                    $status = "Waiting for Final Approval";
                    break;
                case 2:
                    $status = "Approved";
                    break;
                case 0:
                    $status = "Declined";
                    break;
                default:
                    $status = "Unknown";
                    break;
            }

            echo '<td><span style="color: ' . ($status == 'Approved' ? 'green' : ($status == 'Declined' ? 'red' : 'blue')) . ';">' . $status . '</span></td>';
            echo "<td>" . $row["date"] . "</td>";
            echo "<td style='cursor: pointer;' onclick=\"window.location.href = 'viewRetire.php?id=" . $row['id'] . "&username=" . urlencode($username) . "'\"><i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i></td>";
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
        echo "<p style='color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red; max-width:98%;'>No Imprest found:</p>";
    }

    // Close connection
    $conn->close();
} else {
    // Handle the case when department_name or Position_name is not set in session
    echo "<p>Department Name or Position Name not found!</p>";
}
?>
