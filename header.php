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
            padding: 10px 0;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .header a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header a:hover {
            background-color: #555;
        }

        .content {
            padding: 20px;
        }
        .welcome-message {
    font-size: 20px;
    padding: 10px 15px;
    margin-top: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    display: inline-block; /* this will ensure the box wraps tightly around the text */
}

    </style>
</head>
<body>
    <div class="header">
        <a href="homepage.php">Home Page</a> | 
        <a href="account.php">My Account</a> | 
        <a href="member.php">Members</a> | 
        <a href="logout.php">Log Out</a>
    </div>

    <div class="content">
    <?php
    // Check if the user is logged in
if (isset($_SESSION['username'])) {
    echo "<span class='welcome-message'>Welcome, " . $_SESSION['username'] . "</span>";
}

    ?>
    </div>
</body>
</html>
