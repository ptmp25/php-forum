<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["submit"])) {
    $title = $_POST["title"];
    $user_id = $_SESSION["user_id"];

    // Check only for the presence of $title
    if ($title) {
        $query = "INSERT INTO topics (title, user_id) VALUES (:title, :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            header("Location: homepage.php");
            exit();
        } else {
            echo "Error creating the topic.";
        }
    } else {
        echo "Please fill in the title field.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create New Topic</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Topic</h1>
        
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" name="title" required><br>

            <input type="submit" name="submit" value="Create Topic">
        </form>
        
        <a href="homepage.php">Back to Homepage</a>
    </div>
</body>
</html>
