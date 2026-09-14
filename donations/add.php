<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'New Collection Entry';
$donors = mysqli_query($conn, "SELECT donor_id, name, blood_group FROM donors ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_id = (int) $_POST['donor_id'];
    $blood_group = clean($conn, $_POST['blood_group']);
    $units = (int) $_POST['units_collected'];
    $collection_date = clean($conn, $_POST['collection_date']);
    $expiry_date = clean($conn, $_POST['expiry_date']);
    $staff_name = clean($conn, $_POST['staff_name']);

    mysqli_query($conn, "INSERT INTO blood_collection (donor_id, blood_group, units_collected, collection_date, expiry_date, staff_name)
                          VALUES ($donor_id, '$blood_group', $units, '$collection_date', '$expiry_date', '$staff_name')");

    mysqli_query($conn, "UPDATE donors SET last_donation_date='$collection_date' WHERE donor_id=$donor_id");

    // Blood collection entry increases available stock
    adjust_stock($conn, $blood_group, $units);

    flash('success', 'Blood collection recorded and stock updated.');
    redirect('list.php');
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">New Blood Collection Entry</h3>
<div class="card shadow-sm" style="max-width:600px;">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Donor</label>
        <select name="donor_id" id="donorSelect" class="form-select" required>
          <option value="">-- Select Donor --</option>
          <?php while ($d = mysqli_fetch_assoc($donors)): ?>
            <option value="<?php echo $d['donor_id']; ?>" data-bg="<?php echo e($d['blood_group']); ?>">
              <?php echo e($d['name']); ?> (<?php echo e($d['blood_group']); ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Blood Group</label>
        <select name="blood_group" id="bgSelect" class="form-select" required>
          <?php foreach (blood_groups() as $bg): ?><option><?php echo $bg; ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Units Collected</label>
        <input type="number" name="units_collected" min="1" value="1" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Collection Date</label>
        <input type="date" name="collection_date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control" required>
        <div class="form-text">Whole blood is typically valid for 42 days from collection.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Staff Name</label>
        <input type="text" name="staff_name" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger">Save Collection</button>
      <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
  </div>
</div>
<script>
// Auto-select donor's blood group
document.getElementById('donorSelect').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const bg = opt.getAttribute('data-bg');
    if (bg) document.getElementById('bgSelect').value = bg;
});
</script>
<?php include '../includes/footer.php'; ?>
