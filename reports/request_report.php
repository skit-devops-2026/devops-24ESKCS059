<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Request Report';
$report_type = 'request';
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

$rows = mysqli_query($conn, "SELECT br.*, h.hospital_name FROM blood_requests br
    LEFT JOIN hospitals h ON h.hospital_id = br.hospital_id
    WHERE br.request_date BETWEEN '$date_from' AND '$date_to' ORDER BY br.request_date DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Blood Request Report</h3>
<?php include '_toolbar.php'; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>ID</th><th>Patient</th><th>Blood Group</th><th>Units</th><th>Hospital</th><th>Emergency</th><th>Date</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($rows) === 0): ?>
          <tr><td colspan="8" class="text-center text-muted py-3">No requests in this range.</td></tr>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($rows)): ?>
          <tr>
            <td>#<?php echo $r['request_id']; ?></td>
            <td><?php echo e($r['patient_name']); ?></td>
            <td><?php echo e($r['blood_group']); ?></td>
            <td><?php echo e($r['required_units']); ?></td>
            <td><?php echo e($r['hospital_name'] ?? '—'); ?></td>
            <td><?php echo e($r['emergency_level']); ?></td>
            <td><?php echo e($r['request_date']); ?></td>
            <td><?php echo e($r['status']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
