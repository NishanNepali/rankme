<?php
$newMessageUserId = ''; // Initialize the variable


// Retrieve user data query
$sql = "SELECT * FROM users WHERE unique_id != {$outgoing_id}";
$query = mysqli_query($conn, $sql);


while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
            OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
            OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    if (mysqli_num_rows($query2) > 0) {
        $result = $row2['msg'];
        $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
        if (isset($row2['outgoing_msg_id'])) {
            $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
        } else {
            $you = "";
        }
        $offline = ($row['status'] == "Offline now") ? "offline" : "";
        $hid_me = ($outgoing_id == $row['unique_id']) ? "hide" : "";

        // Check if the message is new (not seen)
        $isSeen = $row2['is_seen']; // Assuming you have an 'is_seen' column in the messages table
        if (!$isSeen && $newMessageUserId === '') {
            $newMessageUserId = $row['unique_id'];
        }

        // Get the new message count for the user
        $newMessageCount = 0;
        $sql3 = "SELECT COUNT(*) AS new_count FROM messages WHERE incoming_msg_id = {$outgoing_id} AND outgoing_msg_id = {$row['unique_id']} AND is_seen = 0";
        $query3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($query3);
        if ($row3 && $row3['new_count']) {
            $newMessageCount = $row3['new_count'];
        }

        // Display the user with the new message count
        $output .= '<a href="chat.php?user_id=' . $row['unique_id'] . '">
                    <div class="content">
                    <img src="' . $row['img'] . '" alt="">
                    <div class="details">
                        <span>' . $row['fname'] . " " . $row['lname'] . '</span>
                        <p>' . $you . $msg . ' <span class="new-message-count">' . $newMessageCount . '</span></p>
                    </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
    }
}

// Use the $newMessageUserId variable to handle the new message user
// For example, you can display a notification or apply specific styling to the user in the user list

?>

<style>
    .new-message-count {
  background-color: #ff0000;
  color: #fff;
  font-size: 12px;
  padding: 2px 6px;
  border-radius: 4px;
  margin-left: 6px;
}

</style>
