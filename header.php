<!DOCTYPE html>
<html>
<head>
    <title>Your Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <a href="homepage.php">Home Page</a> |
    <a href="account.php">My Account</a> |
    <a href="member.php">Members</a> |
    <a href="contact.php">Contact Us</a> |
    <?php
  
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') 
            // Display the "Admin Panel" link for admin users
            echo '<a href="admin_panel.php">Admin Panel</a> | '
    
    ?>
    <a href="logout.php">Logout</a> |
   
</div>
</body>
</html>
