<?php

// DoctorDAO is a series of global function which power the backend functionality of anything relating to doctors.
// FOR FUTURE DEVELOPERS: When expanding the functionality of features relating to doctors, add the backend functionality here.

require 'includes/db_connection.php';

class DoctorDAO
{

    // Function to return the StaffNo of the user logged in.

    public static function getStaffNoByUsername($conn, $username)
    {
        $stmt = $conn->prepare("SELECT staffno FROM doctor WHERE username = ?");

        if (!$stmt) return false;

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['staffno'] ?? ($result['staffNo'] ?? false);
    }

    // Function to return all information linked to a specific StaffNo.

    public static function getDoctorProfile($conn, $staffNo)
    {
        $stmt = $conn->prepare("SELECT * FROM doctor WHERE staffno = ?");

        if (!$stmt) return null;

        $stmt->bind_param("s", $staffNo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result;
    }

    // Function for users to edit their own profile.

    public static function updateDoctorProfile($conn, $staffNo, $firstname, $lastname, $address, $specialisation, $username) {
        $stmt = $conn->prepare("
            UPDATE doctor 
            SET firstname = ?, lastname = ?, address = ?, specialisation = ?
            WHERE staffno = ?
        ");
        
        if (!$stmt) return false;

        $stmt->bind_param("sssss", $firstname, $lastname, $address, $specialisation, $staffNo);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }


    // Function to update information stored within a row in the doctor table. Used for ADMIN
    
    public static function updateDoctorFull($conn, $originalStaffNo, $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $newUsername, $newPassword = null, $originalUsername = null) {
        $conn->begin_transaction();

        try {
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

            $userExists = false;
            if (!empty($originalUsername)) {
                $checkStmt = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
                $checkStmt->bind_param("s", $originalUsername);
                $checkStmt->execute();
                if ($checkStmt->get_result()->num_rows > 0) {
                    $userExists = true;
                }
                $checkStmt->close();
            }

            if ($userExists) {
                $stmtUser = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
                $stmtUser->bind_param("ss", $newUsername, $originalUsername);
                if (!$stmtUser->execute()) throw new Exception("Failed to sync username: " . $conn->error);
                $stmtUser->close();

                if (!empty($newPassword)) {
                    $stmtPass = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $stmtPass->bind_param("ss", $newPassword, $newUsername);
                    $stmtPass->execute();
                    $stmtPass->close();
                }
            } else {
                $initialPassword = !empty($newPassword) ? $newPassword : $newUsername; 
                
                $stmtInsert = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
                $stmtInsert->bind_param("ss", $newUsername, $initialPassword);
                
                if (!$stmtInsert->execute()) {
                    if ($conn->errno == 1062) {
                        throw new Exception("The username '$newUsername' is already taken by another user.");
                    }
                    throw new Exception("Failed to create new user account: " . $conn->error);
                }
                $stmtInsert->close();
            }

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }

    // Function for admins to create a new doctor profile within the doctor table.

    public static function createDoctor($conn, $staffNo, $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $username, $password) {
        $conn->begin_transaction();

        try {
            $checkUser = $conn->prepare("SELECT username FROM users WHERE username = ?");
            $checkUser->bind_param("s", $username);
            $checkUser->execute();
            if ($checkUser->get_result()->num_rows > 0) throw new Exception("Username '$username' is already taken.");
            $checkUser->close();

            $checkID = $conn->prepare("SELECT staffno FROM doctor WHERE staffno = ?");
            $checkID->bind_param("s", $staffNo);
            $checkID->execute();
            if ($checkID->get_result()->num_rows > 0) throw new Exception("Staff Number '$staffNo' already exists.");
            $checkID->close();

            $stmtUser = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
            $stmtUser->bind_param("ss", $username, $password); 
            if (!$stmtUser->execute()) throw new Exception("Failed to create user account.");
            $stmtUser->close();

            $stmtDoc = $conn->prepare("
                INSERT INTO doctor (staffno, firstname, lastname, Specialisation, qualification, pay, gender, consultantstatus, address, username) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmtDoc->bind_param("sssssdisss", $staffNo, $firstname, $lastname, $spec, $qual, $pay, $gender, $consultantStatus, $address, $username);
            
            if (!$stmtDoc->execute()) throw new Exception("Failed to create doctor profile.");
            $stmtDoc->close();

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }
    
}
?>