<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (isset($_SESSION['public_user_id'])) {
    redirect('/bloodbank_management_system/bloodbank/public/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM public_users WHERE email = '$email' LIMIT 1");

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['public_user_id'] = $user['user_id'];
            $_SESSION['public_user_name'] = $user['full_name'];
            redirect('/bloodbank_management_system/bloodbank/public/dashboard.php');
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Blood Bank</title>
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
        <h4 class="mt-2">Sign In</h4>
      </div>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Login</button>
        <div class="text-center mt-3">
          Don't have an account? <a href="register.php">Sign Up</a>
        </div>
        <div class="text-center mt-2">
          <a href="/bloodbank_management_system/bloodbank/admin/login.php" class="small text-muted">Admin Login</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
