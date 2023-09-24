<?php

// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    header("Location: login.php");
    exit();
}

// Handle account deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    // Check if the entered password matches the user's hashed password
    $enteredPassword = $_POST['password'];
    $hashedPassword = $_SESSION['password'];

    // Verify the entered password against the hashed password
    if (password_verify($enteredPassword, $hashedPassword)) {
        // Password matches, proceed with account deletion
        $userId = $_SESSION['unique_id'];

        // Include your database connection code here
        $dsn = 'mysql:host=localhost;dbname=rankme;charset=utf8mb4';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Begin the transaction
            $pdo->beginTransaction();

  // Get the list of photo filenames for the user
$stmtUserPhotos = $pdo->prepare("SELECT img, resized_photo, thumbnail_photo FROM users WHERE unique_id = ?");
$stmtUserPhotos->execute([$userId]);
$photoFilenames = $stmtUserPhotos->fetchAll(PDO::FETCH_ASSOC);

// Delete the user-uploaded photo files from the server
foreach ($photoFilenames as $row) {
    $originalPhotoPath =$row['img'];
    $compressedPhotoPath =$row['resized_photo'];
    $cutPhotoPath = $row['thumbnail_photo'];

    if (file_exists($originalPhotoPath)) {
        unlink($originalPhotoPath);
    }

    if (file_exists($compressedPhotoPath)) {
        unlink($compressedPhotoPath);
    }

    if (file_exists($cutPhotoPath)) {
        unlink($cutPhotoPath);
    }
}



            // Delete user data from the likes table
            $stmtLikes = $pdo->prepare("DELETE FROM likes WHERE user_id = ?");
            $stmtLikes->execute([$userId]);

            // Delete user data from the comments table
            $stmtComments = $pdo->prepare("DELETE FROM comments WHERE user_id = ?");
            $stmtComments->execute([$userId]);

            // Delete user data from the friend_requests table
            $stmtFriendRequests = $pdo->prepare("DELETE FROM friend_requests WHERE requester_id = ? OR receiver_id = ?");
            $stmtFriendRequests->execute([$userId, $userId]);

            $stmtVotes = $pdo->prepare("DELETE FROM votes WHERE voter_id = ? OR profile_owner_id = ?");
            $stmtVotes->execute([$userId, $userId]);
            
            $stmtNotifications = $pdo->prepare("DELETE FROM notifications WHERE user_id = ?");
            $stmtNotifications->execute([$userId]);

            
            $stmtFriendRequests = $pdo->prepare("DELETE FROM friends WHERE user_id1 = ? OR user_id2 = ?");
            $stmtFriendRequests->execute([$userId, $userId]);

          
            // Delete user data from the posts table
            $stmtPosts = $pdo->prepare("DELETE FROM posts WHERE user_id = ?");
            $stmtPosts->execute([$userId]);

            // Delete user data from the user_photos table
            $stmtUserPhotos = $pdo->prepare("DELETE FROM user_photos WHERE user_id = ?");
            $stmtUserPhotos->execute([$userId]);


            

            // Delete user data from the users table
            $stmtUsers = $pdo->prepare("DELETE FROM users WHERE unique_id = ?");
            $stmtUsers->execute([$userId]);

            // Commit the transaction
            $pdo->commit();

            // Destroy the session
            session_destroy();

            // Remove login cookies
            setcookie('unique_id', '', time() - 3600, '/');

            // Delete the user-uploaded photo files from the server
 
            // Display a confirmation popup and redirect to login page on "OK" click
            echo '
            <script>
                if (confirm("Account deleted successfully! Thank you for using our service.")) {
                    window.location.href = "login.php";
                } else {
                    window.location.href = "login.php";
                }
            </script>
            ';
            exit();
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $pdo->rollBack();
            echo "Database connection error: " . $e->getMessage();
            exit();
        }
    } else {
        // Password doesn't match, show an error message or perform any other desired action
        echo "Incorrect password. Please try again.";
        exit();
    }
}

?>
