<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  // Redirect to the login page or display an error message
  header("Location: login.php");
  exit;
}

// Get the logged-in user ID
$userId = $_SESSION['unique_id'];

// Define database credentials
require_once('config.php');
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get the links from the form
$facebookLink = $_POST['facebook_link'];
$instagramLink = $_POST['instagram_link'];
$tiktokLink = $_POST['tiktok_link'];

// Update the user's links in the database
$updateQuery = "UPDATE users SET facebook_link = '$facebookLink', instagram_link = '$instagramLink', tiktok_link = '$tiktokLink' WHERE unique_id = '$userId'";
if (mysqli_query($conn, $updateQuery)) {
  echo "Links updated successfully.";

  $url = "ownprofile.php?unique_id=$userId";
  header("Location: $url");
} else {
  echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
