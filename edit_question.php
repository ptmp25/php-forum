<?php
// edit_question.php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $question_id = $_GET["id"];

    // Fetch the question for editing
    $question_query = "SELECT * FROM questions WHERE id = :question_id";
    $stmt = $pdo->prepare($question_query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the current user is the owner
    if ($_SESSION["user_id"] != $question["user_id"]) {
        echo "You are not allowed to edit this question.";
        exit();
    }

    // Handle the update
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["question_title"]) && isset($_POST["question_content"])) {
        $update_query = "UPDATE questions SET title = :title, content = :content WHERE id = :question_id";
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':title', $_POST["question_title"]);
        $stmt->bindParam(':content', $_POST["question_content"]);
        $stmt->bindParam(':question_id', $question_id);

        if ($stmt->execute()) {
            header("Location: question.php?id=$question_id");
            exit();
        } else {
            echo "Error: Failed to update the question.";
            exit();
        }
    }

} else {
    echo "Invalid question ID";
    exit();
}
?>

<!-- The HTML form for editing the question -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Question</title>
    <?php require("header.php"); ?>
</head>
<body>
    <div class="container">
        <h1>Edit Question</h1>

        <form method="post" action="">
            <label for="question_title">Title:</label>
            <input type="text" id="question_title" name="question_title" value="<?php echo $question['title']; ?>" required>

            <label for="question_content">Content:</label>
            <textarea id="question_content" name="question_content" rows="4" required><?php echo $question['content']; ?></textarea>

            <input type="submit" value="Update Question">
        </form>
    </div>
</body>
</html>