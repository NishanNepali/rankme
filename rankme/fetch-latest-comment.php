<?php
// Assuming you have established a database connection
require_once('config.php');
if (isset($_POST['postId'])) {
    $postId = $_POST['postId'];

    // Fetch the latest comment for the given post ID
    $commentSql = "SELECT c.comment_id, c.comment_text, c.comment_created, u.fname, u.lname, u.thumbnail_photo
    FROM comments c
    JOIN users u ON c.user_id = u.unique_id
    WHERE c.post_id = ?
    ORDER BY c.comment_created DESC
    LIMIT 1";

    $commentStmt = $conn->prepare($commentSql);
    $commentStmt->bind_param("i", $postId);
    $commentStmt->execute();
    $commentResult = $commentStmt->get_result();

    // Check if there is a latest comment
    if ($comment = $commentResult->fetch_assoc()) {
        $commentId = $comment['comment_id'];
        $commentText = $comment['comment_text'];
        $commentCreated = $comment['comment_created'];
        $commentUser = $comment['fname'] . " " . $comment['lname'];
        $commentUserThumbnail = $comment['thumbnail_photo'];

        // Build the HTML representation of the latest comment
        $html = "<div class='latest-comment'>";
        $html .= "<h3>Comments</h3>";
        $html .= "<div class='comment-user-avatar'>";
        $html .= "<img src='$commentUserThumbnail' alt='$commentUser Avatar' class='avatar' loading='lazy'>";
        $html .= "<span class='comment-username'>$commentUser</span>";
        $html .= "</div>";
        $html .= "<p class='comment-text'>$commentText</p>";
        $html .= "<p class='comment-created'>$commentCreated</p>";
        $html .= "</div>";

        echo $html;
    } else {
        // Return an empty response if there are no comments
        echo "";
    }

    $commentStmt->close();
}
?>
