<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Donor Report';
$report_type = 'donor';
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

$rows = mysqli_query($conn, "SELECT * FROM donors WHERE created_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59' ORDER BY name");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Donor Report</h3>
<?php include '_toolbar.php'; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>ID</th><th>Name</th><th>Gender</th><th>Age</th><th>Blood Group</th><th>Mobile</th><th>City</th><th>Availability</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($rows) === 0): ?>
          <tr><td colspan="8" class="text-center text-muted py-3">No donors in this range.</td></tr>
        <?php endif; ?>
        <?php while ($d = mysqli_fetch_assoc($rows)): ?>
          <tr>
            <td>#<?php echo $d['donor_id']; ?></td>
            <td><?php echo e($d['name']); ?></td>
            <td><?php echo e($d['gender']); ?></td>
            <td><?php echo e($d['age']); ?></td>
            <td><?php echo e($d['blood_group']); ?></td>
            <td><?php echo e($d['mobile']); ?></td>
            <td><?php echo e($d['city']); ?></td>
            <td><?php echo e($d['availability_status']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
