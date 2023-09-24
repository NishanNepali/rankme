<?php

session_start();
require_once('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['loggedin']) && !isset($_COOKIE['loggedin'])) {
  header('Location: login.php');
  exit;
}

// Check if the user is logged in via cookie
if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] === 'true') {
  $_SESSION['loggedin'] = true;
}

$fname = isset($_SESSION["fname"]) ? $_SESSION["fname"] : "";
$lname = isset($_SESSION["lname"]) ? $_SESSION["lname"] : "";
$username = $fname . " " . $lname; // Concatenate the first name and last name

//$photo = isset($_SESSION["img"]) ? $_SESSION["img"] : "";
//$photo = isset($_SESSION["resized_photo"]) ? $_SESSION["resized_photo"] : "";
//$photo = isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "";
//$photo = isset($_SESSION["photo"]) ? $_SESSION["photo"] : "";

$thumbnailPhoto =isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "";


$user_id = isset($_SESSION["unique_id"]) ? $_SESSION["unique_id"] : "ID not found";

$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Email not found"; // Use $_SESSION["email"] instead of $row['email']

$_SESSION['test'] = 'Hello World';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends Rank-Me</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
<link rel="manifest" href="imgs/site.webmanifest">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>




</head>
<body>


<?php

require_once('header-main.php');
?>

<style>
  
.create-post-form {
  display: flex;
  justify-content: center;
  margin-bottom: 10px;
}

.create-post {
  width: 600px;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  padding: 16px;
  margin-top: 20px;
}

.user-avatar img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.post-content {
  width: 100%;
  resize: none;
  border: none;
  outline: none;
  font-size: 16px;
  padding: 8px 0;
}

.post-options {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.option {
  display: flex;
  align-items: center;
  color: #4c66a4;
  margin-right: 20px;
  cursor: pointer;
}

.option i {
  margin-right: 5px;
}

.post-button {
  margin-top: 10px;
  background-color: #4c66a4;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}

/* ...existing CSS code... */

/* ...existing CSS code... */

.popup {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
}

.popup-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #fff;
  width: 400px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
@media(max-width:450px){
  .popup-content{
    width: 95%;
  }
}
.popup-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #e6e6e6;
}

.popup-header h3 {
  margin: 0;
}

.popup-close-btn {
  background-color: transparent;
  border: none;
  font-size: 24px;
  color: #888;
  cursor: pointer;
}

.popup-body {
  padding: 16px;
}

#caption-input {
  width: 100%;
  resize: none;
  border: none;
  outline: none;
  font-size: 16px;
  padding: 8px 0;
  margin-bottom: 10px;
}

.photo-upload-icon {
  display: flex;
  align-items: center;
  color: #4c66a4;
  cursor: pointer;
  margin-bottom: 10px;
}

.photo-upload-icon i {
  margin-right: 5px;
}

.photo-upload-input {
  display: none;
}

.popup-footer {
  padding: 8px 16px;
  text-align: right;
  border-top: 1px solid #e6e6e6;
}

.preview-section {
  display: none;
  margin-top: 10px;
  justify-content: center;
border: 2px solid black;
}

.preview-image {

  max-width: 200px;
  max-height: 200px;
}

/* ...existing CSS code... */

/* ...existing CSS code... */

.creation-section form{
  margin: 0 auto;
            display: flex;
            
            justify-content: center;
            flex-direction: row;
            background-color: black;
            border-radius: 4px;
            padding: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
          
            width: auto;
}


</style>

<div class="create-post-form">
  <div class="create-post">
    <div class="user-avatar">
      <!-- Place your user avatar here -->
      <img src="<?php echo $thumbnailPhoto ?>" alt="User Avatar">
      <span><?php echo $username ?></span>
    </div>
    <textarea id="caption-input" name="postContent" class="post-content" placeholder="What's on your mind?"></textarea>
    <div class="post-options">
      <label for="photo-upload" class="option photo-upload-icon">
        <i class="fas fa-camera"></i> Photo/Video
    
      </label>
      <label class="option">
        <i class="fas fa-smile"></i> Feeling/Activity
      </label>
    </div>
    
  </div>

  </div>

<!-- ...existing HTML code... -->

<div id="popup" class="popup">
  <div class="popup-content">
    <div class="popup-header">
      <h3>Create Post</h3>
      <button id="popup-close-btn" class="popup-close-btn">&times;</button>
    </div>
    <div class="popup-body">
      <form action="post-uploader.php" method="POST" enctype="multipart/form-data">
        <textarea id="caption-input" name="caption" placeholder="Write a caption..."></textarea>
        <div id="preview-section" class="preview-section" style="display: none;">
          <img id="preview-image" class="preview-image" src="#" alt="Preview" />
        </div>

        <label for="popup-photo-upload" class="option photo-upload-icon">
          <i class="fas fa-camera"></i> Photo/Video
        </label>
        <input type="file" id="popup-photo-upload" accept="image/*" name="photo" class="photo-upload-input" />

        <!-- Visibility Selection -->
        <div class="visibility-dropdown">
  <label for="visibility">Visibility:</label>
  <select name="visibility" id="visibility">
    <option value="friends">Friends</option>
    <option value="public">Public</option>
  </select>
</div>

<style>
  /* Container for the visibility dropdown */
.visibility-dropdown {
  display: inline-block;
  position: relative;
  font-size: 16px;
}

/* Hide the default dropdown arrow */
.visibility-dropdown select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: transparent;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  color: #333;
  border: 1px solid #ccc;
  cursor: pointer;
  outline: none;
  transition: border-color 0.3s ease;
}

/* Add a custom arrow icon using pseudo-elements */
.visibility-dropdown select::after {
  content: "\f078"; /* Unicode for the down-arrow icon (Font Awesome) */
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
  position: absolute;
  top: 50%;
  right: 12px;
  transform: translateY(-50%);
  pointer-events: none;
}

/* Style the options inside the dropdown */
.visibility-dropdown select option {
  background-color: #f9f9f9;
  color: #333;
}

/* Hover effect */
.visibility-dropdown select:hover {
  border-color: #007bff; /* Change the color as needed */
}

/* Focus effect */
.visibility-dropdown select:focus {
  border-color: #007bff; /* Change the color as needed */
  box-shadow: 0 0 8px rgba(0, 123, 255, 0.4); /* Change the color as needed */
}

/* Styling for when the dropdown is opened */
.visibility-dropdown select:active,
.visibility-dropdown select:focus {
  background-color: #fff;
  border-color: #007bff; /* Change the color as needed */
  box-shadow: 0 0 8px rgba(0, 123, 255, 0.4); /* Change the color as needed */
}

</style>
        <!-- Add more options or content as needed -->

        <div class="popup-footer">
          <button id="popup-post-button" class="post-button">Post</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // JavaScript to handle the popup functionality
  const captionInput = document.querySelector('#caption-input');
  const photoUploadIcon = document.querySelector('.photo-upload-icon');
  const photoUploadInput = document.querySelector('.photo-upload-input');
  const popup = document.getElementById('popup');
  const popupCloseBtn = document.getElementById('popup-close-btn');
  const previewSection = document.getElementById('preview-section');
  const previewImage = document.getElementById('preview-image');

  captionInput.addEventListener('focus', openPopup);
  photoUploadIcon.addEventListener('click', openPopup);
  photoUploadInput.addEventListener('change', previewPhoto);
  popupCloseBtn.addEventListener('click', closePopup);

  function openPopup() {
    popup.style.display = 'block';
  }

  function closePopup() {
    popup.style.display = 'none';
  }

  function previewPhoto() {
    const file = photoUploadInput.files[0];
    const reader = new FileReader();

    reader.addEventListener('load', function () {
      previewImage.src = reader.result;
    });

    if (file) {
      reader.readAsDataURL(file);
      previewSection.style.display = 'flex';
    } else {
      previewImage.src = '';
      previewSection.style.display = 'none';
    }
  }
</script>


<?php

require_once('functions.php');
$userId = getUserId();

// Update the SQL query to fetch posts based on visibility
$sql = "SELECT p.post_id, p.user_id, p.caption, p.photo_filename, DATE_FORMAT(p.post_created, '%Y-%m-%d %H:%i:%s') AS formatted_post_created, u.fname, u.lname, u.thumbnail_photo
        FROM posts p
        JOIN users u ON p.user_id = u.unique_id
        WHERE (p.user_id IN (
            SELECT f.user_id1
            FROM friends f
            WHERE f.user_id2 = ? AND p.visibility = 1
            UNION
            SELECT f.user_id2
            FROM friends f
            WHERE f.user_id1 = ? AND p.visibility = 1
        ))
        OR (p.visibility = 2)
        OR (p.user_id = ?)
        ORDER BY p.post_created DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $userId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
// Display the fetched posts
while ($row = $result->fetch_assoc()) {
    $postId = $row["post_id"];
    $caption = $row["caption"];
    $photoFilename = $row["photo_filename"];
    $username = $row["fname"] . " " . $row["lname"];
    $posterthumbnailPhoto = $row["thumbnail_photo"];
   // $created_at = $row['formatted_post_created'];
    //$time_ago = getPostTimeAgo($created_at);
    
    $poster_link = $row["user_id"];

    $created_at = $row['formatted_post_created']; // Replace with your post creation time variable

    // Get the user's time zone
    $userTimeZone = new DateTimeZone('America/New_York'); // Replace with the user's time zone
$postTime = DateTime::createFromFormat('Y-m-d H:i:s', $created_at, $userTimeZone);
$currentTime = new DateTime('now', $userTimeZone);
$timeDiff = $currentTime->getTimestamp() - $postTime->getTimestamp();
// ...

    // Calculate the time difference
  //  $timeDiff = $currentTime->getTimestamp() - $postTime->getTimestamp();
    
    // Define the time intervals in seconds
    $minute = 60;
    $hour = 60 * $minute;
    $day = 24 * $hour;
    $week = 7 * $day;
    $month = 30 * $day;
    
    // Determine the appropriate time interval
    if ($timeDiff < $minute) {
        $timeAgo = floor($timeDiff) . ' sec ago';
    } elseif ($timeDiff < $hour) {
        $timeAgo = floor($timeDiff / $minute) . ' min ago';
    } elseif ($timeDiff < $day) {
        $timeAgo = floor($timeDiff / $hour) . ' hrs ago';
    } elseif ($timeDiff < $week) {
        $timeAgo = floor($timeDiff / $day) . ' days ago';
    } elseif ($timeDiff < $month) {
        $timeAgo = floor($timeDiff / $week) . ' weeks ago';
    } else {
        $timeAgo = floor($timeDiff / $month) . ' months ago';
    }
    
    //echo $timeAgo;
    




  



    echo "<div class='post' id='post-$postId'>";
    echo "<div class='poster-user-avatar'>";
    echo "<a href='profile?unique_id=$poster_link'>";
    echo "<img src='$posterthumbnailPhoto' alt='User Avatar' class='avatar' loading='lazy'>";
    echo "<div>";
    echo "<span class='username'>$username</span>";
    echo "<p class='created-at'><sup>$timeAgo</sup></p>";
    echo "</a>";
   
    echo "</div>";
    echo "</div>";
    echo "<p class='caption'>$caption</p>";
    echo"<div class='uploaded-post-div'>";
    //echo "<img class='uploaded-post' src='posts/$photoFilename' alt='Post Image' onclick='showPostModal($postId)'>";
    echo "<img class='uploaded-post' src='posts/$photoFilename' loading='lazy' alt='Post Image' onclick='openPostModal($postId)'>";
    echo"</div>";

    // Fetch the total number of comments for the post
    $countCommentSql = "SELECT COUNT(*) as comment_count FROM comments WHERE post_id = ?";
    $countCommentStmt = $conn->prepare($countCommentSql);
    $countCommentStmt->bind_param("i", $postId);
    $countCommentStmt->execute();
    $commentCountResult = $countCommentStmt->get_result();
    $commentCount = $commentCountResult->fetch_assoc()['comment_count'];

    $liked = false;

    // Fetch the total number of likes for the post
    $countLikeSql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?";
    $countLikeStmt = $conn->prepare($countLikeSql);
    $countLikeStmt->bind_param("i", $postId);
    $countLikeStmt->execute();
    $likeCountResult = $countLikeStmt->get_result();
    $likeCount = $likeCountResult->fetch_assoc()['like_count'];
    
    // Check if the user has liked the post
    $userLikeSql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ? AND user_id = ?";
    $userLikeStmt = $conn->prepare($userLikeSql);
    $userLikeStmt->bind_param("ii", $postId, $userId);
    $userLikeStmt->execute();
    $userLikeResult = $userLikeStmt->get_result();
    $userLikeCount = $userLikeResult->fetch_assoc()['count'];
    
    if ($userLikeCount > 0) {
      $liked = true;
    }
    
    // Display the like and comment buttons with the respective counts
    echo "<div class='post-actions'>";
    if ($liked) {
      echo "<a href='#' class='like-button liked' id='like-button-$postId' data-post-id='$postId' data-user-id='$user_id'>";
      echo "<span class='heart-icon liked'><i class='fas fa-heart'></i></span> <span class='like-count'>$likeCount</span></a>";
    } else {
      echo "<a href='#' class='like-button' id='like-button-$postId' data-post-id='$postId' data-user-id='$user_id'>";
      echo "<span class='heart-icon'><i class='fas fa-heart'></i></span> <span class='like-count'>$likeCount</span></a>";
    }
     // echo "<button class='comment-button' data-post-id='$postId' data-user-id='$user_id'>$commentCount Comments</button>";
      echo "<button class='comment-button' data-post-id='$postId' data-user-id='$user_id'>$commentCount Comments</button>";
      echo "</div>";
      
      echo "<form class='comment-form' id='comment-input-$postId' style='display: none;' action='comment.php' method='POST'>";
      echo "<input type='hidden' name='user_id' value='$user_id'>";
      echo "<input type='hidden' name='post_id' value='$postId'>";
      echo "<input type='text' name='comment_text' placeholder='Write a comment'>";
      echo "<button type='submit'>Submit</button>";
      echo "</form>";
      // JavaScript to toggle the comment form
echo "<script>
$(document).ready(function() {
    $('.comment-button[data-post-id=$postId]').click(function(e) {
        e.preventDefault();
        $('#comment-input-$postId').toggle();
    });
});
</script>";
      
// ... Previous code ...

// Display the latest comment
echo "<div id='latest-comment-$postId'></div>";

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

// Display the latest comment
if ($comment = $commentsResult->fetch_assoc()) {
    $commentId = $comment['comment_id'];
    $commentText = $comment['comment_text'];
   // $commentCreated = $comment['comment_created'];
    $commentUser = $comment['fname'] . " " . $comment['lname'];
    $commentUserThumbnail = $comment['thumbnail_photo'];
    $commentCreated = $comment['comment_created'];
    // Get the user's time zone
    $userTimeZone = new DateTimeZone('America/New_York'); // Replace with the user's time zone
    
    // Create DateTime objects for the comment time and current time
    $commentTime = new DateTime($commentCreated, $userTimeZone);
    $currentTime = new DateTime('now', $userTimeZone);
    
    // Calculate the time difference
    $timeDiff = $currentTime->getTimestamp() - $commentTime->getTimestamp();
    
    // Define the time intervals in seconds
    $minute = 60;
    $hour = 60 * $minute;
    $day = 24 * $hour;
    $week = 7 * $day;
    $month = 30 * $day;
    
    // Determine the appropriate time interval
    if ($timeDiff < $minute) {
        $timeAgo = floor($timeDiff) . ' sec ago';
    } elseif ($timeDiff < $hour) {
        $timeAgo = floor($timeDiff / $minute) . ' min ago';
    } elseif ($timeDiff < $day) {
        $timeAgo = floor($timeDiff / $hour) . ' hrs ago';
    } elseif ($timeDiff < $week) {
        $timeAgo = floor($timeDiff / $day) . ' days ago';
    } elseif ($timeDiff < $month) {
        $timeAgo = floor($timeDiff / $week) . ' weeks ago';
    } else {
        $timeAgo = floor($timeDiff / $month) . ' months ago';
    }
    



    echo "<div id='comments-section' onclick='openPostModal($postId)'>";
    echo "<div  class='latest-comment'>";
    echo "<h3> Latest Comments</h3>";
    echo "<div class='comment-user-avatar'>";
    echo "<img src='$commentUserThumbnail' alt='$commentUser Avatar' class='avatar' loading='lazy'>";
    echo "<span class='comment-username'>$commentUser</span>";
    echo "<p class='comment-created'>$timeAgo</p>";
    echo "</div>";
    echo "<p class='comment-text'>$commentText</p>";
   
    echo "</div>";
    echo "</div>";

    // JavaScript to update the latest comment in real-time
    // JavaScript to update the latest comment in real-time
echo "<script>
$(document).ready(function() {
    function updateLatestComment(postId) {
        $.ajax({
            url: 'fetch-latest-comment.php', // Replace with the actual PHP script to fetch the latest comment
            type: 'POST',
            data: { postId: postId },
            success: function(response) {
                $('#comments-section-' + postId).html(response);
            }
        });
    }

    // Call the function at regular intervals
    setInterval(function() {
        updateLatestComment($postId);
    }, 5000); // Update every 5 seconds (adjust the interval as needed)
});
</script>";

}



    $commentStmt->close();
    echo "</div>";

   

    // Post modal
echo "<div id='post-modal-$postId' class='post-modal'>";

echo "<div class='post-modal-content'>";
echo "<div class='post-modal-header'>";

echo "<h2>Post by $username</h2>";
echo "<button class='cancel-button' onclick='closePostModal($postId)'>&times;</button>";



echo "</div>";
echo "<div class='post-modal-body'>";
echo "<img src='posts/$photoFilename' alt='Post Image' loading='lazy'>";
echo "<p class='caption'>$caption</p>";
echo "</div>";
echo "<div class='post-modal-actions'>";
//echo "<button class='like-button'>Like</button>";
if ($liked) {
  echo "<a href='#' class='like-button liked' id='like-button-$postId' data-post-id='$postId' data-user-id='$user_id'>";
  echo "<span class='heart-icon liked'>&#x2665;</span> <span class='like-count'>$likeCount</span></a>";
} else {
  echo "<a href='#' class='like-button' id='like-button-$postId' data-post-id='$postId' data-user-id='$user_id'>";
  echo "<span class='heart-icon liked'><i class='fas fa-heart'></i></span></i></span> <span class='like-count'>$likeCount</span></a>";
}

//echo "<button class='comment-button' data-post-id='$postId'>Comment</button>";
echo "<button class='comment-button' data-post-id='$postId'>$commentCount Comments</button>";
echo "</div>";
echo "<div class='post-modal-comments'>";
echo "<h3>Comments</h3>";
// Fetch and display the comments for the post
$commentSql = "SELECT c.comment_id, c.user_id, c.comment_text, c.comment_created, u.fname, u.lname, u.thumbnail_photo
               FROM comments c
               JOIN users u ON c.user_id = u.unique_id
               WHERE c.post_id = ?
               ORDER BY c.comment_created DESC";
$commentStmt = $conn->prepare($commentSql);
$commentStmt->bind_param("i", $postId);
$commentStmt->execute();
$commentsResult = $commentStmt->get_result();

while ($comment = $commentsResult->fetch_assoc()) {
  $commentText = $comment['comment_text'];
  $commenter_link = $comment['user_id'];
  $commentCreated = $comment['comment_created'];
  $commentUser = $comment['fname'] . " " . $comment['lname'];
  $commentUserThumbnail = $comment['thumbnail_photo'];

  
  // Calculate the time ago for each comment
  $commentTime = new DateTime($commentCreated, $userTimeZone);
  $currentTime = new DateTime('now', $userTimeZone);
  $timeDiff = $currentTime->getTimestamp() - $commentTime->getTimestamp();

  if ($timeDiff < $minute) {
      $timeAgo = floor($timeDiff) . ' sec ago';
  } elseif ($timeDiff < $hour) {
      $timeAgo = floor($timeDiff / $minute) . ' min ago';
  } elseif ($timeDiff < $day) {
      $timeAgo = floor($timeDiff / $hour) . ' hrs ago';
  } elseif ($timeDiff < $week) {
      $timeAgo = floor($timeDiff / $day) . ' days ago';
  } elseif ($timeDiff < $month) {
      $timeAgo = floor($timeDiff / $week) . ' weeks ago';
  } else {
      $timeAgo = floor($timeDiff / $month) . ' months ago';
  }

  echo "<div class='comment'>";
    echo"<a href='profile?unique_id=$commenter_link'>";

  echo "<div class='poster-user-avatar'>";
 
  echo "<img class='avatar' src='$commentUserThumbnail' alt='User Avatar' loading='lazy'>";
  
  echo "<span class='comment-username'>$commentUser</span>";
  echo "<p class='comment-created'><sup>$timeAgo</sup></p>";
  echo"</a>";
  echo "</div>";
  echo "<p class='comment-text'>$commentText</p>";

  echo "</div>";
}


$commentStmt->close();
echo "</div>";
echo "</div>";
echo "</div>";

}





$stmt->close();
?>












<script>
// JavaScript function to open the post modal
function openPostModal(postId) {
  // Show the post modal
  var modal = document.getElementById("post-modal-" + postId);
  modal.classList.add("show-modal");

  // Prevent scrolling of the background posts
  document.body.style.overflow = "hidden";
}

// JavaScript function to close the post modal
function closePostModal(postId) {
  // Hide the post modal
  var modal = document.getElementById("post-modal-" + postId);
  modal.classList.remove("show-modal");

  // Enable scrolling of the background posts
  document.body.style.overflow = "auto";
}

// JavaScript function to close all post modals
function closeAllPostModals() {
  // Get all post modals
  var modals = document.getElementsByClassName("post-modal");

  // Hide each post modal
  for (var i = 0; i < modals.length; i++) {
    modals[i].classList.remove("show-modal");
  }

  // Enable scrolling of the background posts
  document.body.style.overflow = "auto";
}

// Check if there is a saved opened post modal in the URL query parameter
var urlParams = new URLSearchParams(window.location.search);
var openedPostModal = urlParams.get("post");
if (openedPostModal) {
  // If there is, open the post modal on page load
  openPostModal(openedPostModal);
} else {
  // If there is no saved opened post modal, close all post modals
  closeAllPostModals();
}


</script>

<!-- Add this JavaScript code in your HTML file or in a separate .js file -->







<style>
.like-button {
  display: inline-block;
  margin-right: 10px;

  color: #999;
}

.like-button .heart-icon {
  font-size: 40px;
  transition: color 0.3s ease-in-out;
}

.like-button.liked .heart-icon {
  color: red;
}

 .post-modal {
  position: fixed;
  z-index: 999;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);

  justify-content: center;
  align-items: center;
}
.post-modal {
  /* ... Existing CSS styles ... */
  display: none; /* Hide the post modal by default */
}

.post-modal.show-modal {
  display: flex; /* Show the post modal when the show-modal class is added */
}

.post-modal-content {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  max-width: 90%; /* Adjust the max-width value as needed */
  width: 60%; /* Ensure the element takes full width */
  background-color: #3b74c9;
  padding: 20px;
  border-radius: 4px;
  overflow: auto;
  max-height: 90%;
}

/* Add media queries for smaller screens */
@media (max-width: 600px) {
  .post-modal-content {
    width: 90%;
    max-width: 90%; /* Adjust the max-width value as needed */
    padding: 10px; /* Adjust the padding value as needed */
  }
}



.post-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.post-modal-header h2 {
  font-size: 24px;
  margin: 0;
}

.cancel-button {
  background: none;
  border: none;
  font-size: 36px;
  background-color: rgb(255, 6, 6);

  color: black;

  cursor: pointer;


}

.post-modal-body {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.post-modal-body img {
  max-width: 80%;
  max-height: 90%;
  object-fit: contain;
  margin-bottom: 10px;

  box-shadow: 10px 10px 5px 2px #000000;


}


.post-modal-actions {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.post-modal-comments h3 {
  font-size: 18px;
  margin: 0 0 10px;
}

.comment-user-avatar img {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  margin-right: 10px;
}

.comment-username {
  font-weight: bold;
}

.comment-text {
  margin: 0 0 5px;
}

.comment-created {
  font-size: 12px;
  color: #999;
  margin: 0;
}












.post {
  background-color: #2c2525;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.poster-user-avatar {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.poster-user-avatar a {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #333;
}

.poster-user-avatar .avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.username {
  font-weight: bold;
  margin-right: 5px;
  color: white;
}

.created-at {
  font-size: 12px;
  color: #999;
  margin: 0;
}

.caption {
  margin-bottom: 10px;
  color: white;
}

.uploaded-post-div {
  text-align: center;
  margin-bottom: 10px;
  max-width: 600px; /* Set a maximum width for the post container */
  margin-left: auto; /* Center the container horizontally */
  margin-right: auto;
}

.uploaded-post {
  max-width: 75%;
  height: auto;
  cursor: pointer;
  display: inline-block; /* Display the image as an inline-block element */
}


.post-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px;
}

.like-button {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #3b5998;
  font-size: 14px;
  font-weight: bold;

  cursor: pointer;
}

.like-button span {
  margin-right: 5px;
}
.comment-button {
  color: #ffffff;
  background-color: #000000;
  border: 1px solid #ffffff;
  border-radius: 5px;
  padding: 5px 10px;
  font-size: 16px;
  cursor: pointer;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

  &:hover {
    background-color: #ffffff;
    color: #000000;
    border-color: #ffffff;
  }
}

.latest-comment {
  background-color: #007d86;
  border: 1px solid #000000;
  border-radius: 5px;
  padding: 10px 20px;
  margin-bottom: 10px;
}

.latest-comment h3 {
  text-align: center;
  margin-top: 0;
}

.latest-comment .comment-user-avatar {
  display: flex;
  align-items: center;
}

.latest-comment .avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.latest-comment .comment-username {
  margin-left: 10px;
}

.latest-comment .comment-created {
  color: #000000;
  font-size: 12px;
}

.latest-comment .comment-text {
  margin-top: 10px;
}


.comment-form {
  margin-top: 10px;
}

.comment-form input[type='text'] {
  width: 100%;
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 5px;
}

.comment-form button[type='submit'] {
  background-color: #4267B2;
  color: #fff;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}



.latest-comment h3 {
  font-size: 14px;
  margin-bottom: 5px;
}




@media (min-width: 360px) and (max-width: 600px){

.username {
    display: block;
}

}




</style>


<script>
$(document).ready(function() {
  // Function to handle like button click
  $('.like-button').click(function(e) {
    e.preventDefault();
    var button = $(this);
    var postId = button.data('post-id');
    var userId = button.data('user-id');
    var likeCountElement = button.find('.like-count');
    var liked = button.hasClass('liked');

    // Send an AJAX request to like.php to handle the like action
    $.ajax({
      url: 'like.php',
      type: 'POST',
      data: {
        postId: postId,
        userId: userId
      },
      success: function(response) {
        // Update the like count
        likeCountElement.text(response);

        // Toggle the like button class
        button.toggleClass('liked', !liked);
      }
    });
  });



  // Function to handle comment form submission
  $('.comment-form').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var postId = form.find('input[name=post_id]').val();
    var userId = form.find('input[name=user_id]').val();
    var commentText = form.find('input[name=comment_text]').val();
    var commentsSection = form.closest('.post').find('#comments-section');

    // Send an AJAX request to comment.php to handle the comment submission
    $.ajax({
      url: 'comment.php',
      type: 'POST',
      data: {
        postId: postId,
        userId: userId,
        commentText: commentText
      },
      success: function(response) {
        var newComment = $(response);
        var existingComments = commentsSection.find('.comment');
        if (existingComments.length === 0) {
          commentsSection.html(newComment);
        } else {
          existingComments.last().after(newComment);
        }
        form.find('input[name=comment_text]').val('');
      }
    });
  });
});

</script>



</body>
</html>
