<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    die(json_encode(['success' => false, 'error' => 'User not logged in.']));
}

// Check if the receiver_id parameter is provided
if (!isset($_POST['receiver_id'])) {
    die(json_encode(['success' => false, 'error' => 'Invalid request.']));
}

$requesterId = $_SESSION['unique_id'];
$receiverId = $_POST['receiver_id'];

require_once('config.php');

// Delete the friend request
$cancelRequestQuery = "DELETE FROM friend_requests WHERE requester_id = '$requesterId' AND receiver_id = '$receiverId'";
if (mysqli_query($conn, $cancelRequestQuery)) {
    // Return a success response
    echo json_encode(['success' => true, 'message' => 'Friend request canceled successfully.']);
} else {
    // Return an error response
    echo json_encode(['success' => false, 'error' => 'Error canceling friend request: ' . mysqli_error($conn)]);
}

// Close database connection
mysqli_close($conn);
?>
