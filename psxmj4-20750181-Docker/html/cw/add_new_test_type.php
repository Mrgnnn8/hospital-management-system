<?php
// Display errors for debugging
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php'; 
require_once 'data_access/TestDAO.php';
require_once 'data_access/formatDisplayValue.php'; 

// ✅ 1. INCLUDE AUDIT FUNCTIONS
if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
}

require_login();

$page_title = 'Manage Test Catalogue';
$message = '';

// Handle Form Submission
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $new_test_name = safeDisplay($_POST['test_name'] ?? '');

    if (empty($new_test_name)) {
        $message = "<div class='alert alert-danger'>Test name cannot be empty.</div>";
    } else {
        $new_id = TestDAO::createNewTest($conn, $new_test_name);

        if ($new_id) {
            // ✅ AUDIT TRIGGER
            if (function_exists('logAction')) {
                logAction($conn, $_SESSION['username'], 'CREATE_TEST', "Added new test type: $new_test_name");
            }
            
            $message = "<div class='alert alert-success'>Successfully added '<strong>$new_test_name</strong>' (ID: $new_id).</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: '$new_test_name' may already exist.</div>";
        }
    }
}

$tests = TestDAO::getAvailableTests($conn);

require 'includes/header.php';
?>

<div class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>Test Catalogue</h2>
            <p class="guide-text">Manage the list of diagnostic tests available for prescription.</p>
        </div>
    </div>

    <section class="task-form-area" style="margin-bottom: 40px;">
        <h3 style="margin-top: 0; color: #51AC74;">Add New Test Type</h3>
        
        <?= $message ?>

        <form method="POST" class="styled-form" style="display: flex; gap: 15px; align-items: flex-end;">
            <div class="form-group" style="flex-grow: 1; margin-bottom: 0;">
                <label for="test_name">Test Name:</label>
                <input type="text" id="test_name" name="test_name" required autocomplete="off">
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-bottom: 0; height: 46px;">
                Add to Database
            </button>
        </form>
    </section>

    <h3 style="color: #333; margin-bottom: 10px;">Current Catalogue</h3>
    
    <?php if ($tests && $tests->num_rows > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th style="width: 150px;">Test ID</th>
                    <th>Test Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $tests->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?= safeDisplay($row['testid']) ?></strong></td>
                        <td><?= safeDisplay($row['testname']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning" style="text-align: center;">
            No tests found in the database. Use the form above to add one.
        </div>
    <?php endif; ?>

</div>

<?php require 'includes/footer.php'; ?>