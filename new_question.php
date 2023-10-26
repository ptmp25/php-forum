<?php
session_start();
require_once("connect.php");
$topic_id = $_GET["topic_id"]; // Get the topic_id from the URL
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $user_id = $_SESSION["user_id"];
   
    // Check if an image file was uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image_dir = "post upload/"; // Directory to store uploaded images
        $image_name = $_FILES["image"]["name"];

        // Generate a unique file name by appending a timestamp
        $timestamp = time();
        $image_name = $timestamp . "_" . $image_name;

        $image_path = $image_dir . $image_name;

        // Move the uploaded image to the designated folder with the unique file name
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            echo "Error: Failed to move uploaded image.";
            exit();
        }
    } else {
        $image_path = ""; // Set a default image path if no image was uploaded
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
            header("Location: topic.php?id=" . $topic_id);
            exit();
        } else {
            echo "Error: Failed to insert question.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Rest of your HTML and form here
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Question</title>
</head>
<body>
    <h1>New Question</h1>
    <form method="post" enctype="multipart/form-data"> <!-- Add enctype attribute for file upload -->
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>
        <label for="content">Content:</label>
        <textarea name="content" rows="4" required></textarea><br>
        <label for="image">Upload Image:</label>
        <input type="file" name="image" accept="image/*"><br>
        <input type="submit" name="submit" value="Post Question">
    </form>
    <a href="topic.php?id=<?php echo $topic_id; ?>">Back to Topic</a>


</body>
</html>
