<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (isset($_POST["submit"]) && $is_admin) { // Only admins can create topics
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
    <?php require("header.php"); ?>
</head>
<body>
    <div class="container">
        <?php if ($is_admin): ?> <!-- Check if the user is an admin -->
            <h1>Create New Topic</h1>
            
            <form method="post" action="">
                <label for="title">Title:</label>
                <input type="text" name="title" required><br>

                <input type="submit" name="submit" value="Create Topic">
            </form>
        <?php else: ?>
            <h1>Only admin can create topics.</h1>
        <?php endif; ?>
        
        <a href="homepage.php">Back to Homepage</a>
    </div>
</body>
</html>
