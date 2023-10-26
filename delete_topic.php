<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header("Location: homepage.php");
    exit();
}

if (isset($_GET["id"])) {
    $topic_id = $_GET["id"];

    try {
        // Fetch all question IDs within the topic
        $question_ids_query = "SELECT id FROM questions WHERE topic_id = :topic_id";
        $question_ids_stmt = $pdo->prepare($question_ids_query);
        $question_ids_stmt->bindParam(':topic_id', $topic_id);
        $question_ids_stmt->execute();
        $question_ids = $question_ids_stmt->fetchAll(PDO::FETCH_COLUMN);

        // Delete associated replies for each question
        foreach ($question_ids as $question_id) {
            $delete_replies_query = "DELETE FROM replies WHERE question_id = :question_id";
            $delete_replies_stmt = $pdo->prepare($delete_replies_query);
            $delete_replies_stmt->bindParam(':question_id', $question_id);
            $delete_replies_stmt->execute();
        }

        // Now, delete the questions within the topic
        $delete_questions_query = "DELETE FROM questions WHERE topic_id = :topic_id";
        $delete_questions_stmt = $pdo->prepare($delete_questions_query);
        $delete_questions_stmt->bindParam(':topic_id', $topic_id);
        $delete_questions_stmt->execute();

        // Finally, delete the topic itself
        $delete_topic_query = "DELETE FROM topics WHERE id = :topic_id";
        $delete_topic_stmt = $pdo->prepare($delete_topic_query);
        $delete_topic_stmt->bindParam(':topic_id', $topic_id);
        $delete_topic_stmt->execute();

        header("Location: homepage.php");
        exit();
    } catch (PDOException $e) {
        echo "Error deleting the topic: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid topic ID";
    exit();
}
?>
