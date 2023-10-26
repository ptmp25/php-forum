<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["question_id"])) {
    $question_id = $_GET["question_id"];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $reply_content = $_POST["reply_content"];
        $user_id = $_SESSION["user_id"];

        // Insert the new reply into the database
        $insert_query = "INSERT INTO replies (question_id, reply_content, user_id) VALUES (:question_id, :reply_content, :user_id)";
        $stmt = $pdo->prepare($insert_query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':reply_content', $reply_content);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            header("Location: question.php?id=" . $question_id);
            exit();
        } else {
            echo "Error: Failed to insert reply.";
        }
    }
} else {
    echo "Invalid question ID";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Reply</title>
</head>
<body>
    <h1>New Reply</h1>
    <form method="post">
        <label for="reply_content">Reply:</label>
        <textarea name="reply_content" rows="4" required></textarea><br>
        <input type="submit" name="submit" value="Post Reply">
    </form>
    <a href="question.php?id=<?php echo $question_id; ?>">Back to Question</a>
</body>
</html>
