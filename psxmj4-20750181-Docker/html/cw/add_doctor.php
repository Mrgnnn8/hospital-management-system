<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/DoctorDAO.php';
require_once 'data_access/formatDisplayValue.php'; 

if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
}

require_login();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access Denied: Administrator privileges required.");
}

$page_title = 'Register New Doctor';
$error_message = '';

$staffNo = '';
$firstname = '';
$lastname = '';
$username = '';
$specialisation = '';
$qualification = '';
$pay = '';
$gender_selection = 'Male'; 
$consultant_status = '0'; 
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $staffNo        = trim($_POST['staffno'] ?? '');
    $firstname      = trim($_POST['firstname'] ?? '');
    $lastname       = trim($_POST['lastname'] ?? '');
    $username       = trim($_POST['username'] ?? '');
    $password       = $_POST['password'] ?? ''; 
    $specialisation = trim($_POST['specialisation'] ?? '');
    $qualification  = trim($_POST['qualification'] ?? ''); 
    $pay            = trim($_POST['pay'] ?? '0');
    $raw_gender     = $_POST['gender'] ?? 'Male';
    $consultant_status = $_POST['consultantstatus'] ?? '0';
    $address        = trim($_POST['address'] ?? '');

    $gender_selection = $raw_gender;

    if (empty($staffNo) || empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
        $error_message = "Please fill in all required fields (Staff ID, Name, Username, Password).";
    } elseif (strlen($password) < 4) {
        $error_message = "Password must be at least 4 characters long.";
    } else {
        
        $gender_int = ($raw_gender === 'Female') ? 1 : 0; 
        $qual_db = empty($qualification) ? null : $qualification;

        $result = DoctorDAO::createDoctor(
            $conn, 
            $staffNo, 
            $firstname, 
            $lastname, 
            $specialisation, 
            $qual_db, 
            $pay, 
            $gender_int, 
            $consultant_status, 
            $address, 
            $username, 
            $password
        );

        if ($result === true) {
            
            if (function_exists('logAction')) {
                logAction($conn, $_SESSION['username'], 'CREATE_DOCTOR', "Added Dr. $lastname ($staffNo)");
            }
            
            header("Location: doctor.php?msg=created");
            exit();
        } else {
            $error_message = "Error: " . $result;
        }
    }
}

require 'includes/header.php';
?>

<section class="task-form-area container">
    <h2>Register New Doctor</h2>
    <p class="guide-text">Create a new doctor profile and system login.</p>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= safeDisplay($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" class="styled-form" id="doctorForm">
        
        <h4 style="margin-top: 0; color: #51AC74;">Staff Details</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="staffno">Staff ID Number (Required)</label>
                <input type="text" id="staffno" name="staffno" maxlength="5" minlength="5" required>
                <small>Must be exactly 5 characters.</small>
            </div>
            <div class="form-group">
                <label for="consultantstatus">Consultant Status</label>
                <select id="consultantstatus" name="consultantstatus">
                    <option value="0" <?= ($consultant_status == '0') ? 'selected' : '' ?>>False </option>
                    <option value="1" <?= ($consultant_status == '1') ? 'selected' : '' ?>>True </option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="Male" <?= ($gender_selection === 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= ($gender_selection === 'Female') ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pay">Annual Pay (£)</label>
                <input type="number" id="pay" name="pay" step="0.01" value="<?= safeDisplay($pay) ?>" placeholder="0.00">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="specialisation">Specialisation</label>
                <input type="text" id="specialisation" name="specialisation">
            </div>
            <div class="form-group">
                <label for="qualification">Qualification (Optional)</label>
                <input type="text" id="qualification" name="qualification">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"></textarea>
        </div>

        <h4 style="margin-top: 15px; color: #51AC74;">System Login Credentials</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="username">System Username</label>
                <input type="text" id="username" name="username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" required autocomplete="new-password">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Doctor</button>
            <a href="doctor.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</section>

<script src="js/validate_doctor.js"></script>

<?php require 'includes/footer.php'; ?>