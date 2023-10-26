<!DOCTYPE html>
<html>
<head>
    <title>User Page</title>
    <style>
        /* Styling specific to the User Page content */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .user-list-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px 15px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
        }

        a.admin-username {
            color: red;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <?php include("header.php")?>
</head>
<body>
    <div class="user-list-content">
        <h2>User List</h2>
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
</body>
</html>
