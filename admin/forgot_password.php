<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($conn, $_POST['email']);
    $result = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email' LIMIT 1");

    if ($result && mysqli_num_rows($result) === 1) {
        // Generate a temporary password and email it to the admin.
        $temp_password = substr(bin2hex(random_bytes(4)), 0, 8);
        $hash = password_hash($temp_password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admin SET password='$hash' WHERE email='$email'");

        // TODO: integrate PHPMailer / SMTP to actually send $temp_password to $email.
        // For local/dev use, the temp password is shown on screen below.
        $message = "A temporary password has been generated: <strong>$temp_password</strong><br>
                    (In production this would be emailed, not displayed. Configure PHPMailer in this file.)";
    } else {
        $message = "If that email exists in our system, a reset link/password has been sent.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/bloodbank_management_system/bloodbank/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrapper">
  <div class="card shadow" style="width:400px;">
    <div class="card-body p-4">
      <h5 class="mb-3">Forgot Password</h5>
      <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Registered Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Reset Password</button>
        <a href="login.php" class="btn btn-link w-100 mt-2">Back to Login</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
