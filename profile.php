<?php
require('connect.php'); 

$username = $email = $reply_count = $topic_count = $registration_date = $profile_pic = $question_count = ""; 

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    try {
        $select_stmt = $pdo->prepare("SELECT username, email, COALESCE(reply_count, 0) AS reply_count, COALESCE(topic_count, 0) AS topic_count, COALESCE(question_count, 0) AS question_count, date, COALESCE(profile_pic, 'default.png') AS profile_pic FROM user WHERE username = :username");
        $select_stmt->bindParam(':username', $username);
        $select_stmt->execute();

        if ($select_stmt->rowCount() == 1) {
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            $email = $row['email'];
            $registration_date = $row['date'];
            $profile_pic = $row['profile_pic'];
            $reply_count = $row['reply_count'];
            $topic_count = $row['topic_count'];
            $question_count = $row['question_count'];
        } else {
            echo "User not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include("header.php")?>
    <title>User Profile</title>
    <style>
        .container {
            font-family: 'Arial', sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        img {
            display: block;
            margin: 0 auto 20px auto;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #ddd;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Profile: <?php echo $username; ?></h2>
        <img src="profilepic/<?php echo $profile_pic; ?>" alt="Profile Picture">
        <p>Email: <?php echo $email; ?></p>
        <p>Registration Date: <?php echo $registration_date; ?></p>
        <p>Replies: <?php echo $reply_count; ?></p>
        <p>Topics Created: <?php echo $topic_count; ?></p>
        <p>Questions Posted: <?php echo $question_count; ?></p>
    </div>
</body>
</html>
