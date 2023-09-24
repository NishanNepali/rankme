<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    die('Error: User not logged in.');
}

// Check if the bio parameter is provided
if (!isset($_POST['bio'])) {
    die('Error: Invalid request.');
}

$loggedInUserId = $_SESSION['unique_id'];
$bio = $_POST['bio'];

require_once('config.php');

// Create a connection to the database
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Update the user's bio in the database
$updateBioQuery = "UPDATE users SET bio = '$bio' WHERE unique_id = '$loggedInUserId'";
if (mysqli_query($conn, $updateBioQuery)) {
    echo "Bio saved successfully.";
} else {
    echo "Error saving bio: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
