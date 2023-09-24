<?php
session_start();
// Connect to the database
require_once('config.php');

// Retrieve email and photo from session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$photo = isset($_SESSION['photo']) ? $_SESSION['photo'] : '';

if (!$email) {
  die("No email address provided.");
}

// Retrieve user's current first name and last name based on email
$query = "SELECT fname, lname FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$current_fname = $row['fname'];
$current_lname = isset($row['lname']) ? $row['lname'] : '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve new first name and last name from form submission
  $new_fname = mysqli_real_escape_string($conn, $_POST['new_fname']);
  $new_lname = mysqli_real_escape_string($conn, $_POST['new_lname']);

  // Update user's first name and last name in the database based on email
  $query = "UPDATE users SET fname = '$new_fname', lname = '$new_lname' WHERE email = '$email'";
  mysqli_query($conn, $query);
  if (mysqli_affected_rows($conn) === 0) {
    $response = [
      'success' => false,
      'message' => 'Failed to update first name and last name.'
    ];
    echo json_encode($response);
    exit;
  }

  // Update current first name and last name variables with new values
  $current_fname = $new_fname;
  $current_lname = $new_lname;

  // Prepare and send the success response
  $response = [
    'success' => true,
    'message' => 'First name and last name updated successfully',
    'fname' => $new_fname,
    'lname' => $new_lname
  ];
  echo json_encode($response);
  exit;
}
?>
