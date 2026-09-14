<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Hospital Report';
$report_type = 'hospital';
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

$rows = mysqli_query($conn, "SELECT * FROM hospitals WHERE created_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59' ORDER BY hospital_name");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Hospital Report</h3>
<?php include '_toolbar.php'; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>ID</th><th>Name</th><th>Reg. No.</th><th>Contact</th><th>Mobile</th><th>City</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($rows) === 0): ?>
          <tr><td colspan="6" class="text-center text-muted py-3">No hospitals in this range.</td></tr>
        <?php endif; ?>
        <?php while ($h = mysqli_fetch_assoc($rows)): ?>
          <tr>
            <td>#<?php echo $h['hospital_id']; ?></td>
            <td><?php echo e($h['hospital_name']); ?></td>
            <td><?php echo e($h['registration_number']); ?></td>
            <td><?php echo e($h['contact_person']); ?></td>
            <td><?php echo e($h['mobile']); ?></td>
            <td><?php echo e($h['city']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
