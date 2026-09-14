<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Stock Report';
$report_type = 'stock';
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

$rows = mysqli_query($conn, "SELECT * FROM blood_stock ORDER BY blood_group");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Blood Stock Report</h3>
<?php include '_toolbar.php'; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>Blood Group</th><th>Units Available</th><th>Low Stock Threshold</th><th>Last Updated</th><th>Status</th></tr></thead>
      <tbody>
        <?php while ($s = mysqli_fetch_assoc($rows)): ?>
          <tr>
            <td><span class="badge bg-danger"><?php echo e($s['blood_group']); ?></span></td>
            <td><?php echo e($s['units_available']); ?></td>
            <td><?php echo e($s['low_stock_threshold']); ?></td>
            <td><?php echo e($s['last_updated']); ?></td>
            <td><?php echo $s['units_available'] <= $s['low_stock_threshold'] ? '<span class="text-danger">Low</span>' : '<span class="text-success">OK</span>'; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
