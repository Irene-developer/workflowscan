<?php
        // Fetch data from the database
        include 'include.php'; // Include database connection
        include('session_timeout.php');
?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positions</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="position.css">
    <link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<header>
    <div class="logo-section">
        <img src="KCBLLOGO.png" alt="Your Logo">
    </div>
    <h1>Manage Positions</h1>
    <div class="send-request">
        <a href="sendrequest.php"><i class='bx bx-add-to-queue bx-burst'></i></a>
    </div>
</header>

<div class="container">
    <div class="header">
  <h2>Positions</h2>
  <button class="add-button" onclick="updateROWSelected()"><li class="fa fa-edit"></li></button>
  <button class="add-button" onclick="deleteROWSelected()"><li class="fa fa-trash"></li></button>
  <button class="add-button" onclick="openForm()"><li class="fa fa-plus-circle"></li></button>
  <a href="dashboard.php" class="add-button" style="text-decoration: none;"><li class="fa fa-arrow-back"></li>Back</a>
</div>

    <table>
        <thead>
        <tr><th>ID</th>
            <th>Position Name</th>
            <th>department Name</th>
            <th>Mark</th>
        </tr>
        </thead>
        <tbody  id="departmentTableBody">
        <!-- Data will be dynamically populated here -->
            <?php
        // Assuming you have a database connection established already
        include 'include.php';
        // Perform the query to fetch department names
        $query = "SELECT * FROM position";
        $result = mysqli_query($conn, $query);

        
        if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr onclick='selectRow(this)'>";
    echo "<td>".$row['position_id']."</td>";
    echo "<td>" . $row['Position_name'] . "</td>";
    echo "<td>". $row['department_name'] ."</td>";
    echo "<td><input type='checkbox' class='delete-checkbox' value='8'></td>"; // Icon for selected row
    echo "</tr>";
}



        } else {
            echo "Error fetching Access Rights: " . mysqli_error($connection);
        }
        ?>
        </tbody>
    </table>
</div>

<!-- The form to add a new position -->
<div id="positionForm" class="form-popup">
 <?php
include 'include.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addposition'])) {
    // Retrieve form data
    $departmentName = $conn->real_escape_string($_POST['departmentName']);
    $positionName = $conn->real_escape_string($_POST['positionName']);

    // Check if all fields are not empty
    if (!empty($departmentName) && !empty($positionName)) {
        // Check if position already exists
        $check_sql = "SELECT * FROM position WHERE department_name = '$departmentName' AND Position_name = '$positionName'";
        $result = $conn->query($check_sql);
        
        if ($result && $result->num_rows > 0) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Position Already Exist',
                    showConfirmButton: false,
                    timer: 3000
                }).then(function () {
                    window.location.href = 'position.php';
                });
            </script>";
        } else {
            // Insert into position table
            $sql = "INSERT INTO position (department_name, Position_name) VALUES ('$departmentName', '$positionName')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'New Position Created successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.href = 'position.php';
                    });
                </script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Department Name and Position Name are required fields',
                showConfirmButton: false,
                timer: 3000
            }).then(function () {
                window.location.href = 'position.php';
            });
        </script>";
    }
}
?>


    <form action="" class="form-container" method="post" id="addPositionForm">
        <h2>Add Position</h2>
        <label for="positionName"><b>Position Name</b></label>
        <input type="text" placeholder="Enter Position Name" name="positionName" required>

        <label for="departmentName"><b>Department Name</b></label>
        <input type="text" placeholder="Enter Department Name" name="departmentName" required id="departmentNameInput">
        <div id="departmentNamesDropdown"></div>

        <!--label for="subdepartmentName"><b>Sub Department Name</b></label>
        <input type="text" placeholder="Enter Sub Department Name" name="subdepartmentName" id="subdepartmentNameInput">
        <div id="subDepartmentDropdown"></div-->

        <button type="submit" class="btn" name="addposition">Add Position</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
</div>
<div id="updatePositionForm" class="form-popup" style="display:none;">
    <form action="" class="form-container" method="post" id="updatePositionForm">
        <h2>Update Position</h2>
        <label for="updatePositionName"><b>Position Name</b></label>
        <input type="text" placeholder="Enter Position Name" name="updatePositionName" id="updatePositionName" required>

        <label for="updateDepartmentName"><b>Department Name</b></label>
        <input type="text" placeholder="Enter Department Name" name="updateDepartmentName" id="updateDepartmentName">

        <input type="hidden" id="updatePositionId" name="updatePositionId">

        <button type="submit" class="btn" name="updateposition">Update Position</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
</div>
<div class="overlay" id="overlay" style="display:none;" onclick="closeForm()"></div>





<!-- Black background overlay -->
<div class="overlay" id="overlay" onclick="closeForm()"></div>
<script>
    
  // Function to fetch departments from the server
    function fetchDepartments() {
        // AJAX request to fetch departments from the server
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const departments = JSON.parse(xhr.responseText);
                    populateDropdown(departments);
                } else {
                    console.error('Error fetching departments:', xhr.status);
                }
            }
        };
        xhr.open('GET', 'fetch_department_names.php', true);
        xhr.send();
    }

    // Function to populate dropdown with department names
    function populateDropdown(departments) {
        const dropdown = document.getElementById('departmentNamesDropdown');
        dropdown.innerHTML = ''; // Clear previous options

        departments.forEach(department => {
            const option = document.createElement('a');
            option.textContent = department.department_name;
            option.addEventListener('click', () => {
                document.getElementById('departmentNameInput').value = department.department_name; // Fill the input field with department name
                dropdown.style.display = 'none'; // Hide the dropdown after selection
            });
            dropdown.appendChild(option);
        });

        dropdown.style.display = 'block'; // Show the dropdown
    }

    // Event listener for input field click
    document.getElementById('departmentNameInput').addEventListener('click', fetchDepartments);
// Function to add a new subDepartment input field
  


</script>
<script>
//for populate popup update
    function openForm() {
        document.getElementById("positionForm").style.display = "block";
        document.getElementById("overlay").style.display = "block"; // Show the overlay
    }

// Function to toggle selected class on table rows
function selectRow(row) {
    row.classList.toggle('selected'); // Toggle 'selected' class on the clicked row

    // Toggle visibility of select sign/icon
    const selectSign = row.querySelector('.select-sign');
    if (selectSign) {
        selectSign.style.display = selectSign.style.display === 'none' ? 'inline-block' : 'none';
    }
}
// Function to hold update logic 
// Function to populate update form fields with selected row data
function populateUpdateForm(row) {
    const cells = row.cells;
    document.getElementById('updatePositionId').value = cells[0].textContent.trim(); // ID
    document.getElementById('updatePositionName').value = cells[1].textContent.trim(); // Position Name
    document.getElementById('updateDepartmentName').value = cells[2].textContent.trim(); // Department Name
}

// Event listener to select row and populate update form fields when row is clicked
document.getElementById('departmentTableBody').addEventListener('click', function(event) {
    const target = event.target.closest('tr');
    if (target && target.tagName === 'TR') {
        // Toggle selection
        document.querySelectorAll('#departmentTableBody tr').forEach(row => row.classList.remove('selected'));
        target.classList.add('selected');
        populateUpdateForm(target);
    }
});

// Function to open the update form popup
function openUpdateForm() {
    document.getElementById("updatePositionForm").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

// Function to close the update form popup
function closeForm() {
    document.getElementById("updatePositionForm").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

// Function to handle edit button click and display update form
function updateROWSelected() {
    const selectedRows = document.querySelectorAll('#departmentTableBody tr.selected');
    
    if (selectedRows.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'No rows selected',
            text: 'Please select a row to update.',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (selectedRows.length > 1) {
        Swal.fire({
            icon: 'error',
            title: 'Multiple rows selected',
            text: 'Please select only one row to update at a time.',
            showConfirmButton: false,
            timer: 1500
        });
    } else {
        // Display the update form as a popup
        openUpdateForm();
    }
}

// Submit handler for the update form
document.getElementById('updatePositionForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    const updatePositionId = document.getElementById('updatePositionId').value;
    const updatePositionName = document.getElementById('updatePositionName').value;
    const updateDepartmentName = document.getElementById('updateDepartmentName').value;

    const updatedRow = {
        id: updatePositionId,
        updatePositionName: updatePositionName,
        updateDepartmentName: updateDepartmentName
    };

    // Send AJAX request to update rows in database
    updateRowsInDatabase(updatedRow);
});

// Function to send AJAX request to update rows in database
function updateRowsInDatabase(updatedRow) {
    fetch('update_rows.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(updatedRow)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Row Updated Successfully',
                showConfirmButton: true,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error Updating Row',
                text: data.message || 'An error occurred while updating the row. Please try again later.',
                showConfirmButton: true,
            });
        }
    })
    .catch(error => {
        console.error('Error updating row:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error Updating Row',
            text: 'An error occurred while updating the row. Please try again later.',
            showConfirmButton: true,
        });
    });
}

// Function to delete selected rows with SweetAlert confirmation
function deleteROWSelected() {
    const selectedRows = document.querySelectorAll('#departmentTableBody tr.selected');
    const deletedIds = [];

    selectedRows.forEach(row => {
        const id = row.cells[0].textContent.trim(); // Assuming ID is in the first cell
        deletedIds.push(id);
    });

    if (deletedIds.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'No rows selected',
            text: 'Please select rows to delete.',
            showConfirmButton: false,
            timer: 1500
        });
    } else {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Selected row(s) will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete rows from database
                deleteRowsFromDatabase(deletedIds);
            }
        });
    }
}

// Function to send AJAX request to delete rows from database
function deleteRowsFromDatabase(ids) {
    // AJAX request to PHP script to delete rows from database
    fetch('delete_rows.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ids: ids }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Rows deleted successfully',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Optionally, reload or update the table after deletion
                location.reload(); // Example: Reload the page
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error deleting rows',
                text: data.message || 'Unknown error occurred',
                showConfirmButton: false,
                timer: 1500
            });
        }
    })
    .catch(error => {
        console.error('Error deleting rows:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error deleting rows',
            text: 'Failed to delete rows. Please try again.',
            showConfirmButton: false,
            timer: 1500
        });
    });
}


    function closeForm() {
        document.getElementById("positionForm").style.display = "none";
        document.getElementById("overlay").style.display = "none"; // Hide the overlay
    }

    
      // Function to fetch departments from the server
    function fetchDepartments() {
        // AJAX request to fetch departments from the server
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const departments = JSON.parse(xhr.responseText);
                    populateDropdown(departments);
                } else {
                    console.error('Error fetching departments:', xhr.status);
                }
            }
        };
        xhr.open('GET', 'fetch_department_names.php', true);
        xhr.send();
    }

    // Function to populate dropdown with department names
    function populateDropdown(departments) {
        const dropdown = document.getElementById('departmentNamesDropdown');
        dropdown.innerHTML = ''; // Clear previous options

        departments.forEach(department => {
            const option = document.createElement('a');
            option.textContent = department.department_name;
            option.addEventListener('click', () => {
                document.getElementById('departmentNameInput').value = department.department_name; // Fill the input field with department name
                dropdown.style.display = 'none'; // Hide the dropdown after selection
            });
            dropdown.appendChild(option);
        });

        dropdown.style.display = 'block'; // Show the dropdown
    }

    // Event listener for input field click
    document.getElementById('departmentNameInput').addEventListener('click', fetchDepartments);
// Function to add a new subDepartment input field
  

</script>

<?php include 'footer.php'; ?> 
</body>
</html>

