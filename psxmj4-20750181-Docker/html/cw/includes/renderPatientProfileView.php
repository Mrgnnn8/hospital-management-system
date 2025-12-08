<?php

function renderPatientFullProfile($patient) {

    if (!$patient) {
        echo "<div class='alert warning'>Patient data not found.</div>";
        echo "<br><a href='patient_lookup.php' class='btn btn-secondary'>&larr; Back to Search</a>";
        return;
    }

    $gender_text = safeDisplay(($patient['gender'] == 1) ? "Female" : "Male", 'N/A');

    echo "<div class='patient-profile-dashboard'>";

    echo "<div class='profile-section demographics-card'>";
    echo "<h2>Patient Details</h2>";
    echo "<table class='styled-table mb-0'>
            <thead>
                <tr>
                    <th>NHS number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Age</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>" . safeDisplay($patient['NHSno']) . "</strong></td>
                    <td>" . safeDisplay($patient['firstname']) . "</td>
                    <td>" . safeDisplay($patient['lastname']) . "</td>
                    <td>" . safeDisplay($patient['phone'], 'No Phone') . "</td>
                    <td>" . safeDisplay($patient['address'], 'N/A') . "</td>
                    <td>" . safeDisplay($patient['age'], 'N/A') . "</td>
                    <td>" . $gender_text . "</td>
                </tr>
            </tbody>
        </table>";
    echo "</div>"; 

    echo "<div class='clinical-grid'>";

        echo "<div class='profile-section'>";
        echo "<h3>Examination History</h3>";
        if (!empty($patient['examinations'])) {
            echo "<table class='styled-table small-text'>
                    <thead>
                        <tr><th>Doctor ID</th><th>Date</th><th>Time</th></tr>
                    </thead>
                    <tbody>";
            foreach ($patient['examinations'] as $exam) {
                echo "<tr>
                        <td>" . safeDisplay($exam['doctorid']) . "</td>
                        <td>" . safeDisplay($exam['date'], 'Unknown') . "</td>
                        <td>" . safeDisplay($exam['time'], '-') . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='empty-state'>No examinations recorded.</p>";
        }
        echo "</div>";

        echo "<div class='profile-section'>";
        echo "<h3>Tests Prescribed</h3>";
        if (!empty($patient['tests'])) {
            echo "<table class='styled-table small-text'>
                    <thead>
                        <tr><th>Doctor</th><th>Test</th><th>Date</th><th>Report</th></tr>
                    </thead>
                    <tbody>";

            foreach ($patient['tests'] as $test) {
                echo "<tr>
                        <td>" . safeDisplay($test['doctorid']) . "</td>
                        
                        <td>" . safeDisplay($test['testname'] ?? $test['testid']) . "</td>
                        
                        <td>" . safeDisplay($test['date'], '-') . "</td>
                        <td>" . safeDisplay($test['report'], 'Pending') . "</td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='empty-state'>No tests found.</p>";
        }
        echo "</div>"; 

    echo "</div>";

    echo "<a href='test_new.php?pid=" . urlencode($patient['NHSno']) . "' class='btn btn-primary'>Prescribe a test</a>";

    echo "<div class='profile-section'>";
    echo "<h3>Ward Admission History</h3>";
    if (!empty($patient['ward_addmissions'])) {
        echo "<table class='styled-table'>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Ward ID</th>
                        <th>Ward Name</th>
                        <th>Admission Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($patient['ward_addmissions'] as $ward) {
            echo "<tr>
                    <td>" . safeDisplay($ward['consultantid']) . "</td>
                    <td>" . safeDisplay($ward['wardid']) . "</td>
                    <td>" . safeDisplay($ward['wardname']) . "</td>
                    <td>" . safeDisplay($ward['date'], '-') . "</td>
                    <td>" . safeDisplay($ward['time'], '-') . "</td>
                    <td>" . safeDisplay($ward['patient_status']) . "</td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='empty-state'>No ward admissions.</p>";
    }
    echo "</div>"; 

    echo "<div class='action-stack'>";
                
        echo "<a href='patient_lookup.php' class='btn btn-secondary'>Back to Directory</a>";
    
    echo "</div>";
    echo "</div>";

}

?>