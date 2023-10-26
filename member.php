<!DOCTYPE html>
<html>
<head>
    
    
    <title>User Page</title>
    <?php include("header.php")?>
</head>
<body>
    <h2>User List</h2>
    <?php
    session_start();

    // Check if the user is logged in by verifying a session variable, for example, 'username'
    if (isset($_SESSION['username'])) {
        echo "Log in as: " . $_SESSION['username']; // Display the username
        require('connect.php');
        try {
            $select_stmt = $pdo->prepare("SELECT username, email FROM user");
            $select_stmt->execute();

            if ($select_stmt->rowCount() > 0) {
                echo '<table>';
                echo '<tr>';
                echo '<th>Username</th>';
                echo '<th>Email</th>';
                // Add other profile information headers here if needed
                echo '</tr>';

                while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td><a href="profile.php?username=' . $row['username'] . '">' . $row['username'] . '</a></td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo 'No users found.';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'You are not logged in. <a href="login.php">Log in</a> to access this page.';
    }
    ?>
</body>
</html>
