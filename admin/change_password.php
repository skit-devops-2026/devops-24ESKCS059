<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Change Password';
$admin_id = $_SESSION['admin_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin WHERE admin_id=$admin_id"));

    if (!password_verify($current, $admin['password'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New password and confirmation do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admin SET password='$hash' WHERE admin_id=$admin_id");
        flash('success', 'Password changed successfully.');
        redirect('change_password.php');
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Change Password</h3>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo e($error); ?></div>
<?php endif; ?>
<div class="card shadow-sm" style="max-width:500px;">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button class="btn btn-danger" type="submit">Update Password</button>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
