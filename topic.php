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

        // Fetch questions related to the selected topic
        $questions_query = "SELECT id, title FROM questions WHERE topic_id = :topic_id";
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
<html>
<head>
    <title>Topic - <?php echo $topic['title']; ?></title>
</head>
<body>
    <h1>Topic: <?php echo $topic['title']; ?></h1>
    
    <!-- Add a button to post a new question -->
    <a href="new_question.php?topic_id=<?php echo $topic_id; ?>">Post Question</a>


    <!-- Display a list of questions related to this topic -->
    <h2>Questions</h2>
    <ul>
        <?php foreach ($questions as $question): ?>
            <li>
                <a href="question.php?id=<?php echo $question['id']; ?>">
                    <?php echo $question['title']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="homepage.php">Back to Homepage</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
