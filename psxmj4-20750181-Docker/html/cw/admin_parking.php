<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/ParkingDAO.php';
require_once 'data_access/formatDisplayValue.php';

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
            $error = "Permit Number is required.";
        } else {
            if (ParkingDAO::approveRequest($conn, $app_id, $permit_no)) {
                $message = "Application #$app_id Approved (Permit: $permit_no).";
            } else {
                $error = "Database Error.";
            }
        }
    } elseif ($action === 'reject') {
        $reason = trim($_POST['reject_reason'] ?? '');
        if (empty($reason)) {
            $error = "Rejection reason is required.";
        } else {
            if (ParkingDAO::rejectRequest($conn, $app_id, $reason)) {
                $message = "Application #$app_id Rejected.";
            } else {
                $error = "Database Error.";
            }
        }
    }
}

$all_requests = ParkingDAO::getAllRequests($conn);
require 'includes/header.php';
?>

<section class="container">
    <h2>Manage Parking Permits</h2>
    <p class="guide-text">Review and process staff parking applications.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= safeDisplay($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= safeDisplay($error) ?></div>
    <?php endif; ?>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Staff Ref</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th>Fee</th>
                <th>Status</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $all_requests->fetch_assoc()): 
                $status = $row['status'];
                $is_pending = ($status === 'Pending' || $status === 'Awaiting approval');
                $color = ($status === 'Approved') ? 'green' : (($status === 'Rejected') ? 'red' : 'orange');
            ?>
                <tr>
                    <td>#<?= safeDisplay($row['permit_application_id']) ?></td>
                    <td><?= safeDisplay($row['StaffNo']) ?></td>
                    <td><?= safeDisplay($row['vehicle_reg']) ?></td>
                    <td><?= safeDisplay($row['permit_choice']) ?></td>
                    <td>£<?= safeDisplay($row['amount']) ?></td>
                    <td style="color: <?= $color ?>; font-weight: bold;"><?= safeDisplay($status) ?></td>
                    
                    <td style="text-align: center;">
                        <?php if ($is_pending): ?>
                            <button class="btn-success" onclick="openApproveModal(<?= $row['permit_application_id'] ?>)">
                                ✓ Approve
                            </button>
                            
                            <button class="btn-danger" onclick="openRejectModal(<?= $row['permit_application_id'] ?>)">
                                ✕ Reject
                            </button>
                        <?php else: ?>
                            <small style="color: #999;">Completed</small>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<div id="approveModal" class="modal-overlay">
    <div class="modal-box">
        <button class="close-modal" onclick="closeModals()">×</button>
        <div class="modal-header" style="color: #51AC74;">Approve Request</div>
        <p>Please assign a Permit Number to finalize this approval.</p>
        
        <form method="POST" class="styled-form">
            <input type="hidden" name="action" value="approve">
            <input type="hidden" name="app_id" id="approve_app_id">
            
            <div class="form-group">
                <label>Permit Number</label>
                <input type="text" name="permit_no" placeholder="e.g. P-2025-001" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Confirm Approval</button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" class="modal-overlay">
    <div class="modal-box" style="border-top-color: #d9534f;">
        <button class="close-modal" onclick="closeModals()">×</button>
        <div class="modal-header" style="color: #d9534f;">Reject Request</div>
        <p>Please provide a reason for rejecting this application.</p>
        
        <form method="POST" class="styled-form">
            <input type="hidden" name="action" value="reject">
            <input type="hidden" name="app_id" id="reject_app_id">
            
            <div class="form-group">
                <label>Reason for Rejection</label>
                <textarea name="reject_reason" rows="3" placeholder="e.g. Payment failed, Incorrect vehicle details..." required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-secondary" style="width: 100%; background-color: #d9534f;">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal(id) {
        document.getElementById('approve_app_id').value = id; 
        document.getElementById('approveModal').style.display = 'flex'; 
    }

    function openRejectModal(id) {
        document.getElementById('reject_app_id').value = id; 
        document.getElementById('rejectModal').style.display = 'flex'; 
    }

    function closeModals() {
        document.getElementById('approveModal').style.display = 'none';
        document.getElementById('rejectModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeModals();
        }
    }
</script>

<?php require 'includes/footer.php'; ?>