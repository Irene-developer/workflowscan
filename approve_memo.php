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

    // Query the database with the approval sequence and approval status check
    $sql = "
    SELECT m.*, e.department_name
    FROM memos m
    INNER JOIN employee_access e 
        ON (m.through = e.username
            OR m.through2 = e.username
            OR m.through3 = e.username
            OR m.through4 = e.username
            OR m.through5 = e.username
            OR m.through6 = e.username
            OR m.through7 = e.username
            OR m.through8 = e.username
            OR m.through9 = e.username
            OR m.through10 = e.username
            OR m.to = e.username)
    WHERE e.department_name = ? 
        AND e.username = ?
        AND m.status IN ('pending', 'approved', 'declined','Recommended')
        AND m.final_approvail_of_To = 0
    ORDER BY 
        CASE
            WHEN m.through = e.username THEN 1
            WHEN m.through2 = e.username THEN 2
            WHEN m.through3 = e.username THEN 3
            WHEN m.through4 = e.username THEN 4
            WHEN m.through5 = e.username THEN 5
            WHEN m.through6 = e.username THEN 6
            WHEN m.through7 = e.username THEN 7
            WHEN m.through8 = e.username THEN 8
            WHEN m.through9 = e.username THEN 9
            WHEN m.through10 = e.username THEN 10
            WHEN m.to = e.username THEN 11
            ELSE 12
        END
    ";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $department_name, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any matching memos
    if ($result->num_rows > 0) {
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
            <style>
                table {
                    width: 99%;
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
                    margin-left: 1200px;
                    color: #3385ff;
                    max-width: 20px;
                    text-decoration: none;
                    background-color: transparent;
                }
                    /* Base styling for all status spans */
.status {
    padding: 5px;
    border: 1px solid;
    border-radius: 0.5em;
    display: inline-block;
    word-wrap: break-word;
}

/* Specific styles for each status */
.pending {
    color: blue;
    background-color: #e6f0ff;
    border-color: blue;
}

.approved {
    color: green;
    background-color: #e6ffe6;
    border-color: green;
}

.declined {
    color: red;
    background-color: #ffe6e6;
    border-color: red;
}

.recommended {
    color: orange;
    background-color: #ffecd9;
    border-color: orange;
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .status {
        font-size: 12px; /* Reduce font size on small screens */
        padding: 3px;    /* Adjust padding for better fit */
    }
    
    /* Add break after each status for small screens */
    .status {
        display: block;
        margin-bottom: 5px;
    }
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
        while ($row = $result->fetch_assoc()) {
            // Check if the current position has access based on approval logic
            $canView = false;

            if ($row["through"] == $username && $row["status"] == 'pending') {
                $canView = true;
            } elseif ($row["through2"] == $username && $row["through"] == $row["through"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through3"] == $username && $row["through2"] == $row["through2"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through4"] == $username && $row["through3"] == $row["through3"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through5"] == $username && $row["through4"] == $row["through4"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through6"] == $username && $row["through5"] == $row["through5"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through7"] == $username && $row["through6"] == $row["through6"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through8"] == $username && $row["through7"] == $row["through7"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through9"] == $username && $row["through8"] == $row["through8"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["through10"] == $username && $row["through9"] == $row["through9"] && $row["status"] == 'Recommended') {
                $canView = true;
            } elseif ($row["to"] == $username && $row["through10"] == $row["through10"] && $row["status"] == 'Recommended') {
                $canView = true;
                
            }

            if ($canView) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["department_name"]) . "</td>";

                // Display status with color
                $status = $row["status"];
                if (!empty($status)) {
                    if ($status == 'pending') {
                        echo '<td><span class="status pending">Pending</span></td>';
                    } elseif ($status == 'approved') {
                        echo '<td><span class="status approved">Approved By ' . htmlspecialchars($row["approved_by"]) . '</span></td>';
                    } elseif ($status == 'declined') {
                        echo '<td><span class="status declined">Declined By ' . htmlspecialchars($row["approved_by"]) . '</span></td>';
                    } elseif ($status == 'Recommended') {
                        echo '<td><span class="status recommended">Recommended By ' . htmlspecialchars($row["approved_by"]) . '</span></td>';
                    } else {
                        echo '<td>' . htmlspecialchars($status) . '</td>';
                    }
                } else {
                    echo '<td><span class="status pending">Pending</span></td>';
                }

                echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                echo "<td style='cursor: pointer;' onclick=\"window.location.href = 'viewMemo.php?id=" . urlencode($row['id']) . "&username=" . urlencode($username) . "'\"><i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i></td>";

                echo "</tr>";
            }
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
        echo "<p style='color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red; max-width: 98%;' >No memos found.</p>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the case when department_name or Position_name is not set in session
    echo "<p style='color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red; max-width: 98%;' >No user  found.</p>";
}
?>
