<?php
$host = "mariadb";       
$user = "root";         
$pass = "rootpwd";       
$dbname = "Hospital";     

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
