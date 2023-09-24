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
$resized_photo = isset($_SESSION["resized_photo"]) ? $_SESSION["resized_photo"] : "";
$unique_id = isset($_SESSION["unique_id"]) ? $_SESSION["unique_id"] : "ID not found";
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Email not found";
$thumbnailPhoto = isset($_SESSION["thumbnail_photo"]) ? $_SESSION["thumbnail_photo"] : "Email not found";
$_SESSION['test'] = 'Hello World';

// SESSION SET

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
  
<style>
ul.search-results {
  list-style: none;
  padding: 0;
}

ul.search-results li {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  background-color: burlywood;
  padding: 10px;
}

ul.search-results li a {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #333;

}
a{
    color: white;
}

ul.search-results li img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 10px;
}

ul.search-results li .profile-info {
  display: flex;
  align-items: center;
}

ul.search-results li .username {
  font-weight: bold;
  margin-left: 10px;
}


</style>
</head>
<body>


<?php

require_once ('header-main.php');

?>

<?php
// Check if the query parameter is provided
if (!isset($_GET['query'])) {
    die('Error: Invalid request.');
}

$query = $_GET['query'];

require_once('config.php');

// Create a connection to the database

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Perform the search query
$searchQuery = "SELECT * FROM users WHERE fname LIKE '%$query%' OR lname LIKE '%$query%' OR thumbnail_photo LIKE '%$query%'";
$searchResult = mysqli_query($conn, $searchQuery);

// Check if any results were found
if (mysqli_num_rows($searchResult) > 0) {
    echo "<h2>Search Results for : $query </h2>";
    echo "<ul class='search-results'>";
    while ($row = mysqli_fetch_assoc($searchResult)) {
        $profileId = $row['unique_id'];
        $profileFullName = $row['fname'] . " " . $row['lname'];
        $profilephoto = $row['thumbnail_photo'];
    
        // Display the search result
        echo "<li>";
        echo "<a href='profile?unique_id=$profileId'>";
        echo "<div class='profile-info'>";
        echo "<img src='$profilephoto' alt='Profile Image'>";
        echo "<p class='username'>$profileFullName</p>";
        echo "</div>";
        echo "</a>";
        echo "</li>";
    }
    echo "</ul>";
    
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($conn);
?>


</body>
</html>
