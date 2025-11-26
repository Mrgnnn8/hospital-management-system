<?php
require "db_connection.php";
require 'session.php';
require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>

<body>

<h1>Welcome to the Home Page</h1>

<p>Website access level: 
    <strong>
        <?php echo ($_SESSION['is_admin'] == 1) ? 'Admin' : 'User'; ?>
    </strong>
</p>

<p>Your username: 
    <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
</p>

<p><a href="user_parking_permit.php">View parking permit status</a></p>

<p><a href="doctor.php">Doctor Information</a></p>

<p><a href="change_password.php">Change Password</a></p>

<p><a href="logout.php">Log Out</a></p>

</body>
</html>

