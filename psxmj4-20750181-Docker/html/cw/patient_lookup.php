<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/session.php';
require_login();

require 'includes/db_connection.php';
require_once 'data_access/formatDisplayValue.php';
require 'includes/renderPatientProfileView.php';
require_once 'data_access/PatientDAO.php';

if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
}

$page_title = 'Patient Information';
$view_nhs = $_GET['view_nhs'] ?? null;
$search_term = $_POST['patient_search'] ?? '';

require 'includes/header.php';
?>

<main class="container">

<div class="patient-lookup-container">

    <?php if ($view_nhs): ?>
        <?php
        $full_data = PatientDAO::getFullPatientData($conn, $view_nhs);

        if ($full_data) {
            if (function_exists('logAction')) {
                logAction(
                    $conn, 
                    $_SESSION['username'], 
                    'VIEW_PATIENT', 
                    "Accessed record for Patient NHS: " . $view_nhs
                );
            }

            renderPatientFullProfile($full_data);
        } else {
            echo "<div class='alert alert-danger'>Patient record not found.</div>";
            echo "<a href='patient_lookup.php' class='btn btn-secondary'>Back to Search</a>";
        }
        ?>

    <?php else: ?>
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="margin: 0; color: #51AC74;">Patient Database</h1>
            <a href='new_patient.php' class='btn btn-primary'>+ Add New Patient</a>
        </div>
        
        <p class="guide-text">Search for existing patients or register a new admission.</p>

        <form method="POST" class="search-bar-container" style="background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex-grow: 1;">
                    <label for="patient_search" style="font-weight: bold; display: block; margin-bottom: 5px;">NHS No. or Last Name:</label>
                    <input type="text" id="patient_search" name="patient_search" style="width: 100%;">
                </div>
                <input type="submit" value="Search" class="btn btn-primary" style="margin-bottom: 2px;">
                <?php if ($search_term): ?>
                    <a href="patient_lookup.php" class="btn btn-secondary" style="margin-bottom: 2px;">Clear</a>
                <?php endif; ?>
            </div>
        </form>

        <hr>

        <?php
        $result = PatientDAO::searchPatients($conn, $search_term);

        if ($result && $result->num_rows > 0) {
            echo "<table class='styled-table'>";
            echo "<thead><tr>
                    <th>NHS No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Action</th>
                  </tr></thead><tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><strong>" . safeDisplay($row['NHSno']) . "</strong></td>";
                echo "<td>" . safeDisplay($row['firstname']) . "</td>";
                echo "<td>" . safeDisplay($row['lastname']) . "</td>";
                echo "<td>" . safeDisplay($row['phone']) . "</td>";
                echo "<td>
                        <a href='patient_lookup.php?view_nhs=" . urlencode($row['NHSno']) . "' class='btn btn-secondary' style='font-size: 0.9em; padding: 5px 10px;'>
                           View Profile
                        </a>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            if ($search_term) {
                echo "<p style='text-align:center; padding: 20px;'>No patients found matching '<strong>" . safeDisplay($search_term) . "</strong>'.</p>";
            } else {
                echo "<p style='text-align:center; padding: 20px; color: #777;'>Enter a name or NHS number above to begin.</p>";
            }
        }
        ?>
    <?php endif; ?>

</div>

<?php
require 'includes/footer.php';
?>