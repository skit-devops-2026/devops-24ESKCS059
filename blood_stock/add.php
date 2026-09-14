<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Add Blood Units';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_group = clean($conn, $_POST['blood_group']);
    $units = (int) $_POST['units'];

    adjust_stock($conn, $blood_group, $units);
    flash('success', "$units unit(s) of $blood_group added to stock.");
    redirect('list.php');
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Add Blood Units</h3>
<div class="card shadow-sm" style="max-width:500px;">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Blood Group</label>
        <select name="blood_group" class="form-select" required>
          <?php foreach (blood_groups() as $bg): ?><option><?php echo $bg; ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Units to Add</label>
        <input type="number" name="units" min="1" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger">Add to Stock</button>
      <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
