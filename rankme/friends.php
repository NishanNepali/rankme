<?php
session_start();

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
//var_dump($_SESSION);


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

  

    <div class="scroll-container">
<div id="people">
<?php

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    die('Error: User not logged in.');
}

require_once('config.php');

// Retrieve friend request data
$receiverId = $_SESSION['unique_id'];
//$friendRequestsQuery = "SELECT fr.requester_id, nu.username, nu.photo, fr.status FROM friend_requests fr JOIN new_users nu ON fr.requester_id = nu.id WHERE fr.receiver_id = '$receiverId'";
$friendRequestsQuery = "SELECT fr.requester_id,nu.unique_id, nu.fname, nu.lname, nu.img, fr.status, nu.thumbnail_photo FROM friend_requests fr JOIN users nu ON fr.requester_id = nu.unique_id WHERE fr.receiver_id = '$receiverId'";
$friendRequestsResult = mysqli_query($conn, $friendRequestsQuery);

// Store incoming friend requests in an array
$incomingFriendRequests = array();
if (mysqli_num_rows($friendRequestsResult) > 0) {
  while ($friendRequestRow = mysqli_fetch_assoc($friendRequestsResult)) {
      $requesterId = $friendRequestRow['requester_id'];
      //$requesterUsername = $friendRequestRow['username'];
      $requesterUsername = $friendRequestRow['fname'] . " ". $friendRequestRow['lname'];
$requester_unique_id = $friendRequestRow['unique_id'];
      $requesterPhoto = $friendRequestRow['thumbnail_photo'];
      //$requesterPhoto = $friendRequestRow['img'];
      $status = $friendRequestRow['status'];

      // Generate a link for the requester's profile page
      $profileLink = "profile?unique_id=" . urlencode($requester_unique_id) . "&img=" . urlencode($requesterPhoto);

      echo "<div class='user-card'>";
      echo "<a href='$profileLink'><img class='user-image' src='$requesterPhoto' alt='Profile photo'></a>";
      echo "<p class='user-name'><a href='$profileLink'>$requesterUsername</a></p>";
      
      // Check if $friendRequest is not null before accessing its values
      if (isset($friendRequestRow['status'])) {
          if ($status === "Friends") {
              echo "<p class='user-status'>Friends</p>";
          } elseif ($status === "Pending") {
              echo "<p class='user-status'>Friend request sent</p>";
          } else {
             
            echo "<div class='user-buttons'>";
            echo "<a class='accept-button' data-requester-id='$requesterId'><button class='custom-button' id='custom-button-check'>
            <i class='fas fa-check'></i>
          </button>
          </a>";
            // echo " <a class='accept-button' href='#' data-requester-id='$requesterId'>Accept</a>";
             echo " <a class='reject-button' data-requester-id='$requesterId'><button class='custom-button' id='custom-button-delete'>
             <i class='fas fa-times'></i>
           </button></a>";              
              
             echo "</div>";
          }
      } else {
          echo "<p class='user-status'>Unknown status</p>";
      }
      
      echo "</div>";
      

  }
} else {
  echo "No friend requests.";
}


?>
<?php
// Retrieve user's gender
$userId = $_SESSION['unique_id'];
$userGenderQuery = "SELECT gender FROM users WHERE unique_id = '$userId'";
$userGenderResult = mysqli_query($conn, $userGenderQuery);

// Check if user's gender is available
if (mysqli_num_rows($userGenderResult) > 0) {
    $userGenderRow = mysqli_fetch_assoc($userGenderResult);
    $userGender = $userGenderRow['gender'];

    // Determine the suggested gender for friend requests
    if ($userGender === 'male') {
        $suggestedGender = 'female';
    } else if ($userGender === 'female') {
        $suggestedGender = 'male';
    } else {
        die('Error: User gender not found.');
    }

    // Retrieve friend request recommendations
    $friendRequestRecommendationQuery = "SELECT nu.unique_id, nu.fname, nu.lname, nu.img, nu.thumbnail_photo
        FROM users nu
        WHERE nu.unique_id NOT IN (
            SELECT f.user_id2 FROM friends f WHERE f.user_id1 = '$userId'
        ) AND nu.unique_id != '$userId' AND nu.gender = '$suggestedGender'
        ORDER BY RAND()
        LIMIT 20"; // Adjust the limit as per your requirement

    $friendRequestRecommendationResult = mysqli_query($conn, $friendRequestRecommendationQuery);

    // Store incoming friend requests in an array
  $incomingFriendRequests = array();
  if (mysqli_num_rows($friendRequestRecommendationResult) > 0) {
      while ($recommendationRow = mysqli_fetch_assoc($friendRequestRecommendationResult)) {
          $recommendationId = $recommendationRow['unique_id'];
          $recommendationUsername = $recommendationRow['fname'] . " " . $recommendationRow['lname'];
          $recommendationPhoto = $recommendationRow['thumbnail_photo'];

          // Generate a link for the user's profile page
          $profileLink = "profile?unique_id=" . urlencode($recommendationId);

          echo "<div class='friend-request'>";
          echo "<a class='friend-request-link' href='$profileLink'><img class='friend-request-image' src='$recommendationPhoto' alt='Profile photo'></a>";
          echo "<p class='friend-request-text'><a class='friend-request-link' href='$profileLink'>$recommendationUsername</a></p>";

          // Check if a friend request has already been sent
          $requestSentQuery = "SELECT * FROM friend_requests WHERE requester_id = '$receiverId' AND receiver_id = '$recommendationId'";
          $requestSentResult = mysqli_query($conn, $requestSentQuery);

          if (mysqli_num_rows($requestSentResult) > 0) {
              echo "<p class='friend-request-status'>Request Sent</p>";
          } else {
              echo "<div class='friend-request-buttons'>";
              echo "<a class='send-request-button' href='' data-receiver-id='$recommendationId'>Add</a>";
              echo "</div>";
          }

          echo "</div>";
      }
  } else {
      echo "<p>No friend request recommendations found.</p>";
  }
} else {
  echo "<p>User gender not available.</p>";
}

// Close database connection
mysqli_close($conn);

?>



   

<script>
document.addEventListener('DOMContentLoaded', function() {
  var rejectButtons = document.querySelectorAll('.reject-button');

  rejectButtons.forEach(function(button) {
    button.addEventListener('click', handleRejectRequest);
  });

  function handleRejectRequest(event) {
    event.preventDefault();
    var requesterId = this.getAttribute('data-requester-id');
    var url = 'reject_request.php?requester_id=' + requesterId;

    fetch(url)
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (data.success) {
          console.log(data.message); // Request rejected successfully
          // Remove the friend request element from the DOM immediately
          var friendRequestElement = event.target.closest('.user-card');
          if (friendRequestElement) {
            friendRequestElement.style.display = 'none';
          }
        } else {
          console.error(data.error); // Error rejecting friend request
        }
      })
      .catch(function(error) {
        console.error('An error occurred:', error);
      });
  }
});




document.addEventListener('DOMContentLoaded', function() {
  var acceptButtons = document.querySelectorAll('.accept-button');

  acceptButtons.forEach(function(button) {
    button.addEventListener('click', handleAcceptRequest);
  });

  function handleAcceptRequest(event) {
    event.preventDefault();
    var requesterId = this.getAttribute('data-requester-id');
    var url = 'accept_request.php?requester_id=' + requesterId;

    fetch(url)
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (data.success) {
          console.log(data.message); // Request accepted successfully
          // Remove the friend request element from the DOM immediately
          var friendRequestElement = event.target.closest('.user-card');
          if (friendRequestElement) {
            friendRequestElement.remove();
          }
        } else {
          console.error(data.error); // Error accepting friend request
        }
      })
      .catch(function(error) {
        console.error('An error occurred:', error);
      });
  }
});



// Additional JavaScript code here

function handleSendRequest(event) {
    event.preventDefault();
    const receiverId = event.target.dataset.receiverId;
    
    // Create an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'send_request.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Update the button's status to "Request Sent"
          event.target.textContent = 'Request Sent';
          event.target.classList.add('request-sent');
          event.target.removeEventListener('click', handleSendRequest);
        } else {
          // Display the error message
          console.error(response.error);
        }
      }
    };
    xhr.send(`receiver_id=${receiverId}`);
  }

  // Attach event listeners to all "Send Friend Request" buttons
  const sendRequestButtons = document.querySelectorAll('.send-request-button');
  sendRequestButtons.forEach(button => {
    button.addEventListener('click', handleSendRequest);
  });




</script>


<style>
.custom-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  border: none;
  background-color: #f2f2f2;
  color: #333;
  border-radius: 50%;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.custom-button:hover {
  background-color: #ddd;
}

.custom-button i {
  font-size: 24px;
}
#custom-button-delete{
  background-color: red;
}

 #people{
  color: white;
 }
.user-card {
  position: relative;
  display: flex;
  align-items: center;
  background-color: #ffffff;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
  transition: transform 0.3s ease;
  color:white;
}

.user-card:hover {
  transform: translateY(-5px);
}

.user-image {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 10px;
}

.user-name {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 5px;

}

.user-status {
  font-size: 14px;
  color: #888;
}

.user-buttons {
  margin-top: 10px;
margin-left: auto;
}

.accept-button,
.reject-button {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 3px;
  
  color: #fff;
  text-decoration: none;
  margin-right: 5px;
  transition: background-color 0.3s ease;
  
}
.accept-button{
  background-color: white;
}

.accept-button:hover,
.reject-button:hover {
 
}

/* Animation styles */
@keyframes slideInFromLeft {
  0% {
    opacity: 0;
    transform: translateX(-20px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideOutToLeft {
  0% {
    opacity: 1;
    transform: translateX(0);
  }
  100% {
    opacity: 0;
    transform: translateX(-20px);
  }
}

@keyframes slideInFromRight {
  0% {
    opacity: 0;
    transform: translateX(20px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideOutToRight {
  0% {
    opacity: 1;
    transform: translateX(0);
  }
  100% {
    opacity: 0;
    transform: translateX(20px);
  }
}

.accept-button.animation,
.reject-button.animation {
  animation-duration: 0.3s;
  animation-fill-mode: both;
}

.accept-button.animation.slideInFromLeft {
  animation-name: slideInFromLeft;
}

.accept-button.animation.slideOutToLeft {
  animation-name: slideOutToLeft;
}

.reject-button.animation.slideInFromRight {
  animation-name: slideInFromRight;
}

.reject-button.animation.slideOutToRight {
  animation-name: slideOutToRight;
}


  .friend-request {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
    transition: background-color 0.3s;
    background-color: rgb(94, 94, 94);
  }

  .friend-request:hover {
    background-color: #f7f7f7;
  }

  .friend-request-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: black;
  font-weight: bold;
    transition: color 0.3s;
  }

  .friend-request-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
  }

  .friend-request:hover .friend-request-image {
    transform: scale(1.1);
  }

  .friend-request-text {
    margin: 0;
    font-weight: bold;
    font-size: 16px;
    transition: color 0.3s;
  }

  .friend-request:hover .friend-request-text {
    color: #222;
  }

  .friend-request-status {
    margin-left: auto;
    padding: 6px 12px;
    background-color: #385898;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: background-color 0.3s;
  }

  .friend-request-buttons {
    margin-left: auto;
    opacity: 1;
    transition: opacity 0.3s;
  }

  .friend-request:hover .friend-request-buttons {
    opacity: 1;
  }

  .send-request-button {
    display: inline-block;
    padding: 6px 12px;
    background-color: #385898;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: background-color 0.3s;
  }

  .send-request-button:hover {
    background-color: #1e3a6e;
  }

.user-buttons{
  
}
</style>





      </div>
</div>








</body>
 
</html>