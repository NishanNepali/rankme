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

// Validate and sanitize the input
$requesterId = mysqli_real_escape_string($conn, $requesterId);

// Delete the friend request from the database using prepared statements
$deleteRequestQuery = "DELETE FROM friend_requests WHERE requester_id = ? AND receiver_id = ?";
$stmt = $conn->prepare($deleteRequestQuery);
$stmt->bind_param("ss", $requesterId, $receiverId);

if ($stmt->execute()) {
    // Return a success response
    echo json_encode(['success' => true, 'message' => 'Friend request rejected successfully.']);
} else {
    // Return an error response
    echo json_encode(['success' => false, 'error' => 'Error rejecting friend request: ' . $conn->error]);
}

// Close database connection
$stmt->close();
$conn->close();

?>