<?php
// Assuming you have established a database connection
require_once('config.php');
if (isset($_POST['postId']) && isset($_POST['userId']) && isset($_POST['commentText'])) {
    $postId = $_POST['postId'];
    $userId = $_POST['userId'];
    $commentText = $_POST['commentText'];

   date_default_timezone_set('America/New_York'); // Replace 'Your_Timezone' with the desired timezone identifier, such as 'UTC' or 'America/New_York'
$commentCreated = date('Y-m-d H:i:s'); // Generate the current timestamp

// Insert the comment into the database along with the commentCreated timestamp
$insertCommentSql = "INSERT INTO comments (post_id, user_id, comment_text, comment_created) VALUES (?, ?, ?, ?)";
$insertCommentStmt = $conn->prepare($insertCommentSql);
$insertCommentStmt->bind_param("iiss", $postId, $userId, $commentText, $commentCreated);
$insertCommentStmt->execute();
$insertCommentStmt->close();


    // Fetch the latest comment for the post
    $latestCommentSql = "SELECT c.comment_id, c.comment_text, c.comment_created, u.fname, u.lname, u.thumbnail_photo
    FROM comments c
    JOIN users u ON c.user_id = u.unique_id
    WHERE c.comment_id = LAST_INSERT_ID()";

    $latestCommentStmt = $conn->prepare($latestCommentSql);
    $latestCommentStmt->execute();
    $latestCommentResult = $latestCommentStmt->get_result();

    // Build the HTML representation of the latest comment
    if ($comment = $latestCommentResult->fetch_assoc()) {
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
        // Return an empty response if there was an error fetching the latest comment
        echo "";
    }

    $latestCommentStmt->close();
}
?>
