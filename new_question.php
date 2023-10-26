<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$topic_id = $_GET["topic_id"]; // Get the topic_id from the URL

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $user_id = $_SESSION["user_id"];

    // Check if an image file was uploaded
    $image_path = ""; // Default image path if no image was uploaded

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image_dir = "post upload/"; // Directory to store uploaded images
        $image_name = $_FILES["image"]["name"];

        // Generate a unique file name by appending a timestamp
        $timestamp = time();
        $image_name = $timestamp . "_" . $image_name;

        $image_path = $image_dir . $image_name;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            echo "Error: Failed to move uploaded image.";
            exit();
        }
    }

    // Insert the new question into the database
    $insert_query = "INSERT INTO questions (topic_id, title, content, user_id, image_path) VALUES (:topic_id, :title, :content, :user_id, :image_path)";

    try {
        $stmt = $pdo->prepare($insert_query);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':image_path', $image_path);

        if ($stmt->execute()) {
            $question_id = $pdo->lastInsertId(); // Get the question_id of the question that was just inserted
            // Increment the question_count for the user
            $update_count_query = "UPDATE user SET question_count = COALESCE(question_count, 0) + 1 WHERE id = :user_id";
            $update_stmt = $pdo->prepare($update_count_query);
            $update_stmt->bindParam(':user_id', $user_id);
            $update_stmt->execute();

            header("Location: question.php?id=$question_id");
            exit();
        } else {
            echo "Error: Failed to insert question.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>New Question</title>
    <?php include("header.php"); ?>
</head>

<body>
    <div class="container">
        <h1>New Question</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" required><br>
            <label for="content">Content:</label>
            <textarea name="content" rows="4" required></textarea><br>
            <label for="image">Upload Image:</label>
            <input type="file" name="image" accept="image/*"><br>
            <input type="submit" name="submit" value="Post Question">
        </form>
        <a href="topic.php?id=<?php echo htmlspecialchars($topic_id); ?>">Back to Topic</a>
    </div>
</body>

</html>