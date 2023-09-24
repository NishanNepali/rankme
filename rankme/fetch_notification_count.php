<?php
// Start the session
session_start();

// Include the database connection code from your existing PHP file
require_once('config.php');

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  die('Error: User not logged in.');
}

// Get the user ID
$user_id = $_SESSION['unique_id'];

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

// Fetch the new notification count
$newNotificationCount = getNotificationCount($conn, $user_id);

// Close the database connection
mysqli_close($conn);

// Return the new notification count as JSON response
echo json_encode(['count' => $newNotificationCount]);
?>
