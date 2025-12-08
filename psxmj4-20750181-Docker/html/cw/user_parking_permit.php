<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/ParkingDAO.php';
require_once 'data_access/DoctorDAO.php';
require_once 'data_access/formatDisplayValue.php';

if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
}

require_login();

$page_title = 'Parking Permit Request';
$success_message = '';
$error_message = '';

$login_username = $_SESSION['username'];

$real_staff_no = DoctorDAO::getStaffNoByUsername($conn, $login_username);

if (!$real_staff_no) {
    $error_message = "Error: Your account ('$login_username') is not linked to a valid Doctor Profile. Please contact an Administrator.";
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($real_staff_no) {
        $vehicle_reg = safeDisplay($_POST['vehicle_reg'] ?? '');
        $payment_type = safeDisplay($_POST['payment_type'] ?? '');

        if (empty($vehicle_reg) || empty($payment_type)) {
            $error_message = "Please provide vehicle registration and payment preference.";
        } else {
            $result = ParkingDAO::createRequest($conn, $real_staff_no, $vehicle_reg, $payment_type);

            if ($result) {
                if (function_exists('logAction')) {
                    logAction($conn, $login_username, 'PARKING_REQUEST', "Requested $payment_type permit for $vehicle_reg");
                }
                
                header("Location: user_parking_permit.php?status=success");
                exit();
            } else {
                $error_message = "Error submitting request. Please check if you already have a pending application.";
            }
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $success_message = "Application submitted successfully! Status is Pending.";
}

$my_requests = null;
if ($real_staff_no) {
    $my_requests = ParkingDAO::getUserRequests($conn, $real_staff_no);
}

require 'includes/header.php';
?>

<section class="task-form-area container">

    <h2>Request Staff Parking</h2>

    <?php if ($real_staff_no): ?>
        <p class="guide-text">
            Logged in as: <strong><?= safeDisplay($login_username) ?></strong><br>
            Staff Ref: <strong><?= safeDisplay($real_staff_no) ?></strong>
        </p>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if ($real_staff_no): ?>
        <form method="POST" class="styled-form">

            <div class="form-group">
                <label for="vehicle_reg">Vehicle Registration Number:</label>
                <input type="text" id="vehicle_reg" name="vehicle_reg" placeholder="e.g. AB12 CDE" required>
            </div>

            <div class="form-group">
                <label for="payment_type">Payment Plan:</label>
                <select name="payment_type" id="payment_type" onchange="updateFee()" required>
                    <option value="">View Options...</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Yearly">Annual</option>
                </select>
            </div>

            <div class="form-group" style="background: #f9f9f9; padding: 10px; border-left: 4px solid #51AC74;">
                <label>Estimated Fee:</label>
                <span id="fee_display" style="font-weight: bold; font-size: 1.2em;">£0.00</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="manage_account.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php else: ?>
        <p class="empty-state">Unable to load application form. Identity verification failed.</p>
    <?php endif; ?>

</section>

<hr class="section-divider">

<section class="container">
    <h3>Application History</h3>
    <?php if ($my_requests && $my_requests->num_rows > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Vehicle</th>
                    <th>Date Requested</th>
                    <th>Payment Type</th> 
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $my_requests->fetch_assoc()):
                    $status = $row['status'];
                    $color = ($status === 'Approved') ? 'green' : (($status === 'Rejected') ? 'red' : 'black');
                    ?>
                    <tr>
                        <td>#<?= safeDisplay($row['permit_application_id']) ?></td>
                        <td><?= safeDisplay($row['vehicle_reg']) ?></td>
                        <td><?= safeDisplay($row['request_date']) ?></td>
                        <td><?= safeDisplay($row['permit_choice']) ?></td> 
                        <td>£<?= safeDisplay($row['amount']) ?></td>
                        <td style="color: <?= $color ?>; font-weight: bold;"><?= safeDisplay($status) ?></td>
                        <td><?= safeDisplay($row['notes'], '-') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no parking permit applications on record.</p>
    <?php endif; ?>
</section>

<script>
    function updateFee() {
        const selector = document.getElementById('payment_type');
        const display = document.getElementById('fee_display');
        const val = selector.value;
        if (val === 'Monthly') display.innerHTML = "£20.00 / month";
        else if (val === 'Yearly') display.innerHTML = "£200.00 / year";
        else display.innerHTML = "£0.00";
    }
</script>

<?php require 'includes/footer.php'; ?>