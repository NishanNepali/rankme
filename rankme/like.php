<?php
// Assuming you have established a database connection
session_start();

require_once('config.php');

if (isset($_POST['postId']) && isset($_POST['userId'])) {
    $postId = $_POST['postId'];
    $userId = $_POST['userId'];

    // Check if the user has already liked the post
    $checkLikeSql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ? AND user_id = ?";
    $checkLikeStmt = $conn->prepare($checkLikeSql);
    $checkLikeStmt->bind_param("ii", $postId, $userId);
    $checkLikeStmt->execute();
    $checkLikeResult = $checkLikeStmt->get_result();
    $count = $checkLikeResult->fetch_assoc()['count'];

    if ($count > 0) {
        // User has already liked the post, so unlike it
        $unlikeSql = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
        $unlikeStmt = $conn->prepare($unlikeSql);
        $unlikeStmt->bind_param("ii", $postId, $userId);
        $unlikeStmt->execute();
        $unlikeStmt->close();
    } else {
        // User has not liked the post, so like it
        date_default_timezone_set('America/New_York'); // Replace 'Your_Timezone' with the appropriate timezone identifier
        $commentCreated = date('Y-m-d H:i:s'); // Generate the current timestamp
        $likeSql = "INSERT INTO likes (post_id, user_id, like_receiver_id) VALUES (?, ?, 
        (SELECT user_id FROM posts WHERE post_id = ?))"; // Fetch the like receiver ID from posts table
        $likeStmt = $conn->prepare($likeSql);
        $likeStmt->bind_param("iii", $postId, $userId, $postId); // Use postId for both post_id and like_receiver_id
        $likeStmt->execute();
        $likeStmt->close();

        // Check the user ID of the post owner
        $checkUserSql = "SELECT user_id FROM posts WHERE post_id = ?";
        $checkUserStmt = $conn->prepare($checkUserSql);
        $checkUserStmt->bind_param("i", $postId);
        $checkUserStmt->execute();
        $checkUserResult = $checkUserStmt->get_result();
        $postUserId = $checkUserResult->fetch_assoc()['user_id'];

        if ($userId != $postUserId) {
            // User has liked the post, create a notification for the post owner
            $message = "You received a like.";
            
            // Insert the notification into the 'notifications' table
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())";
            $insertNotificationStmt = $conn->prepare($insertNotificationSql);
            $insertNotificationStmt->bind_param("is", $postUserId, $message);
            $insertNotificationStmt->execute();
            $insertNotificationStmt->close();
        }
    }

    // Fetch the updated like count for the post
    $countLikeSql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?";
    $countLikeStmt = $conn->prepare($countLikeSql);
    $countLikeStmt->bind_param("i", $postId);
    $countLikeStmt->execute();
    $likeCountResult = $countLikeStmt->get_result();
    $likeCount = $likeCountResult->fetch_assoc()['like_count'];

    echo $likeCount; // Return the updated like count as the response

    $countLikeStmt->close();
}
?>
