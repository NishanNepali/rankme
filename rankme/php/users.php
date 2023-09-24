<?php
session_start();
include_once "config.php";
$outgoing_id = $_SESSION['unique_id'];

// Fetch and display the friend users
$sql = "SELECT DISTINCT users.* FROM users JOIN friends ON (users.unique_id = friends.user_id1 OR users.unique_id = friends.user_id2) WHERE (friends.user_id1 = '$outgoing_id' OR friends.user_id2 = '$outgoing_id') AND users.unique_id != '$outgoing_id' ORDER BY users.user_id DESC";
$query = mysqli_query($conn, $sql);
$output = "";

if(mysqli_num_rows($query) == 0){
    $output .= "No friends available to chat";
}elseif(mysqli_num_rows($query) > 0){
    include_once "data.php";
}

echo $output;
?>
