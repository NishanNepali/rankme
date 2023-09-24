
<?php
file_put_contents('debug.txt', json_encode($_POST));

// Assuming you have established a database connection
require_once('config.php');

// Get the post_id from the AJAX request
$postId = $_GET['post_id'];

// Fetch the latest comments from the database
$commentSql = "SELECT c.comment_id, c.comment_text, c.comment_created, u.fname, u.lname, u.thumbnail_photo
    FROM comments c
    JOIN users u ON c.user_id = u.unique_id
    WHERE c.post_id = ?
    ORDER BY c.comment_created DESC";

$commentStmt = $conn->prepare($commentSql);
$commentStmt->bind_param("i", $postId);
$commentStmt->execute();
$commentsResult = $commentStmt->get_result();

// Prepare the response as an associative array
$response = array();

// Initialize the comments array
$comments = array();

// Loop through the comments and add them to the comments array
while ($comment = $commentsResult->fetch_assoc()) {
    $comments[] = $comment;
}

// Set the comments array in the response
$response['comments'] = $comments;

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
