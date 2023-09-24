<?php
session_start();

// Check if the required parameter is set
if (!isset($_GET['unique_id'])) {
  //die('Error: Unique ID parameter missing.');
  header('Location: index.php');
  exit();
}

// Get the value of the parameter
$unique_id = $_GET['unique_id'];


// Connect to the database
require_once('config.php');

// Check if the connection was successful
if (!$conn) {
  die('Database connection error: ' . mysqli_connect_error());
}

// Get the user details based on the unique_id
$query = "SELECT unique_id, bio, img, upvotes, downvotes, resized_photo, voted FROM users WHERE unique_id = '$unique_id'";
$result = mysqli_query($conn, $query);

if ($result === false) {
  die('Database query error: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if ($row === null) {
  die('Error: No user found with the specified unique ID.');
}

$profileOwnerId = $row['unique_id'];
$photo = $row['resized_photo'];
//$photo = $_SESSION['resized_photo'];
$photolink=$row['img'];
$upvotes = $row['upvotes'];
$downvotes = $row['downvotes'];
$voted = $row['voted'];
$bio = $row['bio'];



// Get the other user's name
$other_username = "";
if ($profileOwnerId === $_SESSION['unique_id']) {
  // If the profile owner is the logged-in user
  $other_username = $_SESSION['fname'] . " " . $_SESSION['lname'];
} else {
  // Fetch the other user's name from the database
  $otherUserQuery = "SELECT fname, lname FROM users WHERE unique_id = '$profileOwnerId'";
  $otherUserResult = mysqli_query($conn, $otherUserQuery);
  $otherUserRow = mysqli_fetch_assoc($otherUserResult);
  
  if ($otherUserRow !== null) {
    $other_username = $otherUserRow['fname'] . " " . $otherUserRow['lname'];
   
  }
}

// Set the user ID and other user ID variables
$user_id = isset($_SESSION['unique_id']) ? $_SESSION['unique_id'] : '';
$other_user_id = $profileOwnerId;

// Retrieve unread notification count
$notificationCountQuery = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = '$user_id' AND is_read = 0";
$notificationCountResult = mysqli_query($conn, $notificationCountQuery);
$notificationCountRow = mysqli_fetch_assoc($notificationCountResult);
$unreadNotificationCount = $notificationCountRow['unread_count'];

// Close the database connection

?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo  $other_username  ?>'s Profile</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
<link rel="manifest" href="imgs/site.webmanifest">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit&family=Roboto:wght@100&display=swap" rel="stylesheet">
<style>
 /* styles.css */

/* styles.css */

/* Global styles */
*{
  font-family: 'Outfit', sans-serif;
font-family: 'Roboto', sans-serif;

}
body {
  font-family: Arial, sans-serif;
  margin: 0;

  background: linear-gradient(45deg, #ffb3ec, #633360);
  
 
  background-attachment: fixed;
}
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
  background-color: #ffffff;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb {
  background-color: #fff;
  border-radius: 5px;
}

::-webkit-scrollbar-track {
  background-color: #000;
  border-radius: 5px;
}
a{
text-decoration: none;
}

/* Header */
.top {
  background-color: #ff96fa;
  padding: 20px;
  display: flex;
 

}

.header {
  color: #fff;
  margin: 0;
}

.logo {
  height: 55px;
  vertical-align: middle;
 
}
/* Profile Container */
/* Profile container */
.profile {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 20px;
  text-align: center; /* Center align the profile content */
}

/* Profile picture */
.profile-pic {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 10px;
 
    animation: glowing-img 2s ease-in-out infinite;
}


@keyframes glowing-img {
    0% {
      box-shadow: 0 0 5px #fff, 0 0 6px #fff, 0 0 7px #fff, 0 0 8px #000000, 0 0 9px #000000, 0 0 10px #007bff, 0 0 15px #007bff;
    }

    100% {
      box-shadow: 0 0 5px #fff, 0 0 6px #fff, 0 0 7px #fff, 0 0 8px #000000, 0 0 9px #000000, 0 0 10px #007bff, 0 0 15px #007bff, 0 0 20px #007bff;
    }
  }

/* Profile name */
.profilename {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 5px;
  color: white;
}

/* Bio */
.bio {
  font-size: 14px;
  margin-bottom: 5px;
  color: white;
}

/* Friend status */
.friend-status {
  font-size: 14px;
  margin-bottom: 5px;
}

/* Friends count */
.friends-count {
  font-size: 14px;
  margin-bottom: 5px;
}

/* Votes */
.vote-btn {
  padding: 8px;
  background-color: transparent;
  border: none;
  cursor: pointer;
  margin: 10px 0; /* Add margin on top and bottom */
}

/* Upvote button */
.upvote-btn img {
  width: 50px;
  height: 50px;
  fill: #000;
}

/* Downvote button */
.downvote-btn img {
  width: 50px;
  height: 50px;
  fill: #000;
}

/* Vote result */
.vote-result {
  font-size: 20px;
  margin-top: 10px;
}


/* Action button */
.action-btn {
  padding: 8px 16px;
  margin: 10px 0;
  font-size: 16px;
  font-weight: bold;
  background-color: black;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

/* Unfriend button */
.unfriend {
  background-color: #ed4956;
}

/* Media queries for mobile responsiveness */
@media screen and (max-width: 480px) {
  .profile-pic {
    width: 150px;
    height: 150px;
  }
  
  .profilename {
    font-size: 20px;
  }
  
  .bio, .friend-status, .friends-count, .votes {
    font-size: 12px;
  }
  
  .action-btn {
    font-size: 14px;
  }
}
.vote-btn {
    padding: 6px;
  }
  
  .vote-result {
    font-size: 20px;
    color: white;
  }


.messageicon{
  width: 30px;
}
.album-name{
  text-align: center;
text-transform: uppercase;
color:#fff;
}
</style>
</head>


<body>
<div id="particles-js" class="hintergrund"></div>
  <div class="top">
  <div class="header">
  <a href="index" onclick="goBack(event);">
  <img class="logo" src="imgs/logo.png" alt="">
</a>

</div>
<script>
  function goBack(event) {
    if (event.ctrlKey || event.metaKey || event.shiftKey || event.which === 2) {
      return; // Ignore the click if it involves a modifier key or mouse middle button
    }

    if (window.history.length > 1) {
      event.preventDefault(); // Prevent the default link behavior
      history.back(); // Redirects the user to the previous page
    }
  }
</script>

<style>
 
  .profile-name {
  font-size: 16px;
  font-weight: bold;
  color: #ffffff;

margin: auto;


 
}

.profile-name:hover {
  color: #fff;
  background-color: #000;
}
.special-top-user {
 
  padding: 5px 10px;
  background-color: skyblue;
  color: #333;
  font-weight: bold;
  border-radius: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  margin-bottom: 50px;

  animation: glowing 1.5s ease-in-out infinite;
}


@keyframes glowing {
    0% {
      box-shadow: 0 0 5px #fff, 0 0 7px #fff, 0 0 10px #fff, 0 0 11px #007bff, 0 0 16px #007bff, 0 0 19px #007bff, 0 0 20px #007bff;
    }

    100% {
      box-shadow: 0 0 5px #fff, 0 0 7px #fff, 0 0 10px #fff, 0 0 11px #007bff, 0 0 16px #007bff, 0 0 19px #007bff, 0 0 20px #007bff, 0 0 30px #007bff;
    }
  }


.special-top-user .fas.fa-crown {
  font-size: 18px;
  margin-right: 5px;
  color: gold;
  
}




/* Queen Crown Styles */
.fa-chess-queen {
  color: blueviolet;
  font-size: 24px;
}

 .message-icon {
    position: relative;
    display: inline-block;
    font-size: 40px;
    color: white;
  }

  
  
</style>


<?php


echo"<p class='profile-name'>$other_username's profile</p>";


?>

 
</div>
<br>
<br>
<!--
<div class="profile">
  <div class="profile-header">
    <div class="profile-pic-wrapper">
      <img class="profile-pic" src="<?php echo $photo ?>" alt="Profile Picture">
    </div>
    <div class="profile-info">
      <h1 class="username"><?php echo $other_username ?></h1>
      <div class="user-bio">
        <p class="bio"><?php echo $bio ?></p>
     
      </div>
    </div>
    <div class="profile-actions">

-->
      <?php
        // Check if the user is logged in
        if (!isset($_SESSION['unique_id'])) {
            die('Error: User not logged in.');
        }

        // Check if the unique_id parameter is provided
        if (!isset($_GET['unique_id'])) {
            die('Error: Invalid request.');
        }

        $loggedInUserId = $_SESSION['unique_id'];
        $profileUniqueId = $_GET['unique_id'];

        require_once('config.php');

        // Create a connection to the database
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve the profile user's ID and username
        $profileQuery = "SELECT unique_id, fname, lname FROM users WHERE unique_id = '$profileUniqueId'";
        $profileResult = mysqli_query($conn, $profileQuery);

        if (mysqli_num_rows($profileResult) > 0) {
            $profileRow = mysqli_fetch_assoc($profileResult);
            $profileId = $profileRow['unique_id'];
            $fname = $profileRow['fname'];
            $profileUsername = $profileRow['fname'] . " " . $profileRow['lname'];

           
    // Count the total number of friends
    $countFriendsQuery = "SELECT COUNT(*) as total_friends FROM friends WHERE (user_id1 = '$profileId' OR user_id2 = '$profileId')";
    $countFriendsResult = mysqli_query($conn, $countFriendsQuery);
    $totalFriends = 0;

    if (mysqli_num_rows($countFriendsResult) > 0) {
      $friendsRow = mysqli_fetch_assoc($countFriendsResult);
      $totalFriends = $friendsRow['total_friends'];
    }

    // Check if the users are friends
    $checkFriendsQuery = "SELECT * FROM friends WHERE (user_id1 = '$loggedInUserId' AND user_id2 = '$profileId') OR (user_id1 = '$profileId' AND user_id2 = '$loggedInUserId')";
    $checkFriendsResult = mysqli_query($conn, $checkFriendsQuery);

          
    /*
    if (mysqli_num_rows($checkFriendsResult) > 0) {
                // Users are friends
                echo "<button class='action-btn unfriend'>Unfriend</button>";
            } else {
                // Users are not friends
                // Check if a friend request has been sent
                $checkRequestQuery = "SELECT * FROM friend_requests WHERE (requester_id = '$loggedInUserId' AND receiver_id = '$profileId') OR (requester_id = '$profileId' AND receiver_id = '$loggedInUserId')";
                $checkRequestResult = mysqli_query($conn, $checkRequestQuery);

                if (mysqli_num_rows($checkRequestResult) > 0) {
                    // Friend request has been sent
                    echo "<button class='action-btn cancel-request'>Cancel Request</button>";
                } else {
                    // Users are not friends and no friend request sent
                    echo "<a href='send_request-direct.php?receiver_id=$profileId'><button class='action-btn add-friend'>Add Friend</button></a>";
                }
            }
            
            */
        }


        // Close database connection
       // mysqli_close($conn);
      ?>
    </div>
  </div>

  <!--

  <div class="profile-content">
   
    <p>Total Friends: <?php echo $totalFriends; ?></p>
  </div>
</div>

-->





<div class="profile">
  <div class="profile-header">
    <?php
// Get the user's upvotes and downvotes
$votesQuery = "SELECT upvotes, downvotes, gender FROM users WHERE unique_id = '$profileId'";
$votesResult = mysqli_query($conn, $votesQuery);

if (mysqli_num_rows($votesResult) > 0) {
  $votesRow = mysqli_fetch_assoc($votesResult);
  $upvotes = $votesRow['upvotes'];
  $downvotes = $votesRow['downvotes'];
  $gender = $votesRow['gender'];

  // Calculate the difference between upvotes and downvotes
  $difference = $upvotes - $downvotes;

  // Check if the user has the highest difference among their gender
  $highestDifferenceQuery = "SELECT unique_id FROM users WHERE gender = '$gender' ORDER BY (upvotes - downvotes) DESC LIMIT 1";
  $highestDifferenceResult = mysqli_query($conn, $highestDifferenceQuery);
  $highestDifferenceRow = mysqli_fetch_assoc($highestDifferenceResult);
  $highestDifferenceUserId = $highestDifferenceRow['unique_id'];

  if ($profileId == $highestDifferenceUserId) {
    if ($gender == 'male') {
      // User has the highest difference among males
      echo "<div class='special-top-user'>Rankme King <i class='fas fa-crown'></i></div>";
      
    } elseif ($gender == 'female') {
      // User has the highest difference among females
      echo "<div class='special-top-user'>Rankme Queen <i class='fas fa-chess-queen'></i></div>";
    }
  }
?>
<style>
  
.hintergrund {
display:none;

position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
overflow: hidden;
z-index:-1;
}
  <?php
 if ($gender === 'male') {
 // echo "body { background: linear-gradient(to bottom right, #b3e6ff, #0077b3); }";
  echo "body { background: #000; }";
  echo ".hintergrund {display: block;}";
  echo ".action-btn {background-color: red}";
  echo ".social-icons-container a i.fab.fa-tiktok{ color: white !important;}";
 
  echo ".top {background-color: rgba(0, 106, 255)}";
  echo ".profile-name{ color: black }";
  echo ".profilename{ color: white }";
  echo ".vote-result{ color: white }";
  echo ".album-name{ color: white }";
  echo ".bio{ color: white }";
  echo ".social-icons-container a{ color: black }";
 }
  ?>

</style>

<?php
}


?>


<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="script-animation.js"></script>
    <img class="profile-pic" src="<?php echo $photo ?>" alt="">
    <div class="profile-content">
      <h1 class="profilename" title="<?php echo $other_username ?>"><?php echo $other_username ?></h1>
      <p class="bio"><?=$bio?></p>
      <!--
      <div class="stats">
        <p class="stats-item"> Friends: <?php echo $totalFriends; ?></p>
      </div>
      -->
    </div>
   

    <?php

if ($profileId == $loggedInUserId) {
  echo'<button id="own-id">Own Id</button>';
?>
<style>
  <?php
  echo'.message-icon{display: none}';
  
  ?>
</style>

<?php
}else{


    if (mysqli_num_rows($checkFriendsResult) > 0) {
      // Users are friends
      echo "<button class='action-btn unfriend' data-receiver-id='$profileId'>Unfriend</button>";
      // Confirmation Dialog 
      echo '<div id="unfriendConfirmation" class="confirmation-dialog">
              <p>Are you sure you want to unfriend this user?</p>
              <div class="confirmation-buttons">
                <button class="confirmation-btn confirmation-yes">Yes</button>
                <button class="confirmation-btn confirmation-no">No</button>
              </div>
            </div>';
    } else {
      // Users are not friends
      // Check if a friend request has been sent
      $checkRequestQuery = "SELECT * FROM friend_requests WHERE (requester_id = '$loggedInUserId' AND receiver_id = '$profileId') OR (requester_id = '$profileId' AND receiver_id = '$loggedInUserId')";
      $checkRequestResult = mysqli_query($conn, $checkRequestQuery);

      if (mysqli_num_rows($checkRequestResult) > 0) {
        // Friend request has been sent
        echo "<button class='action-btn cancel-request' data-receiver-id='$profileId'>Cancel Request</button>";
      } else {
        // Users are not friends and no friend request sent
        echo "<button class='action-btn add-friend' data-receiver-id='$profileId'>Add Friend</button>";
      }
    }
  }
    ?>
    <?php
    echo"
 <a class='message-icon' href='chap.php?user_id=$profileId'> 
    <i class='fa fa-envelope'></i>
    </a>";
    ?>
  </div>



  <?php
// Retrieve the links from the database and assign them to variables
$query = "SELECT facebook_link, tiktok_link, instagram_link FROM users WHERE unique_id = '$profileId'";
$result = mysqli_query($conn, $query);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $facebookLink = $row['facebook_link'];
  $tiktokLink = $row['tiktok_link'];
  $instagramLink = $row['instagram_link'];
} else {
  // Handle the case when the query fails
  $facebookLink = '';
  $tiktokLink = '';
  $instagramLink = '';
}

// Close the database connection
//mysqli_close($conn);
?>
<style>
  /* Center the icons horizontally and remove any default list styling */
.social-icons-container {
  display: flex;
  justify-content: center;
  list-style: none;
  padding: 0;
}

/* Add some spacing between the icons */
.social-icons-container a {
  margin: 0 15px; /* Increased the margin for more spacing */
  font-size: 30px; /* Increased the font size for bigger icons */
}

/* Apply the original color of each social media logo */
.social-icons-container a i.fab.fa-facebook {
  color: #1877f2; /* Facebook logo color */
}

.social-icons-container a i.fab.fa-tiktok {
  color: #000; /* TikTok logo color */
}

.social-icons-container a i.fab.fa-instagram {
  color: #fff; /* Instagram logo color */
}

/* Add hover effect to change the icon color when hovering over it */
.social-icons-container a:hover {
  color: #007bff; /* Change the hover color as needed */
}

</style>
<?php
if ($profileId == $loggedInUserId) {
  if (empty($facebookLink) || empty($instagramLink) || empty($tiktokLink)) {
    echo '<p class="link-via-profile">You can add links via your profile</p>';
  }
}
?>


<div class="social-icons-container">
 
  <?php if (!empty($facebookLink)): ?>
    <a href="<?php echo $facebookLink; ?>" target="_blank">
      <i class="fab fa-facebook"></i>
     
    </a>
  <?php endif; ?>

  <?php if (!empty($tiktokLink)): ?>
    <a href="<?php echo $tiktokLink; ?>" target="_blank">
      <i class="fab fa-tiktok"></i>
     
    </a>
  <?php endif; ?>

  <?php if (!empty($instagramLink)): ?>
    <a href="<?php echo $instagramLink; ?>" target="_blank">
      <i class="fab fa-instagram"></i>
     
    </a>
  <?php endif; ?>
</div>





  <div class="voting">
    <button class="upvote-btn vote-btn" <?php echo $voted ? 'disabled' : ''; ?> onclick="vote('<?php echo $other_user_id ?>', 'upvote')"><img src="imgs/upvote.png" alt=""></button>
    <button class="downvote-btn vote-btn" <?php echo $voted ? 'disabled' : ''; ?> onclick="vote('<?php echo $other_user_id ?>', 'downvote')"><img src="imgs/downvote.png" alt=""></button>
  </div>
  <div class="vote-result">
    <?php
    $voteDifference = $upvotes - $downvotes;
    if ($voteDifference > 0) {
      echo $fname. 's votes: +' . $voteDifference;
    } elseif ($voteDifference < 0) {
      echo 'Vote Result: ' . $voteDifference;
    } else {
      echo 'Vote Result: 0';
    }
    ?>
  </div>
</div>









<script>
// Handle Add Friend
function handleAddFriend(event) {
  event.preventDefault();
  const receiverId = event.target.dataset.receiverId;

  $.ajax({
    url: 'send_request.php',
    method: 'POST',
    data: { receiver_id: receiverId },
    dataType: 'json',
    success: function (response) {
      if (response.success) {
        // Update the button's HTML
        const addFriendButton = event.target;
        addFriendButton.textContent = 'Request Sent';
        addFriendButton.classList.remove('add-friend');
        addFriendButton.removeAttribute('data-receiver-id');
        addFriendButton.removeEventListener('click', handleAddFriend);
      } else {
        // Display the error message
        console.error(response.error);
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
    }
  });
}

// Attach event listeners to all "Add Friend" buttons
const addFriendButtons = document.querySelectorAll('.add-friend');
addFriendButtons.forEach(button => {
  button.addEventListener('click', handleAddFriend);
});













// Handle Cancel Friend Request
function handleCancelRequest(event) {
  event.preventDefault();
  const receiverId = event.target.dataset.receiverId;

  $.ajax({
    url: 'cancel_request.php',
    method: 'POST',
    data: { receiver_id: receiverId },
    dataType: 'json',
    success: function (response) {
      if (response.success) {
        // Update the button's HTML
        const cancelRequestButton = event.target;
        cancelRequestButton.textContent = 'Add Friend';
        cancelRequestButton.classList.remove('cancel-request');
        cancelRequestButton.classList.add('add-friend');
        cancelRequestButton.removeAttribute('data-receiver-id');
        cancelRequestButton.setAttribute('href', `send_request-direct.php?receiver_id=${receiverId}`);
        cancelRequestButton.removeEventListener('click', handleCancelRequest);
      } else {
        // Display the error message
        console.error(response.error);
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
    }
  });
}

// Attach event listeners to all "Cancel Request" buttons
const cancelRequestButtons = document.querySelectorAll('.cancel-request');
cancelRequestButtons.forEach(button => {
  button.addEventListener('click', handleCancelRequest);
});









// Handle Unfriend
function handleUnfriend(event) {
  event.preventDefault();
  
  const receiverId = event.target.dataset.receiverId;
  const confirmationDialog = document.getElementById('unfriendConfirmation');
  const confirmationYesButton = confirmationDialog.querySelector('.confirmation-yes');
  const confirmationNoButton = confirmationDialog.querySelector('.confirmation-no');

  // Display confirmation dialog
  confirmationDialog.style.display = 'block';

  // Handle confirmation yes button click
  confirmationYesButton.addEventListener('click', function() {
    confirmationDialog.style.display = 'none';
  
    $.ajax({
      url: 'unfriend.php',
      method: 'POST',
      data: { receiver_id: receiverId },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          // Update the button's HTML
          const unfriendButton = event.target;
          unfriendButton.textContent = 'Add Friend';
          unfriendButton.classList.remove('unfriend');
          unfriendButton.classList.add('add-friend');
          unfriendButton.setAttribute('data-receiver-id', receiverId);
          unfriendButton.addEventListener('click', handleAddFriend);
        } else {
          // Display the error message
          console.error(response.error);
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  });

  // Handle confirmation no button click
  confirmationNoButton.addEventListener('click', function() {
    confirmationDialog.style.display = 'none';
  });
}

// Attach event listener to all "Unfriend" buttons
const unfriendButtons = document.querySelectorAll('.unfriend');
unfriendButtons.forEach(button => {
  button.addEventListener('click', handleUnfriend);
});
















function vote(userId, action) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'vote.php?user_id=' + userId + '&type=' + action);
  xhr.onload = function() {
    if (xhr.status === 200) {
      console.log(xhr.responseText);

      // Update the vote result on the page
      var voteResultElement = document.querySelector('.vote-result');
      var voteDifference = parseInt(xhr.responseText);
      if (!isNaN(voteDifference)) {
        voteResultElement.textContent = 'Vote Result: ' + voteDifference;

        // Disable the vote buttons
        var upvoteButton = document.querySelector('.upvote-btn');
        var downvoteButton = document.querySelector('.downvote-btn');
        upvoteButton.disabled = true;
        downvoteButton.disabled = true;
      } else {
        voteResultElement.textContent = 'Vote Result: Error';
      }
    }
  };
  xhr.send();
}




    const optionsOverlays = document.querySelectorAll('.options-overlay');
    
    // Add click event listener to each status image
    const statusImages = document.querySelectorAll('.status-image');
    statusImages.forEach((statusImage) => {
      statusImage.addEventListener('click', () => {
        const profileId = statusImage.dataset.profileId;
  
        // Hide all options overlays
        optionsOverlays.forEach((overlay) => {
          overlay.style.display = 'none';
        });
  
        // Show the options overlay for the clicked status image
        const optionsOverlay = document.querySelector(`.options-overlay[data-profile-id='${profileId}']`);
        optionsOverlay.style.display = 'block';
      });
    });
  
    // Add click event listener to each cancel-request option
    const cancelRequestOptions = document.querySelectorAll('.cancel-request');
    cancelRequestOptions.forEach((cancelRequestOption) => {
      cancelRequestOption.addEventListener('click', () => {
        const profileId = cancelRequestOption.closest('.options-overlay').dataset.profileId;
  
        // Implement cancel-request logic here
        window.location.href = `cancel_request.php?profile_id=${profileId}`;
      });
    });
  
    // Add click event listener to each unfriend option
    const unfriendOptions = document.querySelectorAll('.unfriend');
    unfriendOptions.forEach((unfriendOption) => {
      unfriendOption.addEventListener('click', () => {
        const profileId = unfriendOption.closest('.options-overlay').dataset.profileId;
  
        // Implement unfriend logic here
        window.location.href = `unfriend.php?profile_id=${profileId}`;
      });
    });


  </script>
</div>


<style>
  button#own-id {
  background-color: #4CAF50;
  color: #fff;
  font-size: 16px;
  padding: 8px 12px;
  margin-bottom: 10px;
  border: none;
  cursor: pointer;
}

button#own-id:hover {
  background-color: #42A5F5;
}

  .link-via-profile {
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  text-decoration: none;
  cursor: pointer;
}

.link-via-profile:hover {
  color: #fff;
  background-color: #000;
}


.options-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
}

.options-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 20px;
}

.options-container p.option {
  cursor: pointer;
  margin: 10px 0;
}

.status-image {
  cursor: pointer;
}

.confirmation-dialog {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 30px; /* Increase padding */
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    width: 300px; /* Increase width */
    z-index: 999;
  }

  .confirmation-dialog p {
    margin-bottom: 15px; /* Increase margin-bottom */
  }

  .confirmation-buttons {
    text-align: center;
  }

  .confirmation-buttons button {
    margin: 0 10px; /* Increase margin */
    padding: 10px 20px; /* Increase padding */
    border: none;
    border-radius: 3px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
  }

  .confirmation-buttons button:hover {
    background-color: #0056b3;
  }

  /* Responsive Styles */
  @media (max-width: 480px) {
    .confirmation-dialog {
      width: 90%; /* Adjust width for smaller screens */
      padding: 20px; /* Adjust padding for smaller screens */
    }

    .confirmation-buttons button {
      margin: 5px; /* Adjust margin for smaller screens */
      padding: 8px 16px; /* Adjust padding for smaller screens */
    }
  }

  .switcher-btn-post-album{
    text-align:center;

  }
  .switcher-btn {
   
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        /* Style the active button */
        .active {
            background-color: #2196F3;
        }
        #posts-container{
          display: none;
        }
        .posts-name-error{
          display:none;
          text-align:center;
        }
</style>

<div class="switcher-btn-post-album">
  <button class="switcher-btn" onclick="showAlbum()" id="albumBtn">Album</button>
  <button class="switcher-btn" onclick="showPosts()" id="postsBtn">Posts</button>
</div>


<?php
// Assuming $profileUserId is the user ID of the profile being viewed and $loggedInUserId is the ID of the logged-in user
require_once('functions.php');
$userId = getUserId();
// Check if the logged-in user is friends with the profile user
$sqlCheckFriendship = "SELECT COUNT(*) FROM friends WHERE (user_id1 = ? AND user_id2 = ?) OR (user_id1 = ? AND user_id2 = ?)";
$stmtCheckFriendship = $conn->prepare($sqlCheckFriendship);
$stmtCheckFriendship->bind_param("iiii", $loggedInUserId, $profileUserId, $profileUserId, $loggedInUserId);
$stmtCheckFriendship->execute();
$resultCheckFriendship = $stmtCheckFriendship->get_result();
$areFriends = ($resultCheckFriendship->fetch_row()[0] > 1);

// Prepare the main query to fetch posts
if ($areFriends) {
  // Fetch both public and friends' posts
  $sql = "SELECT p.post_id, p.user_id, p.caption, p.photo_filename, DATE_FORMAT(p.post_created, '%Y-%m-%d %H:%i:%s') AS formatted_post_created, u.fname, u.lname, u.thumbnail_photo
          FROM posts p
          JOIN users u ON p.user_id = u.unique_id
          WHERE (p.visibility = 2 OR (p.visibility = 1 AND p.user_id IN (
            SELECT user_id2 AS friend_id
            FROM friends
            WHERE user_id1 = ?
            UNION
            SELECT user_id1 AS friend_id
            FROM friends
            WHERE user_id2 = ?
          )))
          AND p.user_id = ?
          ORDER BY p.post_created DESC";
} else {
  // Fetch only public posts
  $sql = "SELECT p.post_id, p.user_id, p.caption, p.photo_filename, DATE_FORMAT(p.post_created, '%Y-%m-%d %H:%i:%s') AS formatted_post_created, u.fname, u.lname, u.thumbnail_photo
          FROM posts p
          JOIN users u ON p.user_id = u.unique_id
          WHERE p.visibility = 2
          AND p.user_id = ?
          ORDER BY p.post_created DESC";
}

$stmt = $conn->prepare($sql);
if ($areFriends) {
  $stmt->bind_param("iii", $loggedInUserId, $profileId, $profileId);
} else {
  $stmt->bind_param("i", $profileId);
}
$stmt->execute();
$result = $stmt->get_result();
if (mysqli_num_rows($result) > 0) {
  echo"<h1 class='posts-name-error'> $fname's Post</h1>";
} else {
  if($profileId == $loggedInUserId){
    echo"<h3 class='posts-name-error'>You can create your post.</h3>";
  }
  else{

  
 echo"<h3 class='posts-name-error'>User hasn't uploaded any post yet</h3>";
  }
}


?>



<h3 class="posts-name-error"><?php  echo"$fname Posts"; ?></h3>
<!-- Add this code in the appropriate place within the HTML structure of the profile page -->
<div id="posts-container">
  <?php
  // Display the fetched posts
  

  while ($row = $result->fetch_assoc()) {
    $postId = $row["post_id"];
    $caption = $row["caption"];
    $photoFilename = $row["photo_filename"];
    $username = $row["fname"] . " " . $row["lname"];
    $posterthumbnailPhoto = $row["thumbnail_photo"];
    $poster_link = $row["user_id"];
    $created_at = $row['formatted_post_created']; // Replace with your post creation time variable

    // Get the user's time zone
    $userTimeZone = new DateTimeZone('America/New_York'); // Replace with the user's time zone
    $postTime = DateTime::createFromFormat('Y-m-d H:i:s', $created_at, $userTimeZone);
    $currentTime = new DateTime('now', $userTimeZone);
    $timeDiff = $currentTime->getTimestamp() - $postTime->getTimestamp();

    // Calculate the time difference
    $minute = 60;
    $hour = 60 * $minute;
    $day = 24 * $hour;
    $week = 7 * $day;
    $month = 30 * $day;
    $timeAgo = "";

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


var_dump($poster_link);


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
      echo "<span class='heart-icon liked'><i class='fas fa-heart'></i></span> <span class='like-count'>$likeCount</span></a>";
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









?>
</div>

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
 
  font-size: 36px;
  background-color: rgb(255, 6, 6);
  border-radius:50%;

  color: black;
padding:10px;
  cursor: pointer;


}

.post-modal-body {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.post-modal-body img {
  max-width: 100%;
  max-height: 100%;
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
  max-width: 100%;
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


<?php

// Retrieve the user's uploaded photos from the database, ordered by creation date or ID in descending order
$photosQuery = "SELECT * FROM user_photos WHERE user_id = '$profileId' ORDER BY photo DESC";
$photosResult = mysqli_query($conn, $photosQuery);
if (mysqli_num_rows($photosResult) > 0) {
  echo"<h1 class='album-name'> $fname's album</h1>";
} else {
  if($profileId == $loggedInUserId){
    echo"<h3 class='album-name'>You can create your album.</h3>";
  }
  else{

  
 echo"<h3 class='album-name'>User hasn't uploaded any photo yet</h3>";
  }
}

?>



<div id="album-container-div">
<section id="album-container" class="post-list-album">
  <?php while ($row = mysqli_fetch_assoc($photosResult)) { ?>
    
    <a href="<?php echo $row['photo']; ?>" class="album-post">
      <figure class="post-image-album">
        <img src="<?php echo $row['photo']; ?>" alt="Photo" loading="lazy">
      </figure>
      <span class="post-overlay-album">
        <p>
          <span class="post-likes"><?php echo $row['caption']; ?></span>
        </p>
      </span>
    </a>
  <?php } ?>
</section>
</div>

<style>


#album-container-div{
  
}

 .post-list-album {
  display: grid;
  grid-template-columns: repeat(3, minmax(100px, 293px));
  justify-content: center;
  grid-gap: 28px;
}

.album-post{
  cursor: pointer;
  position: relative;
  display: block;
 
  overflow: hidden; /* Hide any content that exceeds the container */
}

.post-image-album {
  margin: 0;
  padding-bottom: 100%; /* Maintain a square aspect ratio */
  position: relative;
}

.post-image-album img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensure the image fills the container while maintaining aspect ratio */
}

.post-overlay-album {
  background: rgba(0, 0, 0, .4);
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  display: none;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
}

.album-post:hover .post-overlay-album {
  display: flex;
}

.post-caption-album {
  text-align: center;
  margin-top: 5px;
  font-size: 14px;
  color: #333;
}

@media screen and (max-width: 768px) {
  .post-list-album {
    grid-gap: 3px;
  }
}


</style>

<script>
  function showAlbum() {
    document.querySelector(".album-name").style.display = "block";
    document.querySelector(".posts-name-error").style.display = "none";
    document.getElementById("album-container-div").style.display = "block";
    document.getElementById("posts-container").style.display = "none";
    document.getElementById("albumBtn").classList.add("active"); // Add ID "albumBtn" to the album button
    document.getElementById("postsBtn").classList.remove("active"); // Add ID "postsBtn" to the posts button

    // Set switcher state in localStorage
    localStorage.setItem('switcher_state', 'album');
  }

  function showPosts() {
    document.querySelector(".album-name").style.display = "none";
    document.querySelector(".posts-name-error").style.display = "block";
    document.getElementById("album-container-div").style.display = "none";
    document.getElementById("posts-container").style.display = "block";
    document.getElementById("albumBtn").classList.remove("active"); // Add ID "albumBtn" to the album button
    document.getElementById("postsBtn").classList.add("active"); // Add ID "postsBtn" to the posts button

    // Set switcher state in localStorage
    localStorage.setItem('switcher_state', 'posts');
  }

  // Check and set initial switcher state on page load
  document.addEventListener('DOMContentLoaded', function () {
    const switcherState = localStorage.getItem('switcher_state');

    if (switcherState === 'album') {
      // Show album and mark album button as active
      showAlbum();
    } else {
      // Show posts (default) and mark posts button as active
      showPosts();
    }
  });
</script>



</body>
</html>