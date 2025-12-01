<?php
function renderPatientFullProfile($conn, $last_name, $nhs_no) {

    $stmt = $conn->prepare("
        SELECT * FROM patient
        WHERE lastname = ? OR NHSno = ?
        ORDER BY lastname ASC
    ");
    $stmt->bind_param("ss", $last_name, $nhs_no);
    $stmt->execute();
    $patient_result = $stmt->get_result();

    if ($patient_result->num_rows === 0) {
        echo "<p>No patient found.</p>";
        return;
    }

    $patient = $patient_result->fetch_assoc();

    echo "<h2>Patient Details</h2>";
    echo "<table border='1' cellpadding='8'>
            <tr>
                <th>NHS number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Age</th>
                <th>Gender</th>
            </tr>
            <tr>
                <td>{$patient['NHSno']}</td>
                <td>{$patient['firstname']}</td>
                <td>{$patient['lastname']}</td>
                <td>{$patient['phone']}</td>
                <td>{$patient['address']}</td>
                <td>{$patient['age']}</td>
                <td>" . (($patient['gender'] == 1) ? "Female" : "Male") . "</td>
            </tr>
        </table><br><br>";

    $exam_stmt = $conn->prepare("
        SELECT * FROM patientexamination
        WHERE patientid = ?
        ORDER BY date DESC
    ");
    $exam_stmt->bind_param("s", $patient['NHSno']);
    $exam_stmt->execute();
    $exam_results = $exam_stmt->get_result();

    echo "<details><summary><strong>Examinations</strong></summary><br>";

    if ($exam_results->num_rows > 0) {
        echo "<table border='1' cellpadding='8'>
                <tr>
                    <th>Doctor ID</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>";
        while ($exam = $exam_results->fetch_assoc()) {
            echo "<tr>
                    <td>{$exam['doctorid']}</td>
                    <td>{$exam['date']}</td>
                    <td>{$exam['time']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No examinations found.</p>";
    }
    echo "</details><br><br>";

    $test_stmt = $conn->prepare("
        SELECT * FROM patient_test
        WHERE pid = ?
        ORDER BY date DESC
    ");
    $test_stmt->bind_param("s", $patient['NHSno']);
    $test_stmt->execute();
    $test_results = $test_stmt->get_result();

    echo "<details><summary><strong>Test Results</strong></summary><br>";

    if ($test_results->num_rows > 0) {
        echo "<table border='1' cellpadding='8'>
                <tr>
                    <th>Doctor ID</th>
                    <th>Test ID</th>
                    <th>Date</th>
                    <th>Report</th>
                </tr>";
        while ($test = $test_results->fetch_assoc()) {
            echo "<tr>
                    <td>{$test['doctorid']}</td>
                    <td>{$test['testid']}</td>
                    <td>{$test['date']}</td>
                    <td>{$test['report']}</td>
                 </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No tests found.</p>";
    }
    echo "</details>";
}
?>