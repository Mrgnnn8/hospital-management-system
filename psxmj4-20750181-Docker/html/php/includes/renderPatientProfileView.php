<?php
function renderPatientFullProfile($patient) {

    if (!$patient) {
        echo "<div class='alert warning'>Patient data not found.</div>";
        echo "<br><a href='patient_lookup.php' class='btn btn-secondary'>&larr; Back to Search</a>";
        return;
    }

    $gender_text = safeDisplay(($patient['gender'] == 1) ? "Female" : "Male", 'N/A');
    
    echo "<h2>Patient Details</h2>";
    echo "<table class='styled-table'>
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
                    <td>" . safeDisplay($patient['NHSno']) . "</td>
                    <td>" . safeDisplay($patient['firstname']) . "</td>
                    <td>" . safeDisplay($patient['lastname']) . "</td>
                    <td>" . safeDisplay($patient['phone'], 'No Phone') . "</td>
                    <td>" . safeDisplay($patient['address'], 'N/A') . "</td>
                    <td>" . safeDisplay($patient['age'], 'N/A') . "</td>
                    <td>" . $gender_text . "</td>
                </tr>
            </tbody>
        </table><br><br>";

    echo "<details open><summary><strong>Examinations</strong></summary><div class='details-content'>";
    
    if (!empty($patient['examinations'])) {
        echo "<table class='styled-table'>
                <thead>
                    <tr>
                        <th>Doctor ID</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($patient['examinations'] as $exam) {
            echo "<tr>
                    <td>" . safeDisplay($exam['doctorid']) . "</td>
                    <td>" . safeDisplay($exam['date'], 'Unknown Date') . "</td>
                    <td>" . safeDisplay($exam['time'], 'N/A') . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No examinations found.</p>";
    }
    echo "</div></details><br><br>";

    echo "<details open><summary><strong>Test Results</strong></summary><div class='details-content'>";
    
    if (!empty($patient['tests'])) {
        echo "<table class='styled-table'>
                <thead>
                    <tr>
                        <th>Doctor ID</th>
                        <th>Test ID</th>
                        <th>Date</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($patient['tests'] as $test) {
            echo "<tr>
                    <td>" . safeDisplay($test['doctorid']) . "</td>
                    <td>" . safeDisplay($test['testid']) . "</td>
                    <td>" . safeDisplay($test['date'], 'Missing Date') . "</td>
                    <td>" . safeDisplay($test['report'], 'Pending') . "</td>
                 </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No tests found.</p>";
    }
    
    echo "</div></details>"; 
    
    echo "<div class='action-buttons' style='margin-top:20px;'>";
    
    echo "<a href='test_new.php?pid=" . urlencode($patient['NHSno']) . "' class='btn btn-primary'>Record Test Result</a>";
    
    echo "&nbsp;";
    
    echo "<a href='patient_lookup.php' class='btn btn-secondary'>Back to Directory</a>";
    echo "</div>";
}
?>