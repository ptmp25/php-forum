<!DOCTYPE html>
<html>
<head>
<?php include("header.php")?>
    <title>Admin Panel</title>
    <style>
        /* Your CSS styles here */
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
    } else {
        echo "You are not authorized to access the admin panel.";
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

</body>
</html>
