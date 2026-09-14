<?php
require_once '../includes/public_auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Request Blood';
$user_id = (int) $_SESSION['public_user_id'];
$hospitals = mysqli_query($conn, "SELECT hospital_id, hospital_name FROM hospitals ORDER BY hospital_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = clean($conn, $_POST['patient_name']);
    $age = (int) $_POST['age'];
    $gender = clean($conn, $_POST['gender']);
    $blood_group = clean($conn, $_POST['blood_group']);
    $required_units = (int) $_POST['required_units'];
    $hospital_id = $_POST['hospital_id'] !== '' ? (int) $_POST['hospital_id'] : 'NULL';
    $mobile = clean($conn, $_POST['mobile']);
    $emergency_level = clean($conn, $_POST['emergency_level']);
    $request_date = date('Y-m-d');

    mysqli_query($conn, "INSERT INTO blood_requests
        (user_id, patient_name, age, gender, blood_group, required_units, hospital_id, mobile, emergency_level, request_date, status)
        VALUES ($user_id, '$patient_name', $age, '$gender', '$blood_group', $required_units, $hospital_id, '$mobile', '$emergency_level', '$request_date', 'Pending')");

    add_notification($conn, 'New Request', "New $emergency_level request for $required_units unit(s) of $blood_group ($patient_name).");

    flash('success', 'Your blood request has been submitted. Our team will review it shortly.');
    redirect('/bloodbank_management_system/bloodbank/public/dashboard.php');
}

include '../includes/public_header.php';
?>
<h3 class="mb-4">Request Blood</h3>
<div class="card shadow-sm" style="max-width:650px;">
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Patient Name</label>
          <input type="text" name="patient_name" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Age</label>
          <input type="number" name="age" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option>Male</option><option>Female</option><option>Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Blood Group</label>
          <select name="blood_group" class="form-select" required>
            <?php foreach (blood_groups() as $bg): ?><option><?php echo $bg; ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Required Units</label>
          <input type="number" name="required_units" min="1" value="1" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Emergency Level</label>
          <select name="emergency_level" class="form-select" required>
            <option>Normal</option><option>Urgent</option><option>Critical</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Hospital (optional)</label>
          <select name="hospital_id" class="form-select">
            <option value="">-- Not sure / Other --</option>
            <?php while ($h = mysqli_fetch_assoc($hospitals)): ?>
              <option value="<?php echo $h['hospital_id']; ?>"><?php echo e($h['hospital_name']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Your Contact Number</label>
          <input type="text" name="mobile" class="form-control" required>
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-danger">Submit Request</button>
        <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/public_footer.php'; ?>
