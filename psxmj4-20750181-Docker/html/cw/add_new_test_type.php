<?php

require 'includes/db_connection.php';
require 'includes/session.php'; 
require 'data_access/TestDAO.php';
require 'data_access/formatDisplayValue.php'; 

require_login();

$page_title = 'Add New Test Type';
$message = '';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $new_test_name = safeDisplay($_POST['test_name'] ?? '');

    if (empty($new_test_name)) {
        $message = "<div class='alert alert-danger'>Test name cannot be empty.</div>";
    } else {
        $new_id = TestDAO::createNewTest($conn, $new_test_name);

        if ($new_id) {
            if (function_exists('logAction')) {
                logAction($conn, $_SESSION['username'], 'CREATE_TEST', "Added new test type: $new_test_name");
            }
            $message = "<div class='alert alert-success'>Successfully added '$new_test_name' to the catalogue.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error adding test. It may already exist.</div>";
        }
    }
}

require 'includes/header.php';
?>

    <h2>Add New Test to Catalogue</h2>
    
    <p class="guide-text">
        Need to prescribe a new type of test to a patient? Here is where you add the test type to the database so it can be attached to a patient
    </p>

    <?= $message ?>

    <form method="POST" class="styled-form">
        <div class="form-group">
            <label for="test_name">New Test Name:</label>
            <input type="text" id="test_name" name="test_name" placeholder="e.g. MRI Scan, Liver Function Test" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add to Catalogue</button>
            <a href="home.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
    
    <hr>
    <h3>Current Test Catalogue</h3>
    <ul style="column-count: 2;">
        <?php
        $tests = TestDAO::getAvailableTests($conn);
        while ($row = $tests->fetch_assoc()) {
            echo "<li>" . safeDisplay($row['testname']) . "</li>";
        }
        ?>
    </ul>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>