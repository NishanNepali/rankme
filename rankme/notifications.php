
<?php

session_start();
if (!isset($_SESSION['loggedin']) && !isset($_COOKIE['loggedin'])) {
  header('Location: login.php');
  exit;
}

// Check if the user is logged in via cookie
if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] === 'true') {
  $_SESSION['loggedin'] = true;
}

//$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
$fname = isset($_SESSION["fname"]) ? $_SESSION["fname"] : "";
$lname = isset($_SESSION["lname"]) ? $_SESSION["lname"] : "";
$username = $fname . " " . $lname; // Concatenate the first name and last name

//$photo = isset($_SESSION["img"]) ? $_SESSION["img"] : "";
//$photo = isset($_SESSION["photo"]) ? $_SESSION["photo"] : "";
$thumbnailPhoto = isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "";
//$photo = isset($_SESSION["resized_photo"]) ? $_SESSION["resized_photo"] : "";
$user_id = isset($_SESSION["unique_id"]) ? $_SESSION["unique_id"] : "ID not found";

$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Email not found"; // Use $_SESSION["email"] instead of $row['email']

$_SESSION['test'] = 'Hello World';
//var_dump($_SESSION);


?>









<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification-Rank-Me</title>
  <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
  <link rel="manifest" href="imgs/site.webmanifest">

</head>
<body>

<?php
require_once('header-main.php');
?>





<!-- Display the notifications -->
<h1 class="notifications-h1">Notifications</h1>
<?php

// Start a session and connect to the database
require_once('config.php');

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  throw new Exception('Error: User not logged in.');
}

// Get the user ID
$user_id = $_SESSION['unique_id'];

// Query to select the latest notification for each unique message
$notificationsQuery = "SELECT n1.*, u.unique_id, u.thumbnail_photo, u.fname, u.lname, 'likes' AS type
                      FROM notifications n1
                      JOIN (
                          SELECT MAX(id) AS max_id, message
                          FROM notifications
                          GROUP BY message
                      ) n2 ON n1.id = n2.max_id
                      JOIN likes l ON n1.user_id = l.like_receiver_id 
                      JOIN users u ON l.user_id = u.unique_id 
                      WHERE n1.user_id = '$user_id' 
                      UNION
                      SELECT n1.*, u.unique_id, u.thumbnail_photo, u.fname, u.lname, 'votes' AS type
                      FROM notifications n1
                      JOIN (
                          SELECT MAX(id) AS max_id, message
                          FROM notifications
                          GROUP BY message
                      ) n2 ON n1.id = n2.max_id
                      JOIN votes v ON n1.user_id = v.profile_owner_id
                      JOIN users u ON v.voter_id = u.unique_id 
                      WHERE n1.user_id = '$user_id' 
                      ORDER BY created_at DESC"; // Select the latest notification for each unique message using a subquery

$notificationsResult = mysqli_query($conn, $notificationsQuery);

// Check if there are notifications
if (mysqli_num_rows($notificationsResult) > 0) {
  while ($notificationRow = mysqli_fetch_assoc($notificationsResult)) {
    $notificationMessage = $notificationRow['type'] === 'likes' ? 'You received a like.' : 'You received a vote.';
    $notificationIsRead = $notificationRow['is_read'];
    $notificationUsername = $notificationRow['fname'] . " " . $notificationRow['lname'];
    $notificationPhoto = $notificationRow['thumbnail_photo'];
    $notificationUniqueID = $notificationRow['unique_id'];

    // Mark the notification as read if it is unread
    if (!$notificationIsRead) {
      $notificationId = $notificationRow['id'];
      $markAsReadQuery = "UPDATE notifications SET is_read = 1 WHERE id = '$notificationId'";
      mysqli_query($conn, $markAsReadQuery);
    }

    // Display the notification
    echo '<a href="profile?unique_id=' . $notificationUniqueID . '">';
    echo '<div class="notification">';
    echo '<div class="notification-photo-container">';
    echo '<img src="' . $notificationPhoto . '" alt="Profile Photo" class="notification-photo">';
    echo '</div>';
    echo '<div class="notification-content">';
    echo '<p class="notification-message"><strong class="notification-username">' . $notificationUsername . '</strong> ' . $notificationMessage . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</a>';
  }
} else {
  echo'<div class="no-notifications-div">';
  echo '<span class="no-notifications">No notifications.</span>';
  echo'</div>';
}

// Close the database connection
mysqli_close($conn);


?>




<style>
   .notifications-h1 {
        font-size: 30px;
        color: #333;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-align: center;
    }

    .no-notifications-div{
      display: flex;
      justify-content: center;
      align-items: center;
      height: 60vh;
    }
    .no-notifications {
        font-size: 24px;
        color: #999;
        padding: 10px;
        border: 2px dashed #ccc;
        border-radius: 5px;
        display: inline-block;
    ]

    }
 .notification {
  display: flex;
  align-items: center;
  background-color: #838283;
  color: white;
  font-weight: bolder;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 12px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.notification:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.notification-photo-container {
  position: relative;
  margin-right: 12px;
}

.notification-photo {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
}

.notification-content {
  flex: 1;
}

.notification-message {
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 4px;
  color:#ffffff;
}

.notification-details {
  font-size: 14px;
  color:  #ffa600;
}


.notification-username {
  color: #000000;
  margin-right: 4px;
}

/* Notification badge */
.notification:before {
  content: '';
  position: absolute;
  top: 50%;
  right: 10px;
  width: 8px;
  height: 8px;
  background-color: #3b5998;
  border-radius: 50%;
  transform: translateY(-50%);
}

/* Animation styles */
@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(-10px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.notification.animation {
  animation-duration: 0.3s;
  animation-fill-mode: both;
}

.notification.animation.fadeIn {
  animation-name: fadeIn;
}


</style>






<!--

<?php
// Check if there are notifications
if (mysqli_num_rows($notificationsResult) > 0) {
  while ($notificationRow = mysqli_fetch_assoc($notificationsResult)) {
    $notificationMessage = $notificationRow['message'];
    $notificationIsRead = $notificationRow['is_read'];

    // Mark the notification as read if it is unread
    if (!$notificationIsRead) {
      $notificationId = $notificationRow['id'];
      $markAsReadQuery = "UPDATE notifications SET is_read = 1 WHERE id = '$notificationId'";
      mysqli_query($conn, $markAsReadQuery);
    }

    // Display the notification
    echo '<p>' . $notificationMessage . '</p>';
  }
} else {
  echo '<span class ="no-notifications">No notifications.</span>';
}

// Close the database connection
mysqli_close($conn);
?>

-->

</body>
</html>

