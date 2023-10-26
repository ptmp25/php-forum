<?php
require('connect.php'); // Include the PDO database connection file

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $repass = $_POST["repassword"];
    $email = $_POST["email"];

    // Check if a profile picture has been uploaded
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0) {
        $profile_pic = $_FILES["profile_pic"]["name"];
        $profile_pic_tmp = $_FILES["profile_pic"]["tmp_name"];
        $profile_pic_path = "profilepic/" . $profile_pic; // Save the profile picture to a directory
        move_uploaded_file($profile_pic_tmp, $profile_pic_path);
    } else {
        // Set the default profile picture path or name
        $default_profile_pic = "test.png"; // Set to your default image filename
        $default_profile_pic_path = "profilepic/" . $default_profile_pic;

        // Check if the default profile picture file exists
        if (file_exists($default_profile_pic_path)) {
            $profile_pic_path = $default_profile_pic_path;
        } else {
            // If the default profile picture is missing, provide a fallback image path
            $profile_pic_path = "fallback_default_profile_pic.jpg";
        }
    }

    if ($username && $password && $repass && $email) {
        if (strlen($username) >= 5 && strlen($username) <= 25 && strlen($password) >= 6) {
            if ($password === $repass) {
                try {
                    // Check if the username already exists
                    $check_stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username");
                    $check_stmt->bindParam(':username', $username);
                    $check_stmt->execute();

                    if ($check_stmt->rowCount() == 0) {
                        // Username is not a duplicate, so we can proceed with the registration
                        // Capture the current date and time
                        $currentDate = date("Y-m-d H:i:s");

                        // Hash the password using password_hash
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                        $insert_stmt = $pdo->prepare("INSERT INTO user (username, password, email, date, profile_pic, reply_count, topic_count) VALUES (:username, :password, :email, :date, :profile_pic, 0, 0)");
                        $insert_stmt->bindParam(':username', $username);
                        $insert_stmt->bindParam(':password', $passwordHash); // Store the hashed password
                        $insert_stmt->bindParam(':email', $email);
                        $insert_stmt->bindParam(':date', $currentDate);
                        $insert_stmt->bindParam(':profile_pic', $profile_pic_path);

                        if ($insert_stmt->execute()) {
                            echo "You have registered as $username. Now you can <a href='login.php'>Login</a>";
                        } else {
                            echo "Error: Failed to insert data";
                        }
                    } else {
                        echo "Username already exists. Please choose a different username.";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Passwords do not match";
            }
        } else {
            if (strlen($username) < 5 || strlen($username) > 25) {
                echo "Username must be between 5 and 25 characters<br>";
            }
            if (strlen($password) < 6) {
                echo "Password must be at least 6 characters long<br>";
            }
        }
    } else {
        echo "Empty field(s)";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="register.style.css">
    <title>Registration</title>
</head>
<body>
    <form action="register.php" method="post" enctype="multipart/form-data">
        <h2>Register</h2>
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="repassword">Confirm Password:</label>
        <input type="password" name="repassword" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <label for="profile_pic">Profile Picture:</label>
        <input type="file" name="profile_pic" accept="image/*"><br>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
