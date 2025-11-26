<?php

require "db_connection.php";
require 'session.php';
require_login();

// Query for all doctors
$sql = "SELECT * FROM doctor ORDER BY lastname ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctors</title>
    <style>
        container {
            width: 80%;
            margin: 50px auto;
            font-family: Arial, sans-serif;
            align-items: center;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            font-size: 18px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #244bcc;
            color: white;
        }
    </style>
</head>
<body>

<h1 style="text-align:center;">Doctors in the System</h1>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Staff No.</th><th>First Name</th><th>Last Name</th><th>Pay</th><th>Specialisation</th><tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['staffno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['pay']) . "</td>";
        echo "<td>" . htmlspecialchars($row['specialisation']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No doctors found in the database.</p>";
}
?>

<div class="container">

    <h1>Add a new Doctor</h1>
    <p>Secure access for doctors and administrators.</p>

<p><a href="home.php">Dashboard</a></p>

    <p class="footer-text">
        Provided by Morgan Jones @ The University of Nottingham
    </p>

</body>
</html>

