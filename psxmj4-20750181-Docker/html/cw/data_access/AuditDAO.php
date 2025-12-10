<?php

//AuditDAO is the backend functionality to record all user activity within the system.
// FOR FUTURE DEVELOPERS: When expanding the functionality of Audit Trail ensure to add backend functionality here.

class AuditDAO {

    // Function designed to log any action which occurs on the system.

    public static function log($conn, $staffNo, $role, $action, $description) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        $stmt = $conn->prepare("INSERT INTO audit_log (staffno, role, action_type, description, ip_address) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sssss", $staffNo, $role, $action, $description, $ip);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        return false;
    }

    // Function to search through the log database and retrieve specific informatin and admin may be looking for.

    public static function getLogs($conn, $staffNoFilter = '', $actionFilter = '') {
        $sql = "SELECT * FROM audit_log WHERE 1=1";
        $params = [];
        $types = "";

        if (!empty($staffNoFilter)) {
            $sql .= " AND staffno LIKE ?";
            $params[] = "%" . $staffNoFilter . "%";
            $types .= "s";
        }
        if (!empty($actionFilter)) {
            $sql .= " AND action_type = ?";
            $params[] = $actionFilter;
            $types .= "s";
        }

        $sql .= " ORDER BY timestamp DESC LIMIT 500"; 

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Function to return all the different activity types have occured on the system. Enables an admin to more precisely search.

    public static function getActionTypes($conn) {
        return $conn->query("SELECT DISTINCT action_type FROM audit_log ORDER BY action_type ASC");
    }
}
?>