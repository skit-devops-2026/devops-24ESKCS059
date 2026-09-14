<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Edit Donor';
$id = (int) ($_GET['id'] ?? 0);

$donor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM donors WHERE donor_id=$id"));
if (!$donor) { redirect('list.php'); }

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

    $photo_sql = '';
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = 'donor_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/donors/' . $photo_name);
        $photo_sql = ", photo='$photo_name'";
    }

    mysqli_query($conn, "UPDATE donors SET
        name='$name', gender='$gender', dob='$dob', age=$age, blood_group='$blood_group',
        weight='$weight', mobile='$mobile', email='$email', address='$address', city='$city',
        state='$state', last_donation_date=$last_donation_date, medical_status='$medical_status',
        availability_status='$availability_status' $photo_sql
        WHERE donor_id=$id");

    flash('success', 'Donor updated successfully.');
    redirect('list.php');
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Edit Donor</h3>
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" value="<?php echo e($donor['name']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender *</label>
          <select name="gender" class="form-select" required>
            <?php foreach (['Male','Female','Other'] as $g): ?>
              <option <?php echo $donor['gender']===$g?'selected':''; ?>><?php echo $g; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date of Birth *</label>
          <input type="date" name="dob" class="form-control" value="<?php echo e($donor['dob']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Blood Group *</label>
          <select name="blood_group" class="form-select" required>
            <?php foreach (blood_groups() as $bg): ?>
              <option <?php echo $donor['blood_group']===$bg?'selected':''; ?>><?php echo $bg; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Weight (kg)</label>
          <input type="number" step="0.1" name="weight" class="form-control" value="<?php echo e($donor['weight']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Mobile Number *</label>
          <input type="text" name="mobile" class="form-control" value="<?php echo e($donor['mobile']); ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo e($donor['email']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" value="<?php echo e($donor['address']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control" value="<?php echo e($donor['city']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">State</label>
          <input type="text" name="state" class="form-control" value="<?php echo e($donor['state']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Last Donation Date</label>
          <input type="date" name="last_donation_date" class="form-control" value="<?php echo e($donor['last_donation_date']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Medical Status</label>
          <input type="text" name="medical_status" class="form-control" value="<?php echo e($donor['medical_status']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Availability Status</label>
          <select name="availability_status" class="form-select">
            <?php foreach (['Available','Not Available'] as $s): ?>
              <option <?php echo $donor['availability_status']===$s?'selected':''; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Replace Photo</label>
          <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-danger">Update Donor</button>
        <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
