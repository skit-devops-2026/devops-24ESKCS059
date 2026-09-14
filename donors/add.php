<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Add Donor';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($conn, $_POST['name']);
    $gender = clean($conn, $_POST['gender']);
    $dob = clean($conn, $_POST['dob']);
    $age = calculate_age($dob);
    $blood_group = clean($conn, $_POST['blood_group']);
    $weight = clean($conn, $_POST['weight']);
    $mobile = clean($conn, $_POST['mobile']);
    $email = clean($conn, $_POST['email']);
    $address = clean($conn, $_POST['address']);
    $city = clean($conn, $_POST['city']);
    $state = clean($conn, $_POST['state']);
    $last_donation_date = $_POST['last_donation_date'] !== '' ? "'" . clean($conn, $_POST['last_donation_date']) . "'" : 'NULL';
    $medical_status = clean($conn, $_POST['medical_status']);
    $availability_status = clean($conn, $_POST['availability_status']);

    // Photo upload
    $photo_name = '';
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = 'donor_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/donors/' . $photo_name);
    }

    if ($name === '' || $mobile === '') {
        $error = 'Name and Mobile Number are required.';
    } else {
        mysqli_query($conn, "INSERT INTO donors
            (name, gender, dob, age, blood_group, weight, mobile, email, address, city, state, last_donation_date, medical_status, availability_status, photo)
            VALUES ('$name','$gender','$dob',$age,'$blood_group','$weight','$mobile','$email','$address','$city','$state',$last_donation_date,'$medical_status','$availability_status','$photo_name')");

        flash('success', 'Donor added successfully.');
        redirect('list.php');
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Add Donor</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender *</label>
          <select name="gender" class="form-select" required>
            <option>Male</option><option>Female</option><option>Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date of Birth *</label>
          <input type="date" name="dob" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Blood Group *</label>
          <select name="blood_group" class="form-select" required>
            <?php foreach (blood_groups() as $bg): ?><option><?php echo $bg; ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Weight (kg)</label>
          <input type="number" step="0.1" name="weight" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Mobile Number *</label>
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
        <div class="col-md-4">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">State</label>
          <input type="text" name="state" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Last Donation Date</label>
          <input type="date" name="last_donation_date" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Medical Status</label>
          <input type="text" name="medical_status" class="form-control" value="Fit">
        </div>
        <div class="col-md-4">
          <label class="form-label">Availability Status</label>
          <select name="availability_status" class="form-select">
            <option>Available</option><option>Not Available</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Photo</label>
          <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-danger">Save Donor</button>
        <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
