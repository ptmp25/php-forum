<?php
session_start();
require('connect.php');

// Initialize variables to store success messages
$emailSuccess = $passwordSuccess = $profilePicSuccess = '';

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Handle email update
if (isset($_POST['updateEmail'])) {
    $newEmail = $_POST['newEmail'];
    $updateEmailStmt = $pdo->prepare("UPDATE user SET email = :email WHERE username = :username");
    $updateEmailStmt->bindParam(':email', $newEmail);
    $updateEmailStmt->bindParam(':username', $username);

    if ($updateEmailStmt->execute()) {
        $emailSuccess = 'Email updated successfully.';
    } else {
        // Handle email update failure
    }
}

// Handle password update
if (isset($_POST['updatePassword'])) {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword === $confirmPassword) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordStmt = $pdo->prepare("UPDATE user SET password = :password WHERE username = :username");
        $updatePasswordStmt->bindParam(':password', $newPasswordHash);
        $updatePasswordStmt->bindParam(':username', $username);

        if ($updatePasswordStmt->execute()) {
            $passwordSuccess = 'Password updated successfully.';
        } else {
            // Handle password update failure
        }
    } else {
        // Handle password and confirm password mismatch
    }
}

// Handle profile picture upload
if (isset($_POST['uploadProfilePic'])) {
    if ($_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'profilepic/';
        $uploadFile = $uploadDir . basename($_FILES['profilePic']['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $uploadFile)) {
                $updateProfilePicStmt = $pdo->prepare("UPDATE user SET profile_pic = :profile_pic WHERE username = :username");
                $updateProfilePicStmt->bindParam(':profile_pic', $_FILES['profilePic']['name']);
                $updateProfilePicStmt->bindParam(':username', $username);
                $updateProfilePicStmt->execute();

                $profilePicSuccess = 'Profile picture updated successfully.';
            } else {
                // Handle file upload failure
            }
        } else {
            // Handle invalid file type
        }
    }
}

// Retrieve user information including the profile picture
$selectStmt = $pdo->prepare("SELECT username, email, profile_pic FROM user WHERE username = :username");
$selectStmt->bindParam(':username', $username);
$selectStmt->execute();
$user = $selectStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <?php include("header.php")?>
    <title>Account Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-top: 0;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .profile-pic {
            max-width: 150px;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Account Information</h2>
        <div class="form-section">
            <div class="success-message"><?php echo $emailSuccess; ?></div>
            <form method="post" action="">
                <label for="username">Username:</label>
                <?php echo $user['username']; ?><br>

                <label for="email">Email:</label>
                <input type="email" name="newEmail" value="<?php echo $user['email']; ?>"><br>

                <input type="submit" name="updateEmail" value="Update Email">
            </form>
        </div>

        <h2>Change Password</h2>
        <div class="form-section">
            <div class="success-message"><?php echo $passwordSuccess; ?></div>
            <form method="post" action="">
                <label for="newPassword">New Password:</label>
                <input type="password" name="newPassword"><br>

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" name="confirmPassword"><br>

                <input type="submit" name="updatePassword" value="Update Password">
            </form>
        </div>

        <h2>Change Profile Picture</h2>
        <div class="form-section">
            <div class="success-message"><?php echo $profilePicSuccess; ?></div>
            <img src="profilepic/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic"><br>

            <form method="post" action="" enctype="multipart/form-data">
                <input type="file" name="profilePic" accept="image/*">
                <input type="submit" name="uploadProfilePic" value="Upload">
            </form>
        </div>
    </div>
</body>
</html>
