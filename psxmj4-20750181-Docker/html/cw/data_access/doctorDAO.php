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

    
    public static function updateDoctorProfile($conn, $staffNo, $firstname, $lastname, $address, $specialisation, $newUsername)
    {
        $conn->begin_transaction();

        try {
            $stmtGet = $conn->prepare("SELECT username FROM doctor WHERE staffNo = ?");
            $stmtGet->bind_param("s", $staffNo);
            $stmtGet->execute();
            $result = $stmtGet->get_result()->fetch_assoc();
            $oldUsername = $result['username'] ?? null;
            $stmtGet->close();

            if (!$oldUsername) throw new Exception("Doctor user link not found.");

            $stmtDoc = $conn->prepare("
                UPDATE doctor 
                SET firstname = ?, lastname = ?, Address = ?, Specialisation = ?, username = ? 
                WHERE staffNo = ?
            ");

            $stmtDoc->bind_param("ssssss", $firstname, $lastname, $address, $specialisation, $newUsername, $staffNo);
            
            if (!$stmtDoc->execute()) {
                throw new Exception("Doctor Update Failed: " . $conn->error);
            }
            $stmtDoc->close();

            $stmtUser = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
            $stmtUser->bind_param("ss", $newUsername, $oldUsername);
            
            if (!$stmtUser->execute()) {
                throw new Exception("User Update Failed: " . $conn->error);
            }
            $stmtUser->close();

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            error_log("Profile Update Error: " . $e->getMessage());
            return false;
        }
    }
}
?>