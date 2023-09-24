<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
      <?php 
        $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
        if(mysqli_num_rows($sql) > 0){
          $row = mysqli_fetch_assoc($sql);
      ?>
        
            <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
            <?php if ($row['unique_id'] != $_SESSION['unique_id']) : ?>
              <img src="<?php echo $row['thumbnail_photo']; ?>" alt="">
            <?php endif; ?>
            <div class="details">
              <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
              <p><?php echo $row['status']; ?></p>
            </div>
          </header>
          <div class="chat-box">
            <?php
              // Check if the user is a friend
              $outgoing_id = $_SESSION['unique_id'];
              $friend_check_sql = "SELECT * FROM friends WHERE (user_id1 = '$outgoing_id' AND user_id2 = '$user_id') OR (user_id1 = '$user_id' AND user_id2 = '$outgoing_id')";
              $friend_check_result = mysqli_query($conn, $friend_check_sql);

              if(mysqli_num_rows($friend_check_result) > 0) {
                // User is a friend, display chat messages
            ?>
                <!-- Display chat messages here -->
            <?php
              } else {
                // User is not a friend, display message to send friend request
            ?>
                <div class="not-friend-message">
                  <p>Please send a friend request to start a chat.</p>
                  <button class="friend-request-button" id="backButton">Not friends</button>
                </div>
<script>
   const backButton = document.getElementById('backButton');

// Add a click event listener to the button
backButton.addEventListener('click', function () {
  // Use the history.back() function to navigate back to the previous page
  history.back();
});
</script>
        
            <?php
              }
            ?>
          </div>
          <?php
            // Check if the user is a friend
            $outgoing_id = $_SESSION['unique_id'];
            $friend_check_sql = "SELECT * FROM friends WHERE (user_id1 = '$outgoing_id' AND user_id2 = '$user_id') OR (user_id1 = '$user_id' AND user_id2 = '$outgoing_id')";
            $friend_check_result = mysqli_query($conn, $friend_check_sql);

            if(mysqli_num_rows($friend_check_result) > 0) {
              // User is a friend, display chat form
          ?>
              <form action="#" class="typing-area">
                <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
                <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                <button><i class="fab fa-telegram-plane"></i></button>
              </form>
          <?php
            }
          ?>
      <?php
        } else {
          header("location: users.php");
        }
      ?>
    </section>
  </div>

  <script src="javascript/chap.js"></script>

</body>
</html>
