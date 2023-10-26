<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["submit"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $user_id = $_SESSION["user_id"]; // Retrieve user ID from the session

    if ($title && $content) {
        // Insert the new topic into the 'topics' table
        $query = "INSERT INTO topics (title, content, user_id) VALUES (:title, :content, :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            header("Location: homepage.php"); // Redirect to the homepage after creating the topic
            exit();
        } else {
            echo "Error creating the topic.";
        }
    } else {
        echo "Please fill in both title and content fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Topic</title>
</head>
<body>
    <h1>Create New Topic</h1>
    
    <form method="post" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>
        
        <label for="content">Content:</label>
        <textarea name="content" rows="4" cols="50" required></textarea><br>
        
        <input type="submit" name="submit" value="Create Topic">
    </form>
    
    <a href="homepage.php">Back to Homepage</a>
</body>
</html>
