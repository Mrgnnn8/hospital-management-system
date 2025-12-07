<?php
require 'includes/db_connection.php'; 
require 'includes/session.php'; 
require 'data_access/TestDAO.php';
require 'data_access/PatientDAO.php'; 
require 'data_access/formatDisplayValue.php'; 
require_login();

$page_title = 'Record Test Result';
$error_message = '';

$patient_pid = safeDisplay($_GET['pid'] ?? '');
$current_doctor_id = $_SESSION['username']; 

$patient_name = PatientDAO::getPatientNameById($conn, $patient_pid);

if (empty($patient_pid) || !$patient_name) {

    header('Location: patient_lookup.php'); 
    exit();
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $test_id = safeDisplay($_POST['test_id'] ?? '');
    $test_date = safeDisplay($_POST['test_date'] ?? '');
    $report = safeDisplay($_POST['report'] ?? '');

    if (empty($test_id) || empty($test_date)) {
        $error_message = 'Please select a test and a date.';
    } else {
        $result = TestDAO::recordResult($conn, $patient_pid, $test_id, $current_doctor_id, $test_date, $report);

        if ($result) {
            if (function_exists('logAction')) {
                logAction($conn, $current_doctor_id, 'RECORD_RESULT', "Recorded Test ID $test_id for PID $patient_pid");
            }

            header('Location: patient_lookup.php?view_nhs=' . $patient_pid . '&status=result_recorded');
            exit();

        } else {
            $error_message = 'Error recording result. Database operation failed.';
        }
    }
}

$available_tests = TestDAO::getAvailableTests($conn);

require 'includes/header.php';
?>

<section class="task-form-area">
    <h2><?= $page_title ?></h2>
    
    <p class="guide-text">
        Recording result for: <strong><?= $patient_name ?> (<?= $patient_pid ?>)</strong>
    </p>

    <?php if ($error_message): ?>
        <div class='alert alert-danger'><?= $error_message ?></div>
    <?php endif; ?>
    
    <form method="POST" class="styled-form">
        <div class="form-group">
            <label for="test_id">Select Test Type:</label>
            <select name="test_id" id="test_id" required>
                <option value="">-- Select a Test --</option>
                <?php 
                if ($available_tests) {
                    while ($row = $available_tests->fetch_assoc()) {
                        echo '<option value="' . $row['testid'] . '">' . safeDisplay($row['testname']) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="test_date">Date Performed:</label>
            <input type="date" id="test_date" name="test_date" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label for="report">Result / Report Details:</label>
            <textarea id="report" name="report" rows="4" placeholder="Enter findings here..."></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Result</button>
            <a href="patient_lookup.php?view_nhs=<?= $patient_pid ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>