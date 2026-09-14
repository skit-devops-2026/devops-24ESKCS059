<?php
require_once '../includes/public_auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Offer to Donate';
$user_id = (int) $_SESSION['public_user_id'];

$me_res = mysqli_query($conn, "SELECT full_name, mobile, blood_group, city FROM public_users WHERE user_id = $user_id");
$me = mysqli_fetch_assoc($me_res);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($conn, $_POST['full_name']);
    $mobile = clean($conn, $_POST['mobile']);
    $blood_group = clean($conn, $_POST['blood_group']);
    $city = clean($conn, $_POST['city']);
    $available_date = clean($conn, $_POST['available_date']);
    $message = clean($conn, $_POST['message']);

    mysqli_query($conn, "INSERT INTO donation_offers
        (user_id, full_name, mobile, blood_group, city, available_date, message, status)
        VALUES ($user_id, '$full_name', '$mobile', '$blood_group', '$city', '$available_date', '$message', 'Pending')");

    add_notification($conn, 'Donor Offer', "$full_name ($blood_group) offered to donate blood.");

    flash('success', 'Thank you! Your donation offer has been submitted. Our team will contact you soon.');
    redirect('/bloodbank_management_system/bloodbank/public/dashboard.php');
}

include '../includes/public_header.php';
?>
<h3 class="mb-4">Offer to Donate Blood</h3>
<div class="card shadow-sm" style="max-width:650px;">
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" value="<?php echo e($me['full_name']); ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Number</label>
          <input type="text" name="mobile" class="form-control" value="<?php echo e($me['mobile']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Blood Group</label>
          <select name="blood_group" class="form-select" required>
            <?php foreach (blood_groups() as $bg): ?>
              <option <?php echo ($me['blood_group'] === $bg) ? 'selected' : ''; ?>><?php echo $bg; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control" value="<?php echo e($me['city']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Available From</label>
          <input type="date" name="available_date" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Message (optional)</label>
          <textarea name="message" class="form-control" rows="3" placeholder="Any preferred time, medical notes, etc."></textarea>
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-success">Submit Offer</button>
        <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/public_footer.php'; ?>
