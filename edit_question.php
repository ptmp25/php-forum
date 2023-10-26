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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px 40px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;  /* Allows vertical resizing of the textarea */
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
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