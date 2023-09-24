<?php

session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Remove login cookies
setcookie('persistent_token', '', time() - 3600, '/');



// Redirect to login page
header("Location: login.php");
exit();
?>