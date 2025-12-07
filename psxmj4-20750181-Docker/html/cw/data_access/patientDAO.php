<?php
require 'includes/db_connection.php';

class PatientDAO
{

    public static function searchPatients($conn, $search_term = '')
    {
        $sql = "SELECT * FROM patient";

        if (!empty($search_term)) {
            $sql .= " WHERE NHSno LIKE ? OR lastname LIKE ?";
        }
        $sql .= " ORDER BY lastname ASC";

        $stmt = $conn->prepare($sql);

        if (!empty($search_term)) {
            $term = "%" . $search_term . "%";
            $stmt->bind_param("ss", $term, $term);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getFullPatientData($conn, $nhs_no)
    {
        $stmt = $conn->prepare("SELECT * FROM patient WHERE NHSno = ?");
        $stmt->bind_param("s", $nhs_no);
        $stmt->execute();
        $patient = $stmt->get_result()->fetch_assoc();

        if (!$patient)
            return null;

        $exam_stmt = $conn->prepare("SELECT * FROM patientexamination WHERE patientid = ? ORDER BY date DESC");
        $exam_stmt->bind_param("s", $nhs_no);
        $exam_stmt->execute();
        $patient['examinations'] = $exam_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $test_stmt = $conn->prepare("SELECT * FROM patient_test WHERE pid = ? ORDER BY date DESC");
        $test_stmt->bind_param("s", $nhs_no);
        $test_stmt->execute();
        $patient['tests'] = $test_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $ward_stmt = $conn->prepare("
            SELECT
                a.*,
                w.wardname,
                CASE
                    WHEN a.status = 1 THEN 'Admitted'
                    WHEN a.status = 0 THEN 'Discharged'
                    ELSE 'N/A'
                END AS patient_status        
            FROM wardpatientaddmission a
            JOIN ward w ON w.wardid = a.wardid 
            WHERE a.pid = ? 
            ORDER BY a.date DESC
        ");

        if ($ward_stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $ward_stmt->bind_param("s", $nhs_no);
        $ward_stmt->execute();
        $patient['ward_addmissions'] = $ward_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $patient;
    }

    public static function getPatientNameById($conn, $nhs_no)
    {
        $stmt = $conn->prepare("SELECT firstname, lastname FROM patient WHERE NHSno = ?");

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("s", $nhs_no);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            return $result['firstname'] . " " . $result['lastname'];
        }

        return false;
    }

    public static function insertPatient($conn, $nhs_no, $firstname, $lastname, $phone, $address, $age, $gender, $emergencyphone)
    {
        $stmt = $conn->prepare('
        INSERT INTO patient (NHSno, firstname, lastname, phone, address, age, gender, emergencyphone)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("sssssiss", $nhs_no, $firstname, $lastname, $phone, $address, $age, $gender, $emergencyphone);

        return $stmt->execute();
    }


}
?>