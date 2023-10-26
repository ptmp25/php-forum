<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Destroy the session
    session_destroy();
}

// Redirect to the login page or another page
header("Location: login.php");
exit();
?>
