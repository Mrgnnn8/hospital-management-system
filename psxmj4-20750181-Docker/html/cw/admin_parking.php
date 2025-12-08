<?php
require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/ParkingDAO.php';
require 'data_access/formatDisplayValue.php';

require_login();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: home.php"); 
    exit();
}

$page_title = 'Manage Parking Permits';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $app_id = $_POST['app_id'] ?? 0;
    $action = $_POST['action'] ?? '';

    if ($action === 'approve') {
        $permit_no = trim($_POST['permit_no'] ?? '');
        
        if (empty($permit_no)) {
            $error = "You must enter a Permit Number to approve.";
        } elseif (strlen($permit_no) > 50) {
            $error = "Permit Number is too long.";
        } else {
            if (ParkingDAO::approveRequest($conn, $app_id, $permit_no)) {
                $message = "Application #$app_id Approved.";
            } else {
                $error = "Database error approving request.";
            }
        }

    } elseif ($action === 'reject') {
        $reason = trim($_POST['reject_reason'] ?? '');
        
        if (empty($reason)) {
            $error = "You must provide a reason for rejection.";
        } else {
            if (ParkingDAO::rejectRequest($conn, $app_id, $reason)) {
                $message = "Application #$app_id Rejected.";
            } else {
                $error = "Database error rejecting request.";
            }
        }
    }
}

$all_requests = ParkingDAO::getAllRequests($conn);

require 'includes/header.php';
?>
    <main class="container">
    <h2>Manage Parking Permits</h2>
    <p class="guide-text">Review staff parking applications. Approve with a permit number or reject with a reason.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= safeDisplay($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= safeDisplay($error) ?></div>
    <?php endif; ?>

    <table class="styled-table" style="font-size: 0.9em;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Staff Ref</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th>Fee</th>
                <th>Requested</th>
                <th>Current Status</th>
                <th style="width: 350px;">Actions</th> </tr>
        </thead>
        <tbody>
            <?php while ($row = $all_requests->fetch_assoc()): 
                $status = $row['status'];
                $is_pending = ($status === 'Pending' || $status === 'Awaiting approval');
                
                $status_color = 'orange';
                if ($status === 'Approved') $status_color = 'green';
                if ($status === 'Rejected') $status_color = 'red';
            ?>
                <tr>
                    <td>#<?= safeDisplay($row['permit_application_id']) ?></td>
                    <td><?= safeDisplay($row['StaffNo']) ?></td>
                    <td><?= safeDisplay($row['vehicle_reg']) ?></td>
                    <td><?= safeDisplay($row['permit_choice']) ?></td>
                    <td>£<?= safeDisplay($row['amount']) ?></td>
                    <td><?= safeDisplay($row['request_date']) ?></td>
                    
                    <td>
                        <strong style="color: <?= $status_color ?>;">
                            <?= safeDisplay($status) ?>
                        </strong>
                        <?php if ($status === 'Approved'): ?>
                            <br><small>Permit: <?= safeDisplay($row['permit_no']) ?></small>
                        <?php endif; ?>
                        <?php if ($status === 'Rejected'): ?>
                            <br><small>Note: <?= safeDisplay($row['notes']) ?></small>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($is_pending): ?>
                            
                            <form method="POST" style="display: flex; gap: 5px; margin-bottom: 5px;">
                                <input type="hidden" name="app_id" value="<?= $row['permit_application_id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <input type="text" name="permit_no" placeholder="Permit No (e.g. P-100)" required 
                                       style="width: 140px; padding: 5px; font-size: 0.85em;">
                                <button type="submit" class="btn btn-primary" 
                                        style="padding: 5px 10px; font-size: 0.85em;">Approve</button>
                            </form>

                            <form method="POST" style="display: flex; gap: 5px;">
                                <input type="hidden" name="app_id" value="<?= $row['permit_application_id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <input type="text" name="reject_reason" placeholder="Reason for rejection..." required 
                                       style="width: 140px; padding: 5px; font-size: 0.85em;">
                                <button type="submit" class="btn btn-danger" 
                                        style="padding: 5px 10px; font-size: 0.85em; background-color: #dc3545; color: white; border: none;">Reject</button>
                            </form>

                        <?php else: ?>
                            <span style="color: #777;">Action Complete</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<?php require 'includes/footer.php'; ?>