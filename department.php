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
    <title>Department</title>
    <link rel="stylesheet" type="text/css" href="stylesdepartment.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<header>
    <div class="logo-section">
        <img src="KCBLLOGO.png" alt="Your Logo">
    </div>
    <h1>Departments</h1>
    <div class="send-request">
        
    </div>
</header>

<div class="container">
    <div class="container-access"> 
        <h2>Departments</h2>
       
        <button onclick="opensubForm()">Add New Sub Department</button>
        <button onclick="openForm()">Add New Department</button>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">Department Name</th>
                <th style="text-align: center;">Head of Department</th>
                <th style="text-align: center;">Subdepartments</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
      <tbody>
        <?php
        // Fetch data from the database
        include 'include.php'; // Include database connection
        
        $sql = "SELECT * FROM department ORDER BY department_name ASC";
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
}
else {
                    echo "No subdepartments";
                }
                echo "</td>";
        echo '<td style="text-align: center;">';
echo '<button onclick="deleteDepartment(' . $row["department_id"] . ')" class="action-button delete-button" data-toggle="tooltip" title="Delete"><i class="fa fa-trash" style="color: red;"></i></button>';


 echo '<button class="updateButton" data-department-id="' . $row["department_id"] . '" data-department-name="' . $row["department_name"] . '">
        <i class="fa fa-edit" data-toggle="tooltip" title="Edit" style="color: #5cd65c;"></i>
    </button>';
echo '</td>';


 // Assuming department_id is the unique identifier
                echo "</tr>";

            }
        } else {
            echo "<tr><td colspan='3'>No departments found</td></tr>";
        }
        
        $conn->close();
        ?>
    </tbody>
    </table>
    <div id="pagination"></div>
</div>
<div id="departmentForm" class="form-popup">
    <span class="close" onclick="closeModal()">&times;</span>
    <?php
    include 'include.php';

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        // Retrieve and sanitize form data
        $departmentName = $conn->real_escape_string($_POST['departmentName']);

        // Check if the department name is not empty
        if (!empty($departmentName)) {
            // Check if department already exists
            $check_sql = "SELECT * FROM department WHERE department_name = '$departmentName'";
            $result = $conn->query($check_sql);

            if ($result && $result->num_rows > 0) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Department already exists!',
                        showConfirmButton: true
                    });
                </script>";
            } else {
                // Insert into department table
                $sql = "INSERT INTO department (department_name) VALUES ('$departmentName')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'New Department Created successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.href = 'department.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error creating department',
                            text: 'Error: " . $conn->error . "',
                            showConfirmButton: true
                        });
                    </script>";
                }
            }
        } 
        $conn->close();
    }
    ?>
    <form action="" method="post" class="form-container">
        <h2>Add New Department</h2>
        <label for="departmentName"><b>Department Name</b></label>
        <input type="text" placeholder="Enter Department Name" name="departmentName" required>
        <button type="submit" class="btn-add" name="add" style="background-color: #3385ff;
    color: white;
    padding: 16px 20px;
    border: none;
    cursor: pointer;
    width: 100px;
    margin-bottom: 10px;
    opacity: 0.8;
    border-radius: 1em;
    float: right;">Add</button>
        <button type="button" class="cancel" onclick="closeForm()">Close</button>
    </form>
</div>

<div id="subdepartmentForm" class="form-popup">
    <span class="close" onclick="closeModal()">&times;</span>
    <?php
    include 'include.php';

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        // Retrieve and sanitize form data
        $departmentName = $conn->real_escape_string($_POST['departmentName']);
        $subDepartment = $conn->real_escape_string($_POST['subdepartmentName'][0]); // Assuming only one subdepartment per form

        // Check if both fields are not empty
        if (!empty($departmentName) && !empty($subDepartment)) {
            // Check if subdepartment already exists
            $check_sql = "SELECT * FROM department WHERE department_name = '$departmentName' AND sub_department = '$subDepartment'";
            $result = $conn->query($check_sql);

            if ($result && $result->num_rows > 0) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Sub-Department already exists!',
                        showConfirmButton: true
                    });
                </script>";
            } else {
                // Insert into department table
                $sql = "INSERT INTO department (department_name, sub_department) VALUES ('$departmentName', '$subDepartment')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'New Sub-Department Created successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.href = 'department.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error creating sub-department',
                            text: 'Error: " . $conn->error . "',
                            showConfirmButton: true
                        });
                    </script>";
                }
            }
        }

        $conn->close();
    }
    ?>
    <form action="" class="form-container" id="subDepartmentForm" onsubmit="return updateSubDepartments(event);">
        <h2>Add New Sub Department</h2>
        <label for="subdepartmentName"><b>Sub Department Name</b></label>
        <input type="text" placeholder="Enter Sub Department Name" name="subdepartmentName[]" required>
        <label for="departmentName"><b>Department Name</b></label>
        <input type="text" placeholder="Enter Department Name" name="departmentName" required id="departmentNameInput">
        <div id="departmentNamesDropdown"></div>

        <button type="submit" class="btn">Save</button>
        <button type="button" class="cancel" onclick="closesubForm()">Close</button>
    </form>
</div>




<div id="updatedepartmentForm" class="form-popup" style="display:none;">
    <span class="close" onclick="closeModal()">&times;</span>
    <form method="POST" class="form-container">
        <input type="hidden" name="department_id" id="updateDepartmentId">
        <label for="updateDepartmentName"><b>Department Name:</b></label>
        <input type="text" name="department_name" id="updateDepartmentName">
        <button type="button" class="btnUPDATE" onclick="updateDepartments()" style="background-color: #3385ff;
    color: white;
    padding: 16px 20px;
    border: none;
    cursor: pointer;
    width: 100px;
    margin-bottom: 10px;
    opacity: 0.8;
    border-radius: 1em;
    float: right;">Update</button>
    </form>
</div>

<script>

//for add department
    function openForm() {
        document.getElementById("departmentForm").style.display = "block";
        document.getElementById("overlay").style.display = "block"; // Show the overlay
    }

    function closeModal() {
        document.getElementById("departmentForm").style.display = "none";
        document.getElementById("overlay").style.display = "none"; // Hide the overlay
    }

//for subdepartment

    function opensubForm() {
        document.getElementById("subdepartmentForm").style.display = "block";
        document.getElementById("overlay").style.display = "block"; // Show the overlay
    }

    function closeModal() {
        document.getElementById("subdepartmentForm").style.display = "none";
        document.getElementById("overlay").style.display = "none"; // Hide the overlay
    }

//for departmenmt updates

    function open_update_department() {
        document.getElementById("updatedepartmentForm").style.display = "block";
        document.getElementById("overlay").style.display = "block"; // Show the overlay
    }

    function closeModal() {
        document.getElementById("updatedepartmentForm").style.display = "none";
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
  


// Function to handle the submission of multiple subDepartments
function updateSubDepartments(event) {
    event.preventDefault(); // Prevent default form submission

    var formData = $('#subDepartmentForm').serialize();

    // Disable the submit button to prevent multiple submissions
    $('#submitButton').prop('disabled', true);

    $.ajax({
        url: 'update_subdepartment.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            Swal.fire({
                icon: response.icon,
                title: response.title,
                text: response.message,
                showConfirmButton: response.showConfirmButton,
                timer: response.timer
            }).then(function () {
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    // Re-enable the submit button if there is no redirect
                    $('#submitButton').prop('disabled', false);
                }
            });
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.',
                showConfirmButton: true
            });

            // Re-enable the submit button on error
            $('#submitButton').prop('disabled', false);
        }
    });
}

    //update department
 
        // Function to fetch department names for selection
        $('#departmentNameInput').click(function() {
            $.ajax({
                url: 'fetch_department_names.php',
                type: 'GET',
                success: function(response) {
                    var departmentNames = JSON.parse(response);
                    $('#departmentNamesDropdown').empty();
                    departmentNames.forEach(function(department) {
                        var departmentName = department.department_name;
                        var dropdownItem = $('<div class="dropdown-item">' + departmentName + '</div>');
                        dropdownItem.click(function() {
                            $('#departmentNameInput').val(departmentName);
                            $('#departmentNamesDropdown').empty();
                        });
                        $('#departmentNamesDropdown').append(dropdownItem);
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

/*Function to handle department updates
function updateDepartments() {
    var formData = $('#updatedepartmentForm').serialize();

    $.ajax({
        url: 'update_department.php', // Replace with the actual endpoint to update department details
        type: 'POST',
        data: formData,
        success: function(response) {
            // Display success message using SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response // Response message from server
            }).then(() => {
                closeModal(); // Close the modal after update
                // Optionally, you can refresh the department list or take other actions here
            });
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(error);
            // Display error message using SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to update department.' // Optional: Customize error message
            });
        }
    });
}

// Function to close the modal
function closeModal() {
    $('#updatedepartmentFormContainer').hide(); // Hide the modal
}

*/


// Function to handle department deletion
function deleteDepartment(departmentId) {
    // Use SweetAlert for confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this department?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed deletion, proceed with AJAX request
            $.ajax({
                url: 'delete_department.php',
                type: 'POST',
                data: { department_id: departmentId }, // Replace 'departmentId' with the actual department ID
                success: function(response) {
                    // Handle success response
                    Swal.fire('Deleted!', 'The department has been deleted.', 'success');
                    // Optionally, update the UI or perform any additional actions
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire('Error!', 'Failed to delete the department.', 'error');
                }
            });
        }
    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Function to handle opening the update popup
    $('.updateButton').on('click', function() {
        var departmentId = $(this).data('department-id');
        var departmentName = $(this).data('department-name');

        $('#updateDepartmentId').val(departmentId);
        $('#updateDepartmentName').val(departmentName);

        $('#updatedepartmentForm').show(); // Show the popup
    });

    // Function to handle closing the update popup
    function closeModal() {
        $('#updatedepartmentForm').hide(); // Hide the popup
        $('#subdepartmentForm').hide();
        $('#departmentForm').hide();
    }

    // Function to handle department updates
    function updateDepartments() {
        var formData = $('form.form-container').serialize();

        $.ajax({
            url: 'update_department.php', // Replace with the actual endpoint to update department details
            type: 'POST',
            data: formData,
            success: function(response) {
                // Display success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response // Response message from server
                }).then(() => {
                    closeModal(); // Close the modal after update
                    window.location.href = 'department.php';
                });
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update department.' // Optional: Customize error message
                });
            }
        });

    }

   
    // Expose the closeModal and updateDepartments functions to the global scope
    window.closeModal = closeModal;
    window.updateDepartments = updateDepartments;
});
</script>

<script>
    $(document).ready(function(){
        // Define function to fetch and display data
        function fetchData(page) {
            $.ajax({
                url: 'fetch_data.php', // Replace with the actual endpoint to fetch data
                type: 'POST',
                data: { page: page },
                success: function(response) {
                    $('#tableBody').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        
        // Fetch and display data initially on page load
        fetchData(1);
        
        // Define function for pagination
        function paginate(totalPages) {
            var paginationHtml = '';
            for (var i = 1; i <= totalPages; i++) {
                paginationHtml += '<button onclick="fetchData(' + i + ')">' + i + '</button>';
            }
            $('#pagination').html(paginationHtml);
        }
        
        // Assume totalRows and rowsPerPage are known from the server-side or can be calculated
        var totalRows = <?php echo $totalRows; ?>;
        var rowsPerPage = 10; // You can adjust this value
        
        // Calculate total pages
        var totalPages = Math.ceil(totalRows / rowsPerPage);
        
        // Call paginate function
        paginate(totalPages);
    });
</script>
<?php include 'footer.php'; ?> 
</body>
</html>
