<?php

//Front end functionality to add a new patient into the 'patient' database.

ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/PatientDAO.php';
require_once 'data_access/formatDisplayValue.php'; 

if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
}

require_login();

$page_title = 'Register New Patient';
$error_message = '';

$nhs_no = '';
$firstname = '';
$lastname = '';
$phone = '';
$address = '';
$age = '';
$gender_selection = '';
$emergencyphone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nhs_no = trim($_POST['nhs_no'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $raw_gender = $_POST['gender'] ?? 'Male';
    $emergencyphone = trim($_POST['emergencyphone'] ?? '');

    $gender_selection = $raw_gender;

    if (empty($nhs_no) || empty($firstname) || empty($lastname)) {
        $error_message = "Please fill in all required fields (NHS No, First Name, Last Name).";
    } elseif (strlen($nhs_no) !== 6) { 
        $error_message = "NHS Number must be exactly 6 characters long.";
    } elseif (PatientDAO::getPatientNameById($conn, $nhs_no)) {
        $error_message = "A patient with NHS Number '$nhs_no' already exists in the system.";
    } else {

        $gender_int = 0;
        if ($raw_gender === 'Female') {
            $gender_int = 1;
        } elseif ($raw_gender === 'Other') {
            $gender_int = 2;
        }

        $result = PatientDAO::insertPatient(
            $conn,
            $nhs_no,
            $firstname,
            $lastname,
            $phone,
            $address,
            $age,
            $gender_int,
            $emergencyphone
        );

        if ($result) {
            if (function_exists('logAction')) {
                logAction($conn, $_SESSION['username'], 'CREATE_PATIENT', "Registered patient $firstname $lastname (NHS: $nhs_no)");
            }

            header("Location: patient_lookup.php?view_nhs=" . urlencode($nhs_no) . "&status=patient_created");
            exit();

        } else {
            $error_message = "Database error: Could not register patient. Check inputs.";
        }
    }
}

require 'includes/header.php';
?>

<section class="task-form-area container">

    <h2><?= $page_title ?></h2>
    <p class="guide-text">Enter the details below to create a new patient record.</p>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" class="styled-form" id="patientForm">

        <div class="form-group">
            <label for="nhs_no">NHS Number:</label>
            <input type="text" id="nhs_no" name="nhs_no" maxlength="6" minlength="6"
                   value="<?= safeDisplay($nhs_no, '') ?>" required 
                   placeholder="e.g. W12345"
                   oninvalid="this.setCustomValidity('Please enter exactly 6 characters for the NHS Number.')"
                   oninput="this.setCustomValidity('')">
        </div>

        <div class="form-row" style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="firstname" 
                       value="<?= safeDisplay($firstname, '') ?>" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="lastname" 
                       value="<?= safeDisplay($lastname, '') ?>" required>
            </div>
        </div>

        <div class="form-row" style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" 
                       value="<?= safeDisplay($age, '') ?>" min="0" max="120" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?= ($gender_selection === 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= ($gender_selection === 'Female') ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= ($gender_selection === 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3" required><?= safeDisplay($address, '') ?></textarea>
        </div>

        <div class="form-row" style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label for="phone">Primary Phone:</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?= safeDisplay($phone, '') ?>" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="emergencyphone">(Optional) Emergency Phone:</label>
                <input type="tel" id="emergencyphone" name="emergencyphone" 
                       value="<?= safeDisplay($emergencyphone, '') ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Patient</button>
            <a href="patient_lookup.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</section>

<script src="js/validate_patient.js"></script>

<?php require 'includes/footer.php'; ?>