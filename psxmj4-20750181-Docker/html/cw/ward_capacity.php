<?php 
require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/WardDAO.php';
require_once 'data_access/formatDisplayValue.php'; 
require_login();

$ward_capacity_list = WardDAO::getAllWardsAvailability($conn);

$page_title = 'Ward Capacity';
require 'includes/header.php';
?>
    <main class="container">

    <h2>Ward Capacity Dashboard</h2>

    <?php if ($ward_capacity_list && $ward_capacity_list->num_rows > 0): ?>
        
        <table class="styled-table" style="width: 100%;">
            
            <thead>
                <tr>
                    <th>Ward Name</th>
                    <th>Capacity</th>
                    <th>Occupied</th>
                    <th>Available</th>
                    <th>Peercentage of capacity</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($ward = $ward_capacity_list->fetch_assoc()): 

                    $capacity = (int)$ward['Capacity'];
                    $occupied = (int)$ward['Occupied'];
                    $available = max(0, $capacity - $occupied);
                    
                    if ($capacity > 0) {
                        $percent_full = ($occupied / $capacity) * 100;
                    } else {
                        $percent_full = 100;
                    }

                    if ($percent_full >= 100) {
                        $bar_color = "#f44336"; 
                    } elseif ($percent_full >= 80) {
                        $bar_color = "#ff9800"; 
                    } else {
                        $bar_color = "#4CAF50"; 
                    }
                    
                    $display_percent = round($percent_full);
                ?>
                    <tr>
                        <td><strong><?= safeDisplay($ward['WardName']) ?></strong></td>
                        <td><?= $capacity ?></td>
                        <td><?= $occupied ?></td>
                        <td style="font-weight: bold; font-size: 1.1em;"><?= $available ?></td>
                        
                        <td style="min-width: 150px;"> 
                            <div style="display: flex; align-items: center;">
                                <div class="progress-track">
                                    <div class="progress-fill" 
                                        style="width: <?= $display_percent ?>%; background-color: <?= $bar_color ?>;">
                                    </div>
                                </div>
                                <span class="capacity-text"><?= $display_percent ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table> <?php else: ?>
        <p>No ward data available.</p>
    <?php endif; ?>

</section> <?php require 'includes/footer.php'; ?>