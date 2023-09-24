<?php

// Start a session and connect to the database
require_once('config.php');

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  die('Error: User not logged in.');
}

// Get the user ID
$user_id = $_SESSION['unique_id'];

// Include the function to fetch the count of new notifications for a user
require_once('fetch_notification_count.php');

// Mark notifications as read for the user
$markAsReadQuery = "UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'";
if (mysqli_query($conn, $markAsReadQuery)) {
  // Fetch the new notification count
  $newNotificationCount = getNotificationCount($conn, $user_id);
  
  // Return the updated notification count
  echo $newNotificationCount;
} else {
  echo "Error marking notifications as read: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
