<?php
include 'include.php';

// Check if the username is provided in the request
if(isset($_GET['username'])) {
    // Get the username from the request
    $username = $_GET['username'];

    // Query the database to fetch the signature information for the provided username
    $query = "SELECT signature_path FROM signature WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $signature = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a signature path was found
    if ($signature) {
        // Construct the full path to the signature image
        $signaturePath = $signature['signature_path'];
        $signatureImage = "../C:/xamppp/htdocs/access_form/signature_images/" . $signaturePath; // Adjust the path as per your directory structure

        // Check if the signature image file exists
        if(file_exists($signatureImage)) {
            // Return both signature path and signature image URL
            $response = [
                'success' => true,
                'signature_path' => $signaturePath,
                'signature_image' => $signatureImage
            ];
        } else {
            // Signature image file not found
            $response = [
                'success' => false,
                'message' => 'Signature image not found.'
            ];
        }
    } else {
        // Signature not found
        $response = [
            'success' => false,
            'message' => 'Signature not found for the provided username.'
        ];
    }
} else {
    // Username not provided in the request
    $response = [
        'success' => false,
        'message' => 'Username not provided in the request.'
    ];
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
