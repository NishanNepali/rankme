<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    // Update the 'is_seen' column to mark the messages as seen
    $updateSql = "UPDATE messages SET is_seen = 1 WHERE outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}";
    mysqli_query($conn, $updateSql);

    $sql = "SELECT * FROM messages
            LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
            OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id})
            ORDER BY time_stamp ASC"; // Update 'time_stamps' with the actual column name for timestamps
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . $row['msg'] . '</p>
                                </div>
                            </div>';
            } else {
                if ($row['unique_id'] != $_SESSION['unique_id']) {
                    $output .= '<div class="chat incoming">
                                    <img src="' . $row['thumbnail_photo'] . '" alt="">
                                    <div class="details">
                                        <p>' . $row['msg'] . '</p>
                                    </div>
                                </div>';
                } else {
                    $output .= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>' . $row['msg'] . '</p>
                                    </div>
                                </div>';
                }
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }
    echo $output;
} else {
    header("location: ../login.php");
}
?>
