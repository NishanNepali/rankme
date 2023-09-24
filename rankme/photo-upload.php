<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  // Redirect to the login page or display an error message
  header("Location: login.php");
  exit;
}

// Get the logged-in user ID
$userId = $_SESSION['unique_id'];

// Define database credentials
require_once('config.php');
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Define the upload directory
$uploadDir = "photo-album/";

// Generate a unique ID based on the current time and more entropy
$uniqueId = uniqid();

// Generate a random number
$randomNumber = mt_rand(1000, 9999);

// Get the file extension
$fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

// Generate the new image name
$newImageName = "photo_" . $uniqueId . "_" . $randomNumber . "." . $fileExtension;

// Define the target file path
$targetFilePath = $uploadDir . $newImageName;

// Move the uploaded photo to the desired location
if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
  // Determine the image format and compress accordingly
  $compressedFilePath = $uploadDir . 'compressed_' . $newImageName;

  // Check the image format
  $imageFormat = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
  if ($imageFormat === 'jpg' || $imageFormat === 'jpeg') {
    // Compress JPEG image
    compressPhotoJPEG($targetFilePath, $compressedFilePath, 80); // Adjust the compression quality as needed (0-100)
  } elseif ($imageFormat === 'png') {
    // Compress PNG image
    compressPhotoPNG($targetFilePath, $compressedFilePath, 9); // Adjust the compression level as needed (0-9)
  } else {
    // Unsupported image format, handle accordingly
    echo "Unsupported image format.";
    exit;
  }

  // Delete the original photo
  unlink($targetFilePath);

  // Get the caption
  $caption = $_POST['caption'];

  // Insert the photo details into the database
  $insertQuery = "INSERT INTO user_photos (user_id, photo, caption) VALUES ('$userId', '$compressedFilePath', '$caption')";
  if (mysqli_query($conn, $insertQuery)) {
    echo "Photo uploaded successfully.";

    $url = "ownprofile.php?unique_id=$userId";
    header("Location: $url");
  } else {
    echo "Error: " . mysqli_error($conn);
  }
} else {
  echo "Error uploading photo.";
}

// Close database connection
mysqli_close($conn);

/**
 * Compresses the given JPEG photo file using JPEG compression.
 *
 * @param string $sourceFilePath The path to the source photo file.
 * @param string $destinationFilePath The path to save the compressed photo file.
 * @param int $quality The compression quality (0-100).
 */
function compressPhotoJPEG($sourceFilePath, $destinationFilePath, $quality) {
  $sourceImage = imagecreatefromjpeg($sourceFilePath);
  imagejpeg($sourceImage, $destinationFilePath, $quality);
  imagedestroy($sourceImage);
}

/**
 * Compresses the given PNG photo file using PNG compression.
 *
 * @param string $sourceFilePath The path to the source photo file.
 * @param string $destinationFilePath The path to save the compressed photo file.
 * @param int $compressionLevel The compression level (0-9).
 */
function compressPhotoPNG($sourceFilePath, $destinationFilePath, $compressionLevel) {
  $sourceImage = imagecreatefrompng($sourceFilePath);
  imagepng($sourceImage, $destinationFilePath, $compressionLevel);
  imagedestroy($sourceImage);
}
?>
