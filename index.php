<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Set the number of items to display per page
$items_per_page = 5;

// Get the current page number
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $items_per_page;

// Fetch topics and their question/reply counts from the database
$query = "SELECT t.id, t.title,
                 COALESCE(COUNT(DISTINCT q.id), 0) AS question_count,
                 COALESCE(COUNT(DISTINCT r.id), 0) AS reply_count
          FROM topics t
          LEFT JOIN questions q ON t.id = q.topic_id
          LEFT JOIN replies r ON q.id = r.question_id
          GROUP BY t.id
          LIMIT $items_per_page OFFSET $offset";

$stmt = $pdo->prepare($query);
$stmt->execute();
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of topics
$total_topics_query = "SELECT COUNT(*) AS total FROM topics";
$total_topics_stmt = $pdo->prepare($total_topics_query);
$total_topics_stmt->execute();
$total_topics = $total_topics_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calculate the total number of pages
$total_pages = ceil($total_topics / $items_per_page);

include("header.php");
?>

<body>
    <main>
        <h1>Welcome, <span>
                <?php echo $_SESSION["username"]; ?>
            </span></h1>

        <div class="container">
            <h2>Modules</h2>
            <ul>
                <?php foreach ($topics as $topic): ?>
                    <div class="card">

                        <a href="topic.php?id=<?php echo $topic['id']; ?>">
                            <li>
                                <div class="name">
                                    <?php echo $topic['title']; ?>
                                </div><small>(
                                    <?php echo $topic['question_count']; ?> questions,
                                    <?php echo $topic['reply_count']; ?> replies)
                                </small>
                                <?php if ($is_admin): ?>
                                    <form method="post" action="delete_topic.php?id=<?php echo $topic['id']; ?>">
                                        <input class="btn" type="submit" name="delete_topic" value="Delete Module">
                                    </form>
                                <?php endif; ?>
                            </li>
                        </a>
                    </div>
                <?php endforeach; ?>
                <ul class="pagination">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a href="?page=<?php echo $current_page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i === $current_page): ?>
                            <li class="page-item">
                                <span>
                                    <?php echo $i; ?>
                                </span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a href="?page=<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a href="?page=<?php echo $current_page + 1; ?>">Next</a>
                        </li>
                        <?php endif; ?>
                </ul>
            </ul>
        </div>

        <?php if ($is_admin): ?>
            <div class="center">
                <a href="new_topic.php">
                    <button class="btn">
                        Create New Module
                    </button>
                </a>
            </div>
        <?php endif; ?>

    </main>
</body>

</html>