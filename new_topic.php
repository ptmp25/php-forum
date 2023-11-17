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
    $content = $_SESSION["content"];

    // Check only for the presence of $title
    if ($title) {
        $query = "INSERT INTO topics (title, user_id, content) VALUES (:title, :user_id, :content)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error creating the module.";
        }
    } else {
        echo "Please fill in the title field.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create New Module</title>
    <?php require("header.php"); ?>
</head>

<body>
    <?php if ($is_admin): ?> <!-- Check if the user is an admin -->
        <h1>Create New Module</h1>
        <div class="container">

            <form method="post" action="">
                <div class="input-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" required><br>
                </div>
                <div class="input-group">
                    <label for="content">Content:</label><br>
                    <textarea type="text" name="content" rows="4" required></textarea> 
                </div>

                <input type="submit" name="submit" class="btn" value="Create Topic">
            </form>
        <?php else: ?>
            <h1>Only admin can create topics.</h1>
        <?php endif; ?>
    </div>
</body>

</html>