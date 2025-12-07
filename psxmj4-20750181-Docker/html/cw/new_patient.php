<?php

require 'includes/db_connection.php';
require 'includes/session.php';
require 'data_access/PatientDAO.php';

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
    } elseif (PatientDAO::getPatientNameById($conn, $nhs_no)) {
        $error_message = "A patient with NHS Number '$nhs_no' already exists in the system.";
    } else {

        $gender_int = ($raw_gender === 'Female') ? 1 : 0;

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
                logAction($conn, $_SESSION['username'], 'CREATE_PATIENT', "Registered patient $firstname $lastname ($nhs_no)");
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


<h2><?= $page_title ?></h2>
<p class="guide-text">Enter the details below to create a new patient record in the QMC system.</p>

<?php if ($error_message): ?>
    <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
<?php endif; ?>

<form method="POST" class="styled-form">

    <div class="form-group">
        <label for="nhs_no">NHS Number (Required):</label>
        <input type="text" id="nhs_no" name="nhs_no" required placeholder="e.g. W12345">
    </div>

    <div class="form-row" style="display: flex; gap: 15px;">
        <div class="form-group" style="flex: 1;">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
    </div>

    <div class="form-row" style="display: flex; gap: 15px;">
        <div class="form-group" style="flex: 1;">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?= safeDisplay($age) ?>" min="0" max="120">
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male" <?= ($gender_selection === 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($gender_selection === 'Female') ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="3"
            placeholder="Street, City, Postcode"><?= safeDisplay($address) ?></textarea>
    </div>

    <div class="form-row" style="display: flex; gap: 15px;">
        <div class="form-group" style="flex: 1;">
            <label for="phone">Primary Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?= safeDisplay($phone) ?>">
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="emergencyphone">Emergency Phone:</label>
            <input type="tel" id="emergencyphone" name="emergencyphone" value="<?= safeDisplay($emergencyphone) ?>">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Register Patient</button>
        <a href="patient_lookup.php" class="btn btn-secondary">Cancel</a>
    </div>

</form>
</section>

<?php require 'includes/footer.php'; ?>