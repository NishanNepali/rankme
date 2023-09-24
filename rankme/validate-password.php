<?php
require_once('config.php');
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted JSON data
    $postData = json_decode(file_get_contents('php://input'), true);

    // Check if the password is provided
    if (isset($postData['password'])) {
        $enteredPassword = $postData['password'];

        // Verify the entered password against the hashed password stored in the session or database
        // Add your password verification logic here

        // For example, if you're storing the hashed password in the session:
        session_start();
        $hashedPassword = $_SESSION['password'];

        if (password_verify($enteredPassword, $hashedPassword)) {
            // Password matches
            $response = [
                'success' => true,
            ];
        } else {
            // Password doesn't match
            $response = [
                'success' => false,
            ];
        }

        // Return the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>