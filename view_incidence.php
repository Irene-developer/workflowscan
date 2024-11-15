<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Incident Details</title>
    <style>
        body {
            margin: 15;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-bottom: 50px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .content {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
        }

        .label {
            font-weight: bold;
        }

        .label-value {
            text-align: center;
        }

        img.logo {
            max-width: 200px;
            height: auto;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .form-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            align-items: flex-start;
            text-align: left;
        }

        .rotate-text {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header-contents {
            display: flex;
            justify-content: center;
            align-items: center;
            align-content: center;
        }

        .print-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 24px;
        }

        @media print {
            .content1 {
                display: none;
            }
        }
    </style>
</head>

<body>
<?php include 'header.php'; ?>
    <?php
    session_start();
    include 'include.php';

    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT * FROM incidents WHERE id = $id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
    ?>
            <div class="container" id="printDetailsForm">
                <div class="header-contents">
                    <img src="KCBLLOGO.png" alt="KCBL Logo" class="logo">
                    <div class="form-title">EVENT & INCIDENT REPORTING FORM</div>
                </div>

               

                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="section-header">Basic Information</div>
                    <div class="content">
                        <div class="row">
                            <div class="label" style="color: black;">Business Unit:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['business_unit']; ?></div>
                        </div>
                        <div class="row">
                            <div class="label" style="color: black;">Name:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['name']; ?></div>
                        </div>

                        <div class="row">
                            <div class="label" style="color: black;">Phone:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['phone_number']; ?></div>
                        </div>

                        <div class="row">
                            <div class="label" style="color: black;">Email:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['email_address']; ?></div>
                        </div>

                        <div class="row">
                            <div class="label" style="color: black;">Unit (Branch):</div>
                            <div class="label-value" style="color: black;"><?php echo $row['branch']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="section"style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="section-header" style="background-color: #3385ff; border: solid #3385ff; color: white;">Incident Dates</div>
                    <div class="content" >
                        <div class="row" >
                            <div class="label" style="color: black;">Discovery Date:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['discovery_date']; ?></div>
                        </div>
                        <div class="row">
                            <div class="label" style="color: black;">Incident Date:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['incident_date']; ?></div>
                        </div>
                        <div>
                            <div class="label" style="color: black;">Reporting Date:</div>
                            <div class="label-value" style="color: black;"><?php echo $row['reporting_date']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="section-header">Impact Dates</div>
                    <div class="content">
                        <div class="row">
                            <div class="label"style="color: black;">Impact Date:</div>
                            <div class="label-value"style="color: black;"><?php echo $row['impact_date_from']; ?> to <?php echo $row['impact_date_to']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="rotate-text">WHAT HAPPENED?</div>
                    <div class="content" style="color: black;"><?php echo $row['what_happened']; ?></div>
                </div>

                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="rotate-text">HOW IT HAPPENED?</div>
                    <div class="content" style="color: black;"><?php echo $row['how_it_happened']; ?></div>
                </div>

                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="rotate-text">IMPACTS TO THE BUSINESS?</div>
                    <div class="content" style="color: black;"><?php echo $row['impacts_to_business']; ?></div>
                </div>

                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="rotate-text">ROOT CAUSE OF INCIDENT (selected)?</div>
                    <div class="content" style="color: black;"><?php echo $row['root_cause_select']; ?></div>
                </div>
                <?php if ($row['root_cause_select'] == "Known") { ?>
                    <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                        <div class="rotate-text">ROOT CAUSE OF INCIDENT?</div>
                        <div class="content" style="color: black;"><?php echo $row['root_cause_input']; ?></div>
                    </div>
                <?php } ?>


                <div class="section" style="background-color: #3385ff; border: solid #3385ff; color: white;">
                    <div class="rotate-text">ACTIONS TAKEN?</div>
                    <div class="content"style="color: black;"><?php echo $row['actions_taken']; ?></div>
                </div>

                <div class="section" style="background-color: #3385ff; /* Background color for sections */
    border: 1px solid #3385ff; /* Border color and style */
    color: white; /* Text color for section header */
    padding: 10px; /* Padding inside the section */
    border-radius: 6px; /* Rounded corners for the section */
    margin-bottom: 20px; /* Space below each section */">
                    <div class="rotate-text" style=" font-weight: bold; /* Bold text for section headers */
    margin-bottom: 10px; /* Space below the header */
    color: white; /* Text color for section headers */">STATUS (CLOSED/OPEN)?</div>
                    <div class="content"style="color: black;"><?php echo $row['status']; ?></div>
                    <div class="content1" style="margin-top: 10px;">
                        <form action="" method="post">
                            <select name="status">
                                <option value="closed" <?php if ($row['status'] == 'closed') echo 'selected'; ?>>Closed</option>
                                <option value="open" <?php if ($row['status'] == 'open') echo 'selected'; ?>>Open</option>
                                <option value="in_progress" <?php if ($row['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                            </select>
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Change Status">
                        </form>
                    </div>
                </div>

                <?php
                // Include your database configuration file or establish a database connection here
                include 'include.php'; // Include file containing database connection settings

                // Check if the form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Retrieve the status and item ID from the form submission
                    $status = $_POST['status'];
                    $id = $_POST['id'];

                    // Perform any necessary validation on the input
                    // (e.g., check if $status is one of the allowed values)

                    // Update the status in the database
                    $sql = "UPDATE incidents SET status = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $status, $id);

                    if ($stmt->execute()) {
                        // Display success message
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated Successfully',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                window.location.href = 'dashboard.php';
                            });
                        </script>";
                    } else {
                        // Display error message
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Updating Status',
                                text: '" . $stmt->error . "',
                                showConfirmButton: true
                            });
                        </script>";
                    }

                    // Close the statement and database connection
                    $stmt->close();
                    $conn->close();
                }
                ?>
                 <h1 onclick="printEmployeeDetails()" class="print-icon"><i id="printIcon" class="fa fa-print"></i></h1>
            </div>

    <?php
        }
    }
    ?>

    <script>
        function printEmployeeDetails() {
            var printContents = document.getElementById('printDetailsForm').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>
