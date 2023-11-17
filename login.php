<?php
require('connect.php'); // Include the PDO database connection file

session_start(); // Start the session

if (isset($_SESSION['user_id'])) {
    // If the user is already logged in, redirect them based on their role
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_panel.php'); // Redirect admin to admin panel
    } else {
        header('Location: index.php'); // Redirect non-admin to homepage
    }
    exit();
}

$errors = array(); // Initialize an empty array for error messages

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username && $password) {
        try {
            $login_stmt = $pdo->prepare("SELECT id, password, role FROM user WHERE username = :username");
            $login_stmt->bindParam(':username', $username);
            $login_stmt->execute();

            if ($login_stmt->rowCount() == 1) {
                $user = $login_stmt->fetch(PDO::FETCH_ASSOC);
                $hashedPassword = $user['password'];

                // Verify the password using password_verify
                if (password_verify($password, $hashedPassword)) {
                    session_start();
                    $_SESSION['user_id'] = $user['id']; // Store the user's ID in the session
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $user['role']; // Set the user's role
                    if ($user['role'] === 'admin') {
                        header('Location: admin_panel.php'); // Redirect to the admin panel after successful login
                    } else {
                        header('Location: index.php'); // Redirect to homepage for non-admin users
                    }
                    exit();
                } else {
                    array_push($errors, "Invalid username or password. Please try again.");
                }
            } else {
                array_push($errors, "Invalid username or password. Please try again.");
            }
        } catch (PDOException $e) {
            array_push($errors, "Error: " . $e->getMessage());
        }
    } else {
        array_push($errors, "Empty field(s)");
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="login_style.css">
</head>

<body>
    <form method="post" action="">
        <h2>Login</h2>

        <?php if (count($errors) > 0) : ?>
            <div class="error-message">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input type="submit" name="login" value="Login">

        <p>Don't have an account? <a href="register.php">Register here</a></p>
        
    </form>

</body>

</html>