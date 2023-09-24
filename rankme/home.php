<?php
session_start();

if (isset($_COOKIE['persistent_token'])) {
    $_SESSION['loggedin'] = true;
    $persistentToken = $_COOKIE['persistent_token'];

    // Connect to database
    require_once('config.php');

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL statement to select user record with matching token
    $sql = "SELECT * FROM persistent_tokens WHERE token=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind token to SQL statement parameter
    mysqli_stmt_bind_param($stmt, "s", $persistentToken);

    // Execute SQL statement
    mysqli_stmt_execute($stmt);

    // Get query result
    $result = mysqli_stmt_get_result($stmt);

    // Check if there is a matching record
    if (mysqli_num_rows($result) > 0) {
        // Fetch persistent token details from database
        $row = mysqli_fetch_assoc($result);
        $unique_id = $row['unique_id'];

        // Prepare SQL statement to select user record with matching unique_id
        $sql = "SELECT * FROM users WHERE unique_id=?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind unique_id to SQL statement parameter
        mysqli_stmt_bind_param($stmt, "s", $unique_id);

        // Execute SQL statement
        mysqli_stmt_execute($stmt);

        // Get query result
        $result = mysqli_stmt_get_result($stmt);

        // Check if there is a matching record
        if (mysqli_num_rows($result) > 0) {
            // Fetch user details from database
            $row = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION['unique_id'] = $row['unique_id'];
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];
            $_SESSION['photo'] = $row['img'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['resized_photo'] = $row['resized_photo'];
            $_SESSION['thumbnail_photo'] = $row['thumbnail_photo'];
            $_SESSION['gender'] = $row['gender'];
            $_SESSION['password'] = $row['password'];
        }
    }
}

// Rest of your code...

$fname = isset($_SESSION["fname"]) ? $_SESSION["fname"] : "";
$lname = isset($_SESSION["lname"]) ? $_SESSION["lname"] : "";
$username = $fname . " " . $lname; // Concatenate the first name and last name
$photo = isset($_SESSION["photo"]) ? $_SESSION["photo"] : "";
$unique_id = isset($_SESSION["unique_id"]) ? $_SESSION["unique_id"] : "ID not found";
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Email not found";
$thumbnailPhoto = isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "Thumbnail photo not found";

$_SESSION['test'] = 'Hello World';

// Rest of your code...
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rank Me</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
<link rel="manifest" href="imgs/site.webmanifest">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">






  

</head>
<body>



<?php  

require_once('header-main.php');

?>



<?php

require_once('config.php');



// Fetch all user records from the database
$sql = "SELECT unique_id, fname, lname, img FROM users";
$result = mysqli_query($conn, $sql);

// Check if any records were found
if (mysqli_num_rows($result) > 0) {
    // Loop through all records and display their data
    while ($row = mysqli_fetch_assoc($result)) {
        $unique_id = $row['unique_id'];
        $othersusername = $row['fname'] . ' ' . $row['lname'];
       // $othersphoto = $row['img'];
       $othersphoto = $_SESSION['resized_photo'];
      
    }
} else {
    echo "No users found.";
}

// Close database connection
//mysqli_close($conn);
?>





    

<?php
/*

// Retrieve male leaderboard
$maleQuery = "SELECT * FROM new_users WHERE gender = 'male' ORDER BY upvotes DESC";
$maleResult = mysqli_query($conn, $maleQuery);

// Retrieve female leaderboard
$femaleQuery = "SELECT * FROM new_users WHERE gender = 'female' ORDER BY upvotes DESC";
$femaleResult = mysqli_query($conn, $femaleQuery);

*/

// Retrieve male leaderboard ordered by vote difference
$maleQuery = "SELECT *, (upvotes - downvotes) AS vote_difference FROM users WHERE gender = 'male' ORDER BY vote_difference DESC";
$maleResult = mysqli_query($conn, $maleQuery);

// Retrieve female leaderboard ordered by vote difference
$femaleQuery = "SELECT *, (upvotes - downvotes) AS vote_difference FROM users WHERE gender = 'female' ORDER BY vote_difference DESC";
$femaleResult = mysqli_query($conn, $femaleQuery);


//$profileLink = "profile.php?username=" . urlencode($othersusername). "&photo=" . urlencode($othersphoto);

//$fname = isset($_SESSION["fname"]) ? $_SESSION["fname"] : "";
//$lname = isset($_SESSION["lname"]) ? $_SESSION["lname"] : "";

$profileLink = "profile?fname=" . urlencode($fname) . "&lname=" . urlencode($lname) . "&img=" . urlencode($othersphoto);
$othersusername = $fname . " " . $lname; // Concatenate the first name and last name

?>
<style>
  body{
    margin: 0px;
  }
  /* Leaderboard */
  .leaderboard {
    max-width: 80%;
    margin: 0 auto;
  }
  
  .leaderboard h3 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
  }
  
  .top-users {
    display: flex;
    
    margin-bottom: 20px;
  }
  
  .top-users .leaderboard-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
  }
  
  .top-users .leaderboard-item:nth-child(1) {
    background-color: #fcd07a;
    text-align: center;
    width: 35%;
    height: auto;
    
  }


/*

  .top-users .leaderboard-item:nth-child(2) {
    background-color: #9cd3e6;
    text-align: center;
    width: 30%;
    height: auto;
    padding-top: 10px;
    margin-top: 4rem;
  }
  
  .top-users .leaderboard-item:nth-child(3) {
    background-color: #f5a2c3;
    text-align: center;
    width: 25%;
    height: auto;
    margin-top: 6.5rem;
  }


  */


  .top-users .leaderboard-item a {
    text-decoration: none;
    color: #ffffff;
    margin-right: 0px;
   
  }
  
  .top-users .leaderboard-item img {
    width: 80%;
    height: auto;
    border-radius: 50%;
    margin-top: 0 auto;
   margin-right: 0px;
 
  }
  .top-user-1-img{
    animation: glowing-image 1.5s ease-in-out infinite;
  }


  
  .top-users .leaderboard-item img:hover {
  
    transition: 0.5s ease-in-out;
    transform: scale(0.9);

  }
  

  .top-users .leaderboard-item .leaderboard-username {
    font-weight: bold;
    font-size: 30px;
    margin-top: 10px;
  }
  
  .top-users .leaderboard-item .leaderboard-votes {
  
  }
 
  
  .votes-username {
    display: flex;
  }
  
  .upvote-img {
    float: left;
  }
  
  .upvote-img:hover {
    filter: invert(100%);
    -webkit-filter: invert(100%);
  }
  
  .downvote-img {
    float: right;
  }
  
  .downvote-img:hover {
    filter: invert(100%);
    -webkit-filter: invert(100%);
  }
  
  .positive-votes {
    color: green;
    font-weight: bold;
    font-size: 30px;
  }
  
  .negative-votes {
    color: red;
    font-size: 30px;
  }
  
  h3 {
    font-size: 24px;
    font-weight: bold;
    margin-top: 2rem;
  }
  
  .leaderboard-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .leaderboard-item {
    display: flex;
    align-items: center;
    margin: 1rem 0;
  }
  
  .leaderboard-item img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 1rem;
  }
  
  .leaderboard-username {
    font-size: 18px;
    font-weight: bold;
  }
  
  .other-username {
    margin-right: 0.5rem;
  }
  
  .leaderboard-item a {
    margin-right: 0.5rem;
  }
  
  .other-leaderboard-votes {
    font-size: 16px;
    font-weight: bold;
    margin-left: auto;
  }
  
  .positive-votes {
    color: green;
  }
  
  .negative-votes {
    color: red;
  }
  
  .serial-number {
    font-size: 16px;
    font-weight: bold;
    margin-right: 0.5rem;
  }
  
  .other-upvote {
    margin-left: 0.5rem;
  }
  
  .other-downvote {
    margin-left: 0.5rem;
  }
  
  .leaderboard-switcher img {
    width: 100px;
    height: 100px;
  }
  
  .leaderboard-switcher {
    display: flex;
    justify-content: center;
  }
  
  .leaderboard-item1 {
    position: relative;
  }
  
  .crown-overlay {
    position: absolute;
    top: -200;
    right: 0;
    width: 50px; /* Adjust the width as needed */
    height: auto;
    z-index: 1;
  }
  
  .leaderboard-item2 {
    text-align: center;
    width: 30%;
    height: auto;
    padding-top: 10px;
    margin-top: 4rem;
  }
  
  .leaderboard-item3 {
    text-align: center;
    width: 25%;
    height: auto;
    margin-top: 6.5rem;
  }

 
  @media (max-width: 768px) {
  
    .top-users .leaderboard-item .leaderboard-username {
    font-weight: bold;
    font-size: 9px;
    margin-top: 10px;
  }
  
  .top-users .leaderboard-item .leaderboard-votes {
 font-size: 12px; 
 margin-top: auto;
}

  
}


  @media (max-width: 900px) {
 .upvote-other-btn,
 .downvote-other-btn,  
 .upvote-other-img,
 .downvote-other-img, 
.upvote-img,
.downvote-img{
  display: none;
}

  }


  .upvote-btn {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

.upvote-img {
  opacity: 0.5;
  transition: opacity 0.3s ease;
}

.upvote-img:hover {
  opacity: 1;
}


 .downvote-btn {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

.downvote-img {
  opacity: 0.5;
  transition: opacity 0.3s ease;
}

.downvote-img:hover {
  opacity: 1;
}

.rankme {
  font-size: 24px;
  color: #333;
  margin-bottom: 10px;
  
  font-weight: bold;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  letter-spacing: 2px;
  border: 2px solid black;
  border-left: none;
  border-right: none;
  border-top: none;
  
}


  </style>
  

  <div class="leaderboard">
  <div class="leaderboard-switcher">
    <button id="male-leaderboard-btn" onclick="showMaleLeaderboard()">Male Leaderboard</button>
    <button id="female-leaderboard-btn" onclick="showFemaleLeaderboard()">Female Leaderboard</button>
  </div>



<style>
  #male-leaderboard {
  display: none;
  color: wheat;
  
}

#female-leaderboard {
  display: none;
  color: wheat;
}
.leaderboard-switcher {
  margin-top: 10px;
  text-align: center;
  margin-bottom: 20px;
}

.leaderboard-switcher button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #ffffff;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: background-color 0.3s ease;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
}

.leaderboard-switcher button.active {
  background-color: black;
  animation: glowing 1.5s ease-in-out infinite;
}

.leaderboard-switcher button:hover {
  background-color: #0056b3;
}

.leaderboard-switcher button:focus {
  outline: none;
}

#male-leaderboard-btn {
  margin-right: 10px;
}

#female-leaderboard-btn {
  margin-left: 10px;
}

@media only screen and (max-width: 600px) {
  .leaderboard-switcher button {
    padding: 8px 16px;
    font-size: 12px;
  }
  
  #male-leaderboard-btn {
    margin-right: 5px;
  }
  
  #female-leaderboard-btn {
    margin-left: 5px;
  }
}

@keyframes glowing {
  0% {
    box-shadow: 0 0 2px #fff, 0 0 8px #fff, 0 0 12px #fff, 0 0 20px #795548, 0 0 28px #795548, 0 0 40px #795548, 0 0 60px #795548;
  }

  100% {
    box-shadow: 0 0 2px #fff, 0 0 8px #fff, 0 0 12px #fff, 0 0 20px #795548, 0 0 28px #795548, 0 0 40px #795548, 0 0 60px #795548;
  }
}


@keyframes glowing-image {
  0% {
    box-shadow: 0 0 5px #ffffff,
                0 0 10px #dfdfdf,
                0 0 11px #ffffff,
                0 0 12px #919191,
                0 0 13px #727272,
                0 0 14px #5f5f5f,
                0 0 14px #990000;
  }

  100% {
    box-shadow: 0 0 5px #ffffff,
                0 0 10px #dfdfdf,
                0 0 11px #ffffff,
                0 0 12px #919191,
                0 0 13px #727272,
                0 0 14px #5f5f5f,
                0 0 14px #990000;
  }
}


@keyframes glowing-image-female {
  0% {
    box-shadow: 0 0 5px #ffffff,
                0 0 10px #ffffff,
                0 0 11px #ffffff,
                0 0 12px #ff0000,
                0 0 13px #ff0000,
                0 0 14px #ff0000,
                0 0 15px #ff0000;
  }

  100% {
    box-shadow: 0 0 5px #ffffff,
                0 0 10px #ffffff,
                0 0 11px #ffffff,
                0 0 12px #ff0000,
                0 0 13px #ff0000,
                0 0 14px #ff0000,
                0 0 15px #ff0000;
  }
}



</style>





  
<div class="leaderboard">

 
 <div id="male-leaderboard">
 <h3>Male Leaderboard</h3>
  <div  class="top-users">
    <?php
    $counter = 0;

    while ($maleRow = mysqli_fetch_assoc($maleResult)) {
      $userId = $maleRow['unique_id'];
      $username = $maleRow['fname'] . " " . $maleRow['lname'];
      $upvotes = $maleRow['upvotes'];
      $downvotes = $maleRow['downvotes'];
      $difference = $maleRow['vote_difference'];
      $voteClass = $difference >= 0 ? 'positive-votes' : 'negative-votes';
    

      //$photoQuery = "SELECT img FROM users WHERE unique_id = $userId LIMIT 1";
      $photoQuery = "SELECT resized_photo FROM users WHERE unique_id = $userId LIMIT 1";
      $photoResult = mysqli_query($conn, $photoQuery);
      $photoRow = mysqli_fetch_assoc($photoResult);
    //  $photo = $photoRow ? $photoRow['img'] : 'default.png';
      $photo = $photoRow ? $photoRow['resized_photo'] : 'default.png';

      
      if ($counter === 0) {
      
      echo"  <div class='leaderboard-item leaderboard-item1' data-user-id='$userId' title='$username ranks $counter'>
      <h2 class='rankme'>Mr.Rankme <i class='fas fa-crown'></i></h2>
     
        <a href='profile?unique_id=$userId'>
          <img  class='top-user-1-img'src='$photo' alt='$username photo'  loading='lazy'>
        </a>
        <div class='votes-username'>
          <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-img' src='imgs/upvote.png' alt='Upvote'></button>
          <a href='profile?unique_id=$userId'>
            <span class='leaderboard-username'>$username</span>
          </a>
          <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-img' src='imgs/downvote.png' alt='Downvote'></button>
        </div>
        <span class='leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
      </div>";
      }
      
      
       else if ($counter === 1) {
        echo "<div class='leaderboard-item leaderboard-item2' data-user-id='$userId' title='$username ranks $counter'>
        <h2 class='rankme'>Rankme 2</h2>
  <a href='profile?unique_id=$userId'>
    <img src='$photo' alt='$username photo' loading='lazy'>
  </a>
  <div class='votes-username'>
    <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-img' src='imgs/upvote.png' alt='Upvote'></button>
    <a href='profile?unique_id=$userId'>
      <span class='leaderboard-username'>$username</span>
    </a>
    <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-img' src='imgs/downvote.png' alt='Downvote'></button>
  </div>
  <span class='leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
</div>";

      } else if ($counter === 2) {
        echo "<div class='leaderboard-item leaderboard-item3' data-user-id='$userId' title='$username ranks $counter'>
        <h2 class='rankme'>Rankme 3</h2>
        <a href='profile?unique_id=$userId'>
          <img src='$photo' alt='$username photo' loading='lazy'>
        </a>
        <div class='votes-username'>
          <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-img' src='imgs/upvote.png' alt='Upvote'></button>
          <a href='profile?unique_id=$userId'>
            <span class='leaderboard-username'>$username</span>
          </a>
          <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-img' src='imgs/downvote.png' alt='Downvote'></button>
        </div>
        <span class='leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
      </div>";
      
      } else {
        break;
      }

      $counter++;
    
    }
    ?>
  </div>


  <h3>All Users</h3>
  <ol class="custom-leaderboard-list">
    <?php
    $counter = 4; // Initialize the counter

    while ($maleRow = mysqli_fetch_assoc($maleResult)) {
      $userId = $maleRow['unique_id'];
      $username = $maleRow['fname'] . " " . $maleRow['lname'];
      $upvotes = $maleRow['upvotes'];
      $downvotes = $maleRow['downvotes'];
      $difference = $maleRow['vote_difference'];
      $voteClass = $difference >= 0 ? 'positive-votes' : 'negative-votes';
  
      //$photoQuery = "SELECT img FROM users WHERE unique_id = $userId LIMIT 1";
      $photoQuery = "SELECT thumbnail_photo FROM users WHERE unique_id = $userId LIMIT 1";
      $photoResult = mysqli_query($conn, $photoQuery);
      $photoRow = mysqli_fetch_assoc($photoResult);
     // $photo = $photoRow ? $photoRow['img'] : 'default.png';
     $photo = $photoRow ? $photoRow['thumbnail_photo'] : 'default.png';
     //$photo = $photoRow ? $photoRow['resized_photo'] : 'default.png';
      echo "<li class='custom-leaderboard-item' title='$username ranks $counter'>
        <span class='custom-serial-number'>$counter</span>
        <a href='profile?unique_id=$userId'>
          <img class='custom-other-img' src='$photo' loading='lazy' alt='$username photo'>
        </a>
        <a href='profile?unique_id=$userId'>
          <span class='custom-other-username'>$username</span>
        </a>

        <span class='custom-other-leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
        
        <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-other-img' src='imgs/upvote.png' alt='Upvote'></button>
        <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-other-img' src='imgs/downvote.png' alt='Downvote'></button>
     
      </li>";
      $counter ++;
    }
    ?>
  </ol>

  
  <style>

   .custom-leaderboard-item {
  display: flex;
  align-items: center;
  justify-content: space-between; /* Add this line to create space between elements */
  margin: 1rem 0;
  padding: 1rem;
  background-color: #222;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.custom-serial-number {
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  margin-right: 0.5rem;
}
.upvote-other-btn,
.downvote-other-btn{
  background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.custom-other-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 1rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.custom-other-username {
  font-size: 18px;
  font-weight: bold;
  color: #fff;
  margin-right: 1rem;
}

.custom-other-leaderboard-votes {
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  margin-right: 1rem;
  text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.positive-votes {
  color: #00ff00;
}

.negative-votes {
  color: #ff0000;
}

.upvote-other-btn,
.downvote-other-btn {
  display: flex;
  align-items: center;
}

.upvote-other-img,
.downvote-other-img {
  width: 30px;
  height: 30px;
  margin-left: 0.5rem;
  transition: transform 0.2s ease-in-out;
}

.upvote-other-btn:hover .upvote-other-img {
  transform: scale(1.1);
}

.downvote-other-btn:hover .downvote-other-img {
  transform: scale(1.1);
}

@media(max-width:748px){

  .custom-leaderboard-item {
  display: flex;
  align-items: center;

  padding: 1rem;
  background-color: #222;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}
.custom-other-username {
  font-size: 10px;
  font-weight: bold;
  color: #fff;
  margin-right: 1rem;
}


ol {
    display: block;
    list-style-type: decimal;
    margin-block-start: 0em;
    margin-block-end: 0em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 0px;
}

}

    </style>
    

</div>
</div>
<style>
/* Female Leaderboard */
.female-leaderboard {
  max-width: 80%;
  margin: 0 auto;
}

.female-leaderboard h3 {
  text-align: center;
  font-size: 24px;
 
}

.female-leaderboard .top-users {
  display: flex;
  justify-content: center;

 
}

.female-leaderboard .leaderboard-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
}

.female-leaderboard .leaderboard-item1 {
  background-color: #fcd07a;
  text-align: center;
  width: 35%;
  height: auto;
}

.female-leaderboard .leaderboard-item2 {
  background-color: #9cd3e6;
  text-align: center;
  width: 30%;
  height: auto;
  padding-top: 10px;
  margin-top: 4rem;
}

.female-leaderboard .leaderboard-item3 {
  background-color: #f5a2c3;
  text-align: center;
  width: 25%;
  height: auto;
  margin-top: 6.5rem;
}

.female-leaderboard .leaderboard-item a {
  text-decoration: none;
  color: #333;
  margin-right: 0px;
}

.female-leaderboard .leaderboard-item img {
  width: 80%;
  height: auto;
  border-radius: 50%;
  margin-top: 0 auto;
  margin-right: 0px;
}

.female-leaderboard .leaderboard-item img:hover {
  transition: 0.5s ease-in-out;
  transform: scale(0.9);
}

.female-leaderboard .leaderboard-item .votes-username {
  display: flex;
}

.female-leaderboard .leaderboard-item .upvote-img,
.female-leaderboard .leaderboard-item .downvote-img {
  float: left;
}

.female-leaderboard .leaderboard-item .upvote-img:hover,
.female-leaderboard .leaderboard-item .downvote-img:hover {
  filter: invert(100%);
  -webkit-filter: invert(100%);
}

.female-leaderboard .leaderboard-item .leaderboard-username {
  font-weight: bold;
  font-size: 30px;
  margin-top: 10px;
}

.female-leaderboard .leaderboard-item .leaderboard-votes {
  margin-top: auto;
}

.female-leaderboard .leaderboard-item .positive-votes {
  color: green;
  font-size: 30px;
}

.female-leaderboard .leaderboard-item .negative-votes {
  color: red;
  font-size: 30px;
}

.female-leaderboard .leaderboard-item1 .leaderboard-votes {
 
}

.female-leaderboard .leaderboard-item2 .leaderboard-votes {

}

.female-leaderboard .leaderboard-item3 .leaderboard-votes {
 
}

.female-leaderboard h3 {
  font-size: 24px;
  font-weight: bold;
  margin-top: 2rem;
}

.female-leaderboard .leaderboard-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.female-leaderboard .leaderboard-list-item {
  display: flex;
  align-items: center;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
  background-color: #f0f0f0;
}

.female-leaderboard .leaderboard-list-item img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 10px;
}

.female-leaderboard .leaderboard-list-item .username {
  font-weight: bold;
}

.female-leaderboard .leaderboard-list-item .votes {
  margin-left: auto;
  font-weight: bold;
}

.female-leaderboard .leaderboard-list-item .positive-votes {
  color: green;
}

.female-leaderboard .leaderboard-list-item .negative-votes {
  color: red;
}

  /* Leaderboard Item 3 */
.leaderboard-item.leaderboard-item3 {
  background: linear-gradient(to bottom, sienna,  #333);
}

/* Top Users Leaderboard Item Styles */
.top-users .leaderboard-item {
  /* Existing styles */
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.top-users .leaderboard-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.top-users .leaderboard-item:nth-child(1) {
  background: linear-gradient(to bottom, rgb(25, 156, 196), rgb(175, 32, 218));
 
  
}

.top-users .leaderboard-item:nth-child(2) {
  background: linear-gradient(to bottom, silver, rgb(100, 99, 99));
}

/*
.top-users .leaderboard-item:nth-child(3) {
  background: linear-gradient(to right,  #000000 #370018);
}

*/
/* Mobile Responsive Styles */
@media (max-width: 768px) {


  .female-leaderboard {
  max-width: 95%;
  margin: 0 auto;
}

  
  .female-leaderboard .top-users .leaderboard-item img {
    width: 80%;
    margin-top: 10px;
  }
  
  .female-leaderboard .top-users .leaderboard-item .leaderboard-username {
    font-size: 9px; /* Adjust the font size for mobile */
    margin-top: 10px;
  }
  
  .female-leaderboard .top-users .leaderboard-item .leaderboard-votes {
    font-size: 12px; /* Adjust the font size for mobile */
  }
  
  .female-leaderboard .votes-username {
    justify-content: center;
  }
  
  .female-leaderboard .upvote-img,
  .female-leaderboard .downvote-img {
    width: 16px;
    height: 16px;
    display: none; /* Hide voting images on mobile */
  }
  
  .female-leaderboard .positive-votes,
  .female-leaderboard .negative-votes {
    font-size: 12px; /* Adjust the font size for mobile */
  }
}

@media (max-width: 900px) {
  .female-leaderboard .upvote-img,
  .female-leaderboard .downvote-img {
    display: none; /* Hide voting images on mobile */
  }
}

.fa-crown {
  color: gold;
  font-size: 18px;
}

/* Queen Crown Styles */
.fa-chess-queen {
  color: blueviolet;
  font-size: 18px;
}

.top-user-1-female-img{
  animation: glowing-image-female 1.5s ease-in-out infinite;
}

</style>

<div class="fe-lead">

<div id="female-leaderboard" class="female-leaderboard">
  <h3>Female Leaderboard</h3>
  <div class="top-users">
    <?php
    $counter = 0;

    while ($femaleRow = mysqli_fetch_assoc($femaleResult)) {
      $userId = $femaleRow['unique_id'];
      $username = $femaleRow['fname'] . " " . $femaleRow['lname'];
      $upvotes = $femaleRow['upvotes'];
      $downvotes = $femaleRow['downvotes'];
      $difference = $femaleRow['vote_difference'];
      $voteClass = $difference >= 0 ? 'positive-votes' : 'negative-votes';

      //$photoQuery = "SELECT img FROM users WHERE unique_id = $userId LIMIT 1";
      $photoQuery = "SELECT resized_photo FROM users WHERE unique_id = $userId LIMIT 1";
      $photoResult = mysqli_query($conn, $photoQuery);
      $photoRow = mysqli_fetch_assoc($photoResult);
      //$photo = $photoRow ? $photoRow['img'] : 'default.png';
      $photo = $photoRow ? $photoRow['resized_photo'] : 'default.png';
      if ($counter === 0) {
        echo "<div class='leaderboard-item leaderboard-item1' data-user-id='$userId' title='$username ranks $counter'>
        <h2 class='rankme'>Mrs.Rankme <i class='fas fa-chess-queen'></i></h2>
          <a href='profile?unique_id=$userId'>
          
            <img class='top-user-1-female-img' src='$photo' alt='$username photo' loading='lazy'>
          </a>
          <div class='votes-username'>
          <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-img' src='imgs/upvote.png' alt='Upvote'></button>
            <a href='profile?unique_id=$userId'>
              <span class='leaderboard-username'>$username</span>
            </a>
            <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-img' src='imgs/downvote.png' alt='Downvote'></button>
          </div>
          <span class='leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
        </div>";
      } else if ($counter === 1) {
        echo "<div class='leaderboard-item leaderboard-item2' data-user-id='$userId' title='$username ranks $counter'>
        <h2 class='rankme'>Rankme 2</h2> 
        <a href='profile?unique_id=$userId'>
            <img src='$photo' alt='$username photo' loading='lazy'>
          </a>
          <div class='votes-username'>
          <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-img' src='imgs/upvote.png' alt='Upvote'></button>
            <a href='profile?unique_id=$userId'>
              <span class='leaderboard-username'>$username</span>
            </a>
            <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-img' src='imgs/downvote.png' alt='Downvote'></button>
          </div>
          <span class='leaderboard-votes $voteClass'  id='vote-result-$userId'>$difference</span>
        </div>";
      } else if ($counter === 2) {
        echo "<div class='leaderboard-item leaderboard-item3 data-user-id='$userId'' title='$username ranks $counter'>
        <h2 class='rankme'>Rankme 3</h2>
        <a href='profile?unique_id=$userId'>
            <img src='$photo' alt='$username photo' loading='lazy'>
          </a>
          <div class='votes-username'>
          <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-img' src='imgs/upvote.png' alt='Upvote'></button>
            <a href='profile?unique_id=$userId'>
              <span class='leaderboard-username'>$username</span>
            </a>
            <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-img' src='imgs/downvote.png' alt='Downvote'></button>
          </div>
          <span class='leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
        </div>";
      } else {
        break;
      }

      $counter++;
    }
    ?>
  </div>



<h3>All Users</h3>
<ol class="leaderboard-list">
  <?php
  $counter = 4;
  while ($femaleRow = mysqli_fetch_assoc($femaleResult)) {
    $userId = $femaleRow['unique_id'];
    $username = $femaleRow['fname'] . " " . $femaleRow['lname'];
    $upvotes = $femaleRow['upvotes'];
    $downvotes = $femaleRow['downvotes'];
    $difference = $femaleRow['vote_difference'];
    $voteClass = $difference >= 0 ? 'positive-votes' : 'negative-votes';

    //$photoQuery = "SELECT img FROM users WHERE unique_id = $userId LIMIT 1";
    $photoQuery = "SELECT thumbnail_photo FROM users WHERE unique_id = $userId LIMIT 1";
    $photoResult = mysqli_query($conn, $photoQuery);
    $photoRow = mysqli_fetch_assoc($photoResult);
   // $photo = $photoRow ? $photoRow['img'] : 'default.png';
   $photo = $photoRow ? $photoRow['thumbnail_photo'] : 'default.png';
   //$photo = $photoRow ? $photoRow['resized_photo'] : 'default.png';
   echo "<li class='custom-leaderboard-item' title='$username ranks $counter'>
   <span class='custom-serial-number'>$counter</span>
   <a href='profile?unique_id=$userId'>
     <img class='custom-other-img' src='$photo' loading='lazy' alt='$username photo'>
   </a>
   <a href='profile?unique_id=$userId'>
     <span class='custom-other-username'>$username</span>
   </a>

   <span class='custom-other-leaderboard-votes $voteClass' id='vote-result-$userId'>$difference</span>
   <button class='upvote-btn' onclick='vote(\"$userId\", \"upvote\")'><img class='upvote-other-img' src='imgs/upvote.png' alt='Upvote'></button>
   <button class='downvote-btn' onclick='vote(\"$userId\", \"downvote\")'><img class='downvote-other-img' src='imgs/downvote.png' alt='Downvote'></button>
 </li>";
 $counter ++;

   
  }
  ?>
</ol>
</div>
</div>


<script>
  // Function to show the male leaderboard and hide the female leaderboard
function showMaleLeaderboard() {
  document.getElementById('male-leaderboard').style.display = 'block';
  document.getElementById('female-leaderboard').style.display = 'none';
  document.getElementById('male-leaderboard-btn').classList.add('active');
  document.getElementById('female-leaderboard-btn').classList.remove('active');

  // Store the selected leaderboard in localStorage
  localStorage.setItem('selectedLeaderboard', 'male');
}

// Function to show the female leaderboard and hide the male leaderboard
function showFemaleLeaderboard() {
  document.getElementById('male-leaderboard').style.display = 'none';
  document.getElementById('female-leaderboard').style.display = 'block';
  document.getElementById('female-leaderboard-btn').classList.add('active');
  document.getElementById('male-leaderboard-btn').classList.remove('active');


  // Store the selected leaderboard in localStorage
  localStorage.setItem('selectedLeaderboard', 'female');
}

// Function to display the leaderboard based on user's gender or the previously selected leaderboard
function displayLeaderboardByGender(userGender) {
  var selectedLeaderboard = localStorage.getItem('selectedLeaderboard');

  if (selectedLeaderboard === 'male') {
    showMaleLeaderboard();
  } else if (selectedLeaderboard === 'female') {
    showFemaleLeaderboard();
  } else {
    // No leaderboard preference found, use user's gender
    if (userGender === 'male') {
      showMaleLeaderboard();
    } else if (userGender === 'female') {
      showFemaleLeaderboard();
    }
  }
}

// Get the user's gender from PHP and call the displayLeaderboardByGender function
<?php
$userGender = $_SESSION['gender'];
echo "displayLeaderboardByGender('$userGender')";
?>

</script>


<script>
function vote(userId, action) {
  var voteResultElement = document.getElementById('vote-result-' + userId);
  var upvoteButton = document.querySelector('.upvote-btn');
  var downvoteButton = document.querySelector('.downvote-btn');
  
  // Disable the vote buttons if already clicked
  if (upvoteButton.disabled || downvoteButton.disabled) {
    return;
  }
  
  // Disable the vote buttons
  upvoteButton.disabled = true;
  downvoteButton.disabled = true;

  // Send an AJAX request to the server to increment the upvotes or downvotes and update the voted status
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'vote.php?user_id=' + userId + '&type=' + action);
  xhr.onload = function() {
    if (xhr.status === 200) {
      console.log(xhr.responseText); // Display the response from the server

      // Update the vote result on the page
      var voteDifference = parseInt(xhr.responseText);
      if (!isNaN(voteDifference)) {
        voteResultElement.textContent = voteDifference;
      } else {
        voteResultElement.textContent = 'Error';
      }
    }

    // Enable the vote buttons after the AJAX request is completed
    upvoteButton.disabled = false;
    downvoteButton.disabled = false;
  };
  xhr.send();
}
</script>

<style>

@media (max-width: 768px) {
  
  .leaderboard {
    max-width: 95%;
    
  }
  
 
  
.rankme {
  font-size: 14px;
  color: #333;
  margin-bottom: 10px;

  font-weight: bold;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  letter-spacing: 2px;
  border: 2px solid black;
  border-left: none;
  border-right: none;
  border-top: none;
  
}
}

@media (max-width: 460px){
  .rankme{
    font-size: 7px;
  }
}


</style>



</body>

</html>