<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .container-incidence_add {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .container-incidence_add a {
            color: #3385FF;
            font-size: 24px;
            text-decoration: none;
            transition: transform 0.2s ease-in-out;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3385FF;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .fa-eye {
            font-size: 18px;
            transition: color 0.2s ease-in-out;
        }

        .fa-eye:hover {
            color: #0056b3;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #fefefe;
           
        }

        ::-webkit-scrollbar-track {
            background-color: #fefefe;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container-incidence_add">
        <a href="incidence.php">
            <i class="fa fa-plus-circle"></i>
        </a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Business Unit</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Status</th>
                <th>Reporting Date</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include 'include.php';

                // Fetch data from the database
                $sql = "SELECT * FROM incidents";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["business_unit"] . "</td>";
                        echo "<td>" . $row["phone_number"] . "</td>";
                        echo "<td>" . $row["email_address"] . "</td>";
                       $status = $row["status"];

// Determine the color based on the status
switch ($status) {
    case 'Open':
        $color = 'red';
        break;
    case 'in_progress':
        $color = 'blue';
        break;
    case 'closed':
        $color = 'green';
        break;
    default:
        $color = 'gray'; // Default color if status is not recognized
}

echo "<td><span style='color: $color; background-color: rgba(255, 255, 255, 0.1); padding: 5px; border: 1px solid; border-radius: 0.5em; border-color: $color;'>$status</span></td>";

                        echo "<td>" . $row["reporting_date"] . "</td>";    
                        echo '<td><a href="view_incidence.php?id=' . $row['id'] . '" style="color: #3385ff;"><i class="fa fa-eye"></i></a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No data found</td></tr>";
                }
                $conn->close();
            ?>
        </tbody>
    </table>

    <script>
        // Optional: Add smooth scrolling to top when the page loads
        document.addEventListener("DOMContentLoaded", function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
