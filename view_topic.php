<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $topic_id = $_GET["id"];

    // Fetch the topic details from the 'topics' table
    $query = "SELECT title, content FROM topics WHERE id = :topic_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $topic = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch replies associated with the topic, including the username
    $query = "SELECT r.reply_content, u.username FROM replies r
              JOIN user u ON r.user_id = u.id
              WHERE r.topic_id = :topic_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Invalid topic ID";
    exit();
}

// Handle posting replies
if (isset($_POST["submit_reply"])) {
    $reply_content = $_POST["reply_content"];
    $user_id = $_SESSION["user_id"];

    if ($reply_content) {
        // Insert the reply into the 'replies' table
        $query = "INSERT INTO replies (topic_id, user_id, reply_content) VALUES (:topic_id, :user_id, :reply_content)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':reply_content', $reply_content);

        if ($stmt->execute()) {
            // Redirect back to the topic after posting the reply
            header("Location: view_topic.php?id=$topic_id");
            exit();
        } else {
            echo "Error posting the reply.";
        }
    } else {
        echo "Please fill in the reply field.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Topic - <?php echo $topic['title']; ?></title>
</head>
<body>
    <h1>Topic: <?php echo $topic['title']; ?></h1>
    <p><?php echo $topic['content']; ?></p>

    <h2>Replies</h2>
    <ul>
        <?php foreach ($replies as $reply): ?>
            <li>
                <p><?php echo $reply['reply_content']; ?></p>
                <p>Posted by: <?php echo $reply['username']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Form to post a reply -->
    <h3>Post a Reply</h3>
    <form method="post" action="">
        <textarea name="reply_content" rows="4" cols="50" required></textarea><br>
        <input type="submit" name="submit_reply" value="Post Reply">
    </form>

    <a href="homepage.php">Back to Topics</a>
</body>
</html>
