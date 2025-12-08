<?php
ini_set('display_errors', 1); error_reporting(E_ALL);

require 'includes/db_connection.php';
require 'includes/session.php';
require_once 'data_access/formatDisplayValue.php'; 

require_login();

$page_title = 'Doctor Directory';

$search = trim($_GET['search'] ?? '');
$params = [];
$types = '';
$sql = "SELECT * FROM doctor";

if (!empty($search)) {
    $sql .= " WHERE firstname LIKE ? OR lastname LIKE ? OR staffno LIKE ? OR Specialisation LIKE ?";
    $term = "%" . $search . "%";
    $params = [$term, $term, $term, $term];
    $types = 'ssss';
}

$sql .= " ORDER BY lastname ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

require 'includes/header.php';
?>

<section class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>Doctor Directory</h2>
            <p class="guide-text">Search and view details of all hospital staff.</p>
        </div>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <a href="new_doctor.php" class="btn btn-primary">+ Register New Doctor</a>
        <?php endif; ?>
    </div>

    <form method="GET" style="margin-bottom: 30px; display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Search by Name, Staff ID, or Specialisation..." 
               value="<?= safeDisplay($search) ?>" style="flex: 1;">
        <button type="submit" class="btn btn-secondary">Search</button>
        <?php if ($search): ?>
            <a href="doctor.php" class="btn btn-secondary" style="background: #999;">Clear</a>
        <?php endif; ?>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Full Name</th>
                    <th>Specialisation</th>
                    <th>Contact Address</th>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= safeDisplay($row['staffno']) ?></strong></td>
                        <td>
                            <?= safeDisplay($row['firstname']) ?> <?= safeDisplay($row['lastname']) ?>
                        </td>
                        <td><?= safeDisplay($row['Specialisation']) ?></td>
                        <td><?= safeDisplay($row['address']) ?></td>                        
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <td>
                                <a href="edit_doctor.php?id=<?= urlencode($row['staffno']) ?>" 
                                   style="color: #51AC74; font-weight: bold; text-decoration: none;">Edit</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-danger" style="text-align: center; margin-top: 20px;">
            No doctors found matching your search.
        </div>
    <?php endif; ?>

</section>

<?php require 'includes/footer.php'; ?>

