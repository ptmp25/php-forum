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
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>Username</th>';
                    echo '<th>Email</th>';
                    if ($_SESSION['role'] === 'admin') {
                        echo '<th>Action</th>';
                    }
                    echo '</tr>';

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';

                        $isAdmin = $row['role'] === 'admin';

                        $usernameStyle = $isAdmin ? 'style="color:red;"' : '';

                        echo '<td><a href="profile.php?username=' . $row['username'] . '" ' . $usernameStyle . '>' . $row['username'] . '</a></td>';
                        echo '<td>' . $row['email'] . '</td>';

                        if ($_SESSION['role'] === 'admin' && !$isAdmin && $_SESSION['user_id'] != $row['id']) {
                            echo '<td>';
                            echo '<form method="post" action="">';
                            echo '<input type="hidden" name="user_id" value="' . $row['id'] . '">';
                            echo '<input type="submit" name="delete_user" value="Delete">';
                            echo '</form>';
                            echo '</td>';
                        } elseif ($_SESSION['role'] !== 'admin') {
                            echo '<td></td>';
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

            if (isset($_POST['delete_user'])) {
                $user_id = $_POST['user_id'];

                try {
                    $delete_query = "DELETE FROM user WHERE id = :user_id";
                    $stmt = $pdo->prepare($delete_query);
                    $stmt->bindParam(':user_id', $user_id);
                    if ($stmt->execute()) {
                        echo "User with ID $user_id has been deleted.";
                        // Redirect back to the same page after a successful deletion
                        header("Location: $_SERVER[PHP_SELF]");
                        exit();
                    } else {
                        echo "Error deleting user with ID $user_id.";
                    }
                } catch (PDOException $e) {
                    echo 'Error deleting user: ' . $e->getMessage();
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
