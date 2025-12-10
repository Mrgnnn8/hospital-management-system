<?php
// ParkingDAO handles all the back end functionality when interacting with 'user_parking_permit' database.
// FOR FUTURE DEVELOPERS: When expanding or changing the functionality of parking permits, ensure all back end is stored here.

require 'includes/db_connection.php';

class ParkingDAO
{

    // Function to create a new parking permit request within the database (user end)

    public static function createRequest($conn, $staffNo, $vehicleReg, $paymentType)
    {

        $amount = ($paymentType === 'Yearly') ? 200.00 : 20.00;

        $stmt = $conn->prepare("
        INSERT INTO parking_permit_status 
        (staffno, vehicle_reg, status, request_date, last_update, permit_choice, amount, notes) 
        VALUES (?, ?, 'Awaiting approval', NOW(), NOW(), ?, ?, 'Pending')
    ");

        if ($stmt === false) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param("sssd", $staffNo, $vehicleReg, $paymentType, $amount);

        return $stmt->execute();
    }

    // Function which returns the application history of the user logged in

    public static function getUserRequests($conn, $staffNo)
    {
        $stmt = $conn->prepare("
            SELECT * FROM parking_permit_status 
            WHERE staffno = ? 
            ORDER BY request_date DESC
        ");
        $stmt->bind_param("s", $staffNo);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Function which updates the status of an application depending on an admins decision.
    // Ensures the user sees the immediate result on their 'manage account' page.

    public static function updateStatus($conn, $applicationId, $status, $adminNote)
    {
        $stmt = $conn->prepare("
            UPDATE parking_permit_status 
            SET status = ?, notes = ?, last_updated = NOW() 
            WHERE permit_application_id = ?
        ");
        $stmt->bind_param("ssi", $status, $adminNote, $applicationId);
        return $stmt->execute();
    }

    // Function which returns the fees of the different permit types.

    public static function getFee($paymentType)
    {
        if ($paymentType === 'Monthly')
            return 20.00;
        if ($paymentType === 'Yearly')
            return 200.00; 
        return 0.00;
    }

    //Admin side
    // Returns a table of all requests within the system.

    public static function getAllRequests($conn) {
        $sql = "SELECT * FROM parking_permit_status ORDER BY request_date DESC";
        $result = $conn->query($sql);
        return $result;
    }

    //Function which updates the database entry to approved and permentantly assigns a permit number to the user.

    public static function approveRequest($conn, $applicationId, $permitNo) {
        $stmt = $conn->prepare("
            UPDATE parking_permit_status
            SET status = 'Approved', permit_no = ?, last_update = NOW()
            WHERE permit_application_id = ?
        ");
        $stmt->bind_param("si", $permitNo, $applicationId);
        return $stmt->execute();
    }

    // Function whihc updates the database entry to rejected and attaches a note as to why.

    public static function rejectRequest($conn, $applicationId, $note) {
        $stmt = $conn->prepare("
            UPDATE parking_permit_status
            SET status = 'Approved', notes = ?, last_updated = NOW()
            WHERE permit_application_id = ?
        ");
        $stmt->bind_param("si", $notes, $applicationId);
        return $stmt->execute();
    }

}
?>