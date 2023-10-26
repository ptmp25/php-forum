<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

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
<?php include("header.php")?>
    <title>Forum - Topics</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION["username"]; ?></h1>
    <h2>Topics</h2>
    
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
                <a href="topic.php?id=<?php echo $topic['id']; ?>">
                    <?php echo $topic['title']; ?>
                </a>
                <small>(<?php echo $topic['question_count']; ?> questions, <?php echo $topic['reply_count']; ?> replies)</small>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <a href="new_topic.php">Create New Topic</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
