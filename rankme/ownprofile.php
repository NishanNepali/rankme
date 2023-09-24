<?php
session_start();

// Check if unique_id is specified
if (!isset($_GET['unique_id'])) {
  // Redirect the user back to the homepage if no unique_id is specified
  header('Location: index.php');
  exit;
}

require('config.php');

// Get unique_id from GET parameter
$unique_id = $_GET['unique_id'];
$userId = $_SESSION['unique_id'];

// Retrieve user details from database
$query = "SELECT fname, bio, lname, img, resized_photo, facebook_link, instagram_link, tiktok_link FROM users WHERE unique_id = '$unique_id'";
$result = mysqli_query($conn, $query);
if (!$result) {
  // handle error
  die("Query failed: " . mysqli_error($conn));
}
$row = mysqli_fetch_assoc($result);
if (!$row) {
  // handle error
  die("No results found for unique ID: " . $unique_id);
}

$username = $row['fname'] . " " . $row['lname'];
$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$photo = $row['resized_photo'];
$orgphoto = $_SESSION['photo'];
$facebookLink = $row['facebook_link'];
$instagramLink = $row['instagram_link'];
$tiktokLink = $row['tiktok_link'];
$bio = $row['bio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // retrieve email address from session
  $email = $_SESSION['email'];
  $user_Id = $_SESSION['unique_id'];

  // retrieve new username from form submission
  $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);

  // send email address and new username to update_username.php file
  $_SESSION['email'] = $email; // Store email in session
  $url = "update_username.php";
  header("Location: $url");
  exit;
}
else {
  // Check if the unique_id in the URL matches the logged-in user's unique_id
  if ($userId != $unique_id) {
    // Redirect to the logged-in user's own profile page
    header("Location: ownprofile.php?unique_id=$userId");
    exit;
  }
}
?>

<!-- Rest of your HTML code goes here -->

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $username ?></title>
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
<link rel="manifest" href="imgs/site.webmanifest">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



    <style>

        body {
  background-color: #333;
  

}

/* CSS for the responsive design */
#alll {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.profile {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
}

.back{
  float: left;
  font-size: 40px;
  color: black;
}
.profilepic {
  width: 300px;
  height: 300px;
  border-radius: 50%;
}

.edit {
  text-align: center;
  margin-top: 10px;
}

.profilename {
  font-size: 30px;
  margin-top: 10px;
  text-transform: uppercase;
}

.bio-show {
  margin-top: 10px;
}

.editlogo {
  font-size: 20px;
  margin: 10px;
  background-color: blueviolet;
  color: white;
  border: 0px;
  border-radius: 20px;
  padding: 10px;
  
}

/* CSS for responsiveness */
@media (max-width: 768px) {
  .profilepic {
    width: 200px;
    height: 200px;
  }

  .profilename {
    font-size: 20px;
  }

  .editlogo {
    font-size: 18px;
  }
}

@media (max-width: 480px) {
  .profilepic {
    width: 120px;
    height: 120px;
  }

  .profilename {
    font-size: 16px;
  }

  .editlogo {
    font-size: 18px;
  }
}
#changes {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 500px;
  height: 500px;
  background-color: #a79f9f;
  display: none;
  z-index: 1;
  overflow: hidden;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.cancel-button {
  margin-bottom: 20px;
  padding: 10px 20px;
  background-color: #ccc;
  color: #333;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.cancel-button:hover {
  background-color: #999;
  color: #fff;
}

.photos {
  margin-top: 20px;
}

.upload-section {
  position: relative;
  display: inline-block;
}

.upload-button-photo {
  display: inline-block;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  border: 2px solid black;
  background-color: #1877f2;
  color: #fff;
  cursor: pointer;
  text-align: center;
  line-height: 150px;
  font-size: 24px;
}

#photo {
  display: none;
}

.photo-preview {
  display: none;
  margin-top: 10px;
  text-align: center;
}

#preview {
  max-width: 100%;
  max-height: 400px;
  border-radius: 4px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
}

.upload-photo-button {
  display: none;
  margin-top: 10px;
  padding: 10px 15px;
  background-color: #1877f2;
  color: #fff;
  cursor: pointer;
}

.username {
  margin-top: 20px;
}

.username-label {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.username-text {
  font-size: 18px;
  margin-right: 10px;
}

.edit-username-button {
  font-size: 16px;
  padding: 5px;
  border: none;
  background-color: transparent;
  cursor: pointer;
}

.username-fields {
  display: none;
}

.username-fields.active {
  display: block;
}

.username-field {
  margin-bottom: 10px;
}

.username-field label {
  font-size: 16px;
}

.username-field input[type="text"] {
  width: 100%;
  padding: 8px;
  font-size: 16px;
}

.username-actions {
  margin-top: 10px;
}

.save-username-button {
  padding: 8px 16px;
  font-size: 16px;
  background-color: #1877f2;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.cancel-username-button {
  padding: 8px 16px;
  font-size: 16px;
  background-color: #e9ebee;
  color: #333;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.bio-section {
  border: 1px solid #ddd;
  padding: 10px;
  margin-bottom: 20px;
}

.bio-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.edit-button {
  background-color: #1877f2;
  color: #fff;
  padding: 5px 10px;
  border: none;
  cursor: pointer;
}

.current-bio {
  margin-bottom: 10px;
}

.edit-bio {
  width: 100%;
  padding: 5px;
  resize: vertical;
}

.save-button {
  background-color: #1877f2;
  color: #fff;
  padding: 5px 10px;
  border: none;
  cursor: pointer;
}

.save-button {
  display: none;
}

@media screen and (max-width: 768px) {
  #changes {
    width: 90%;
    height: 90%;
    padding: 10px;
  }

  .cancel-button {
    margin-bottom: 10px;
    padding: 8px 16px;
    font-size: 14px;
  }

  .upload-button-photo {
    width: 120px;
    height: 120px;
    line-height: 120px;
    font-size: 20px;
  }

  .photo-preview {
    margin-top: 8px;
  }

  #preview {
    max-height: 300px;
  }

  .upload-photo-button {
    margin-top: 8px;
    padding: 8px 12px;
    font-size: 14px;
  }

  .username-label {
    margin-bottom: 8px;
  }

  .username-text {
    font-size: 16px;
    margin-right: 8px;
  }

  .edit-username-button {
    font-size: 14px;
    padding: 4px;
  }

  .username-field input[type="text"] {
    padding: 6px;
    font-size: 14px;
  }

  .save-username-button {
    padding: 6px 12px;
    font-size: 14px;
  }

  .cancel-username-button {
    padding: 6px 12px;
    font-size: 14px;
  }

  .edit-button {
    padding: 4px 8px;
    font-size: 14px;
  }

  .edit-bio {
    padding: 3px;
  }

  .save-button {
    padding: 4px 8px;
    font-size: 14px;
  }
}



      </style>
</head>
<body>
<a href="index.php">
   
  <p class="back">&#8592;</p>

  </a>
<div id="alll">
  
  <div class="profile">
    <a href="<?php echo $orgphoto ?>">
      <img class="profilepic" src="<?php echo $photo ?>" alt="">
    </a>
    <div class="edit">
      <h1 class="profilename" title="<?php echo $username ?>"><?php echo $username ?></h1>
      <div class="bio-show">
        <span><?php echo $bio ?></span>
      </div>
      <a href="#changes">
        <!--
        <p class="editlogo">&#9998;</p>
-->


<button type="button" class="editlogo">Change Info</button>
      </a>
    </div>
  </div>
</div>




<style>
  a{
    text-decoration: none;
    color: black;
  }
    /* Style for the container */
    .container {
      text-align: center;
    }

    /* Style for the social media container */
    .social-container {
      display: inline-block;
      margin-right: 20px;
      text-align: center;
      vertical-align: middle;
    }

    /* Style for the social media icons */
    .social-container i {
      font-size: 40px;
      margin-bottom: 10px;
    }

    /* Style for the social media links */
    .social-container span {
      display: block;
    }

    /* Style for the edit button */
    .edit-link-button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 5px 10px;
      border-radius: 3px;
      cursor: pointer;
    }

    /* Popup styles */
    .popup {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      z-index: 999;
    }

    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
    }

    /* Optional: Style for the form container */
    form {
      margin-top: 20px;
    }

    /* Optional: Style for the save button */
    button[type="submit"] {
      margin-top: 10px;
    }
  </style>

<div class="container">
  <div class="social-container">
    <a href="<?php echo $facebookLink; ?>">
    <i class="fab fa-facebook"></i>
  </a>
   
  </div>

  <div class="social-container">
  <a href="<?php echo $tiktokLink ;?>">
    <i class="fab fa-tiktok"></i>
  </a>
  </div>

  <div class="social-container">
  <a target="_parent" href="<?php echo $instagramLink ; ?>">
    <i class="fab fa-instagram"></i>
  </a>
  </div>

  <button type="button" class="edit-link-button">Edit</button>
</div>

  <!-- Popup HTML -->
  <div class="overlay"></div>
  <div class="popup">
    <h2>Edit Links</h2>
    <form method="POST" action="insert_links.php">
      <input type="text" name="facebook_link" placeholder="Facebook Link" value="<?php echo $facebookLink; ?>">
      <input type="text" name="tiktok_link" placeholder="TikTok Link" value="<?php echo $tiktokLink; ?>">
      <input type="text" name="instagram_link" placeholder="Instagram Link" value="<?php echo $instagramLink; ?>">
      <button type="submit">Save</button>
    </form>
  </div>

  <!-- Include Font Awesome CDN JavaScript (optional) -->


  <script>
    // Get the edit button
    const editlinkButton = document.querySelector('.edit-link-button');

    // Get the popup elements
    const overlay = document.querySelector('.overlay');
    const popup = document.querySelector('.popup');

    // Open the popup
    function openPopup() {
      overlay.style.display = 'block';
      popup.style.display = 'block';
    }

    // Close the popup
    function closePopup() {
      overlay.style.display = 'none';
      popup.style.display = 'none';
    }

    // Attach event listener to the edit button
    editlinkButton.addEventListener('click', openPopup);

    // Close the popup when the overlay is clicked
    overlay.addEventListener('click', closePopup);
  </script>








  
<div id="changes">
  <button class="cancel-button" id="cancel">Cancel</button>

  <div class="photos">
    <form method="post" action="update_photo.php" enctype="multipart/form-data">
      <div class="upload-section">
        <p id="update-message">Click to update photo here</p>
        <label for="photo" class="upload-button-photo" style="background: none;"></label>
        <input type="file" id="photo" name="new_photo" onchange="previewImage()" required>
      </div>
      <div class="photo-preview">
        <img id="preview" alt="Preview" style="display: none;">
      </div>
      <button type="submit" id="uploadPhotoButton" class="upload-photo-button" style="display: none;">Upload</button>
    </form>
  </div>

  <br>
  <br>
  <?php if (isset($_GET['message'])): ?>
  <p><?php echo $_GET['message']; ?></p>
  <?php endif; ?>

  <div class="username">
    <div class="username-label">
      <span class="username-text">Name:</span>
      <button class="edit-username-button">&#9998;</button>
    </div>
    <div class="username-fields">
      <form action="update_username.php" method="post">
        <div class="username-field">
          <label for="new_fname">First Name:</label>
          <input type="text" name="new_fname" id="new_fname" required value="<?php echo $fname; ?>">
        </div>
        <div class="username-field">
          <label for="new_lname">Last Name:</label>
          <input type="text" name="new_lname" id="new_lname" required value="<?php echo $lname; ?>">
        </div>
        <div class="username-actions">
          <input type="submit" value="Save" class="save-username-button">
          <button class="cancel-username-button">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <div class="profile-content">
    <div class="bio-section">
      <div class="bio-header">
        <h3>Bio</h3>
        <button class="edit-button">Edit</button>
      </div>
      <div class="bio-content">
        <p class="current-bio"><?php echo $bio; ?></p>
        <textarea name="bio" rows="4" cols="50" placeholder="Enter your bio" class="edit-bio" style="display: none;"></textarea>
      </div>
      <div class="bio-footer">
        <button class="save-button">Save Bio</button>
      </div>
    </div>
  </div>

</div>

<script>

function previewImage() {
  var uploadButton = document.querySelector('.upload-button-photo');
  var photoInput = document.querySelector('#photo');
  var photoPreview = document.querySelector('#preview');
  var uploadPhotoButton = document.querySelector('.upload-photo-button');

  var file = photoInput.files[0];
  var reader = new FileReader();

  reader.onloadend = function() {
    uploadButton.style.background = "url(" + reader.result + ") no-repeat center center";
    uploadButton.style.backgroundSize = "cover";
    photoPreview.setAttribute('src', reader.result);
    photoPreview.style.display = 'block';
    uploadPhotoButton.style.display = 'block';
  };

  if (file) {
    reader.readAsDataURL(file);
  } else {
    uploadButton.style.background = 'none';
    photoPreview.style.display = 'none';
    uploadPhotoButton.style.display = 'none';
  }
}



</script>


<script>

let editing = document.getElementById('changes');
let editicon = document.querySelector('.editlogo');
let alll = document.getElementById('alll');
let cancelBtn = document.getElementById('cancel');

editicon.addEventListener('click', function() {
  editing.style.display = 'flex';
  alll.classList.add('blur');
  body.style.position = 'fixed';

});

cancelBtn.addEventListener('click', function() {
  editing.style.display = 'none';
  alll.classList.remove('blur');
});



// Get the necessary elements
const editUsernameButton = document.querySelector('.edit-username-button');
const usernameFields = document.querySelector('.username-fields');
const saveUsernameButton = document.querySelector('.save-username-button');
const cancelUsernameButton = document.querySelector('.cancel-username-button');
const newFnameInput = document.querySelector('#new_fname');
const newLnameInput = document.querySelector('#new_lname');
const usernameText = document.querySelector('.username-text');

// Add event listener to the Edit button
editUsernameButton.addEventListener('click', function() {
  // Toggle the visibility of username fields and edit mode
  usernameFields.style.display = 'block';
  editUsernameButton.style.display = 'none';
  usernameText.style.display = 'none';
});

// Add event listener to the Cancel button
cancelUsernameButton.addEventListener('click', function(e) {
  e.preventDefault(); // Prevent form submission

  // Toggle the visibility of username fields and edit mode
  usernameFields.style.display = 'none';
  editUsernameButton.style.display = 'block';
  usernameText.style.display = 'inline';
});

// Add event listener to the Save button
saveUsernameButton.addEventListener('click', function(e) {
  e.preventDefault(); // Prevent form submission

  // Get the updated first name and last name values
  const updatedFname = newFnameInput.value.trim();
  const updatedLname = newLnameInput.value.trim();

  // Send the updated username to the server using AJAX
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'update_username.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.success) {
        // Update the username text dynamically
        usernameText.textContent = 'Name: ' + updatedFname + ' ' + updatedLname;
        usernameFields.style.display = 'none';
        editUsernameButton.style.display = 'block';
        usernameText.style.display = 'inline';
      } else {
        console.error(response.message);
      }
    }
  };
  xhr.send('new_fname=' + encodeURIComponent(updatedFname) + '&new_lname=' + encodeURIComponent(updatedLname));
});


</script>

<script>
  // Get the necessary elements
const editButton = document.querySelector('.edit-button');
const currentBio = document.querySelector('.current-bio');
const editBio = document.querySelector('.edit-bio');
const saveButton = document.querySelector('.save-button');

// Add event listener to the Edit button
editButton.addEventListener('click', function() {
  // Toggle the visibility of bio content and edit mode
  currentBio.style.display = 'none';
  editBio.style.display = 'block';
  saveButton.style.display = 'block';
});

// Add event listener to the Save Bio button
saveButton.addEventListener('click', function() {
  const updatedBio = editBio.value.trim(); // Get the updated bio value
  
  // Send the updated bio to the server using AJAX
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'save-bio.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    if (xhr.status === 200) {
      // Update the bio content dynamically
      currentBio.textContent = updatedBio;
      currentBio.style.display = 'block';
      editBio.style.display = 'none';
      saveButton.style.display = 'none';
    }
  };
  xhr.send('bio=' + encodeURIComponent(updatedBio));
});

</script>



<style>
.photo-upload {
  display: flex;
  flex-direction: row;

  justify-content: center;
  margin-bottom: 20px;
  gap: 28px;
}

.upload-wrapper {
  display: flex;

  
}

.upload-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: #e9ebee;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.upload-button:hover {
  background-color: #d3d6db;
}

.upload-button i {
  font-size: 36px;
  color: #888;
}

.upload-text {
  margin-left: 10px;
  font-size: 14px;
  color: #888;
}

#photo-upload {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  width: 100px;
  height: auto;
  cursor: pointer;
}

.caption-section {
  position: relative;
  margin-bottom: 40px;
}

#caption-input {
  width: 100%;
  height: auto;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ddd;
  resize: none;
}
#selected-image-preview {
  display: flex;
  flex-wrap: wrap;
align-items: center;
  
  justify-content: center;
  margin-top: 20px;
}
#selected-photo-section{
  display: none;
  flex-wrap: wrap;
    justify-content: center;
  margin-top: 20px;
  background-color: black;
}

#selected-photo{
  margin: 0 auto;
  max-width: 200px;

height: auto;

}

#selected-image-preview img {
  max-width: 200px;

  height: auto;
  margin: 10px;
}


.post-button {
  display: block;
  width: 100px;
  height: 60px;
  padding: 10px 20px;

  font-size: 16px;
  background-color: #1877f2;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  
}

.post-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.post-button:hover:not([disabled]) {
  background-color: #166fe5;
}
.note-container {
  text-align: center;
  margin-top: 20px;
}

.note-text {
  color: red;
  font-size: 36px;
}

@media(max-width:720px){
  .note-text {
  color: red;
  font-size: 20px;
}

}

</style>

<div class="note-container">
  <span class="note-text">Note: Only for album and text ❗</span>
</div>


<form action="photo-upload.php" method="POST" enctype="multipart/form-data" class="photo-upload-form">
  <div class="photo-upload">
    <div class="upload-wrapper">
      <div id="upload-button" class="upload-button">
        <i class="fas fa-camera"></i>
        <!--
        <span class="upload-text">Add Photo</span>
-->
      </div>
      <input type="file" id="photo-upload" accept="image/*" name="photo" required />
    </div>
    <div id="caption-section" class="caption-section">
      <textarea id="caption-input" name="caption" placeholder="Write a caption..."></textarea>
    </div>
   
    <button id="post-button" class="post-button" disabled>Post</button>
    </div>
    <div id="selected-photo-section" class="selected-photo-section">
      <img id="selected-photo" src="#" alt="Selected Photo" />
    </div>
 
</form>




<script>
  const uploadButton = document.getElementById('upload-button');
const photoUpload = document.getElementById('photo-upload');
const captionSection = document.getElementById('caption-section');
const captionInput = document.getElementById('caption-input');
const selectedPhotoSection = document.getElementById('selected-photo-section');
const selectedPhoto = document.getElementById('selected-photo');
const postButton = document.getElementById('post-button');

uploadButton.addEventListener('click', () => {
  photoUpload.click();
});

photoUpload.addEventListener('change', () => {
  const file = photoUpload.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (event) => {
      selectedPhoto.src = event.target.result;
    };
    reader.readAsDataURL(file);
    captionSection.style.display = 'block';
   selectedPhotoSection.style.display = 'flex';
    postButton.disabled = false;
  }
});

postButton.addEventListener('click', () => {
  const caption = captionInput.value;
  const photo = selectedPhoto.src;
  
  // Perform necessary actions with the caption and photo
});

</script>

<div class="photo-grid">
  <?php
  
  // Retrieve the user's uploaded photos from the database
  $photosQuery = "SELECT * FROM user_photos WHERE user_id = '$userId'";
  $photosResult = mysqli_query($conn, $photosQuery);


  ?>
    <h1 class="album-name"><?php echo $fname . "'s album"; ?></h1>

<section class="post-list">
  <?php while ($row = mysqli_fetch_assoc($photosResult)) { ?>
    <a href="<?php echo $row['photo']; ?>" class="post">
      <figure class="post-image">
        <img src="<?php echo $row['photo']; ?>" alt="Photo">
      </figure>
      <span class="post-overlay">
        <p>
          <span class="post-likes"><?php echo $row['caption']; ?></span>
        </p>
      </span>
    </a>
  <?php } ?>
</section>

<style>
.album-name{
  text-align: center;
text-transform: uppercase;
}
 .post-list {
 
  display: grid;
  grid-template-columns: repeat(3, minmax(100px, 293px));
  justify-content: center;
  grid-gap: 28px;
}

.post {
  cursor: pointer;
  position: relative;
  display: block;
 
  overflow: hidden; /* Hide any content that exceeds the container */
}

.post-image {
  margin: 0;
  padding-bottom: 100%; /* Maintain a square aspect ratio */
  position: relative;
}

.post-image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensure the image fills the container while maintaining aspect ratio */
}

.post-overlay {
  background: rgba(0, 0, 0, .4);
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  display: none;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
}

.post:hover .post-overlay {
  display: flex;
}

.post-caption {
  text-align: center;
  margin-top: 5px;
  font-size: 14px;
  color: #333;
}

@media screen and (max-width: 768px) {
  .post-list {
    grid-gap: 3px;
  }



.album-name{
  text-align: center;
text-transform: uppercase;
font-size: 20px;
}
}
</style>




</body>
</html>