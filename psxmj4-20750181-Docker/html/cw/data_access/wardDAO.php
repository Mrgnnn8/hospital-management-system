<?php

//WardDAO controls all the backend relating to ward information.
//FOR FUTUTRE DEVELOPERS: any functionality relating to wards should be implemented here.

class WardDAO {

    //Returns all wards within QMC

    public static function getAllWardsAvailability($conn) {
        $sql = "
            SELECT 
                w.wardid, 
                w.wardname AS WardName, 
                w.noofbeds AS Capacity, 
                COUNT(pa.pid) AS Occupied
            FROM ward w
            LEFT JOIN wardpatientaddmission pa 
                ON w.wardid = pa.wardid AND pa.status = 1
            GROUP BY w.wardid
            ORDER BY w.wardname ASC
        ";

        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) { return false; }

        $stmt->execute();
        return $stmt->get_result(); 
    
        
        $current_patients = $admitted_stmt->get_result()->fetch_assoc()['current_patients'] ?? 0;

        $available = $max_capacity - $current_patients;

        if ($max_capacity > 0) {
            $percentage_available = ($available / $max_capacity) * 100;
        } else {
            $percentage_available = 0;
        }

        return [
            'capacity' => (int)$max_capacity,
            'occupied' => (int)$current_patients, 
            'available' => max(0, $available),
            'percentage' => round($percentage_available, 1) 
        ]; 
    }
}
?>