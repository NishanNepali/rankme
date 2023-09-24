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
<a href="friends.php" class="friends-icon">
  <img class="icon" src="imgs/friends.png" alt="Friends">
  <?php if ($friendRequestCount > 0): ?>
    <span class="notification-badge"><?php echo $friendRequestCount; ?></span>
  <?php endif; ?>
</a>
