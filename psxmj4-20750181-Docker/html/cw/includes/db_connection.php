<?php

// Establishes a database handshake on every page.
//  Every PHP file that needs to read or write data should include 'requires 'includes/db_connection'' at the top.

$host = "mariadb";       
$user = "root";         
$pass = "rootpwd";       
$dbname = "Hospital";     

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
