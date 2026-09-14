<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Stock';

$stock = mysqli_query($conn, "SELECT * FROM blood_stock ORDER BY blood_group");

// Units expiring within 7 days (collected 42 days ago is the typical shelf life; we use expiry_date directly)
$expiring = mysqli_query($conn, "SELECT bc.*, d.name AS donor_name FROM blood_collection bc
    JOIN donors d ON d.donor_id = bc.donor_id
    WHERE bc.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ORDER BY bc.expiry_date ASC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Blood Stock Management</h3>
  <div>
    <a href="add.php" class="btn btn-danger"><i class="bi bi-plus-lg"></i> Add Blood Units</a>
  </div>
</div>

<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <?php mysqli_data_seek($stock, 0); while ($s = mysqli_fetch_assoc($stock)): ?>
    <div class="col-md-3 col-sm-6">
      <div class="card shadow-sm <?php echo $s['units_available'] <= $s['low_stock_threshold'] ? 'border-danger' : ''; ?>">
        <div class="card-body text-center">
          <div class="fs-2 fw-bold text-danger"><?php echo e($s['blood_group']); ?></div>
          <div class="fs-4"><?php echo e($s['units_available']); ?> units</div>
          <?php if ($s['units_available'] <= $s['low_stock_threshold']): ?>
            <span class="badge bg-danger mt-2">Low Stock</span>
          <?php endif; ?>
          <div class="mt-2">
            <a href="update.php?id=<?php echo $s['stock_id']; ?>" class="btn btn-sm btn-outline-warning">Update</a>
            <a href="delete.php?id=<?php echo $s['stock_id']; ?>" class="btn btn-sm btn-outline-danger btn-delete-confirm">Reset</a>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<div class="card shadow-sm">
  <div class="card-header bg-warning-subtle"><i class="bi bi-exclamation-triangle-fill"></i> Blood Expiry Record (expiring within 7 days)</div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>Blood Group</th><th>Donor</th><th>Units</th><th>Collection Date</th><th>Expiry Date</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($expiring) === 0): ?>
          <tr><td colspan="5" class="text-center text-muted py-3">No units expiring soon.</td></tr>
        <?php endif; ?>
        <?php while ($row = mysqli_fetch_assoc($expiring)): ?>
          <tr>
            <td><span class="badge bg-danger"><?php echo e($row['blood_group']); ?></span></td>
            <td><?php echo e($row['donor_name']); ?></td>
            <td><?php echo e($row['units_collected']); ?></td>
            <td><?php echo e($row['collection_date']); ?></td>
            <td class="text-danger fw-bold"><?php echo e($row['expiry_date']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
