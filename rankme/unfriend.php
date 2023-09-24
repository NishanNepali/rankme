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

// Unfriend the users
$unfriendQuery = "DELETE FROM friends WHERE (user_id1 = '$requesterId' AND user_id2 = '$receiverId') OR (user_id1 = '$receiverId' AND user_id2 = '$requesterId')";
if (mysqli_query($conn, $unfriendQuery)) {
    // Return a success response
    echo json_encode(['success' => true, 'message' => 'User unfriended successfully.']);
} else {
    // Return an error response
    echo json_encode(['success' => false, 'error' => 'Error unfriending user: ' . mysqli_error($conn)]);
}

// Close database connection
mysqli_close($conn);
?>
