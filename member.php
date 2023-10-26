<!DOCTYPE html>
<html>
    
<head>
<?php
session_start();
include("header.php");
    ?>
    <title>User Page</title>
</head>
<body>
    <div class="user-list-content">
        <h2>User List</h2>
        <div class="table-container">
            <?php
            // You can add your code here to display the user list
            require('connect.php');
            try {
                $select_stmt = $pdo->prepare("SELECT username, email, role FROM user");
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
                        
                        // Check if the user has the "admin" role
                        $isAdmin = $row['role'] === 'admin';
                        
                        // Apply red color to admin user's username
                        $usernameStyle = $isAdmin ? 'class="admin-username"' : '';
                        
                        echo '<td><a href="profile.php?username=' . $row['username'] . '" ' . $usernameStyle . '>' . $row['username'] . '</a></td>';
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
            ?>
        </div>
    </div>
</body>
</html>
