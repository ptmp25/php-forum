<!DOCTYPE html>
<html>
<head>
    <title>Your Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
        .header a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <center>
            <a href="homepage.php">Home Page</a> | 
            <a href="account.php">My Account</a> | 
            <a href="member.php">Members</a> | 
            <a href="header.php?action=logout">Log Out</a>
        </center>
    </div>
    <div class="content">
    <?php


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Destroy the session
    session_destroy();

    // Redirect to a login page or another page
    header("Location: login.php");
    exit();
}
?>