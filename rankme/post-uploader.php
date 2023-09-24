<?php
session_start();
require_once('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $userId = $_SESSION["unique_id"]; // Assuming you have a user authentication system
  $caption = $_POST["caption"];

  // Check if the "visibility" field exists in the $_POST array
  $visibility = isset($_POST["visibility"]) ? $_POST["visibility"] : null;

  // Handle the uploaded photo
  $photoFilename = null;
  if ($_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
    $photoTempPath = $_FILES["photo"]["tmp_name"];
    $photoFilename = $_FILES["photo"]["name"];

    // Generate a unique filename for the uploaded photo
    $photoExtension = pathinfo($photoFilename, PATHINFO_EXTENSION);
    $uniqueFilename = uniqid() . "." . $photoExtension;
    $photoDestination = "posts/" . $uniqueFilename;

    // Compress and move the uploaded photo to the destination directory
    if (strtolower($photoExtension) === "jpeg" || strtolower($photoExtension) === "jpg") {
      $compressedImageQuality = 20; // Adjust the compression quality as per your requirement (0-100)
      $sourceImage = imagecreatefromjpeg($photoTempPath);
      imagejpeg($sourceImage, $photoDestination, $compressedImageQuality);
    } elseif (strtolower($photoExtension) === "png") {
      $compressedImageLevel = 5; // Adjust the compression level as per your requirement (0-9)
      $sourceImage = imagecreatefrompng($photoTempPath);

      // Enable compression
      imagepalettetotruecolor($sourceImage);
      imagealphablending($sourceImage, true);
      imagesavealpha($sourceImage, true);

      // Compress and save the image
      imagepng($sourceImage, $photoDestination, $compressedImageLevel);
    } else {
      // Unsupported image format
      echo "Unsupported image format. Please upload a JPEG or PNG image.";
      exit;
    }

    imagedestroy($sourceImage);

    // Determine the visibility value based on user selection
    $visibilityValue = ($visibility === "friends") ? 1 : 2;

    // Insert the post data into the database
    $sql = "INSERT INTO posts (user_id, caption, photo_filename, visibility) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $userId, $caption, $uniqueFilename, $visibilityValue);
    $stmt->execute();

    // Check if the post was successfully inserted
    if ($stmt->affected_rows > 0) {
      // Post inserted successfully
      echo "Post uploaded successfully!";
      
      header('location:post.php');
    } else {
      // Failed to insert the post
      echo "Failed to upload post. Please try again.";
    }
    $stmt->close();
  } else {
    // Error in the uploaded photo
    echo "Error occurred while uploading the photo. Please try again.";
  }
}
?>
