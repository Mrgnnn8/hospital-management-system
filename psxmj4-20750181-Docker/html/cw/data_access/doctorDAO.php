<?php

require 'includes/db_connection.php';

class DoctorDAO
{
    public static function getStaffNoByUsername($conn, $username)
    {
        $stmt = $conn->prepare("SELECT staffNo FROM doctor WHERE username = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['staffNo'] ?? false;
    }

    public static function getDoctorProfile($conn, $staffNo)
    {
        $stmt = $conn->prepare("SELECT * FROM doctor WHERE staffNo = ?");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $staffNo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result;
    }

    public static function updateDoctorProfile($conn, $staffNo, $address, $specialisation)
    {
        $stmt = $conn->prepare("
            UPDATE doctor 
            SET Address = ?, Specialisation = ? 
            WHERE staffNo = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sss", $address, $specialisation, $staffNo);

        return $stmt->execute();
    }
}
?>