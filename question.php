<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $question_id = $_GET["id"];

    // Handle the reply posting
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reply_content"]) && $_POST["reply_content"] != '') {
        $reply_content = $_POST["reply_content"];
        $user_id = $_SESSION["user_id"];

        // Insert the new reply into the database
        $insert_query = "INSERT INTO replies (question_id, reply_content, user_id, timestamp) VALUES (:question_id, :reply_content, :user_id, NOW())";
        $stmt = $pdo->prepare($insert_query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':reply_content', $reply_content);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            // Update the reply_count for the user
            $update_count_query = "UPDATE user SET reply_count = COALESCE(reply_count, 0) + 1 WHERE id = :user_id";
            $update_stmt = $pdo->prepare($update_count_query);
            $update_stmt->bindParam(':user_id', $user_id);
            $update_stmt->execute();
        } else {
            echo "Error: Failed to insert reply.";
            exit();
        }
    }

    // Handle the deletion of the question
    if (isset($_POST["action"]) && $_POST["action"] === "delete") {
        $delete_query = "DELETE FROM questions WHERE id = :question_id";
        $stmt = $pdo->prepare($delete_query);
        $stmt->bindParam(':question_id', $question_id);

        if ($stmt->execute()) {
            header("Location: homepage.php");
            exit();
        } else {
            echo "Error: Failed to delete the question.";
            exit();
        }
    }

    // Fetch the question and its details from the database
    $question_query = "SELECT q.title AS question_title, q.content AS question_content, q.image_path AS question_image, q.topic_id, u.username AS posted_by, q.user_id, q.timestamp AS question_date
                      FROM questions AS q
                      JOIN user AS u ON q.user_id = u.id
                      WHERE q.id = :question_id";
    $stmt = $pdo->prepare($question_query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the current user is the owner of the question
    $isOwner = ($_SESSION["user_id"] == $question["user_id"]);

    // Fetch the replies related to the question
    $replies_query = "SELECT r.id AS reply_id, r.reply_content AS reply_content, r.timestamp AS reply_date, u.username AS replied_by, r.user_id
                    FROM replies AS r
                    JOIN user AS u ON r.user_id = u.id
                    WHERE r.question_id = :question_id";
    $stmt = $pdo->prepare($replies_query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $topic_id = $question['topic_id']; // Get the topic_id from the question details
} else {
    echo "Invalid question ID";
    exit();
}
?>

<!-- Your PHP code remains unchanged -->

<!DOCTYPE html>
<html>
<head>
<?php include("header.php")?>
    <title>Question</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px 40px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        p, li {
            color: #555;
        }

        img {
            max-width: 100%;
            border-radius: 5px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            margin-right: 15px;
        }

        a:hover {
            text-decoration: underline;
        }

        button, input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover, input[type="submit"]:hover {
            background-color: #0056b3;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $question['question_title']; ?></h1>
        <p><strong>Posted by:</strong> <?php echo $question['posted_by']; ?> on <?php echo $question['question_date']; ?></p>
        <p><?php echo $question['question_content']; ?></p>

        <?php if (!empty($question['question_image'])): ?>
            <img src="<?php echo $question['question_image']; ?>" alt="Question Image">
        <?php endif; ?>

        <?php if ($isOwner): ?>
            <a href="edit_question.php?id=<?php echo $question_id; ?>">Edit Question</a>
            <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                <input type="hidden" name="action" value="delete">
                <input type="submit" value="Delete Question">
            </form>
        <?php endif; ?>

        <h2>Replies</h2>
        <ul>
            <?php foreach ($replies as $reply): ?>
                <li>
                    <p><strong>Replied by:</strong> <?php echo $reply['replied_by']; ?> on <?php echo $reply['reply_date']; ?></p>
                    <p><?php echo $reply['reply_content']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

        <h2>Post a Reply</h2>
        <form method="post" action="">
            <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
            <textarea name="reply_content" rows="4" required></textarea><br>
            <input type="submit" name="submit" value="Post Reply">
        </form>

        <a href="topic.php?id=<?php echo $topic_id; ?>">Back to Topic</a>
        <a href="homepage.php">Back to Homepage</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
