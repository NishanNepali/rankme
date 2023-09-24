<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $receiver_id = $_SESSION['unique_id'];

    // Update the messages as seen
    $sql = "UPDATE messages SET is_seen = 1 WHERE incoming_msg_id = {$receiver_id} AND is_seen = 0";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        // Retrieve the unread message count for each user
        $unread_sql = "SELECT outgoing_msg_id, COUNT(*) AS unread_count FROM messages WHERE incoming_msg_id = {$receiver_id} AND is_seen = 0 GROUP BY outgoing_msg_id";
        $unread_query = mysqli_query($conn, $unread_sql);
        $output = '';

        if (mysqli_num_rows($unread_query) > 0) {
            while ($row = mysqli_fetch_assoc($unread_query)) {
                $output .= $row['outgoing_msg_id'] . ':' . $row['unread_count'] . ',';
            }
        }

        echo rtrim($output, ',');
    } else {
        echo "Error";
    }
} else {
    echo "Error";
}
?>
