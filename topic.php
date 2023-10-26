<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $topic_id = $_GET["id"];

    try {
        // Fetch topic details
        $topic_query = "SELECT title FROM topics WHERE id = :topic_id";
        $stmt = $pdo->prepare($topic_query);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->execute();

        // Check if the query executed successfully and if there is a valid topic
        if ($stmt->rowCount() > 0) {
            $topic = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Topic not found";
            exit();
        }

        // Fetch questions related to the selected topic along with the last reply information
        $questions_query = "
            SELECT
                q.id,
                q.title,
                u.username AS last_reply_username,
                r.timestamp AS last_reply_timestamp
            FROM
                questions q
            LEFT JOIN
                (SELECT
                    question_id,
                    MAX(timestamp) AS timestamp
                FROM
                    replies
                GROUP BY
                    question_id) r
            ON
                q.id = r.question_id
            LEFT JOIN
                replies lr
            ON
                lr.question_id = q.id AND lr.timestamp = r.timestamp
            LEFT JOIN
                user u
            ON
                u.id = lr.user_id
            WHERE
                q.topic_id = :topic_id
            ORDER BY
                q.id DESC
        ";
        $stmt = $pdo->prepare($questions_query);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid topic ID";
    exit();
}

// Handle posting new questions
if (isset($_POST["submit"])) {
    $new_question_title = $_POST["new_question_title"];
    $new_question_content = $_POST["new_question_content"];
    $user_id = $_SESSION["user_id"]; // Retrieve user ID from the session

    if ($new_question_title && $new_question_content) {
        try {
            // Insert the new question into the 'questions' table
            $query = "INSERT INTO questions (title, content, user_id, topic_id) VALUES (:title, :content, :user_id, :topic_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':title', $new_question_title);
            $stmt->bindParam(':content', $new_question_content);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':topic_id', $topic_id);

            if ($stmt->execute()) {
                header("Location: topic.php?id=$topic_id"); // Redirect to the topic page after posting
                exit();
            } else {
                echo "Error creating the question.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please fill in both title and content fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topic - <?php echo $topic['title']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        li:last-child {
            border-bottom: none;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Topic: <?php echo $topic['title']; ?></h1>
    </header>

    <div class="container">
        <!-- Add a button to post a new question -->
        <div style="text-align: right; margin-bottom: 1rem;">
            <a href="new_question.php?topic_id=<?php echo $topic_id; ?>" class="btn">Post Question</a>
        </div>

        <!-- Display a list of questions related to this topic -->
        <h2>Questions</h2>
        <ul>
            <?php foreach ($questions as $question): ?>
                <li>
                    <a href="question.php?id=<?php echo $question['id']; ?>">
                        <?php echo $question['title']; ?>
                    </a>
                    <?php if ($question['last_reply_username'] && $question['last_reply_timestamp']): ?>
                        <div>Last Reply by <?php echo $question['last_reply_username']; ?> on <?php echo $question['last_reply_timestamp']; ?></div>
                    <?php else: ?>
                        <div>No replies yet.</div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div style="margin-top: 1rem;">
            <a href="homepage.php">Back to Homepage</a><br>
            <a href="logout.php" style="margin-top: 1rem; display: inline-block;" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
