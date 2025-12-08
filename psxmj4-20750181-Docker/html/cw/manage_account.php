<?php
// Display errors for debugging
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php'; 
require 'includes/session.php'; 
require_once 'data_access/formatDisplayValue.php';
require_once 'data_access/DoctorDAO.php';

require_login();

$page_title = 'My Profile';
$error_message = '';
$success_message = '';
$doctor_data = null;

$username = $_SESSION['username']; 
$real_staff_no = DoctorDAO::getStaffNoByUsername($conn, $username);

if (isset($_GET['status']) && $_GET['status'] === 'updated') {
    $success_message = "Your profile details have been successfully updated.";
}

if ($real_staff_no) {
    $doctor_data = DoctorDAO::getDoctorProfile($conn, $real_staff_no);
} else {
    $error_message = "Error: Your account is not linked to a Doctor Profile.";
}

require 'includes/header.php'; 
?>

<section class="task-form-area container">

    <div style="border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="border: none; margin: 0; padding: 0; font-size: 2rem; color: #333;">
                <?= safeDisplay(($doctor_data['firstname'] ?? '') . ' ' . ($doctor_data['lastname'] ?? 'Doctor')) ?>
            </h2>
            <p style="margin: 5px 0 0; color: #777; font-size: 1.1em;">
                Staff ID: <strong><?= safeDisplay($real_staff_no) ?></strong>
            </p>
        </div>
    
    </div>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= safeDisplay($success_message) ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
    <?php else: ?>

        <h4 style="color: #51AC74; margin-top: 0;">Personal Details</h4>
        <table class="styled-table" style="margin-top: 10px;">
            <tbody>
                <tr>
                    <th style="width: 200px; color: #555;">Full Name</th>
                    <td><?= safeDisplay($doctor_data['firstname']) ?> <?= safeDisplay($doctor_data['lastname']) ?></td>
                </tr>
                <tr>
                    <th style="color: #555;">System Username</th>
                    <td><?= safeDisplay($doctor_data['username']) ?></td>
                </tr>
                <tr>
                    <th style="color: #555;">Gender</th>
                    <td>
                        <?php 
                            $g = $doctor_data['gender'];
                            echo ($g == 1) ? 'Female' : (($g == 2) ? 'Other' : 'Male');
                        ?>
                    </td>
                </tr>
                <tr>
                    <th style="color: #555;">Current Pay Band</th>
                    <td>£<?= safeDisplay($doctor_data['pay']) ?> / year</td>
                </tr>
                <tr>
                    <th style="color: #555;">Home Address</th>
                    <td><?= safeDisplay($doctor_data['address']) ?></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <h4 style="color: #51AC74; margin-top: 0; margin-bottom: 15px;">Account Actions</h4>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="edit_profile.php" class="btn btn-primary" style="margin: 0;">
                    Edit My Details
                </a>

                <a href="change_password.php" class="btn btn-secondary" style="margin: 0;">
                    Change Password
                </a>
                
                <a href="user_parking_permit.php" class="btn btn-secondary" style="margin: 0;">
                    Parking Permit Application
                </a>
            </div>
        </div>

    <?php endif; ?>

</section>

<?php require 'includes/footer.php'; ?>