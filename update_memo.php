<?php
session_start();
include 'include.php';

if (isset($_GET['id'])) {
    $memo_id = $_GET['id'];
    $sql = "SELECT * FROM memos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $memo = $result->fetch_assoc();
    } else {
        echo "Memo not found!";
        exit;
    }
} else {
    echo "ID parameter is missing!";
    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Memo</title>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" type="text/css" href="stylescreatememo.css">
     <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="stylescreatememo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
       <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.tiny.cloud/1/1xpae6vwk3sbnv03ga4b1lznhp5ucril1rznh7lh9cw8ilsi/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        form {
            max-width: 900px;
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
header {
            background-color: #4285f4; /* Google blue background color */
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #fff; /* White text color */
            padding: 20px 0; /* Padding top and bottom */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow effect */
            max-width: 1000px; /* Maximum width */
            margin: 0 auto; /* Center align horizontally */
            font-family: 'Roboto', sans-serif; /* Google's Roboto font */
        }
        header span {
            font-weight: bold;
            color: #fbbc05; /* Google yellow color for bold text */
        }
        #editor {
            height: 300px;
        }
        .ql-font-serif {
            font-family: serif;
        }
        .ql-font-monospace {
            font-family: monospace;
        }
        .ql-font-arial {
            font-family: Arial, sans-serif;
        }
        .ql-font-courier {
            font-family: Courier, monospace;
        }
        .ql-font-georgia {
            font-family: Georgia, serif;
        }
        .ql-font-helvetica {
            font-family: Helvetica, sans-serif;
        }
        .ql-font-lucida {
            font-family: "Lucida Console", monospace;
        }
        .ql-font-tahoma {
            font-family: Tahoma, sans-serif;
        }
        .ql-font-times {
            font-family: "Times New Roman", serif;
        }
        .ql-font-trebuchet {
            font-family: "Trebuchet MS", sans-serif;
        }
        .ql-font-verdana {
            font-family: Verdana, sans-serif;
        }
                .subject-column {
    max-width: auto; /* Set the maximum width you desire */
    white-space: normal; /* Allows the text to wrap */
    word-wrap: break-word; /* Breaks long words if necessary */
    text-align: left; /* Optional: Aligns the text to the left */
    height: 100px;
}
    </style>
</head>
<body>
<header>Welcome <span style="font-weight: bold;">      <?php echo $memo['username']; ?>      !,</span> Edit Your Memo No:-<span><?php echo $memo_id; ?></span></header>
<form action="process_update_memo.php" method="post">
    <input type="hidden" name="id" value="<?php echo $memo_id; ?>">

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
    <input class="subject-column" type="text" name="subject" value="<?php echo $memo['subject']; ?>" required>

    <!-- Content editor -->
    <script src="https://cdn.tiny.cloud/1/1xpae6vwk3sbnv03ga4b1lznhp5ucril1rznh7lh9cw8ilsi/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <label for="content">Content</label>
    
<div id="toolbar">
        <select class="ql-font">
            <option selected></option>
            <option value="serif">Serif</option>
            <option value="monospace">Monospace</option>
            <option value="arial">Arial</option>
            <option value="courier">Courier</option>
            <option value="georgia">Georgia</option>
            <option value="helvetica">Helvetica</option>
            <option value="lucida">Lucida</option>
            <option value="tahoma">Tahoma</option>
            <option value="times">Times New Roman</option>
            <option value="trebuchet">Trebuchet</option>
            <option value="verdana">Verdana</option>
        </select>
        <select class="ql-size">
            <option value="small"></option>
            <option selected></option>
            <option value="large"></option>
            <option value="huge"></option>
        </select>
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-strike"></button>
        <select class="ql-color"></select>
        <select class="ql-background"></select>
        <button class="ql-script" value="sub"></button>
        <button class="ql-script" value="super"></button>
        <button class="ql-header" value="1"></button>
        <button class="ql-header" value="2"></button>
        <button class="ql-blockquote"></button>
        <button class="ql-code-block"></button>
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-indent" value="-1"></button>
        <button class="ql-indent" value="+1"></button>
        <button class="ql-direction" value="rtl"></button>
        <select class="ql-align">
            <option selected></option>
            <option value="center"></option>
            <option value="right"></option>
            <option value="justify"></option>
        </select>
        <button class="ql-link"></button>
        <button class="ql-image"></button>
        <button class="ql-video"></button>
        <button class="ql-formula"></button>
        <button class="ql-clean"></button>
    </div>

    <div id="editor"></div>
    <textarea name="content" id="content" style="display:none;">
        <?php echo isset($memo['content']) ? htmlspecialchars_decode($memo['content']) : ''; ?>
    </textarea>

    <!-- Signature image container -->
    <div id="signature-image-container"></div>

    <input type="submit" value="Update Memo">
</form>

<button id="signature-trigger" style="display: none;">Fetch Signature</button>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
   <script>
        var Font = Quill.import('formats/font');
        Font.whitelist = ['serif', 'monospace', 'arial', 'courier', 'georgia', 'helvetica', 'lucida', 'tahoma', 'times', 'trebuchet', 'verdana'];
        Quill.register(Font, true);
        
        var quill = new Quill('#editor', {
            modules: {
                toolbar: '#toolbar'
            },
            theme: 'snow'
        });

        // Set the initial content of the editor
        var content = document.getElementById('content').value;
        quill.root.innerHTML = content;

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('content').value = quill.root.innerHTML;
        });
    </script>
<script>
document.addEventListener('DOMContentLoaded', function() {
   
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
