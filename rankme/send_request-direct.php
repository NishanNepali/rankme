<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    die('Error: User not logged in.');
}

// Check if the receiver_id parameter is provided
if (!isset($_GET['receiver_id'])) {
    die('Error: Invalid request.');
}

$requesterId = $_SESSION['unique_id'];
$receiverId = $_GET['receiver_id'];

require_once('config.php');

// Check if the friend request already exists
$existingRequestQuery = "SELECT * FROM friend_requests WHERE requester_id = '$requesterId' AND receiver_id = '$receiverId'";
$result = mysqli_query($conn, $existingRequestQuery);

if (mysqli_num_rows($result) > 0) {
    // Friend request already exists, update the status to "Request Sent"
    $updateRequestQuery = "UPDATE friend_requests SET status = 'Request Sent' WHERE requester_id = '$requesterId' AND receiver_id = '$receiverId'";
    if (mysqli_query($conn, $updateRequestQuery)) {
        echo "Friend request sent successfully.";
    } else {
        echo "Error sending friend request: " . mysqli_error($conn);
    }
} else {
    // Friend request doesn't exist, insert a new request
 // Friend request doesn't exist, insert a new request
$sendRequestQuery = "INSERT INTO friend_requests (requester_id, receiver_id, status) VALUES ('$requesterId', '$receiverId', 'Request Sent')";
if (mysqli_query($conn, $sendRequestQuery)) {
    echo "Friend request sent successfully.";
    header("Location: profile.php?unique_id=$_GET[receiver_id]");
    exit();
} else {
    echo "Error sending friend request: " . mysqli_error($conn);
}

}

// Close database connection
mysqli_close($conn);
?>
