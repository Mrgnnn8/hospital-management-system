<?php
require 'includes/db_connection.php';
require 'includes/session.php';
require_login();
require 'includes/header.php';

$page_title = 'QMC Dashboard';

?>

<section class="dashboard-welcome">
    <h2>Welcome to the QMC System</h2>
</section>

<div class="task-grid">
    <div class="task-card">
            <h4>Patient Lookup</h4>
            <p>Search for patients by Name or NHS Number.</p>
            <a href="patient_lookup.php" class="btn btn-primary">Go to Search</a>
    </div>

    <div class="task-card">
        <h4>Parking Permit</h4>
        <p>View status or reqiest a new permit.</p>
        <a href="user_parking_permit.php" class="btn btn-primary">Manage Permit</a>
    </div>

</div>


