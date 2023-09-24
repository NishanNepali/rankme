<?php
session_start();

if (!isset($_SESSION['loggedin']) && !isset($_COOKIE['loggedin'])) {
  header('Location: login.php');
  exit;
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
$photo = isset($_SESSION["photo"]) ? $_SESSION["photo"] : "";
$thumbnailPhoto = isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "";
//$resized_photo = isset($_SESSION["resized_photo"]) ? $_SESSION["resized_photo"] : "";
$unique_id = isset($_SESSION["unique_id"]) ? $_SESSION["unique_id"] : "ID not found";
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Email not found";
$_SESSION['test'] = 'Hello World';

// SESSION SET
//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rankme friends</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
<link rel="manifest" href="imgs/site.webmanifest">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
.friend {
  display: inline-block;
  text-align: center;
  margin: 10px;
  width: 180px;
}

.friend img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 2px solid #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease-in-out;
}

.friend img:hover {
  transform: scale(1.1);
}

.friend-name {
  margin-top: 5px;
  font-weight: bold;
  color: #333;
  font-size: 14px;
}

.friend-link {
  color: #555;
  text-decoration: none;
}

.friend-link:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .friend {
    width: calc(50% - 20px);
  }
  
  @media (max-width: 320px) {
    .friend {
      width: calc(100% - 20px);
    }
  }
}





body {
  font-family: Arial, sans-serif;
  margin: 0;

  background: linear-gradient(45deg, #436b7e, #f3e5f5);
 
  background-attachment: fixed;
}

a{
text-decoration: none;
}

/* Header */
.top {
  background-color: #4267B2;
  padding: 20px;
  display: flex;
 

}

.header {
  color: #fff;
  margin: 0;
}

.logo {
  height: 55px;
  vertical-align: middle;
 
}
.profile-name {
  font-size: 16px;
  font-weight: bold;
  color: #000;

margin: auto;


 
}

.profile-name:hover {
  color: #fff;
  background-color: #000;
}
    </style>
</head>
<body>


<div class="top">
  <div class="header">
  <a href="index.php" onclick="goBack(event);">
  <img class="logo" src="imgs/logo.png" alt="">
</a>

</div>
<script>
  function goBack(event) {
    if (event.ctrlKey || event.metaKey || event.shiftKey || event.which === 2) {
      return; // Ignore the click if it involves a modifier key or mouse middle button
    }

    if (window.history.length > 1) {
      event.preventDefault(); // Prevent the default link behavior
      history.back(); // Redirects the user to the previous page
    }
  }
</script>


<?php
echo"<p class='profile-name'>$fname's friends</p>";
?>
</div>

<?php
// Assuming you have a variable $unique_id representing the logged-in user's ID

require_once('config.php');

// Retrieve the list of distinct friends for the logged-in user
$friendsQuery = "SELECT DISTINCT user_id1, user_id2 FROM friends WHERE user_id1 = '$unique_id' OR user_id2 = '$unique_id'";
$friendsResult = mysqli_query($conn, $friendsQuery);

// Check if any friends are found
if (mysqli_num_rows($friendsResult) > 0) {
  $displayedFriends = array(); // Array to store displayed friend IDs

  while ($row = mysqli_fetch_assoc($friendsResult)) {
    // Get the friend's ID
    $friendId = ($row['user_id1'] == $unique_id) ? $row['user_id2'] : $row['user_id1'];

    // Check if the friend ID has already been displayed
    if (!in_array($friendId, $displayedFriends)) {
      // Retrieve the friend's details from the users table
      $friendQuery = "SELECT * FROM users WHERE unique_id = '$friendId'";
      $friendResult = mysqli_query($conn, $friendQuery);

      if (mysqli_num_rows($friendResult) > 0) {
        $friendRow = mysqli_fetch_assoc($friendResult);
        $friendName = $friendRow['fname'] . " " . $friendRow['lname'];
        $friendNameShort = strlen($friendName) > 10 ? substr($friendName, 0, 10) . "..." : $friendName;
        $friendPhoto = $friendRow['img'];
        $friendLink = "profile.php?unique_id=" . $friendId;

        // Display the friend's photo, name, and link
        echo "<div class='friend'>
                <a href='$friendLink'>
                  <img src='$friendPhoto' alt='$friendName' />
                  <p class='friend-name'>$friendNameShort</p>
                </a>
              </div>";

        // Add the friend ID to the displayed friends array
        $displayedFriends[] = $friendId;
      }
    }
  }
} else {
  // If no friends are found
  echo "<p>No friends found.</p>";
}

// Close the database connection
mysqli_close($conn);
?>



</body>
</html>