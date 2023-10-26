<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $question_id = $_GET["id"];

    try {
        // Fetch the question and its details from the database
        $question_query = "SELECT q.title AS question_title, q.content AS question_content, q.image_path AS question_image, q.topic_id, u.username AS posted_by
                          FROM questions AS q
                          JOIN user AS u ON q.user_id = u.id
                          WHERE q.id = :question_id";
        $stmt = $pdo->prepare($question_query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the replies related to the question
        $replies_query = "SELECT r.reply_content AS reply_content, u.username AS replied_by
                        FROM replies AS r
                        JOIN user AS u ON r.user_id = u.id
                        WHERE r.question_id = :question_id";
        $stmt = $pdo->prepare($replies_query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $topic_id = $question['topic_id']; // Get the topic_id from the question details
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid question ID";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Question</title>
</head>
<body>
    <h1><?php echo $question['question_title']; ?></h1>
    <p>Posted by: <?php echo $question['posted_by']; ?></p>
    <p><?php echo $question['question_content']; ?></p>

    <?php if (!empty($question['question_image'])): ?>
        <img src="<?php echo $question['question_image']; ?>" alt="Question Image" width="400">
    <?php endif; ?>

    <h2>Replies</h2>
    <ul>
        <?php foreach ($replies as $reply): ?>
            <li>
                <p>Replied by: <?php echo $reply['replied_by']; ?></p>
                <p><?php echo $reply['reply_content']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Add a reply form -->
    <h2>Post a Reply</h2>
    <form method="post" action="post_reply.php">
        <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
        <textarea name="reply_content" rows="4" cols="50" required></textarea><br>
        <input type="submit" name="submit" value="Post Reply">
    </form>

    <a href="topic.php?id=<?php echo $topic_id; ?>">Back to Topic</a><br>
    <a href="homepage.php">Back to Homepage</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
