<?php
// Start a session
session_start();

require_once('config.php');

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  die('Error: User not logged in.');
}

// Get the user ID and other parameters
$user_id = $_SESSION['unique_id'];
$other_user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$action = mysqli_real_escape_string($conn, $_GET['type']);

// Check if the action is valid
if ($action !== 'upvote' && $action !== 'downvote') {
  die('Error: Invalid action.');
}

// Check if the user has already voted on this post
$voteCheckQuery = "SELECT * FROM votes WHERE voter_id = '$user_id' AND profile_owner_id = '$other_user_id'";
$voteCheckResult = mysqli_query($conn, $voteCheckQuery);
$hasVoted = mysqli_num_rows($voteCheckResult) > 0;
$voteRow = mysqli_fetch_assoc($voteCheckResult);
$previousVote = isset($voteRow['vote_type']) ? $voteRow['vote_type'] : null;


// Update the votes in the database
if ($hasVoted) {
  // User has already voted
  if ($action === 'upvote' && $previousVote === 'upvote') {
    // User is trying to upvote again, remove the upvote
    $deleteVoteQuery = "DELETE FROM votes WHERE voter_id = '$user_id' AND profile_owner_id = '$other_user_id'";
    mysqli_query($conn, $deleteVoteQuery);

    $upvoteQuery = "UPDATE users SET upvotes = CASE WHEN upvotes > 0 THEN upvotes - 1 ELSE 0 END WHERE unique_id = '$other_user_id'";
    mysqli_query($conn, $upvoteQuery);
  } elseif ($action === 'downvote' && $previousVote === 'downvote') {
    // User is trying to downvote again, remove the downvote
    $deleteVoteQuery = "DELETE FROM votes WHERE voter_id = '$user_id' AND profile_owner_id = '$other_user_id'";
    mysqli_query($conn, $deleteVoteQuery);

    $downvoteQuery = "UPDATE users SET downvotes = CASE WHEN downvotes > 0 THEN downvotes - 1 ELSE 0 END WHERE unique_id = '$other_user_id'";
    mysqli_query($conn, $downvoteQuery);
  } else {
    // User is changing the vote type, update the vote
    $updateVoteQuery = "UPDATE votes SET vote_type = '$action' WHERE voter_id = '$user_id' AND profile_owner_id = '$other_user_id'";
    mysqli_query($conn, $updateVoteQuery);

    // Update the vote count
    if ($action === 'upvote') {
      $upvoteQuery = "UPDATE users SET upvotes = upvotes + 1, downvotes = CASE WHEN downvotes > 0 THEN downvotes - 1 ELSE 0 END WHERE unique_id = '$other_user_id'";
      mysqli_query($conn, $upvoteQuery);
    } elseif ($action === 'downvote') {
      $downvoteQuery = "UPDATE users SET downvotes = downvotes + 1, upvotes = CASE WHEN upvotes > 0 THEN upvotes - 1 ELSE 0 END WHERE unique_id = '$other_user_id'";
      mysqli_query($conn, $downvoteQuery);
    }
  }
} else {
  // User hasn't voted, insert the new vote
  $voteInsertQuery = "INSERT INTO votes (voter_id, profile_owner_id, vote_type) VALUES ('$user_id', '$other_user_id', '$action')";
  mysqli_query($conn, $voteInsertQuery);

  // Update the vote count
  if ($action === 'upvote') {
    $upvoteQuery = "UPDATE users SET upvotes = upvotes + 1 WHERE unique_id = '$other_user_id'";
    mysqli_query($conn, $upvoteQuery);
  } elseif ($action === 'downvote') {
    $downvoteQuery = "UPDATE users SET downvotes = downvotes + 1 WHERE unique_id = '$other_user_id'";
    mysqli_query($conn, $downvoteQuery);
  }
}

// Send notification to the profile owner
$notificationMessage = ($action === 'upvote') ? 'You received an upvote.' : 'You received a downvote.';
$notificationInsertQuery = "INSERT INTO notifications (user_id, message, is_read) VALUES ('$other_user_id', '$notificationMessage', 0)";
mysqli_query($conn, $notificationInsertQuery);

// Get the updated vote difference for the user
$voteDifference = getVoteDifference($conn, $other_user_id);

// Return the updated vote difference as the response
echo $voteDifference;

// Close the database connection
mysqli_close($conn);

function getVoteDifference($conn, $user_id) {
  // Get the vote difference for the user
  $voteQuery = "SELECT upvotes, downvotes FROM users WHERE unique_id = '$user_id'";
  $voteResult = mysqli_query($conn, $voteQuery);
  $voteRow = mysqli_fetch_assoc($voteResult);
  $upvotes = $voteRow['upvotes'];
  $downvotes = $voteRow['downvotes'];

  // Calculate the vote difference
  $voteDifference = $upvotes - $downvotes;

  // Return the vote difference
  return $voteDifference;
}
?>
