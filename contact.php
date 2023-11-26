<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Fetch a list of admins for the dropdown
$admin_role = 'admin';
$admin_query = "SELECT id, username FROM user WHERE role = :role";
$stmt = $pdo->prepare($admin_query);
$stmt->bindParam(':role', $admin_role);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $user_id = $_SESSION["user_id"];
    $role = $_SESSION["role"];
    $name = $_SESSION["username"];
    $admin_username = $_POST["admin_username"];
    $message = $_POST["message"];

    // Fetch recipient admin's ID based on their username
    $recipient_query = "SELECT id FROM user WHERE username = :admin_username AND role = 'admin'";
    $stmt = $pdo->prepare($recipient_query);
    $stmt->bindParam(':admin_username', $admin_username);
    $stmt->execute();
    $recipient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipient) {
        // Insert the message into the messages table
        $insert_message_query = "INSERT INTO messages (user_id, role, name, email, message, admin_id) 
                                 VALUES (:user_id, :role, :name, :email, :message, :recipient_id)";
        $stmt = $pdo->prepare($insert_message_query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $sender['email']);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':recipient_id', $recipient['id']);

        if ($stmt->execute()) {
            $success_message = "Message sent successfully!";
        } else {
            $error_message = "Error sending the message.";
        }
    } else {
        $error_message = "Recipient admin not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include("header.php") ?>
    <title>Message Panel</title>
</head>

<body>
    <div class="container">
        <h1>Welcome,
            <?php echo $_SESSION["username"]; ?>
        </h1>

        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <h2>Message Panel</h2>

        <form method="post" action="">

            <div class="input-group">
                <label for="admin_select">Select Admin:</label>
                <select class="input-group" name="admin_username" id="admin_select" required>
                    <option value="" disabled selected>Select an Admin</option>
                    <?php foreach ($admins as $admin): ?>
                        <option value="<?php echo $admin['username']; ?>">
                            <?php echo $admin['username']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div >
                <label for="message">Message:</label>
                <textarea class="input-group" name="message" id="message" rows="4" required></textarea><br>
            </div>
            <input type="submit" class="btn" name="submit" value="Send Message">
        </form>

        <!-- <a href="logout.php">Logout</a> -->
    </div>
</body>

</html>