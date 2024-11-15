<?php
// Include database connection
include 'include.php';

// Assuming $_POST['page'] contains the page number to fetch
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rowsPerPage = 10; // Number of rows per page

// Calculate offset for pagination
$offset = ($page - 1) * $rowsPerPage;

// Query to fetch data with pagination
$sql = "SELECT * FROM department ORDER BY department_name ASC LIMIT $offset, $rowsPerPage";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["department_name"] . "</td>";
        echo "<td>" . $row["Head_of_department"] . "</td>";
        echo "<td>";
        if (!empty($row["sub_department"])) {
            // Output subdepartment details
            echo "<details class='dropdown'>";
            echo "<summary>" . $row["sub_department"] . "</summary>";
            echo "<div class='dropdown-content'>";
            echo "<p>Head of Subdepartment: " . $row["Head_of_subdepartment"] . "</p>";
            echo "</div>";
            echo "</details>";
        } else {
            echo "No subdepartments";
        }
        echo "</td>";
        echo "<td style='text-align: center;'><button onclick='deleteDepartment(" . $row["department_id"] . ")' style='background-color: red; color: white; border-color: red; border-radius: 0.4em;'>Delete</button>
            <button  style='background-color: green; color: white; border-color: green; border-radius: 0.4em;' onclick='open_update_department(" . $row["department_id"] . ")'>Update</button>
        </td>"; // Assuming department_id is the unique identifier
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No departments found</td></tr>";
}

$conn->close();
?>
