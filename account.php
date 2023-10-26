<?php
session_start();
require('connect.php');

// Initialize variables to store success messages and error messages
$emailSuccess = $passwordSuccess = $profilePicSuccess = '';
$passwordError = '';

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

    if (strlen($newPassword) >= 6) { // Check if the password is 6 characters or longer
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
            $passwordError = 'Passwords do not match.';
        }
    } else {
        $passwordError = 'Password must be 6 characters or longer.';
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
            font-size: 16px;
            line-height: 1.5;
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
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 10px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section label {
            display: block;
            margin-bottom: 10px;
        }

        .form-section input[type="text"],
        .form-section input[type="email"],
        .form-section input[type="password"],
        .form-section input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .form-section input[type="submit"] {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-section input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success-message, .error-message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            background-color: #e6ffed;
        }

        .error-message {
            color: red;
            background-color: #ffe6e6;
        }

        .profile-pic {
            max-width: 150px;
            display: block;
            margin: 0 auto;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Account Information</h2>
        <div class="form-section">
            <?php if ($emailSuccess): ?>
            <div class="success-message"><?php echo $emailSuccess; ?></div>
            <?php endif; ?>
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
            <?php if ($passwordSuccess): ?>
            <div class="success-message"><?php echo $passwordSuccess; ?></div>
            <?php endif; ?>
            <?php if ($passwordError): ?>
            <div class="error-message"><?php echo $passwordError; ?></div>
            <?php endif; ?>
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
            <?php if ($profilePicSuccess): ?>
            <div class="success-message"><?php echo $profilePicSuccess; ?></div>
            <?php endif; ?>
            <img src="profilepic/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic"><br>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="file" name="profilePic" accept="image/*">
                <input type="submit" name="uploadProfilePic" value="Upload">
            </form>
        </div>
    </div>
</body>
</html>