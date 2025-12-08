<?php
class AuditDAO {

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
    
    public static function getActionTypes($conn) {
        return $conn->query("SELECT DISTINCT action_type FROM audit_log ORDER BY action_type ASC");
    }
}
?>