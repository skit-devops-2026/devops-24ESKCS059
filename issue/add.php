<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'New Issue Entry';
$hospitals = mysqli_query($conn, "SELECT hospital_id, hospital_name FROM hospitals ORDER BY hospital_name");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = clean($conn, $_POST['patient_name']);
    $blood_group = clean($conn, $_POST['blood_group']);
    $units = (int) $_POST['units_issued'];
    $hospital_id = (int) $_POST['hospital_id'];
    $issue_date = clean($conn, $_POST['issue_date']);
    $approved_by = clean($conn, $_POST['approved_by']);
    $remarks = clean($conn, $_POST['remarks']);

    $stock_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units_available FROM blood_stock WHERE blood_group='$blood_group'"));

    if ($stock_row['units_available'] < $units) {
        $error = "Insufficient stock: only {$stock_row['units_available']} unit(s) of $blood_group available.";
    } else {
        mysqli_query($conn, "INSERT INTO blood_issue (patient_name, blood_group, units_issued, hospital_id, issue_date, approved_by, remarks)
                              VALUES ('$patient_name', '$blood_group', $units, $hospital_id, '$issue_date', '$approved_by', '$remarks')");

        // Blood issue entry decreases available stock
        adjust_stock($conn, $blood_group, -$units);

        flash('success', 'Blood issue recorded and stock updated.');
        redirect('list.php');
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">New Blood Issue Entry</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="card shadow-sm" style="max-width:600px;">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Patient Name</label>
        <input type="text" name="patient_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Blood Group</label>
        <select name="blood_group" class="form-select" required>
          <?php foreach (blood_groups() as $bg): ?><option><?php echo $bg; ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Units Issued</label>
        <input type="number" name="units_issued" min="1" value="1" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Hospital</label>
        <select name="hospital_id" class="form-select" required>
          <option value="">-- Select Hospital --</option>
          <?php while ($h = mysqli_fetch_assoc($hospitals)): ?>
            <option value="<?php echo $h['hospital_id']; ?>"><?php echo e($h['hospital_name']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Approved By</label>
        <input type="text" name="approved_by" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2"></textarea>
      </div>
      <button type="submit" class="btn btn-danger">Save Issue</button>
      <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
