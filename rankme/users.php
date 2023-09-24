<?php 
  session_start();
  include_once "config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
    exit(); // Add an exit statement to prevent further execution
  }


// Check if the user is logged in via cookie
if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] === 'true') {
  $_SESSION['loggedin'] = true;
}

//$username = isset($_SESSION["fname"]) ? $_SESSION["name"] : "";
//$username = isset($_SESSION["fname"]) ? $_SESSION["lname"] : "";
$fname = isset($_SESSION["fname"]) ? $_SESSION["fname"] : "";
$lname = isset($_SESSION["lname"]) ? $_SESSION["lname"] : "";
$username = $fname . " " . $lname; // Concatenate the first name and last name
//$photo = isset($_SESSION["photo"]) ? $_SESSION["photo"] : "";
$thumbnailPhoto = isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "";
//$photo = isset($_SESSION["resized_photo"]) ? $_SESSION["resized_photo"] : "";

$unique_id = isset($_SESSION["unique_id"]) ? $_SESSION["unique_id"] : "ID not found";
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Email not found";
$_SESSION['test'] = 'Hello World';

// SESSION SET
//var_dump($_SESSION);

?>
<?php include_once "header.php"; ?>
<body>
<?php
require_once('header-main.php');

?>
<div class="wrapper">
  <section class="users">
    <header>
      <div class="content">
        <?php 
          $row = null; // Initialize the $row variable
          if(isset($_SESSION['unique_id'])) {
            $unique_id = $_SESSION['unique_id'];
            $sql = "SELECT * FROM users WHERE unique_id = '$unique_id'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
              $row = mysqli_fetch_assoc($result);
            }
          }
        ?>
        <?php if($row): ?>
          <img src="<?php echo $row['thumbnail_photo']; ?>" alt="Profile Picture">
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        <?php endif; ?>
      </div>
      <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
    </header>
    <div class="search">
      <span class="text">Select a user to start a chat</span>
      <input class="search-input" type="text" placeholder="Enter name to search...">
      <button><i class="fas fa-search"></i></button>
    </div>
    <div class="users-list">
    <?php
// Fetch and display the friend users
if ($row) {
  $user_id = $row['unique_id'];
  $sql = "SELECT users.*, MAX(messages.time_stamp) AS last_message_time, COUNT(messages.is_seen) AS new_message_count
          FROM users
          JOIN friends ON (users.unique_id = friends.user_id1 OR users.unique_id = friends.user_id2)
          LEFT JOIN messages ON (users.unique_id = messages.outgoing_msg_id AND messages.incoming_msg_id = '$user_id')
          WHERE (friends.user_id1 = '$user_id' OR friends.user_id2 = '$user_id')
          GROUP BY users.unique_id
          ORDER BY messages.is_seen DESC, messages.time_stamp DESC";

  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    while ($friend = mysqli_fetch_assoc($result)) {
      // Display friend user details here
      echo '<div class="user">';
      echo '<img src="' . $friend['thumbnail_photo'] . '" alt="Profile Picture">';
      echo '<div class="details">';
      echo '<span>' . $friend['fname'] . ' ' . $friend['lname'] . '</span>';
      echo '<p>' . $friend['status'] . '</p>';
      echo '</div>';
      // Display the message count if it's greater than 0
      if ($friend['new_message_count'] > 0) {
        echo '<div class="message-count">' . $friend['new_message_count'] . '</div>';
      }
      echo '</div>';
    }
  } else {
    echo '<p>No friends found.</p>';
  }
} else {
  echo '<p>User not found.</p>';
}
?>



    </div>
  </section>
</div>



  <script src="javascript/users.js"></script>
  <script>
 

function logout() {
    // Make an AJAX request to a PHP script to destroy the session
    var xhr = new  XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Redirect to the login page after the session is destroyed
            window.location.href = "destroycookie.php";
        }
    };
    xhr.open("GET", "destroycookie.php", true);
    xhr.send();
}




</script>  

</body>
</html>
