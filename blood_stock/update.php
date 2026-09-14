<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Update Blood Stock';
$id = (int) ($_GET['id'] ?? 0);
$stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_stock WHERE stock_id=$id"));
if (!$stock) { redirect('list.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $units_available = (int) $_POST['units_available'];
    $low_stock_threshold = (int) $_POST['low_stock_threshold'];

    mysqli_query($conn, "UPDATE blood_stock SET units_available=$units_available,
                          low_stock_threshold=$low_stock_threshold, last_updated=NOW() WHERE stock_id=$id");

    flash('success', 'Blood stock updated successfully.');
    redirect('list.php');
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Update Stock: <?php echo e($stock['blood_group']); ?></h3>
<div class="card shadow-sm" style="max-width:500px;">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Units Available</label>
        <input type="number" name="units_available" min="0" class="form-control" value="<?php echo e($stock['units_available']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Low Stock Threshold</label>
        <input type="number" name="low_stock_threshold" min="0" class="form-control" value="<?php echo e($stock['low_stock_threshold']); ?>" required>
      </div>
      <button type="submit" class="btn btn-danger">Update</button>
      <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
