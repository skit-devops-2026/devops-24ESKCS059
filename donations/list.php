<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Donations';

$records = mysqli_query($conn, "SELECT bc.*, d.name AS donor_name FROM blood_collection bc
    JOIN donors d ON d.donor_id = bc.donor_id
    ORDER BY bc.collection_date DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Blood Collection Records</h3>
  <a href="add.php" class="btn btn-danger"><i class="bi bi-plus-lg"></i> New Collection Entry</a>
</div>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>ID</th><th>Donor</th><th>Blood Group</th><th>Units</th><th>Collection Date</th><th>Expiry Date</th><th>Staff</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($records) === 0): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No collection records yet.</td></tr>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($records)): ?>
          <tr>
            <td>#<?php echo $r['collection_id']; ?></td>
            <td><?php echo e($r['donor_name']); ?></td>
            <td><span class="badge bg-danger"><?php echo e($r['blood_group']); ?></span></td>
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
