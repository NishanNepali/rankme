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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Menu</title>

</head>

<body>
<?php

require_once('header-main.php');

?>
  
  <style>
  .menu-list {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
    background-color: #333;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 10px;
    box-sizing: border-box;
    color: white;
  }

  .menu-item {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    text-align: center;
  }

  .menu-item a {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: wheat;
  }

  .menu-item img {
    width: 50px;
    height: 50px;
    margin-bottom: 5px;
    border-radius: 50%;
  }

  .menu-item p {
    font-size: 14px;
    margin: 0;
  }
</style>

<div class="menu-list">
  <div class="menu-item">
    <a href="ownprofile?unique_id=<?php echo $unique_id; ?>">
      <img src="<?php echo $thumbnailPhoto ?>" alt="Profile Picture">
      <p><?php echo $username; ?></p>
    </a>
  </div>
  <div class="menu-item">
    <a href="friends-list">
      <img src="imgs/visitors.png" alt="rankme friends">
      <p>Friends</p>
    </a>
  </div>
  <div class="menu-item">
    <a href="games/index.html">
      <img src="imgs/games.png" alt="Your Matches">
      <p>Games</p>
    </a>
  </div>
  <div class="menu-item">
    <a href="settings">
      <img src="imgs/settings.png" alt="Settings">
      <p>Settings</p>
    </a>
  </div>
  <div class="menu-item">
    <a href="#" onclick="logout()">
      <img src="imgs/logout.png" alt="Logout">
      <p>Logout</p>
    </a>
  </div>
</div>

<script>
 const btn = document.getElementById('dark-mode-btn');
const body = document.body;

btn.addEventListener('click', function() {
  if (body.classList.contains('dark-mode')) {
    // Toggle off dark mode
    body.classList.remove('dark-mode');
    btn.innerHTML = '<img class="messageicon" src="imgs/darkmode.png" alt="">';
  } else {
    // Toggle on dark mode
    body.classList.add('dark-mode');
    btn.innerHTML = '<img class="messageicon" src="imgs/daymode.png" alt="">';
  }
});

/*
function toggleNav() {
  var navLinks =document.querySelector("dropdown-content");
  var navbar = document.querySelector(".dropbtn");
  dropdown-content.classList.toggle("show");
}
*/

function toggleNav(event) {
  var dropdownContent = document.getElementById("dropdown-content");
  dropdownContent.classList.toggle("show");
  event.stopPropagation();
}

window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}


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
