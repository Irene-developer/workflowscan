<?php
            // Include file with database connection
            include 'include.php';

            // Process form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Initialize variables to store form data
                $applicantName = $_POST['applicant-name'];
                $designation = $_POST['designation'];
                $department = $_POST['department'];
                $natureOfClaim = $_POST['claim-nature'];
                $claimantSignature = $_POST['claimant-signature'];
                $claimDate = $_POST['claim-date'];
                $imprestReferenceCode = $_POST['imprest-reference-code'];

                // File handling
                $uploadDir = "uploadsretire/"; // Directory where files will be uploaded
                $uploadedFiles = array();

                // Handle file uploads
                if (!empty($_FILES['file-upload']['name'][0])) {
                    foreach ($_FILES['file-upload']['name'] as $key => $filename) {
                        $targetFilePath = $uploadDir . basename($_FILES['file-upload']['name'][$key]);
                        if (move_uploaded_file($_FILES['file-upload']['tmp_name'][$key], $targetFilePath)) {
                            $uploadedFiles[] = $targetFilePath;
                        } else {
                            echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'File Upload Error',
                                        text: 'Error uploading file $filename'
                                    });
                                 </script>";
                            exit;
                        }
                    }
                }

                // Prepare SQL insert statement
                $sql = "INSERT INTO retirement (applicant_name, designation, department, nature_of_claim, claimant_signature, date, uploaded_files, retirement_status, imprest_reference_code) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending Retirement', ?)";

                // Prepare and bind parameters
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Database Error',
                                text: 'Error preparing statement: " . htmlspecialchars($conn->error) . "'
                            });
                         </script>";
                    exit;
                } else {
                    $uploadedFilesStr = implode(",", $uploadedFiles); // Convert uploaded files array to string
                    $stmt->bind_param("sssssssi", $applicantName, $designation, $department, $natureOfClaim, $claimantSignature, $claimDate, $uploadedFilesStr, $imprestReferenceCode);

                    // Execute the prepared statement
                    if ($stmt->execute()) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Records inserted successfully.'
                                }).then(function() {
                                    window.location = 'your_redirect_page.php'; // Redirect to a new page if needed
                                });
                             </script>";
                    } else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Database Error',
                                    text: 'Error executing statement: " . htmlspecialchars($stmt->error) . "'
                                });
                             </script>";
                    }

                    // Close statement
                    $stmt->close();
                }
            }

            // Close connection
            $conn->close();
            ?>
