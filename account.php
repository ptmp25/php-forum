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
<link rel="stylesheet" type="text/css" href="styles.css">

<head>
    <?php include("header.php") ?>
    <title>Account Page</title>
</head>

<body>
    <div class="container">
        <h2>Your Account Information</h2>
        <div class="form-section">
            <?php if ($emailSuccess): ?>
                <div class="success-message">
                    <?php echo $emailSuccess; ?>
                </div>
            <?php endif; ?>
            <div class="card profile">
                <form method="post" action="">
                    <div class="input-group">
                        <label for="username">Username:
                            <strong><?php echo $user['username']; ?> </strong>
                        </label>
                    </div>
                    <div class="input-group">
                        <label for="email">Email:</label>
                        <input type="email" name="newEmail" value="<?php echo $user['email']; ?>"><br>
                    </div>
                    <div class="submit-button">
                        <input class="btn" type="submit" name="updateEmail" value="Update Email">
                    </div>
                </form>
            </div>
        </div>

        <h2>Change Password</h2>
        <div class="form-section card profile">
            <?php if ($passwordSuccess): ?>
                <div class="success-message">
                    <?php echo $passwordSuccess; ?>
                </div>
            <?php endif; ?>
            <?php if ($passwordError): ?>
                <div class="error-message">
                    <?php echo $passwordError; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="newPassword">New Password:</label>
                    <input type="password" name="newPassword"><br>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" name="confirmPassword"><br>
                </div>
                <div class="submit-button">
                    <input type="submit" class="btn" name="updatePassword" value="Update Password" style="width:150px">
                </div>
            </form>
        </div>

        <h2>Change Profile Picture</h2>
        <div class="form-section container">
            <?php if ($profilePicSuccess): ?>
                <div class="success-message">
                    <?php echo $profilePicSuccess; ?>
                </div>
            <?php endif; ?>
            <img src="profilepic/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic"><br>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="file" name="profilePic" accept="image/*">
                <div class="submit-button">
                    <input type="submit" class="btn" name="uploadProfilePic" value="Upload">
                </div>
            </form>
        </div>
    </div>
</body>

</html>