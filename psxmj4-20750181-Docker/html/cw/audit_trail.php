<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/AuditDAO.php';
require_once 'data_access/formatDisplayValue.php';

require_login();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access Denied.");
}

$page_title = 'System Audit Trail';

$staff_search = trim($_GET['staffno'] ?? '');
$action_search = trim($_GET['action'] ?? '');

$logs = AuditDAO::getLogs($conn, $staff_search, $action_search);
$actions_list = AuditDAO::getActionTypes($conn);

require 'includes/header.php';
?>

<section class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>Audit Trail</h2>
            <p class="guide-text">Track database changes by Staff ID.</p>
        </div>
        
        <button onclick="window.print()" class="btn btn-secondary">Print / Export PDF</button>
    </div>

    <div class="task-form-area" style="padding: 20px; margin-top: 0; max-width: 100%; border-top: 3px solid #777;">
        <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 200px;">
                <label style="font-weight: bold; font-size: 0.9em; color: #555;">Filter by Staff ID:</label>
                <input type="text" name="staffno" style="width: 100%; padding: 8px;">
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="font-weight: bold; font-size: 0.9em; color: #555;">Filter by Action:</label>
                <select name="action" style="width: 100%; padding: 8px;">
                    <option value="">-- All Actions --</option>
                    <?php while($row = $actions_list->fetch_assoc()): ?>
                        <option value="<?= safeDisplay($row['action_type']) ?>" 
                            <?= ($action_search === $row['action_type']) ? 'selected' : '' ?>>
                            <?= safeDisplay($row['action_type']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin: 0; padding: 10px 20px;">Search Logs</button>
            <?php if($staff_search || $action_search): ?>
                <a href="audit_trail.php" class="btn btn-secondary" style="margin: 0;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="styled-table" style="font-size: 0.85em;">
        <thead>
            <tr>
                <th style="width: 140px;">Date & Time</th>
                <th style="width: 100px;">Staff ID</th>
                <th style="width: 80px;">Role</th>
                <th style="width: 150px;">Action Type</th>
                <th>Description / Details</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($logs->num_rows > 0): ?>
                <?php while ($log = $logs->fetch_assoc()): 
                    $color = '#333';
                    if (strpos($log['action_type'], 'DELETE') !== false || strpos($log['action_type'], 'REJECT') !== false) $color = '#d9534f'; 
                    if (strpos($log['action_type'], 'CREATE') !== false || strpos($log['action_type'], 'APPROVE') !== false) $color = '#51AC74'; 
                ?>
                    <tr>
                        <td style="color: #777; font-weight: bold;">
                            <?= date('d-M-Y H:i', strtotime($log['timestamp'])) ?>
                        </td>
                        
                        <td>
                            <a href="audit_trail.php?staffno=<?= urlencode($log['staffno']) ?>" style="text-decoration: none; color: #51AC74; font-weight: bold;">
                                <?= safeDisplay($log['staffno']) ?>
                            </a>
                        </td>

                        <td>
                            <span style="background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;">
                                <?= safeDisplay($log['role']) ?>
                            </span>
                        </td>

                        <td style="color: <?= $color ?>; font-weight: bold;">
                            <?= safeDisplay($log['action_type']) ?>
                        </td>

                        <td><?= safeDisplay($log['description']) ?></td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No logs found matching your criteria.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require 'includes/footer.php'; ?>