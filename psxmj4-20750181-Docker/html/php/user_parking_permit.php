<?php
require 'session.php';
require 'db_connection.php';
require_login();

$sql = "SELECT permit_application_id, staffno, vehicle_reg, status, request_date,
               last_update, notes
        FROM parking_permit_status";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parking Permit Applications</title>
</head>

<body>

<h2>Parking Permit Applications</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Application ID</th>
        <th>Staff No</th>
        <th>Vehicle Reg</th>
        <th>Status</th>
        <th>Request Date</th>
        <th>Last Updated</th>
        <th>Permit ID</th>
        <th>Valid From</th>
        <th>Valid Until</th>
        <th>Notes</th>
    </tr>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['permit_application_id'] . "</td>";
            echo "<td>" . $row['staffno'] . "</td>";
            echo "<td>" . $row['vehicle_reg'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['request_date'] . "</td>";
            echo "<td>" . $row['last_update'] . "</td>";
            echo "<td>" . ($row['notes'] ?? '') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No permit applications found.</td></tr>";
    }

    $conn->close();
    ?>
</table>

<p><a href="home.php">Dashboard</a></p>

</body>
</html>
