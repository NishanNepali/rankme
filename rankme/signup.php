<?php
session_start();

if (isset($_COOKIE['persistent_token'])) {
    header('Location: home.php');
    exit;
}

require_once('config.php');

// Retrieve user input and sanitize the data
$fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
$lname = isset($_POST['lname']) ? trim($_POST['lname']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';

// Check if email already exists
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $row['count'];

// Generate unique_id
$unique_id = mt_rand(100000, 999999);

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Upload the profile picture (if provided)
if (isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];

    // Validate the image file
    $imageInfo = getimagesize($photo['tmp_name']);
    if ($imageInfo === false) {
        $stmt->close();
        $conn->close();
        echo "Invalid image file.";
        exit;
    }

    $allowedExtensions = ["jpeg", "jpg", "png"];
    $allowedTypes = ["image/jpeg", "image/jpg", "image/png"];

    // Get the file extension
    $imgExtension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

    if (!in_array($imgExtension, $allowedExtensions) || !in_array($photo['type'], $allowedTypes)) {
        $stmt->close();
        $conn->close();
        echo "Invalid image format. Please upload a JPEG, JPG, or PNG file.";
        exit;
    }

    $uploadsDirectory = 'uploads/';
    $resizedDirectory = 'resized/';
    $thumbnailDirectory = 'resize_mini/';

    // Generate unique filenames
    $timestamp = time();
    $originalFilename = $timestamp . '_' . $photo['name'];
    $resizedFilename = $timestamp . '_resized_' . $photo['name'];
    $thumbnailFilename = $timestamp . '_thumbnail_' . $photo['name'];
    $originalPath = $uploadsDirectory . $originalFilename;
    $resizedPath = $resizedDirectory . $resizedFilename;
    $thumbnailPath = $thumbnailDirectory . $thumbnailFilename;

    // Move the original image to the uploads directory
    move_uploaded_file($photo['tmp_name'], $originalPath);

    // Determine the image type
    $imageType = exif_imagetype($originalPath);

    if ($imageType === IMAGETYPE_JPEG) {
        // Reduce the quality of the original JPEG image
        $originalImage = imagecreatefromjpeg($originalPath);
        imagejpeg($originalImage, $originalPath, 10);
        imagedestroy($originalImage);
    } elseif ($imageType === IMAGETYPE_PNG) {
        // Compress and save the original PNG image
        $originalImage = imagecreatefrompng($originalPath);
        imagepng($originalImage, $originalPath, 1);
        imagedestroy($originalImage);
    } else {
        // Unsupported image type
        $stmt->close();
        $conn->close();
        echo "Unsupported image format. Please upload a JPEG, JPG, or PNG file.";
        exit;
    }

    // Resize the image to 400x400 pixels for the resized image
    resizeImage($originalPath, $resizedPath, 400, 400);
    // Create a thumbnail version of the image
    resizeImage($originalPath, $thumbnailPath, 50, 50);

    $originalImg = $originalPath;
    $resizedImg = $resizedPath;
    $thumbnailImg = $thumbnailPath;
} else {
    $originalImg = "";
    $resizedImg = "";
    $thumbnailImg = "";
}

// Check if originalImg is not null before inserting into the database
if ($originalImg != "") {
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (user_id, unique_id, fname, lname, email, password, img, resized_photo, thumbnail_photo, status, created_at, gender) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, 'inactive', NOW(), ?)");
    $stmt->bind_param("issssssss", $unique_id, $fname, $lname, $email, $hashedPassword, $originalImg, $resizedImg, $thumbnailImg, $gender);

    if ($stmt->execute()) {
        $persistentToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 month'));
        $stmt = $conn->prepare("INSERT INTO persistent_tokens (unique_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $unique_id, $persistentToken, $expiresAt);
        $stmt->execute();

        // Set the persistent token as a cookie
        setcookie('persistent_token', $persistentToken, strtotime('+1 month'), '/');

        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['unique_id'] = $unique_id;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['img'] = $originalImg;
        $_SESSION['status'] = 'inactive';
        $_SESSION['gender'] = $gender;
        $_SESSION['resized_photo'] = $resizedImg;
        $_SESSION['thumbnail_photo'] = $thumbnailImg;

        // Redirect to home page
        header('location: home.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Function to resize the image
function resizeImage($sourcePath, $destinationPath, $width, $height)
{
    $imageInfo = getimagesize($sourcePath);
    $sourceType = $imageInfo[2];

    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        default:
            echo "Unsupported image format.";
            return;
    }

    $resizedImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, imagesx($sourceImage), imagesy($sourceImage));

    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $destinationPath, 75);
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $destinationPath, 9);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($resizedImage);
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Rank Me</title>
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
<link rel="manifest" href="imgs/site.webmanifest">
    <style>


/* Signup */
html {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}

*, *::after, *::before {
  -webkit-box-sizing: inherit;
          box-sizing: inherit;
}

body {
  font-family: Arial , Sans-serif;
  background-color: #ffa2f7;
  padding: 0;
  margin: 0;
}

.signup {
  margin: 50px auto;
  background: #7a8b94;
  max-width: 430px;
  max-height: 850px;
  padding: 50px;
  color: #3E4A55;

  border-radius: 5px;
  box-shadow: 10px 20px 20px rgba(0, 0, 0, 10), 0 4px 6px rgba(0, 0, 0, 0.1);
}




.signup-text {
  display: block;
  text-align: center;
  font-size: 25px;
}

.signup form {
  margin-top: 50px;
}

.signup form input {
  display: block;
  margin: 20px;
}

.signup form label {
  text-align: left;
  margin: 20px;
}

.signup form input[type=text], .signup form input[type=password], .signup form input[type=email] {
  width: 90%;
  height: 40px;
  border-radius: 2px;
  border: 1px solid #EBEEF0;
  padding: 10px;
}

.signup form .agree {
  margin-left: 25px;
  font-size: 16px;
}

.signup form .agree input[type=checkbox] {
  display: inline;
  margin: 0 !important;
}

.signup form input[type=submit] {
  background-color: #478CCC;
  width: 90%;
  height: 40px;
  color: white;
  border: 1px solid currentColor;
  border-radius: 5px;
}


.signup form .photo-container {
  text-align: center;
  margin-top: 20px;
}

.signup form .photo-container label {
  display: block;
  text-align: left;
  margin-bottom: 5px;
}

.signup form .photo-container input[type="file"] {
  display: none;
}

.signup form .photo-container label.photo-upload-btn {
  display: inline-block;
  background-color: #478CCC;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.signup form .photo-container label.photo-upload-btn:hover {
  background-color: #3366BB;
}

.top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #312323;
  color: #FFF;
  font-family: Arial, sans-serif;
}

.top .logo {
  width:70px;
  height: auto;
}

.top .register {
  display: flex;
  gap: 10px;
}

.top .register a {
  text-decoration: none;
}

.top .register button {
  padding: 10px 15px;
  background-color: #FFF;
  color: #478CCC;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.top .register button:hover {
  background-color: #EBEEF0;
}

.top .register .login {
  background-color: #478CCC;
  color: #FFF;
}

.top .register .login:hover {
  background-color: #3366BB;
}

.top .register .sign {
  background-color: #FFF;
  border: 2px solid #478CCC;
}

.top .register .sign:hover {
  background-color: #EBEEF0;
}



@media(max-width:560px){
    .signup{
        width: 90%;
    }
}
.preview-container {
  display: none;

}

.photo-preview {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border: 2px solid black;
  margin-top: 10px;
}

.preview-container.show {
  display: flex;
  justify-content: center;

}

.gender-container {
  display: flex;
}

.gender-label {
  display: flex;
  align-items: center;
  margin-right: 20px;
  cursor: pointer;
}

.gender-label input[type="radio"] {
  display: none;
}

.checkmark {
  display: inline-block;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 2px solid #555;
  margin-right: 8px;
}

.gender-label:hover .checkmark {
  border-color: #333;
}

.gender-label input[type="radio"]:checked + .checkmark {
  background-color: #555;
}


    </style>
</head>
<body>
<div class="top">
        <a href="index">
         <img class="logo" src="imgs/logo.png" alt="">
        </a>
        <div class="register">
        <a href="login">
            <button class="login" type="button">Login</button>
        </a>
        <a href="signup">
            <button class="sign" type="button">Sign Up</button>
        </a>
    </div>
    </div>
<div class="signup">
  <span class="signup-text">Create  Account</span>
  <form action="signup.php" method="POST" enctype="multipart/form-data" class="signupform">
    <label for="name">First Name:</label>
    <input type="text" name="fname" id="name" required>
    <label for="lastname">Last Name:</label>
    <input type="text" name="lname" id="lastname">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    
    <div class="gender-container">
  <label for="male" class="gender-label">
    <input type="radio" name="gender" id="male" value="male" required>
    <span class="checkmark"></span>
    Male
  </label>
  <label for="female" class="gender-label">
    <input type="radio" name="gender" id="female" value="female" required>
    <span class="checkmark"></span>
    Female
  </label>
</div>


    <div class="photo-container">
  <label for="photo" class="photo-label">Profile Picture:</label>
  <input type="file" name="photo" id="photo" class="photo-input" required>
  <label for="photo" class="photo-upload-btn">Upload Photo</label>
</div>
<div class="preview-container">
  <img id="preview" src="#" alt="Preview" class="photo-preview">
</div>

<script>
// Get the input element, preview image, and preview container
const input = document.getElementById('photo');
const preview = document.getElementById('preview');
const previewContainer = document.querySelector('.preview-container');

// Listen for changes in the input element
input.addEventListener('change', function(event) {
  // Get the selected file
  const file = event.target.files[0];

  // Create a FileReader to read the file
  const reader = new FileReader();

  // Set up the FileReader to load the selected file as an image
  reader.onload = function() {
    preview.src = reader.result;
  }

  // Read the file as a data URL
  reader.readAsDataURL(file);

  // Show or hide the preview container
  if (file) {
    previewContainer.classList.add('show');
  } else {
    previewContainer.classList.remove('show');
  }
});

</script>
    <!--
    <div class="agree">
      <input type="checkbox" name="agree" id="agree"> 
      <label for="agree"><span>I Agree To Terms And Conditions</span></label>
    </div> 
--> 
    
    <input type="submit" value="Sign Up">       
  </form>
</div>







</body>
<script>
    function togglePassword() {
      var x = document.getElementById("password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
    </script>
</html>