<?php
// Assuming you have established a database connection
require_once('config.php');

// Get the post_id from the AJAX request
$postId = $_POST['post_id'];

// Fetch the updated like count from the database
$sqlLikeCount = "SELECT COUNT(*) AS likeCount FROM likes WHERE post_id = ?";
$stmtLikeCount = $conn->prepare($sqlLikeCount);
$stmtLikeCount->bind_param("i", $postId);
$stmtLikeCount->execute();
$resultLikeCount = $stmtLikeCount->get_result();
$rowLikeCount = $resultLikeCount->fetch_assoc();
$likeCount = $rowLikeCount['likeCount'];

// Fetch the updated comment count from the database
$sqlCommentCount = "SELECT COUNT(*) AS commentCount FROM comments WHERE post_id = ?";
$stmtCommentCount = $conn->prepare($sqlCommentCount);
$stmtCommentCount->bind_param("i", $postId);
$stmtCommentCount->execute();
$resultCommentCount = $stmtCommentCount->get_result();
$rowCommentCount = $resultCommentCount->fetch_assoc();
$commentCount = $rowCommentCount['commentCount'];

// Fetch the latest comment from the database
$commentSql = "SELECT c.comment_id, c.comment_text, c.comment_created, u.fname, u.lname, u.thumbnail_photo
    FROM comments c
    JOIN users u ON c.user_id = u.unique_id
    WHERE c.post_id = ?
    ORDER BY c.comment_created DESC
    LIMIT 1";

$commentStmt = $conn->prepare($commentSql);
$commentStmt->bind_param("i", $postId);
$commentStmt->execute();
$commentsResult = $commentStmt->get_result();

// Prepare the response as an associative array
$response = array(
    'likeCount' => $likeCount,
    'commentCount' => $commentCount,
    'latestComment' => null // Initialize the latestComment value as null
);

// Check if there is a latest comment
if ($comment = $commentsResult->fetch_assoc()) {
    $commentId = $comment['comment_id'];
    $commentText = $comment['comment_text'];
    $commentCreated = $comment['comment_created'];
    $commentUser = $comment['fname'] . " " . $comment['lname'];
    $commentUserThumbnail = $comment['thumbnail_photo'];

    // Set the latestComment value in the response
    $response['latestComment'] = array(
        'commentId' => $commentId,
        'commentText' => $commentText,
        'commentCreated' => $commentCreated,
        'commentUser' => $commentUser,
        'commentUserThumbnail' => $commentUserThumbnail
    );
}

// Send the response as JSON
echo json_encode($response);
exit(); // Add this line to stop further script execution
?>
