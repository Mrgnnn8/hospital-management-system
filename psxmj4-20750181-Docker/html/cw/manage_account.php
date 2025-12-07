<?php

require 'includes/db_connection.php'; 
require 'includes/session.php'; 

require_login();

$page_title = 'Manage Account & Profile';
$doctor_id = $_SESSION['username']; 
$error_message = '';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
}

require 'includes/header.php'; 
?>

    <h2>Doctor Profile Overview</h2>
    
    <div class="profile-details-grid">
        <div class="detail-card">
            <h3>Account Actions (Task 1)</h3>
            <p>Manage security and parking requests.</p>
            <hr>
            
            <p><a href="change_password.php" class="btn btn-secondary">Change Password</a></p>

            <p><a href="user_parking_permit.php" class="btn btn-secondary">View/Request Parking Permit</a></p>
        </div>

        <div class="detail-card">
            <h3>Personal Information</h3>
            <p>Review and update your core staff details.</p>
            <hr>
            
            <table class="styled-table no-header">
                <tbody>
                    <tr><th>Staff ID:</th><td><?= safeDisplay($doctor_data['Doctor_id']) ?></td></tr>
                    <tr><th>Specialization:</th><td><?= safeDisplay($doctor_data['Specialisation']) ?></td></tr>
                    <tr><th>Current Pay:</th><td><?= safeDisplay($doctor_data['Pay']) ?></td></tr>
                    <tr><th>Ward:</th><td><?= safeDisplay($doctor_data['Ward_id']) ?></td></tr>
                    <tr><th>Address:</th><td><?= safeDisplay($doctor_data['Address']) ?></td></tr>
                </tbody>
            </table>
            
            <a href="edit_profile.php" class="btn btn-primary">Edit Details</a>
        </div>
    </div>
</section>

<?php
require 'includes/footer.php';
?>