<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/DoctorDAO.php';
require_once 'data_access/formatDisplayValue.php'; 

require_login();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access Denied: Administrator privileges required.");
}

$page_title = 'Edit Doctor Details';
$error_message = '';
$success_message = '';

if (!isset($_GET['id'])) {
    header("Location: doctor.php"); 
    exit();
}

$target_staff_no = $_GET['id'];

$doctor = DoctorDAO::getDoctorProfile($conn, $target_staff_no);

if (!$doctor) {
    die("Error: Doctor with ID '$target_staff_no' not found.");
}

$firstname      = $doctor['firstname'];
$lastname       = $doctor['lastname'];
$username       = $doctor['username'];
$specialisation = $doctor['Specialisation'];
$qualification  = $doctor['qualification'];
$pay            = $doctor['pay'];
$gender_val     = $doctor['gender']; 
$consultant_val = $doctor['consultantstatus'];
$address        = $doctor['Address'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname      = trim($_POST['firstname'] ?? '');
    $lastname       = trim($_POST['lastname'] ?? '');
    $username       = trim($_POST['username'] ?? '');
    $specialisation = trim($_POST['specialisation'] ?? '');
    $qualification  = trim($_POST['qualification'] ?? '');
    $pay            = trim($_POST['pay'] ?? '0');
    $address        = trim($_POST['address'] ?? '');
    
    $gender_val     = $_POST['gender'] ?? 0;
    $consultant_val = $_POST['consultantstatus'] ?? 0;
    
    $new_password   = $_POST['password'] ?? ''; 

    if (empty($firstname) || empty($lastname) || empty($username)) {
        $error_message = "Name and Username are required.";
    } else {
        $qual_db = empty($qualification) ? null : $qualification;

        $result = DoctorDAO::updateDoctorFull(
            $conn,
            $target_staff_no,
            $firstname,
            $lastname,
            $specialisation,
            $qual_db,
            $pay,
            $gender_val,
            $consultant_val,
            $address,
            $username,
            $new_password 
        );

        if ($result === true) {
            if (function_exists('logAction')) {
                logAction($conn, $_SESSION['username'], 'UPDATE_DOCTOR', "Updated Dr. $lastname ($target_staff_no)");
            }
            header("Location: doctor.php?msg=updated");
            exit();
        } else {
            $error_message = "Update Failed: " . $result;
        }
    }
}

require 'includes/header.php';
?>

<section class="task-form-area container">
    <h2>Edit Doctor Profile</h2>
    <p class="guide-text">Update details for <strong><?= safeDisplay($firstname) ?> <?= safeDisplay($lastname) ?></strong>.</p>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" class="styled-form">
        
        <h4 style="margin-top: 0; color: #51AC74;">Staff Identity</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="staffno">Staff ID (Read Only)</label>
                <input type="text" value="<?= safeDisplay($target_staff_no) ?>" disabled style="background-color: #f0f0f0; color: #888;">
            </div>
            
            <div class="form-group">
                <label for="consultantstatus">Consultant Status</label>
                <select id="consultantstatus" name="consultantstatus">
                    <option value="0" <?= ($consultant_val == 0) ? 'selected' : '' ?>>No (Standard Doctor)</option>
                    <option value="1" <?= ($consultant_val == 1) ? 'selected' : '' ?>>Yes (Consultant)</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?= safeDisplay($firstname) ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?= safeDisplay($lastname) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="0" <?= ($gender_val == 0) ? 'selected' : '' ?>>Male</option>
                    <option value="1" <?= ($gender_val == 1) ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pay">Annual Pay (£)</label>
                <input type="number" id="pay" name="pay" step="0.01" value="<?= safeDisplay($pay) ?>">
            </div>
        </div>

        <h4 style="margin-top: 15px; color: #51AC74;">Professional Info</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="specialisation">Specialisation</label>
                <input type="text" id="specialisation" name="specialisation" value="<?= safeDisplay($specialisation) ?>" required>
            </div>
            <div class="form-group">
                <label for="qualification">Qualification</label>
                <input type="text" id="qualification" name="qualification" value="<?= safeDisplay($qualification) ?>">
            </div>
        </div>

        <h4 style="margin-top: 15px; color: #51AC74;">Login & Security</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="username">System Username</label>
                <input type="text" id="username" name="username" value="<?= safeDisplay($username) ?>" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Reset Password</label>
                <input type="text" id="password" name="password" placeholder="Leave blank to keep current password" autocomplete="new-password">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"><?= safeDisplay($address) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="doctor.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</section>

<?php require 'includes/footer.php'; ?>