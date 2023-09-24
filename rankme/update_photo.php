<?php

session_start();

if (!isset($_SESSION['unique_id'])) {
    header('Location: index.php');
    exit;
}

require_once('config.php');

// Get email from session
$email = $_SESSION['unique_id'];

// Upload the new photo (if provided)
if (isset($_FILES['new_photo'])) {
    $new_photo = $_FILES['new_photo'];

    // Check if a file was uploaded
    if ($new_photo['error'] !== UPLOAD_ERR_OK) {
        die("Failed to upload photo.");
    }

    // Validate the image file
    $imageInfo = getimagesize($new_photo['tmp_name']);
    if ($imageInfo === false) {
        die("Invalid image file.");
    }

    $allowedExtensions = ["jpeg", "jpg", "png"];
    $allowedTypes = ["image/jpeg", "image/jpg", "image/png"];

    // Get the file extension
    $new_photo_ext = strtolower(pathinfo($new_photo['name'], PATHINFO_EXTENSION));

    if (!in_array($new_photo_ext, $allowedExtensions) || !in_array($new_photo['type'], $allowedTypes)) {
        die("Invalid image format. Please upload a JPEG, JPG, or PNG file.");
    }

    // Generate unique file names to prevent overwriting existing files
    $original_photo_name = uniqid() . '.' . $new_photo_ext;
    $thumbnail_photo_name = uniqid() . '_thumbnail.' . $new_photo_ext;

    // Move the uploaded file to the photos folder
    $original_photo_path = 'uploads/' . $original_photo_name;
    $thumbnail_photo_path = 'resize_mini/' . $thumbnail_photo_name;

    if (!move_uploaded_file($new_photo['tmp_name'], $original_photo_path)) {
        die("Failed to move uploaded photo.");
    }

    // Generate the thumbnail photo
    createThumbnail($original_photo_path, $thumbnail_photo_path, 50, 50);

    // Resize the image to 400x400 format
    $resized_photo_path = 'resized/' . uniqid() . '_resized.' . $new_photo_ext;
    resizeImage($original_photo_path, $resized_photo_path, 400, 400);

    // Update the photo paths in the database
    $stmt = $conn->prepare("UPDATE users SET img = ?, resized_photo = ?, thumbnail_photo = ? WHERE unique_id = ?");
    $stmt->bind_param("ssss", $original_photo_path, $resized_photo_path, $thumbnail_photo_path, $email);
    if ($stmt->execute()) {
        // Update the photo in the session
        $_SESSION['img'] = $original_photo_path;
        $_SESSION['resized_photo'] = $resized_photo_path;
        $_SESSION['thumbnail_photo'] = $thumbnail_photo_path;

        // Redirect the user back to their profile page
        $url = "ownprofile.php?unique_id=$email&img=$original_photo_path";
        header("Location: $url");
        exit;
    } else {
        die("Failed to update photo: " . $stmt->error);
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
            die("Unsupported image format.");
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

// Function to create a thumbnail image
function createThumbnail($sourcePath, $destinationPath, $thumbnailWidth, $thumbnailHeight)
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
            die("Unsupported image format.");
    }

    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    $thumbnailImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
    imagecopyresampled($thumbnailImage, $sourceImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $sourceWidth, $sourceHeight);

    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumbnailImage, $destinationPath, 75);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumbnailImage, $destinationPath, 9);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($thumbnailImage);
}

?>
