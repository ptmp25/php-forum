<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" name="login" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
<?php

require('connect.php'); // Include the PDO database connection file

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $pass_en = sha1($password);

    if ($username && $password) {
        try {
            $login_stmt = $pdo->prepare("SELECT id, role FROM user WHERE username = :username AND password = :password");
            $login_stmt->bindParam(':username', $username);
            $login_stmt->bindParam(':password', $pass_en);
            $login_stmt->execute();

            if ($login_stmt->rowCount() == 1) {
                $user = $login_stmt->fetch(PDO::FETCH_ASSOC);
                session_start();
                $_SESSION['user_id'] = $user['id']; // Store the user's ID in the session
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role']; // Store the user's role in the session
                header('Location: homepage.php'); // Redirect to a welcome page after successful login
            } else {
                echo "Invalid username or password. Please try again.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Empty field(s)";
    }
}
?>

