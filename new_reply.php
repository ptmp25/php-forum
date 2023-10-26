<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
if ($update_stmt->rowCount() === 0) {
    echo "Update Error: No rows updated for user ID: " . $user_id . ". Please check if this user exists in the database.";
    exit;
}
if (isset($_GET["question_id"])) {
    $question_id = $_GET["question_id"];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reply_content"]) && $_POST["reply_content"] != '') {
        $reply_content = $_POST["reply_content"];
        $user_id = $_SESSION["user_id"];

        // Insert the new reply into the database
        $insert_query = "INSERT INTO replies (question_id, reply_content, user_id) VALUES (:question_id, :reply_content, :user_id)";
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
