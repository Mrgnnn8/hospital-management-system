<?php

// Should be included on every page past index.php

require_once 'data_access/DoctorDAO.php';

$page_title = $page_title ?? 'QMC Dashboard';

$is_admin = ($_SESSION['is_admin'] ?? 0) == 1;
$username = htmlspecialchars($_SESSION['username'] ?? 'Guest');
$access_level = $is_admin ? 'Admin' : 'Doctor';

$staffno = DoctorDAO::getStaffNoByUsername($conn, $username)

?>

<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-sclae=1.0">
    <title><?= $page_title ?> - Queens Medical Centre</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<header>
    <div class="header-container">
        <h1>Queens Medical Centre</h1>

        <nav>
            <ul>
                <li><a href="home.php">Dashboard</a></li>

                <li><a href="patient_lookup.php">Patient Directory</a></li>

                <li><a href="ward_capacity.php">Ward Capacity</a></li>

                <li><a href="add_new_test_type.php">New Test</a></li>

                <?php if ($is_admin): ?>
                    <li><a href="doctor.php">Staff Directory</a></li>
                <?php endif; ?>

                <?php if ($is_admin): ?>
                    <li><a href="admin_parking.php">View permit requests</a></li>
                
                    <li class="admin-link"><a href="audit_trail.php">Audit Trail</a></li>

                <?php endif; ?>

                <li><a href="manage_account.php">Manage Account</a></li>

                <li><a href="includes/logout.php" class="btn btn-logout">Log Out</a></li>
            </ul>
        </nav>
    </div>        
</header>

<div class="user-info-bar">
    <div class="info-container">
        <span>Logged in as: <strong><?= $staffno ?></strong></span>
    </div>
</div>

