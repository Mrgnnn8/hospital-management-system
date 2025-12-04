<?php
require 'includes/db_connection.php'; 

class TestDAO {

    public static function getAvailableTests($conn) {
        $stmt = $conn->prepare("SELECT testid, testname FROM test ORDER BY testname ASC");
        
        if ($stmt === false) {
            return false;
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function recordResult($conn, $pid, $testid, $doctorid, $date, $report) {
        $stmt = $conn->prepare("
            INSERT INTO patient_test (pid, testid, date, report, doctorid) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        if ($stmt === false) {
             return false; 
        }

        $stmt->bind_param("sisss", $pid, $testid, $date, $report, $doctorid);
        
        return $stmt->execute();
    }

    public static function createNewTest($conn, $test_name) {
        $stmt = $conn->prepare("INSERT INTO test (testname) VALUES (?)");
        if ($stmt === false) return false;
        $stmt->bind_param("s", $test_name);
        if ($stmt->execute()) return $conn->insert_id;
        return false;
    } 
}
?>