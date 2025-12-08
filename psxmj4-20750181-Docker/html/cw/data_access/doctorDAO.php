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


    public static function createDoctor($conn, $staffNo, $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $username, $password) {
        $conn->begin_transaction();

        try {
            $checkUser = $conn->prepare("SELECT username FROM users WHERE username = ?");
            $checkUser->bind_param("s", $username);
            $checkUser->execute();
            if ($checkUser->get_result()->num_rows > 0) {
                throw new Exception("Username '$username' is already taken.");
            }
            $checkUser->close();

            $checkID = $conn->prepare("SELECT staffno FROM doctor WHERE staffno = ?");
            $checkID->bind_param("s", $staffNo);
            $checkID->execute();
            if ($checkID->get_result()->num_rows > 0) {
                throw new Exception("Staff Number '$staffNo' already exists.");
            }
            $checkID->close();

            $stmtUser = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
            $stmtUser->bind_param("ss", $username, $password); 
            
            if (!$stmtUser->execute()) {
                throw new Exception("Failed to create user account: " . $conn->error);
            }
            $stmtUser->close();

            $stmtDoc = $conn->prepare("
                INSERT INTO doctor (staffno, firstname, lastname, Specialisation, qualification, pay, gender, consultantstatus, address, username) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmtDoc->bind_param("sssssdisss", $staffNo, $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $username);

            if (!$stmtDoc->execute()) {
                throw new Exception("Failed to create doctor profile: " . $conn->error);
            }
            $stmtDoc->close();

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }


    public static function updateDoctorFull($conn, $originalStaffNo, $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $newUsername, $newPassword = null) {
        $conn->begin_transaction();

        try {
            $stmtGet = $conn->prepare("SELECT username FROM doctor WHERE staffno = ?");
            $stmtGet->bind_param("s", $originalStaffNo);
            $stmtGet->execute();
            $result = $stmtGet->get_result()->fetch_assoc();
            $oldUsername = $result['username'] ?? null;
            $stmtGet->close();

            if (!$oldUsername) {
                throw new Exception("Original doctor record not found.");
            }

            $stmtDoc = $conn->prepare("
                UPDATE doctor 
                SET firstname=?, lastname=?, Specialisation=?, qualification=?, pay=?, gender=?, consultantstatus=?, address=?, username=?
                WHERE staffno=?
            ");
            $stmtDoc->bind_param("ssssdiisss", $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $newUsername, $originalStaffNo);
            
            if (!$stmtDoc->execute()) {
                throw new Exception("Failed to update doctor profile: " . $conn->error);
            }
            $stmtDoc->close();

            $stmtUser = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
            $stmtUser->bind_param("ss", $newUsername, $oldUsername);
            if (!$stmtUser->execute()) {
                throw new Exception("Failed to sync username: " . $conn->error);
            }
            $stmtUser->close();

            if (!empty($newPassword)) {
                $stmtPass = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmtPass->bind_param("ss", $newPassword, $newUsername);
                if (!$stmtPass->execute()) {
                    throw new Exception("Failed to update password.");
                }
                $stmtPass->close();
            }

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }
    
}
?>