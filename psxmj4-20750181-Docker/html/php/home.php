<?php
require 'includes/db_connection.php';
require 'includes/session.php';
require_login();

$page_title = 'QMC Dashboard';
require 'includes/header.php';

?>

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
    <?php endif; ?>

    <div class="task-card">
            <h4>Manage Account</h4>
            <p>Manage account settings.</p>
            <a href="manage_account.php" class="btn btn-primary">Manage Account</a>
    </div>

</div>

<?php
require 'includes/footer.php';
?>


