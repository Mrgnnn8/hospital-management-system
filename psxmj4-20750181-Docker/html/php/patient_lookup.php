<?php
require 'db_connection.php';
require 'render_patient_info.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $last_name = $_POST['lastname'] ?? '';
    $nhs_no = $_POST['patient_no'] ?? '';

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Information</title>
</head>
<body>

<h1>Patient Lookup</h1>

<form method="POST">
    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname">

    <br><br>

    <label for="patient_no">NHS Number:</label>
    <input type="text" id="patient_no" name="patient_no">

    <br><br>

    <input type="submit" value="Search">
</form>

<hr>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    renderPatientFullProfile($conn, $last_name, $nhs_no);
}
?>

</body>
</html>
