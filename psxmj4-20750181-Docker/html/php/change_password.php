<?php
require "db_connection.php";
require 'session.php';
require_login();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username      = $_SESSION['username'];   
    $old_password  = $_POST['old_password'];
    $new_password  = $_POST['new_password'];
    $confirm_new   = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new) {
        $message = "New passwords do not match.";
    } else {

        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {

            $stmt->bind_result($stored_password);
            $stmt->fetch();

            if ($old_password !== $stored_password) {
                $message = "Old password is incorrect.";
            } else {

                $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt2->bind_param("ss", $new_password, $username);

                if ($stmt2->execute()) {

                    session_unset();
                    session_destroy();

                    header("Location: login.php?password_changed=1");
                    exit();

                } else {
                    $message = "Error updating password.";
                }

                $stmt2->close();
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
</head>
<body>

<h2>Change Password</h2>

<form method="POST" action="change_password.php">

    <label for="old_password">Current Password:</label>
    <input type="password" id="old_password" name="old_password" required>

    <br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required>

    <br><br>

    <label for="confirm_new_password">Confirm New Password:</label>
    <input type="password" id="confirm_new_password" name="confirm_new_password" required>

    <br><br>

    <button type="submit">Update Password</button>
</form>

<p><a href="home.php">Back to Home</a></p>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

</body>
</html>
