<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php'; 
require 'includes/session.php'; 
require_once 'data_access/formatDisplayValue.php';
require_once 'data_access/DoctorDAO.php';

require_login();

$page_title = 'Manage Account & Profile';
$error_message = '';
$doctor_data = null; 
$username = $_SESSION['username']; 

$real_staff_no = DoctorDAO::getStaffNoByUsername($conn, $username);

if ($real_staff_no) {
    $doctor_data = DoctorDAO::getDoctorProfile($conn, $real_staff_no);
} else {
    $error_message = "Error: Your account is not linked to a Doctor Profile.";
}

require 'includes/header.php'; 
?>
<main class="container">

<h2>Doctor Profile Overview</h2>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
    <?php endif; ?>

    <div class="profile-details-grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
        
        <div class="detail-card task-card">
            <h3>Account Actions</h3>
            <hr>
            <p><a href="change_password.php" class="btn btn-secondary" style="width:100%; margin-bottom: 10px;">Change Password</a></p>
            <p><a href="user_parking_permit.php" class="btn btn-secondary" style="width:100%;">Parking Permit</a></p>
            <p><a href="edit_profile.php" class="btn btn-secondary" style="width:100%;">Edit Information</a></p>

        </div>

        <div class="detail-card task-card">
            <h3>Personal Information</h3>
            <p class="guide-text">Review your core staff details.</p>
            <hr>
            
            <?php if ($doctor_data): ?>
                <table class="styled-table no-header">
                    <tbody>
                        <tr><th>Staff ID:</th><td><?= safeDisplay($doctor_data['Doctor_id'] ?? $doctor_data['staffno']) ?></td></tr>
                        <tr>
                        <th>Name:</th>
                        <td><?= safeDisplay(($doctor_data['firstname'] ?? '') . ' ' . ($doctor_data['lastname'] ?? '')) ?></td>
                        </tr>
                        <tr><th>Username:</th><td><?= safeDisplay($doctor_data['username']) ?></td></tr>
                        <tr>
                        <th>Gender:</th>
                        <td><?= safeDisplay($doctor_data['gender'] == 1 ? 'Female' : 'Male') ?></td>
                        </tr>
                        <tr><th>Current Pay:</th><td>£<?= safeDisplay($doctor_data['pay']) ?></td></tr>
                        <tr><th>Address:</th><td><?= safeDisplay($doctor_data['address']) ?></td></tr>

                    </tbody>
                </table>
                <br>
            <?php else: ?>
                <p>Profile data unavailable.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>