<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $question_id = $_GET["id"];

    // Fetch the question and its details from the database
    $question_query = "SELECT q.title AS question_title, q.content AS question_content, q.image_path AS question_image, q.topic_id, u.username AS posted_by, q.user_id, q.timestamp AS question_date
                      FROM questions AS q
                      JOIN user AS u ON q.user_id = u.id
                      WHERE q.id = :question_id";
    $stmt = $pdo->prepare($question_query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the current user is the owner of the question or an admin
    $isOwner = ($_SESSION["user_id"] == $question["user_id"]);
    $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    $isOwnerOrAdmin = $isOwner || $isAdmin;

    // Handle the reply posting
    // if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reply_content"]) && $_POST["reply_content"] != '') {

    // Handle the deletion of the question
    if (isset($_POST["action"]) && $_POST["action"] === "delete" && $isOwnerOrAdmin) {
        // First, delete associated replies
        $delete_replies_query = "DELETE FROM replies WHERE question_id = :question_id";
        $stmt = $pdo->prepare($delete_replies_query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();

        // Now, delete the question
        $delete_query = "DELETE FROM questions WHERE id = :question_id";
        $stmt = $pdo->prepare($delete_query);
        $stmt->bindParam(':question_id', $question_id);
        $topic_id = $question['topic_id'];
        if ($stmt->execute()) {
            header("Location: topic.php?id=$topic_id");
            exit();
        } else {
            echo "Error: Failed to delete the question.";
            exit();
        }
    }

    // Handle the deletion of a reply
    if (isset($_POST["action"]) && $_POST["action"] === "delete_reply" && ($isOwnerOrAdmin)) {
        $reply_id = $_POST["reply_id"];

        $delete_reply_query = "UPDATE replies SET reply_content = 'Deleted reply' WHERE id = :reply_id";
        $stmt = $pdo->prepare($delete_reply_query);
        $stmt->bindParam(':reply_id', $reply_id);

        if ($stmt->execute()) {
            // Reload the current page after "deleting" the reply
            header("Location: question.php?id=$question_id");
            exit();
        } else {
            echo "Error: Failed to delete the reply.";
            exit();
        }
    }

    // Set the current page number
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Set the number of results per page
    $results_per_page = 5;

    // Calculate the starting index for the current page
    $starting_index = ($page - 1) * $results_per_page;

    // Fetch the replies related to the question for the current page
    $replies_query = "SELECT r.id AS reply_id, r.reply_content AS reply_content, r.timestamp AS reply_date, u.username AS replied_by, r.user_id
                    FROM replies AS r
                    JOIN user AS u ON r.user_id = u.id
                    WHERE r.question_id = :question_id
                    ORDER BY r.timestamp DESC
                    LIMIT :starting_index, :results_per_page";
    $stmt = $pdo->prepare($replies_query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->bindParam(':starting_index', $starting_index, PDO::PARAM_INT);
    $stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count the total number of replies for the question
    $count_query = "SELECT COUNT(*) AS count FROM replies WHERE question_id = :question_id";
    $stmt = $pdo->prepare($count_query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $count_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_results = $count_result['count'];

    // Calculate the total number of pages
    $total_pages = ceil($total_results / $results_per_page);

    $topic_id = $question['topic_id']; // Get the topic_id from the question details
} else {
    echo "Invalid question ID";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include("header.php") ?>
    <title>Question</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>
                <?php echo $question['question_title']; ?>
            </h1>
            <p><strong>Posted by:</strong>
                <?php echo $question['posted_by']; ?> on
                <?php echo $question['question_date']; ?>
            </p>
            <p>
                <?php echo $question['question_content']; ?>
            </p>

            <?php if (!empty($question['question_image'])): ?>
                <img src="<?php echo $question['question_image']; ?>" alt="Question Image">
            <?php endif; ?>
            <?php if ($isOwner): ?>
                <div class="center">
                    <a href="edit_question.php?id=<?php echo $question_id; ?>">
                        <button class="btn">
                            Edit Question
                        </button>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($isOwnerOrAdmin): ?>
                <form method="post" action=""
                    onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                    <input type="hidden" name="action" value="delete">
                    <div class="center">
                        <input class="btn" type="submit" value="Delete Question">
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <h2>Replies</h2>
        <ul>
            <?php foreach ($replies as $reply): ?>
                <li>
                    <div class="card">
                        <p><strong>Replied by:</strong>
                            <?php echo $reply['replied_by']; ?> on
                            <?php echo $reply['reply_date']; ?>
                        </p>
                        <?php if ($reply['reply_content'] === 'Deleted reply'): ?>
                            <p>Deleted reply</p>
                        <?php else: ?>
                            <p>
                                <?php echo $reply['reply_content']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($isOwnerOrAdmin): ?>
                            <form method="post" action="">
                                <input type="hidden" name="action" value="delete_reply">
                                <input type="hidden" name="reply_id" value="<?php echo $reply['reply_id']; ?>">
                                <input type="submit" class="btn" value="Delete Reply">
                            </form>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($total_pages > 1): ?>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a href="question.php?id=<?php echo $question_id; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <li class="page-item">
                            <span class="current-page">
                                <?php echo $i; ?>
                            </span>
                        </li>
                    <?php else: ?>                        <li class="page-item">
                        <a href="question.php?id=<?php echo $question_id; ?>&page=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a href="question.php?id=<?php echo $question_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <h2>Post a Reply</h2>
        <form method="post" action="post_reply.php">
            <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
            <textarea name="reply_content" rows="4" cols="70" required></textarea><br>
            <input type="submit" name="post_reply_btn" class="btn" value="Post Reply">
        </form>
    </div>
</body>

</html>