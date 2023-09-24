
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit&family=Roboto:wght@100&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
*{
  font-family: 'Outfit', sans-serif;
font-family: 'Roboto', sans-serif;
margin: 0px;
}



body {
background-color: rgb(0, 0, 0);
  /* Additional styles for the body element */
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

/* Dark mode styles */
.dark-mode {
  background-color: #fff; /* Dark mode background color */
}

a{

  text-decoration: none;
  margin: 0px;
  padding: 0px;
 
}
.logo{
    width:30%;
  
}
.upper-section {
  background-color: #4e4e4e;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}



.icon {
  filter: none;
  -webkit-filter: none;
}

.icon.active {
 
  filter: invert(100%);
  -webkit-filter: invert(100%);
}




.upper-section a {
  text-decoration: none;
}

.upper-section a img {
  height: 50px;
}


.notification-badge {
  background-color: red;
  color: #fff;
  border-radius: 50%;
  padding: 2px 5px;
  font-size: 12px;
  position: absolute;
  top: 5px;
  right: 5px;
}

.profile-dropdown {
  position: relative;
}

.dropbtn {
  background-color: transparent;
  border: none;
  cursor: pointer;
}

.profile-picture {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}



.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background-color: #f9f9f9;
  min-width: 160px;
  z-index: 1;
}

.dropdown-content a {
  color: #333;
  padding: 8px 16px;
  text-decoration: none;
  display: flex;
  align-items: center;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
}

.username {
  margin-left: 10px;
  font-size: 14px;
}

.dropdown-icon {
  width: 16px;
  height: 16px;
  margin-right: 10px;
}

@media (max-width: 480px) {
  .upper-section {
    flex-wrap: wrap;
    padding: 10px 5px;
  }
  
  .upper-section a {
    margin-bottom: 5px;
  }
  
  .notification-badge {
    font-size: 10px;
    top: 3px;
    right: 3px;
  }
  
  .profile-dropdown {
    margin-top: 5px;
  }
  
  .username {
    font-size: 12px;
  }
  
  .dropdown-content {
    min-width: 120px;
  }
}




        @media (min-width:300px) and (max-width:600px){

            .logo{
                width: 100%;
                height: 100%;
            }
            .messageicon{
    width: 50px;
    height: 50px;
    padding-right: 0px;


}

.upper-section a img{
  width: 35px;
  height: 35px;
}
.navbar {
    text-align: center;
  }
  
  .navlinks {
    width: 100%;
    max-width: 250px;
  }
  .username{
    display: none;
  }


     
}


.friends-icon {
  position: relative;
}

.notification-badge {
  background-color: red;
  color: white;
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 50%;
  position: absolute;
  top: -5px;
  right: -5px;
}


.notification-badge {
  position: relative;
  display: inline-block;
}

.notification-badge .badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: red;
  color: white;
  font-size: 12px;
  padding: 4px 6px;
  border-radius: 50%;
}

</style>



  
<style>

.combine-div {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.search-container {
  flex-grow: 1;
  display: flex;
  align-items: center;
  background-color: rgb(0, 0, 0);
  border-radius: 4px;
  padding: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  
}


.search-container form {
  display: flex;
  flex-grow: 1;
  justify-content: center;
}

.search-container form input[type="text"] {
  background-color: rgb(197, 197, 197);
  color: white;
  font-weight: bold;
  padding: 8px;
  border: none;
  border-radius: 4px 0 0 4px;
  outline: none;
  width: 40%;
 
}

.search-container form .search-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #ccc;
  color: black;
  padding: 8px;
  border-radius: 0 4px 4px 0;
  cursor: pointer;
  transition: background-color 0.3s ease-in-out;
}

.message-top-icon {
  width: 50px;
  filter: invert(100%);
  -webkit-filter: invert(100%);
  
}
#notificationCount{
  font-size: 20px;
  color: white;
}

@media(max-width:500px){
  .search-container form input[type="text"] {
 
  width: 60%;
 
}
.message-top-icon {
  width: 40px;
 
  
}
}

    </style>
<div class="combine-div">
  <div class="search-container">
    <form id="searchForm" action="search" method="GET">
      <input type="text" id="searchInput" name="query" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" placeholder="Search for friends...">
      <div class="search-icon" onclick="submitSearchForm()">
        <i class="fas fa-search"></i>
      </div>
    </form>
  </div>

  <a href="users.php" class="message-container">
    <img id="messageicon" class="message-top-icon" src="imgs/message.png" alt="">
    <span id="notificationCount"></span>
  </a>
</div>


<script>
  function submitSearchForm() {
    var searchInput = document.getElementById("searchInput");
    var searchValue = searchInput.value.trim(); // Trim whitespace

    // Check if search value is empty
    if (searchValue === "") {
      return; // Exit the function without submitting the form
    }

    var searchForm = document.getElementById("searchForm");
    searchForm.submit();
  }

  document.addEventListener("DOMContentLoaded", function() {
    // Get the search input element
    var searchInput = document.getElementById("searchInput");

    // Retrieve the search query value from the URL parameter
    var urlParams = new URLSearchParams(window.location.search);
    var query = urlParams.get("query");

    // Set the search query value in the search input field
    searchInput.value = query;
  });
</script>





<script>
  // Get the message icon and notification count elements
  const messageIcon = document.getElementById('messageicon');
  const notificationCount = document.getElementById('notificationCount');

  // Add a click event listener to the message icon
  messageIcon.addEventListener('click', () => {
    // Reset the notification count
    notificationCount.textContent = '';

    // Make an AJAX request to mark messages as seen
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'update-is-seen.php', true);
    xhr.send();
  });

  // Function to fetch and update the notification count
  function getNotificationCount() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get-notification-count.php', true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Update the notification count in the UI
          const counts = xhr.responseText.split(',');
          let totalCount = 0;
          for (const count of counts) {
            const [userId, userCount] = count.split(':');
            totalCount += parseInt(userCount);
          }
          notificationCount.textContent = totalCount > 0 ? totalCount : '';
        } else {
          console.error('Error: ' + xhr.status);
        }
      }
    };
    xhr.send();
  }

  // Call the function to fetch the notification count initially
  getNotificationCount();

  // Refresh the notification count every 5 seconds
  setInterval(getNotificationCount, 5000);
</script>









<div class="upper-section">
  <a href="post"><img class="icon" src="imgs/homeicon.png" alt="Home"></a>
  <?php
// Assuming you have already established a database connection

require_once('config.php');
// Function to fetch the friend request count from the database
function getFriendRequestCount($connection, $receiverId)
{
    // Perform the database query to fetch the count
    $query = "SELECT COUNT(*) AS request_count FROM friend_requests WHERE receiver_id = '$receiverId'";
    
    // Execute the query
    $result = mysqli_query($connection, $query);
    
    // Check if the query executed successfully
    if ($result) {
        // Fetch the result row
        $row = mysqli_fetch_assoc($result);
        
        // Retrieve the friend request count from the result row
        $requestCount = $row['request_count'];
        
        // Free the result set
        mysqli_free_result($result);
        
        // Return the friend request count
        return $requestCount;
    } else {
        // Error handling if the query fails
        echo "Error: " . mysqli_error($connection);
    }
}

// Fetch the friend request count for the logged-in user (receiver_id)
$receiverId = $_SESSION['unique_id']; // Assuming you have the user ID stored in a session variable

// Fetch the friend request count
$friendRequestCount = getFriendRequestCount($conn, $receiverId);

// Display the friend requests icon with the notification badge
?>
<a href="friends" class="friends-icon">
  <img class="icon" src="imgs/friends.png" alt="Friends">
  
  <?php if ($friendRequestCount > 0): ?>
    <span class="notification-badge"><?php echo $friendRequestCount; ?></span>
  <?php endif; ?>
</a>









<a href="home"><img class="icon" src="imgs/logo.png" alt="Home"></a>





<?php

// Function to fetch the count of new notifications for a user from the database
function getNotificationCount($conn, $userId)
{
    // Perform the database query to fetch the count
    $query = "SELECT COUNT(*) AS new_count FROM notifications WHERE user_id = '$userId' AND is_read = 0";
    
    // Execute the query
    $result = $conn->query($query);
    
    // Check if the query executed successfully
    if ($result) {
        // Fetch the result row
        $row = $result->fetch_assoc();
        
        // Retrieve the new notification count from the result row
        $newCount = $row['new_count'];
        
        // Free the result set
        $result->free_result();
        
        // Return the new notification count
        return $newCount;
    } else {
        // Error handling if the query fails
        echo "Error: " . $conn->error;
    }
}

// Function to mark notifications as read for a user
function markNotificationsAsRead($conn, $userId)
{
    // Perform the database update to mark the notifications as read
    $query = "UPDATE notifications SET is_read = 1 WHERE user_id = '$userId'";
    
    // Execute the update query
    $result = $conn->query($query);
    
    // Check if the query executed successfully
    if (!$result) {
        // Error handling if the query fails
        echo "Error: " . $conn->error;
    }
}

// Fetch the notification count for the logged-in user (user_id)
$userId = $_SESSION['unique_id']; // Assuming you have the user ID stored in a session variable

// Fetch the new notification count
$newNotificationCount = getNotificationCount($conn, $userId);

// Check if the notification icon is clicked
if (isset($_GET['mark_read'])) {
    // Call the function to mark notifications as read
    markNotificationsAsRead($conn, $userId);
    
    // Reset the notification count to 0
    $newNotificationCount = 0;
}

// Close the database connection


// Display the notification icon with the notification badge
?>
<a href="notifications" class="notification-icon">
  <img class="icon" src="imgs/notificatioin.png" alt="Notifications">
  <?php if ($newNotificationCount > 0 || $newNotificationCount === "0"): ?>
    <span class="notification-badge" id="notification-badge"><?php echo $newNotificationCount; ?></span>
  <?php endif; ?>
</a>



<script>
$(document).ready(function() {
  // Function to update the notification count
  function updateNotificationCount() {
    $.ajax({
      url: 'fetch_notification_count.php',
      success: function(response) {
        var count = JSON.parse(response).count;
        
        // Update the notification count on success
        if (count > 0) {
          $('#notification-badge').text(count);
        } else {
          $('#notification-badge').text('');
        }
      }
    });
  }

  // Call the function to update the notification count initially
  updateNotificationCount();

  // Refresh the notification count every 10 seconds
  setInterval(updateNotificationCount, 5000);
});


</script>




   
  <div class="profile-dropdown">
  <a href="profile-menu">
    <img class="profile-picture" src="<?php echo $thumbnailPhoto ?>" alt="Profile Picture">
  </a>
</div>

</div>

<script>
 document.addEventListener('DOMContentLoaded', function() {
  var currentLocation = window.location.href;

  var icons = document.querySelectorAll('.icon');
  icons.forEach(function(icon) {
    if (icon.parentNode.href === currentLocation) {
      icon.classList.add('active');
    }
  });
});


</script>