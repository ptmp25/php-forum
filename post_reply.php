<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post_reply_btn"])) {
    $question_id = $_POST["question_id"];
    $reply_content = $_POST["reply_content"];

    try {
        // Insert the reply into the database
        $insert_reply_query = "INSERT INTO replies (question_id, user_id, reply_content) VALUES (:question_id, :user_id, :reply_content)";
        $stmt = $pdo->prepare($insert_reply_query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':user_id', $_SESSION["user_id"]); // Replace with your actual session variable
        $stmt->bindParam(':reply_content', $reply_content);
        
        if ($stmt->execute()) {
            // Redirect back to the question page after posting the reply
            header("Location: question.php?id=$question_id");
            exit();
        } else {
            echo "Error: Failed to insert reply.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
