<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $receiver_id = $_SESSION['unique_id'];
    $output = "";

    // Query to count new messages for each user
    $sql = "SELECT users.unique_id, COUNT(messages.msg_id) AS count 
            FROM users
            LEFT JOIN messages ON users.unique_id = messages.outgoing_msg_id AND messages.incoming_msg_id = {$receiver_id} AND messages.is_seen = 0
            WHERE users.unique_id != {$receiver_id}
            GROUP BY users.unique_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $user_id = $row['unique_id'];
            $count = $row['count'];
            $output .= "{$user_id}:{$count},";
        }
        // Send the counts as a response
        echo rtrim($output, ',');
    } else {
        echo ""; // If an error occurs, send an empty response
    }
} else {
    echo ""; // If user session not set, send an empty response
}
?>
