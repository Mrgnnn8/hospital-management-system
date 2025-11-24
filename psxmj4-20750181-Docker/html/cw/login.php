<?php
require 'session.php';
require 'db_connection.php';

if (isset($_SESSION['logged_in'])) {
    header("Location: home.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to prevent SQL injection

    $stmt = $conn->prepare("
    SELECT password, is_admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_password, $is_admin);
        $stmt->fetch();

        if ($password === $stored_password) {
            $message = "Login successful.";
            $toastClass = "bg-success";

            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $is_admin;

            //
            session_regenerate_id(true);

            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $message = "Incorrect password.";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Username not found.";
        $toastClass = "bg-warning";
    }

$stmt->close();
$conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="/css/login2.css">
</head>

<body>
<div id="login-form">
    <h3>Login Portal</h3>

    <p>Queens Medical Centre Staff Login</p>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <input type="submit" id="login" value="Login">
    </form>

    <?php if (!empty($error)): ?>
        <p style="color:red; font-weight:bold;"><?php echo $error; ?></p>
    <?php endif; ?>
</div>
</body>

</html>
