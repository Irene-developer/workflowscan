<?php
include 'include.php'; // Ensure your database connection is included
include('session_timeout.php');
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form data
    $memo_id = $_POST['memo_id'];
    $classfication = $_POST['classfication'];
    $to = $_POST['To'];
    $from = $_POST['from'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    
    // Fetch through fields dynamically
    $throughFields = [];
    for ($i = 1; $i <= 10; $i++) {
        $throughVar = "through" . ($i == 1 ? "" : $i);
        if (!empty($_POST[$throughVar])) {
            $throughFields[$throughVar] = $_POST[$throughVar];
        }
    }

    // Update the memo details in the database
    $sql = "UPDATE memos SET classfication = ?, `to` = ?, `from` = ?, subject = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $classfication, $to, $from, $subject, $content, $memo_id);

    if ($stmt->execute()) {
        // Update through fields
        foreach ($throughFields as $key => $value) {
            $sqlThrough = "UPDATE memos SET $key = ? WHERE id = ?";
            $stmtThrough = $conn->prepare($sqlThrough);
            $stmtThrough->bind_param("si", $value, $memo_id);
            $stmtThrough->execute();
        }
        
        // Success feedback
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Memo Updated Successfully',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'your_redirect_page.php';
            });
        </script>";
    } else {
        // Error feedback
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error updating memo',
                text: '" . $stmt->error . "',
                showConfirmButton: true
            });
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Memo</title>
    <link rel="stylesheet" type="text/css" href="stylescreatememo.css">
    
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #3385ff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #1e70ff;
        }
        .form-header {
            margin-bottom: 15px;
        }
        .custom-dropdown {
            display: flex;
            align-items: center;
        }
        .fa-plus-circle {
            margin-left: 10px;
            cursor: pointer;
        }
        /* Your existing styles */
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            height: 80%;
            overflow-y: auto;
        }
        .popup-header {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .popup-content {
            margin-bottom: 20px;
        }
        .popup-footer {
            text-align: right;
        }
    </style>
</head>
<body>
<h1>Edit Memo</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="memo_id" value="<?php echo $memo_id; ?>">

    <!-- Existing fields -->
    <input type="text" name="username" value="<?php echo $memo['username']; ?>" required readonly>
    <input type="text" name="date" value="<?php echo $memo['date']; ?>" required readonly>
    <input type="text" name="departmentName" value="<?php echo $memo['departmentName']; ?>" required readonly>
    <input type="text" name="refNo" value="<?php echo $memo['refNo']; ?>" required readonly>
    
    <div class="form-header">
        <label for="classfication">Classification</label>
        <select id="classfication" name="classfication" required>
            <option value="">Select classification</option>
            <option value="Internal Memo" <?php if ($memo['classfication'] == "Internal Memo") echo 'selected'; ?>>Internal Memo</option>
            <option value="Open" <?php if ($memo['classfication'] == "Open") echo 'selected'; ?>>Open</option>
            <option value="Confidential" <?php if ($memo['classfication'] == "Confidential") echo 'selected'; ?>>Confidential</option>
        </select>
    </div>

    <!-- Dynamic "Through" fields -->
    <div id="dropdown-container">
        <?php
        for ($i = 1; $i <= 10; $i++) {
            $throughVar = "through" . ($i == 1 ? "" : $i);
            if (!empty($memo[$throughVar])) {
                echo "<div class='form-header'>
                        <label for='{$throughVar}'>Through</label>
                        <div class='custom-dropdown'>
                            <select id='{$throughVar}' name='{$throughVar}' required>
                                <option value=''>Select Position</option>";
                                include 'include.php';
                                if (isset($_SESSION['Position_name'])) {
                                    $currentPositionName = $_SESSION['Position_name'];
                                    $sql = "SELECT DISTINCT Position_name FROM position WHERE Position_name != '$currentPositionName'";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = $memo[$throughVar] == $row["Position_name"] ? 'selected' : '';
                                            echo "<option value='" . $row["Position_name"] . "' $selected>" . $row["Position_name"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No positions found</option>";
                                    }
                                }
                                $conn->close();
                echo "      </select>
                            <a href='#' class='dropdown-toggle'>
                                <i class='fa fa-plus-circle'></i>
                            </a>
                        </div>
                    </div>";
            }
        }
        ?>
    </div>

    <!-- To field -->
    <div class="form-header">
        <label for="To">To</label>
        <select id="To" name="To" required>
            <option value="">Select Position</option>
            <?php
            include 'include.php';
            if (isset($_SESSION['Position_name'])) {
                $currentPositionName = $_SESSION['Position_name'];
                $sql = "SELECT DISTINCT Position_name FROM position WHERE Position_name != '$currentPositionName'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $selected = $memo['to'] == $row["Position_name"] ? 'selected' : '';
                        echo "<option value='" . $row["Position_name"] . "' $selected>" . $row["Position_name"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No positions found</option>";
                }
            }
            $conn->close();
            ?>
        </select>
    </div>

    <!-- Other fields -->
    <input type="text" name="from" value="<?php echo $memo['from']; ?>" required readonly>
    <input type="text" name="subject" value="<?php echo $memo['subject']; ?>" required>

    <!-- Content editor -->
    <script src="https://cdn.tiny.cloud/1/1xpae6vwk3sbnv03ga4b1lznhp5ucril1rznh7lh9cw8ilsi/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <label for="content">Content</label>
    <div id="editor">
        <?php echo isset($memo['content']) ? htmlspecialchars_decode($memo['content']) : ''; ?>
    </div>
    <textarea name="content" id="content" style="display:none;">
        <?php echo isset($memo['content']) ? htmlspecialchars_decode($memo['content']) : ''; ?>
    </textarea>

    <!-- Signature image container -->
    <div id="signature-image-container"></div>

    <input type="submit" value="Update Memo">
</form>

<button id="signature-trigger" style="display: none;">Fetch Signature</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    quill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('content').value = quill.root.innerHTML;
    });

    // Handle adding new "through" fields dynamically
    var throughCount = <?php echo count(array_filter($memo, function($key) { return strpos($key, 'through') === 0; }, ARRAY_FILTER_USE_KEY)); ?>;

    $('.dropdown-toggle').click(function(e) {
        e.preventDefault();
        if (throughCount < 10) {
            throughCount++;
            var newLabel = $('<label>').attr('for', 'through' + throughCount).text('Through');
            var newSelect = $('<select>').attr('id', 'through' + throughCount).attr('name', 'through' + throughCount).attr('required', true);

            $.ajax({
                url: 'fetch_select.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(index, value) {
                        var option = $('<option>').attr('value', value).text(value);
                        newSelect.append(option);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });

            var dropdownContainer = $('#dropdown-container');
            dropdownContainer.append(newLabel);
            dropdownContainer.append(newSelect);
        } else {
            alert('Maximum number of "Through" fields reached.');
        }
    });
});
</script>

<script>
document.getElementById('signature-trigger').addEventListener('click', function() {
    fetchSignature();
});

function fetchSignature() {
    fetch('get_signature.php', {
        method: 'POST',
        credentials: 'same-origin',
    })
    .then(response => response.text())
    .then(signaturePath => {
        displaySignatureImage(signaturePath);
    })
    .catch(error => console.error('Error fetching signature:', error));
}

function displaySignatureImage(signaturePath) {
    var img = document.createElement('img');
    img.src = signaturePath;
    img.style.maxWidth = '40px';
    var signatureContainer = document.getElementById('signature-image-container');
    signatureContainer.innerHTML = '';
    signatureContainer.appendChild(img);
}

document.getElementById('signature-trigger').click();
</script>

<script>
function fetchSignatureURL() {
    fetch('get_signature_url.php', {
        method: 'POST',
        credentials: 'same-origin',
    })
    .then(response => response.text())
    .then(signatureURL => {
        var signatureInput = document.createElement('input');
        signatureInput.type = 'hidden';
        signatureInput.name = 'signature_url';
        signatureInput.value = signatureURL;
        document.querySelector('form').appendChild(signatureInput);
    })
    .catch(error => console.error('Error fetching signature URL:', error));
}

var sessionUsername = "<?php echo $_SESSION['username']; ?>";
var tableUsername = "<?php echo $memo['username']; ?>";

if (sessionUsername === tableUsername) {
    fetchSignatureURL();
}
</script>

</body>
</html>
