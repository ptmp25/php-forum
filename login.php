<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 30px 40px;
            width: 300px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Login</h2>
        
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input type="submit" name="login" value="Login">

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>




<?php

require('connect.php'); // Include the PDO database connection file

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username && $password) {
        try {
            $login_stmt = $pdo->prepare("SELECT id, password FROM user WHERE username = :username");
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
                    header('Location: homepage.php'); // Redirect to a welcome page after successful login
                    exit;
                } else {
                    echo "Invalid username or password. Please try again.";
                }
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


