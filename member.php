<!DOCTYPE html>
<html>

<head>
    <?php
    require('connect.php');
    session_start();

    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }

    if (isset($_POST['delete_user']) && isset($_POST['delete_user_id'])) {
        $user_id_to_delete = $_POST['delete_user_id'];

        try {
            // Start a transaction
            $pdo->beginTransaction();

            // 1. Delete the user's replies
            $delete_replies_query = "DELETE FROM replies WHERE user_id = :user_id";
            $stmt = $pdo->prepare($delete_replies_query);
            $stmt->bindParam(':user_id', $user_id_to_delete);
            $stmt->execute();

            // 2. Delete the user's topics or posts
            $delete_topics_query = "DELETE FROM topics WHERE user_id = :user_id";
            $stmt = $pdo->prepare($delete_topics_query);
            $stmt->bindParam(':user_id', $user_id_to_delete);
            $stmt->execute();

            // 3. Delete the user's questions
            $delete_questions_query = "DELETE FROM questions WHERE user_id = :user_id";
            $stmt = $pdo->prepare($delete_questions_query);
            $stmt->bindParam(':user_id', $user_id_to_delete);
            $stmt->execute();

            // 4. Finally, delete the user
            $delete_user_query = "DELETE FROM user WHERE id = :user_id";
            $stmt = $pdo->prepare($delete_user_query);
            $stmt->bindParam(':user_id', $user_id_to_delete);
            $stmt->execute();

            // Commit the transaction
            $pdo->commit();

            // Refresh the page to see the changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            // If any of the operations fail, we roll back
            $pdo->rollBack();
            echo 'Error deleting user and associated data: ' . $e->getMessage();
        }
    }

    require("header.php");
    ?>
    <title>User Page</title>
</head>

<body>
    <div class="user-list-content">
        <h2>User List</h2>
        <div class="table-container">
            <?php
            try {
                $select_stmt = $pdo->prepare("SELECT id, username, email, role FROM user");
                $select_stmt->execute();
                if ($select_stmt->rowCount() > 0) {
                    echo '<table class="container">';
                    echo '<tr>';
                    echo '<th>Username</th>';
                    echo '<th>Email</th>';
                    if ($_SESSION['role'] === 'admin') { // Only show the action column if the logged-in user is an admin
                        echo '<th>Action</th>'; // Add this column for actions
                    }
                    echo '</tr>';

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';

                        // Check if the user has the "admin" role
                        $isAdmin = $row['role'] === 'admin';

                        // Apply red color to admin user's username
                        $usernameStyle = $isAdmin ? 'style="color:red;"' : '';

                        echo '<td><a href="profile.php?username=' . $row['username'] . '" ' . $usernameStyle . '>' . $row['username'] . '</a></td>';
                        echo '<td>' . $row['email'] . '</td>';
                        if ($_SESSION['role'] === 'admin' && !$isAdmin) { // Only show the delete button if the logged-in user is an admin and the user to be deleted is not an admin
                            echo '<td>';
                            echo '<form method="post" action="">';
                            echo '<input type="hidden" name="delete_user_id" value="' . $row['id'] . '">';
                            echo '<input type="submit" class="btn" name="delete_user" value="Delete">';
                            echo '</form>';
                            echo '</td>';
                        }
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo 'No users found.';
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
            ?>
        </div>
    </div>
</body>

</html>