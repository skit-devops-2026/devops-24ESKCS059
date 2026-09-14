<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Profile';
$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($conn, $_POST['full_name']);
    $email = clean($conn, $_POST['email']);

    mysqli_query($conn, "UPDATE admin SET full_name='$full_name', email='$email' WHERE admin_id=$admin_id");
    $_SESSION['admin_name'] = $full_name;
    flash('success', 'Profile updated successfully.');
    redirect('profile.php');
}

$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin WHERE admin_id=$admin_id"));

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Profile Management</h3>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>
<div class="card shadow-sm" style="max-width:500px;">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" value="<?php echo e($admin['username']); ?>" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?php echo e($admin['full_name']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo e($admin['email']); ?>" required>
      </div>
      <button class="btn btn-danger" type="submit">Save Changes</button>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
