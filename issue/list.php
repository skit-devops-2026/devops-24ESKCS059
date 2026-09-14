<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Issue';

$records = mysqli_query($conn, "SELECT bi.*, h.hospital_name FROM blood_issue bi
    LEFT JOIN hospitals h ON h.hospital_id = bi.hospital_id
    ORDER BY bi.issue_date DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Blood Issue Records</h3>
  <a href="add.php" class="btn btn-danger"><i class="bi bi-plus-lg"></i> New Issue Entry</a>
</div>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>
<?php if ($err = flash('error')): ?>
  <div class="alert alert-danger"><?php echo e($err); ?></div>
<?php endif; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>ID</th><th>Patient</th><th>Blood Group</th><th>Units</th><th>Hospital</th><th>Date</th><th>Approved By</th><th>Remarks</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($records) === 0): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">No issue records yet.</td></tr>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($records)): ?>
          <tr>
            <td>#<?php echo $r['issue_id']; ?></td>
            <td><?php echo e($r['patient_name']); ?></td>
            <td><span class="badge bg-danger"><?php echo e($r['blood_group']); ?></span></td>
            <td><?php echo e($r['units_issued']); ?></td>
            <td><?php echo e($r['hospital_name'] ?? '—'); ?></td>
            <td><?php echo e($r['issue_date']); ?></td>
            <td><?php echo e($r['approved_by']); ?></td>
            <td><?php echo e($r['remarks']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
