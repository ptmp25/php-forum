<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Fetch topics and their question/reply counts from the database
$query = "SELECT t.id, t.title,
                 COALESCE(COUNT(DISTINCT q.id), 0) AS question_count,
                 COALESCE(COUNT(DISTINCT r.id), 0) AS reply_count
          FROM topics t
          LEFT JOIN questions q ON t.id = q.topic_id
          LEFT JOIN replies r ON q.id = r.question_id
          GROUP BY t.id";
          
$stmt = $pdo->prepare($query);
$stmt->execute();
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <?php include("header.php") ?>
    <title>Forum - Topics</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 28px;
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }

        h1 span {
            font-weight: bold;
            color: #333;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-top: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 10px;
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

        small {
            color: #888;
        }

        form {
            display: inline;
        }

        input[type="submit"] {
            background-color: #FF5722;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        input[type="submit"]:hover {
            background-color: #E64A19;
        }

        a.create-topic {
            display: block;
            background-color: #007BFF;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 10px;
        }

        a.create-topic:hover {
            background-color: #0056b3;
        }

        a.logout {
            display: block;
            background-color: #FF5722;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 10px;
        }

        a.logout:hover {
            background-color: #E64A19;
        }
    </style>
</head>
<body>
<h1>Welcome, <span><?php echo $_SESSION["username"]; ?></span></h1>
    
    <h2>Topics</h2>
    
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
                <a href="topic.php?id=<?php echo $topic['id']; ?>">
                    <?php echo $topic['title']; ?>
                </a>
                <small>(<?php echo $topic['question_count']; ?> questions, <?php echo $topic['reply_count']; ?> replies)</small>
                <?php if ($is_admin): ?>
                    <form method="post" action="delete_topic.php?id=<?php echo $topic['id']; ?>">
                        <input type="submit" name="delete_topic" value="Delete Topic">
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <?php if ($is_admin): ?>
        <a href="new_topic.php" class="create-topic">Create New Topic</a><br>
    <?php endif; ?>

    <a href="logout.php" class="logout">Logout</a>
</body>
</html>
