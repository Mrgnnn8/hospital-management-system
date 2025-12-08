<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/DoctorDAO.php';
require_once 'data_access/formatDisplayValue.php';

if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
}

require_login();

$page_title = 'Edit My Profile';
$success_message = '';
$error_message = '';

$current_session_username = $_SESSION['username'];

$staffNo = DoctorDAO::getStaffNoByUsername($conn, $current_session_username);

if (!$staffNo) {
    die("Error: Account not linked to a doctor profile.");
}

$doctor_data = DoctorDAO::getDoctorProfile($conn, $staffNo);

$firstname      = $doctor_data['firstname'] ?? '';
$lastname       = $doctor_data['lastname'] ?? '';
$address        = $doctor_data['Address'] ?? '';
$specialisation = $doctor_data['Specialisation'] ?? '';
$username_val   = $doctor_data['username'] ?? $current_session_username;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $new_firstname      = trim($_POST['firstname'] ?? '');
    $new_lastname       = trim($_POST['lastname'] ?? '');
    $new_address        = trim($_POST['address'] ?? '');
    $new_specialisation = trim($_POST['specialisation'] ?? '');
    $new_username       = trim($_POST['username'] ?? '');

    if (empty($new_firstname) || empty($new_lastname) || empty($new_username)) {
        $error_message = "Name and Username fields are required.";
    } else {
        $result = DoctorDAO::updateDoctorProfile(
            $conn, 
            $staffNo, 
            $new_firstname, 
            $new_lastname, 
            $new_address, 
            $new_specialisation, 
            $new_username
        );

        if ($result) {
            
            if (function_exists('logAction')) {
                logAction($conn, $_SESSION['username'], 'UPDATE_PROFILE', "Updated personal details and login username (to $new_username) for Staff ID $staffNo.");
            }

            $success_message = "Profile updated successfully.";
            
            if ($new_username !== $current_session_username) {
                $_SESSION['username'] = $new_username;
                $username_val = $new_username; 
            }

            header("Location: manage_account.php?status=updated");
            exit();

            $firstname      = $new_firstname;
            $lastname       = $new_lastname;
            $address        = $new_address;
            $specialisation = $new_specialisation;

        } else {
            $error_message = "Update failed. Username '$new_username' might already be taken.";
        }
    }
}

require 'includes/header.php';
?>
<section class="task-form-area container">

    <h2>Edit Profile</h2>
    <p class="guide-text">Update your personal and login details.</p>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= safeDisplay($success_message) ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" class="styled-form">
        
        <div class="form-row" style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" 
                       value="<?= safeDisplay($firstname) ?>" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" 
                       value="<?= safeDisplay($lastname) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="username">Login Username:</label>
            <input type="text" id="username" name="username" 
                   value="<?= safeDisplay($username_val) ?>" required>
            <small style="color: #666;">Changing this will change how you log in.</small>
        </div>

        <div class="form-group">
            <label for="specialisation">Specialisation:</label>
            <input type="text" id="specialisation" name="specialisation" 
                   value="<?= safeDisplay($specialisation) ?>">
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3"><?= safeDisplay($address) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage_account.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</section>

<?php require 'includes/footer.php'; ?>