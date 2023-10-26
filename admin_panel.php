<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .header a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header a:hover {
            background-color: #555;
        }

        .content {
            padding: 20px;
        }

        h1 {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php
    require('connect.php'); // Include the PDO database connection file

    session_start(); // Start the session

    // Check if the user is logged in and is an admin
    if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        // Check if the promotion form is submitted
        if (isset($_POST['promote_user'])) {
            $promote_username = $_POST['promote_username'];

            if (!empty($promote_username)) {
                try {
                    // Check if the username exists and is not already an admin
                    $user_query = "SELECT id FROM user WHERE username = :promote_username AND role != 'admin'";
                    $stmt = $pdo->prepare($user_query);
                    $stmt->bindParam(':promote_username', $promote_username);
                    $stmt->execute();

                    if ($stmt->rowCount() == 1) {
                        // Promote the user to admin
                        $promote_query = "UPDATE user SET role = 'admin' WHERE username = :promote_username";
                        $stmt = $pdo->prepare($promote_query);
                        $stmt->bindParam(':promote_username', $promote_username);
                        $stmt->execute();

                        echo "User '$promote_username' has been promoted to admin.";
                    } else {
                        echo "User '$promote_username' not found or is already an admin.";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Please enter a username.";
            }
        }

        // Fetch messages from the database and display them
        try {
            $recipient_id = $_SESSION['user_id']; // Get the current admin's user ID

            // Select messages sent to the current admin only, join with user table to get sender's email
            $messages_query = "SELECT messages.*, user.email as sender_email, messages.created_at as message_created_at FROM messages JOIN user ON messages.user_id = user.id WHERE recipient_admin_id = :recipient_id";
            $stmt = $pdo->prepare($messages_query);
            $stmt->bindParam(':recipient_id', $recipient_id);
            $stmt->execute();

            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching messages: " . $e->getMessage();
        }
    } else {
        echo "You are not authorized to access the admin panel.";
    }
    ?>

    <!-- Include your header content here -->
    <div class="header">
        <a href="homepage.php">Home Page</a> |
        <a href="account.php">My Account</a> |
        <a href="member.php">Members</a> |
        <?php
        // Check if the user is logged in
        if (isset($_SESSION['username'])) {
            // Check if the user is an admin
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                echo '<a href="admin_panel.php">Admin Panel</a> | ';
            }
        } 
        ?>
        <a href="logout.php">Logout</a> |
    </div>

    <!-- Admin Panel Content -->
    <h1>Welcome to the Admin Panel, <?php echo $_SESSION['username']; ?></h1>

    <!-- Add User Promotion Form -->
    <form method="post" action="">
        <label for="promote_username">Username to Promote:</label>
        <input type="text" name="promote_username" required>
        <input type="submit" name="promote_user" value="Promote User to Admin">
    </form>

    <!-- Messages Section -->
    <h2>Messages</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?php echo $message['name']; ?></td>
                <td><?php echo $message['sender_email']; ?></td>
                <td><?php echo $message['message']; ?></td>
                <td><?php echo $message['message_created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
