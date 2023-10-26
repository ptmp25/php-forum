<?php
require('connect.php'); // Include the PDO database connection file

// Initialize variables to prevent undefined index warnings
$username = $email = $reply_count = $topic_count = $registration_date = $profile_pic = "";

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    try {
        $select_stmt = $pdo->prepare("SELECT username, email, COALESCE(reply_count, 0) AS reply_count, COALESCE(topic_count, 0) AS topic_count, date, COALESCE(profile_pic, 'test.png') AS profile_pic FROM user WHERE username = :username");
        $select_stmt->bindParam(':username', $username);
        $select_stmt->execute();

        if ($select_stmt->rowCount() == 1) {
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            $email = $row['email'];
            $registration_date = $row['date'];
            $profile_pic = $row['profile_pic'];
            $reply_count = $row['reply_count'];
            $topic_count = $row['topic_count'];
        } else {
            echo "User not found.";
            exit; // Stop further execution
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit; // Stop further execution
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("header.php")?>
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        p {
            margin-bottom: 10px;
        }
        img {
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Profile: <?php echo $username; ?></h2>
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture">
        <p>Email: <?php echo $email; ?></p>
        <p>Registration Date: <?php echo $registration_date; ?></p>
        <p>Replies: <?php echo $reply_count; ?></p>
        <p>Topics Created: <?php echo $topic_count; ?></p>
    </div>
</body>
</html>
