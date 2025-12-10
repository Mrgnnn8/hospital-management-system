<?php

// Middle man function for audit trail. Figures out who the user is and what role they have within the system.

require_once __DIR__ . '/../data_access/AuditDAO.php';
require_once __DIR__ . '/../data_access/DoctorDAO.php'; 

function logAction($conn, $username, $action, $description) {
    $role = 'Staff';
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        $role = 'Admin';
    }

    $staffNo = DoctorDAO::getStaffNoByUsername($conn, $username);

    if (!$staffNo) {
        $staffNo = "UNKNOWN ($username)";
    }

    return AuditDAO::log($conn, $staffNo, $role, $action, $description);
}
?>