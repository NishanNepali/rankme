<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    die(json_encode(['success' => false, 'error' => 'User not logged in.']));
}

// Check if the requester_id parameter is provided
if (!isset($_GET['requester_id'])) {
    die(json_encode(['success' => false, 'error' => 'Invalid request.']));
}

$requesterId = $_GET['requester_id'];
$receiverId = $_SESSION['unique_id'];

require_once('config.php');

// Update the friend request status to "Friends"
$updateRequestQuery = "UPDATE friend_requests SET status = 'Friends' WHERE requester_id = '$requesterId' AND receiver_id = '$receiverId'";
if (mysqli_query($conn, $updateRequestQuery)) {
    // Insert a new record in the friends table for both users
    $insertFriendsQuery = "INSERT INTO friends (user_id1, user_id2) VALUES ('$receiverId', '$requesterId'), ('$requesterId', '$receiverId')";
    if (mysqli_query($conn, $insertFriendsQuery)) {
        // Remove the friend request from the friend_requests table
        $deleteRequestQuery = "DELETE FROM friend_requests WHERE requester_id = '$requesterId' AND receiver_id = '$receiverId'";
        mysqli_query($conn, $deleteRequestQuery);

        echo json_encode(['success' => true, 'message' => 'Friend request accepted successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error accepting friend request: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error accepting friend request: ' . mysqli_error($conn)]);
}

// Close database connection
mysqli_close($conn);
?>