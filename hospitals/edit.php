<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Edit Hospital';
$id = (int) ($_GET['id'] ?? 0);
$hospital = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hospitals WHERE hospital_id=$id"));
if (!$hospital) { redirect('list.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_name = clean($conn, $_POST['hospital_name']);
    $registration_number = clean($conn, $_POST['registration_number']);
    $contact_person = clean($conn, $_POST['contact_person']);
    $mobile = clean($conn, $_POST['mobile']);
    $email = clean($conn, $_POST['email']);
    $address = clean($conn, $_POST['address']);
    $city = clean($conn, $_POST['city']);
    $state = clean($conn, $_POST['state']);

    mysqli_query($conn, "UPDATE hospitals SET hospital_name='$hospital_name', registration_number='$registration_number',
        contact_person='$contact_person', mobile='$mobile', email='$email', address='$address', city='$city', state='$state'
        WHERE hospital_id=$id");

    flash('success', 'Hospital updated successfully.');
    redirect('list.php');
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Edit Hospital</h3>
<div class="card shadow-sm" style="max-width:650px;">
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Hospital Name *</label>
          <input type="text" name="hospital_name" class="form-control" value="<?php echo e($hospital['hospital_name']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Registration Number *</label>
          <input type="text" name="registration_number" class="form-control" value="<?php echo e($hospital['registration_number']); ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Person</label>
          <input type="text" name="contact_person" class="form-control" value="<?php echo e($hospital['contact_person']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="mobile" class="form-control" value="<?php echo e($hospital['mobile']); ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo e($hospital['email']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" value="<?php echo e($hospital['address']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control" value="<?php echo e($hospital['city']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">State</label>
          <input type="text" name="state" class="form-control" value="<?php echo e($hospital['state']); ?>">
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-danger">Update Hospital</button>
        <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
