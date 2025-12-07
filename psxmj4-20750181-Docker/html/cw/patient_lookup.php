<?php

require 'includes/session.php';
require_login();

require 'includes/db_connection.php';

require 'data_access/formatDisplayValue.php';
require 'includes/renderPatientProfileView.php';
require 'data_access/PatientDAO.php';

$page_title = 'Patient Information';
$view_nhs = $_GET['view_nhs'] ?? null;
$search_term = $_POST['patient_search'] ?? '';

require 'includes/header.php';
?>

<div class="patient-lookup-container">

    <?php if ($view_nhs): ?>
        <?php
        $full_data = PatientDAO::getFullPatientData($conn, $view_nhs);
        renderPatientFullProfile($full_data);
        ?>

    <?php else: ?>
        <h1>Patient Database</h1>

        <form method="POST" class="search-bar-container">
            <label for="patient_search">NHS No. or Last Name:</label>
            <input type="text" id="patient_search" name="patient_search" value="<?= htmlspecialchars($search_term) ?>">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>

        <hr>

        <?php
        $result = PatientDAO::searchPatients($conn, $search_term);

        if ($result->num_rows > 0) {
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
                echo "<td>" . safeDisplay($row['NHSno']) . "</td>";
                echo "<td>" . safeDisplay($row['firstname']) . "</td>";
                echo "<td>" . safeDisplay($row['lastname']) . "</td>";
                echo "<td>" . safeDisplay($row['phone']) . "</td>";
                echo "<td>
                        <a href='patient_lookup.php?view_nhs=" . urlencode($row['NHSno']) . "' class='btn btn-secondary'>
                           View Profile
                        </a>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p style='text-align:center;'>No patients found.</p>";
        }

        echo "<br><a href='new_patient.php' class='btn btn-primary'>Add new patient</a>";

        ?>
    <?php endif; ?>

</div>

<?php
require 'includes/footer.php';
?>