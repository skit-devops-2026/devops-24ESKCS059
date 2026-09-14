<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (isset($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' LIMIT 1");

    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            log_login($conn, $username, 'admin', 'Success');
            redirect('dashboard.php');
        } else {
            $error = 'Invalid username or password.';
            log_login($conn, $username, 'admin', 'Failed');
        }
    } else {
        $error = 'Invalid username or password.';
        log_login($conn, $username, 'admin', 'Failed');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Blood Bank Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/bloodbank_management_system/bloodbank/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrapper">
  <div class="card shadow" style="width: 380px;">
    <div class="card-body p-4">
      <div class="text-center mb-3">
        <i class="bi bi-droplet-fill text-danger" style="font-size:3rem;"></i>
        <h4 class="mt-2">Blood Bank Admin Login</h4>
      </div>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Login</button>
        <div class="text-center mt-3">
          <a href="forgot_password.php" class="small">Forgot Password?</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
