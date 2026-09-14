<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Collection Report';
$report_type = 'collection';
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

$rows = mysqli_query($conn, "SELECT bc.*, d.name AS donor_name FROM blood_collection bc
    JOIN donors d ON d.donor_id = bc.donor_id
    WHERE bc.collection_date BETWEEN '$date_from' AND '$date_to' ORDER BY bc.collection_date DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Blood Collection Report</h3>
<?php include '_toolbar.php'; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>ID</th><th>Donor</th><th>Blood Group</th><th>Units</th><th>Collection Date</th><th>Expiry</th><th>Staff</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($rows) === 0): ?>
          <tr><td colspan="7" class="text-center text-muted py-3">No collections in this range.</td></tr>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($rows)): ?>
          <tr>
            <td>#<?php echo $r['collection_id']; ?></td>
            <td><?php echo e($r['donor_name']); ?></td>
            <td><?php echo e($r['blood_group']); ?></td>
            <td><?php echo e($r['units_collected']); ?></td>
            <td><?php echo e($r['collection_date']); ?></td>
            <td><?php echo e($r['expiry_date']); ?></td>
            <td><?php echo e($r['staff_name']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
