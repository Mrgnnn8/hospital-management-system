<?php

$page_title = $page_title ?? 'QMC Dashboard';

$is_admin = ($_SESSION['is_admin'] ?? 0) == 1;
$username = htmlspecialchars($_SESSION['username'] ?? 'Guest');
$access_level = $is_admin ? 'Admin' : 'Doctor';
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
        <h1>Queens Medical Centre System</h1>

        <nav>
            <ul>
                <li><a href="home.php">Dashboard</a></li>

                <li><a href="patient_lookup.php">Patient Lookup</a></li>

                <li><a href="test_new.php">Add & Prescribe Test</a></li>

                <li><a href="change_password.php">Change Password</a></li>

                <li><a href="user_parking_permit.php">Parking Permit Status</a></li>

                <?php if ($is_admin): ?>
                    <li class="admin-link"><a href="admin_manage_doctors.php">Manage Doctors</a></li>
                    <li class="admin-link"><a href="admin_audit_trail.php">Audit Trail</a></li>
                <?php endif; ?>

                <li><a href="includes/logout.php" class="btn btn-logout">Log Out</a></li>
            </ul>
        </nav>
    </div>        
</header>

<div class="user-info-bar">
    <div class="info-container">
        <span>Logged in as: <strong><?= $username ?></strong></span>
        <span>Access Level: <strong><?= $access_level ?></strong></span>
    </div>
</div>

<main class="container">
