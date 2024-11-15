<?php
// Initialize variables to hold upload status and file URL
$uploadMessage = "";
$fileUrl = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] == 0) {
        // Define the allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf']; // Add other types as needed

        // Get file details
        $fileName = $_FILES['uploadedFile']['name'];
        $fileTmpName = $_FILES['uploadedFile']['tmp_name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileError = $_FILES['uploadedFile']['error'];

        // Set the upload directory
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($fileName);

        // Check if the file type is allowed
        if (in_array($fileType, $allowedTypes)) {
            // Check if the file was uploaded successfully
            if (move_uploaded_file($fileTmpName, $uploadFile)) {
                $uploadMessage = "File uploaded successfully.";
                $fileUrl = $uploadFile; // Set the file URL for display
            } else {
                $uploadMessage = "Failed to upload file.";
            }
        } else {
            $uploadMessage = "Invalid file type. Only JPEG, PNG, and PDF files are allowed.";
        }
    } else {
        $uploadMessage = "No file uploaded or there was an upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .upload-wrapper {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }
        .upload-wrapper span.file-name {
            display: block;
            margin-bottom: 10px;
            color: #666;
        }
        .upload-wrapper label {
            background: #3385ff;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
        }
        .upload-wrapper input[type="file"] {
            display: none;
        }
        .upload-wrapper input[type="submit"] {
            background: #3385ff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .upload-wrapper input[type="submit"]:hover {
            background: #0056b3;
        }
        .upload-message {
            margin-top: 10px;
            color: #333;
        }
        .file-url {
            display: block;
            margin-top: 10px;
            color: #0066cc;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="upload-wrapper">
        <form action="" method="post" enctype="multipart/form-data">
            <span class="file-name">Choose a file...</span>
            <label for="file-upload">Browse
                <input type="file" id="file-upload" name="uploadedFile">
            </label>
            <input type="submit" value="Upload">
        </form>
        <div class="upload-message">
            <?php echo $uploadMessage; ?>
        </div>
        <?php if ($fileUrl): ?>
            <div class="file-url">
                File URL: <a href="<?php echo htmlspecialchars($fileUrl); ?>" target="_blank"><?php echo htmlspecialchars($fileUrl); ?></a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
