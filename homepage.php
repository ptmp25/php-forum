<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Fetch topics from the database
$query = "SELECT id, title FROM topics";
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
            </li>
        <?php endforeach; ?>
    </ul>
    
    <a href="new_topic.php">Create New Topic</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
