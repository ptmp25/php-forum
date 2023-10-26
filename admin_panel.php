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
<link rel="stylesheet" href="style.css">
    <title>Admin Panel</title>
</head>
<body>
   
    <?php
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
        
        // Handle message deletion
        if (isset($_POST['delete_message'])) {
            $message_id = $_POST['message_id'];

            try {
                // Check if the message belongs to the current admin
                $check_message_query = "SELECT id FROM messages WHERE id = :message_id AND recipient_admin_id = :recipient_id";
                $stmt = $pdo->prepare($check_message_query);
                $stmt->bindParam(':message_id', $message_id);
                $stmt->bindParam(':recipient_id', $recipient_id);
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    // Delete the message
                    $delete_message_query = "DELETE FROM messages WHERE id = :message_id";
                    $stmt = $pdo->prepare($delete_message_query);
                    $stmt->bindParam(':message_id', $message_id);
                    $stmt->execute();

                    echo "Message with ID $message_id has been deleted.";

                    // Redirect back to the admin panel page after deletion
                    header("Location: admin_panel.php");
                    exit();
                } 
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        header("Location: login.php");
    }
    ?>
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
            <th>Action</th> <!-- Add a new column for the delete button -->
        </tr>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?php echo $message['name']; ?></td>
                <td><?php echo $message['sender_email']; ?></td>
                <td><?php echo $message['message']; ?></td>
                <td><?php echo $message['message_created_at']; ?></td>
                <td>
                    <!-- Add a Delete button with a form to handle the delete action -->
                    <form method="post" action="">
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                        <input type="submit" name="delete_message" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
