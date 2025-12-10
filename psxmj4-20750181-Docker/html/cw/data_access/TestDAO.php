<?php
// TestDAO deals with the backend functionality relating to the 'TEST' database and 'patient_test' database.
// FOR FUTURE DEVELOPERS: When expanding the functionality of test pages, implement the backend here.

require 'includes/db_connection.php'; 

class TestDAO {

    // returns all tests stored in 'TEST' database

    public static function getAvailableTests($conn) {
        $stmt = $conn->prepare("SELECT testid, testname FROM test ORDER BY testid ASC");
        
        if ($stmt === false) {
            return false;
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }

    // records a new prescribed test in 'patient_test'.

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

    // Adds a new test type to the 'TEST' database.

    public static function createNewTest($conn, $test_name) {
        $stmt = $conn->prepare("INSERT INTO test (testname) VALUES (?)");
        if ($stmt === false) return false;
        $stmt->bind_param("s", $test_name);
        if ($stmt->execute()) return $conn->insert_id;
        return false;
    }

    
}
?>