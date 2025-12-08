<?php
require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/formatDisplayValue.php';

require_login();

$page_title = 'Change Password';
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_SESSION['username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_new = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new) {
        $error_message = "New passwords do not match.";
    } else {

        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {

            $stmt->bind_result($stored_password);
            $stmt->fetch();

            if ($old_password !== $stored_password) {
                $error_message = "Old password is incorrect.";
            } else {

                $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt2->bind_param("ss", $new_password, $username);

                if ($stmt2->execute()) {
                    session_unset();
                    session_destroy();
                    header("Location: index.php?password_changed=1");
                    exit();
                } else {
                    $error_message = "Error updating password.";
                }
                $stmt2->close();
            }
        }
        $stmt->close();
    }
}

require 'includes/header.php';
?>
<main class="container">

<h2>Change Password</h2>
<p class="guide-text">Update your account security credentials.</p>

<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
<?php endif; ?>

<form method="POST" action="change_password.php" class="styled-form">

    <div class="form-group">
        <label for="old_password">Current Password:</label>
        <input type="password" id="old_password" name="old_password" required>
    </div>

    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>

        <div class="password-strength-container">
            Strength: <span id="strength-text"></span>
            <div class="strength-bar">
                <div id="strength-bar-fill"></div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="confirm_new_password">Confirm New Password:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Password</button>
        <a href="home.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</section>

<script src="js/password_strength.js"></script>

<?php require 'includes/footer.php'; ?>