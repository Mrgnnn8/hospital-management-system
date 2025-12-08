<?php
require 'includes/db_connection.php';
require 'includes/session.php';
require_login();

$page_title = 'QMC Dashboard';
require 'includes/header.php';
require_once 'data_access/WardDAO.php';

?>

<main class="container">

<section class="dashboard-welcome">

    <h2>Welcome to the QMC System</h2>
</section>

<div class="task-grid">
    <div class="task-card">
            <h4>Patient Directory</h4>
            <p>Access existing patient records.</p>
            <a href="patient_lookup.php" class="btn btn-primary">Go to Search</a>
    </div>

    <?php if ($is_admin): ?>

        <div class="task-card">
                <h4>Doctor Directory</h4>
                <p>View all currently employed doctors.</p>
                <a href="doctor.php" class="btn btn-primary">View Doctors</a>
        </div>

        <div class="task-card">
                <h4>Parking Permit Requests</h4>
                <p>View pending actions on parking permit requests.</p>
                <a href="admin_parking.php" class="btn btn-primary">View Parking Permit Requests</a>
        </div>

        <div class="task-card">
                <h4>User Activity</h4>
                <p>A detailed database for all user activity.</p>
                <a href="audit_trail.php" class="btn btn-primary">View User Activity</a>
        </div>

    <?php endif; ?>

    <div class="task-card">
            <h4>Manage Account</h4>
            <p>Manage account settings.</p>
            <a href="manage_account.php" class="btn btn-primary">Manage Account</a>
    </div>

    <div class="task-card">
        <h4>Hospital Ward Capacity Overview</h4>
        <p>View spaces available<p>
        <a href="ward_capacity.php" class="btn btn-primary">View Availability</a>
    </div>

    <div class="task-card">
        <h4>Add a New Test Type</h4>
        <p>Add a new test type to the database<p>
        <a href="add_new_test_type.php" class="btn btn-primary">Add a new test</a>
    </div>

</div>

<?php
require 'includes/footer.php';
?>


