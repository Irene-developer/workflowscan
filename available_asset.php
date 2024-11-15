<?php
include 'include.php'; // Ensure this includes database connection setup

// Start the session
session_start();

// Check if the user is logged in (if necessary for other parts of your application)
if (!isset($_SESSION['username'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php"); // Replace with your login page URL
    exit(); // Ensure script stops here
}

// Prepare the SQL statement to avoid SQL injection
$sql = "SELECT asset_id, asset_name, asset_type, asset_tag, serial_number, purchase_date, warranty_expiry, status, username, department, location, remarks
        FROM employee_access
        INNER JOIN ict_assets ON employee_access.id = ict_assets.assigned_to";

// Use prepared statements for safe querying
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Failed to prepare the SQL statement: " . $conn->error);
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asset Management Dashboard</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 20px;
    }
    h2 {
        text-align: center;
        color: #333;
    }
    .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 1px 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #3385ff;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    .button {
        background-color: #3385ff;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        border-radius: 5px;
        max-width: 100px;
    }
    .popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .popup-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 80%;
        max-width: 800px;
        max-height: 80%; /* Limit height to prevent full-screen overflow */
        overflow-y: auto; /* Enable vertical scrolling */
        position: relative;
    }
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 24px;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        th, td {
            padding: 8px;
            font-size: 14px;
        }
        .button {
            padding: 8px 16px;
            font-size: 14px;
        }
    }
    @media (max-width: 480px) {
        th, td {
            padding: 6px;
            font-size: 12px;
        }
        .button {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>
<body>

<div class="container">
    <h2>Asset List</h2>
    <a href="#" id="add-asset-link" onclick="showPopup(event)" class="button">Add New Asset</a>
</div>

<!-- Popup element -->
<div id="popup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <div id="popup-body">
            <!-- Content from add_asset.php will be loaded here -->
        </div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Asset ID</th>
            <th>Asset Name</th>
            <th>Asset Type</th>
            <th>Asset Tag</th>
            <th>Serial Number</th>
            <th>Purchase Date</th>
            <th>Warranty Expiry</th>
            <th>Status</th>
            <th>Assigned To</th>
            <th>Department</th>
            <th>Location</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            // Output data for each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["asset_id"]) . "</td>
                    <td>" . htmlspecialchars($row["asset_name"]) . "</td>
                    <td>" . htmlspecialchars($row["asset_type"]) . "</td>
                    <td>" . htmlspecialchars($row["asset_tag"]) . "</td>
                    <td>" . htmlspecialchars($row["serial_number"]) . "</td>
                    <td>" . htmlspecialchars($row["purchase_date"]) . "</td>
                    <td>" . htmlspecialchars($row["warranty_expiry"]) . "</td>
                    <td>" . htmlspecialchars($row["status"]) . "</td>
                    <td>" . htmlspecialchars($row["username"]) . "</td>
                    <td>" . htmlspecialchars($row["department"]) . "</td>
                    <td>" . htmlspecialchars($row["location"]) . "</td>
                    <td>" . htmlspecialchars($row["remarks"]) . "</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No assets found</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
function showPopup(event) {
    event.preventDefault(); // Prevent default link behavior
    var popup = document.getElementById('popup');
    var popupBody = document.getElementById('popup-body');

    // Fetch content from add_asset.php and display it in the popup
    fetch('add_asset.php')
        .then(response => response.text())
        .then(data => {
            popupBody.innerHTML = data; // Insert fetched content into the popup
            popup.style.display = 'flex'; // Show the popup
        })
        .catch(error => {
            console.error('Error fetching the content:', error);
        });
}

function closePopup() {
    document.getElementById('popup').style.display = 'none'; // Hide the popup
}

document.addEventListener('DOMContentLoaded', function() {
    var popup = document.getElementById('popup');
    var closeBtn = document.querySelector('.close-btn');

    // Handle the close button click
    closeBtn.addEventListener('click', function() {
        closePopup();
    });

    // Close popup when clicking outside of the popup content
    window.addEventListener('click', function(e) {
        if (e.target === popup) {
            closePopup();
        }
    });
});
</script>
</body>
</html>

<?php
// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>


