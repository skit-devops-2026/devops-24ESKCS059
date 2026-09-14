<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Add Hospital';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_name = clean($conn, $_POST['hospital_name']);
    $registration_number = clean($conn, $_POST['registration_number']);
    $contact_person = clean($conn, $_POST['contact_person']);
    $mobile = clean($conn, $_POST['mobile']);
    $email = clean($conn, $_POST['email']);
    $address = clean($conn, $_POST['address']);
    $city = clean($conn, $_POST['city']);
    $state = clean($conn, $_POST['state']);

    if ($hospital_name === '' || $registration_number === '') {
        $error = 'Hospital Name and Registration Number are required.';
    } else {
        mysqli_query($conn, "INSERT INTO hospitals (hospital_name, registration_number, contact_person, mobile, email, address, city, state)
            VALUES ('$hospital_name','$registration_number','$contact_person','$mobile','$email','$address','$city','$state')");
        flash('success', 'Hospital added successfully.');
        redirect('list.php');
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Add Hospital</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="card shadow-sm" style="max-width:650px;">
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Hospital Name *</label>
          <input type="text" name="hospital_name" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Registration Number *</label>
          <input type="text" name="registration_number" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Person</label>
          <input type="text" name="contact_person" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="mobile" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">State</label>
          <input type="text" name="state" class="form-control">
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-danger">Save Hospital</button>
        <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
